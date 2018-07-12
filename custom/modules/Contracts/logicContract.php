<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicContract {
    function deletedContract(&$bean, $event, $arguments){
        $count_rel = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(IFNULL(j_payment.id, '')) count_rel
            FROM
            j_payment
            INNER JOIN
            contracts l1 ON j_payment.contract_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$bean->id}')))
            AND j_payment.deleted = 0");
        if($count_rel > 0){
            if($event == "before_delete"){
                echo '
                <script type="text/javascript">
                alert("Bạn không thể xóa hợp đồng!\nLý do: Học viên vẫn còn trong hợp đồng.");
                location.href=\'index.php?module=Contracts&action=DetailView&record='.$bean->id.'\';
                </script>';
                die();
            }else{
                $q1 = "SELECT DISTINCT
                IFNULL(j_payment.id, '') primaryid,
                IFNULL(j_payment.payment_type, '') j_payment_payment_type
                FROM
                j_payment
                INNER JOIN
                contracts l1 ON j_payment.contract_id = l1.id
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$bean->id}')))
                AND j_payment.deleted = 0";
                $pays = $GLOBALS['db']->fetchArray($q1);
                foreach($pays as $pay){
                    $pm_cop = BeanFactory::getBean('J_Payment',$pay['primaryid']);
                    //delete Relationship
                    $GLOBALS['db']->query("DELETE FROM contracts_contacts WHERE contact_id='{$pm_cop->contacts_j_payment_1contacts_ida}' AND contract_id='{$pm_cop->contract_id}'");
                    $pm_cop->mark_deleted();
                }
                //Delete Payment Detail
                $GLOBALS['db']->query("UPDATE j_paymentdetail SET deleted = 1 WHERE contract_id='{$bean->id}'");
            }
        }

    }

    function addCode(&$bean, $event, $arguments){
        $code_field = 'contract_id';
        $res        = $GLOBALS['db']->query("SELECT short_name FROM accounts WHERE id = '{$bean->account_id}'");
        $row        = $GLOBALS['db']->fetchByAssoc($res);
        $prefix     = $row['short_name'];
        if(empty($bean->$code_field) || strpos($bean->fetched_row[$code_field], $prefix) === false){
            //Get Prefix
            $year       = date('y',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '000';
            $padding    = 3;
            $query      = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($prefix.$year).") = '".$prefix.$year."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";
            $result = $GLOBALS['db']->query($query);
            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $prefix . $year . $sep  . $first_pad;

            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $prefix . $year . $sep;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;

            //write to database - Logic: Before Save
            $bean->$code_field = $new_code;
        }
    }

    function beforeSaveContract(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            global $timedate;
            //Update Account
            $account = BeanFactory::getBean('Accounts', $bean->account_id);
            $count_acc = 0;
            if($account->phone_office != $bean->account_phone){
                $count_acc++;
                $account->phone_office = $bean->account_phone;
            }
            if($account->tax_code != $bean->account_tax_code){
                $count_acc++;
                $account->tax_code = $bean->account_tax_code;
            }
            if($account->bank_name != $bean->account_bank_name){
                $count_acc++;
                $account->bank_name = $bean->account_bank_name;
            }
            if($account->bank_number != $bean->account_bank_number){
                $count_acc++;
                $account->bank_number = $bean->account_bank_number;
            }
            if($account->billing_address_street != $bean->account_address){
                $count_acc++;
                $account->billing_address_street = $bean->account_address;
            }
            if($count_acc > 0){
                $account->save();
            }

            if(!empty($bean->fetched_row)){
                if($bean->fetched_row['status'] == 'signed' && $bean->status != $bean->fetched_row['status']){
                    logicContract::deletedContract($bean, $event, $arguments);
                }
            }
        }
    }

    function afterSaveContract(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            global $timedate;
            $PayDtl = array();
            $count_pmd = (int)$bean->number_of_payment;
            if(!empty($bean->fetched_row)){    //In Case Edit
                $sqlPayDtl = "SELECT DISTINCT id,
                IFNULL(payment_no, '') payment_no,
                IFNULL(status, '') status,
                IFNULL(invoice_number, '') invoice_number,
                IFNULL(payment_amount, 0) payment_amount
                FROM j_paymentdetail
                WHERE contract_id = '{$bean->id}' AND (payment_id = '' OR payment_id IS NULL)
                AND deleted = 0
                AND status <> 'Cancelled'
                ORDER BY payment_no";
                $PayDtl = $GLOBALS['db']->fetchArray($sqlPayDtl);
                //TH1: Tổng tiền thay đổi giảm + Hoặc thay đổi số lần thu -> Hủy thu lại
                if($bean->fetched_row['total_contract_value'] > $bean->total_contract_value || $count_pmd != count($PayDtl)){
                    $GLOBALS['db']->query("UPDATE j_paymentdetail SET status = 'Cancelled' WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status = 'Paid') AND (invoice_number <> '' AND invoice_number IS NOT NULL)");
                    $GLOBALS['db']->query("UPDATE j_paymentdetail SET deleted = 1 WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status <> 'Cancelled')");
                    unset($PayDtl);
                }
                //TH2: Tổng tiền thay đổi tăng và không thay đổi số lần thu -> Tăng tiền lần thu cuối
                if($bean->fetched_row['total_contract_value'] < $bean->total_contract_value && $count_pmd == count($PayDtl)){
                    $GLOBALS['db']->query("UPDATE j_paymentdetail SET status = 'Cancelled' WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status = 'Paid') AND (invoice_number <> '' AND invoice_number IS NOT NULL) AND (payment_no = '$count_pmd')");
                    $GLOBALS['db']->query("UPDATE j_paymentdetail SET deleted = 1 WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status <> 'Cancelled') AND (payment_no = '$count_pmd')");
                    unset($PayDtl[$count_pmd-1]);
                }
                //TH3: Nếu tổng tiền không thay đổi nhưng tiền thu các lần thay đổi thì xóa hết làm lại
                if($bean->fetched_row['total_contract_value'] == $bean->total_contract_value && $count_pmd == count($PayDtl)){
                    $count3 = 0;
                    for($i = 0; $i < $count_pmd; $i++){
                        if(unformat_number($_POST['pay_dtl_amount'][$i]) != $PayDtl[$i]['payment_amount'])
                            $count3++;
                    }
                    if($count3 > 0){
                        $GLOBALS['db']->query("UPDATE j_paymentdetail SET status = 'Cancelled' WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status = 'Paid') AND (invoice_number <> '' AND invoice_number IS NOT NULL)");
                        $GLOBALS['db']->query("UPDATE j_paymentdetail SET deleted = 1 WHERE (contract_id = '{$bean->id}') AND (payment_id = '' OR payment_id IS NULL) AND (status <> 'Cancelled')");
                        unset($PayDtl);
                    }
                }
            }
            //Create payment Detail
            for($i = 0; $i < $count_pmd; $i++){

                if(empty($PayDtl[$i]['id']))
                    $pmd            = BeanFactory::newBean('J_PaymentDetail');
                else
                    $pmd            = BeanFactory::getBean('J_PaymentDetail', $PayDtl[$i]['id']);
                $index          = $i+1;
                $payDtlAmount   = unformat_number($_POST['pay_dtl_amount'][$i]);

                $pmd->payment_no    = $index;
                $pmd->name          = $bean->contract_id."-$index";
                $pmd->before_discount   = format_number($payDtlAmount);
                $pmd->discount_amount   = 0;
                $pmd->sponsor_amount    = 0;
                $pmd->payment_amount    = format_number($payDtlAmount);
                $pmd->payment_date      = $_POST['pay_dtl_invoice_date'][$i];
                if(empty($PayDtl[$i]['id']))
                    $pmd->status            = "Unpaid";
                if($payDtlAmount == 0){
                    $pmd->status                = "Paid";
                    $pmd->payment_method        = 'Other';
                }
                $pmd->type               = 'Normal';
                $pmd->contract_id        = $bean->id;
                $pmd->assigned_user_id   = $bean->assigned_user_id;
                $pmd->team_id            = $bean->team_id;
                $pmd->team_set_id        = $bean->team_id;
                $pmd->save();
            }

        }
    }
}
?>
