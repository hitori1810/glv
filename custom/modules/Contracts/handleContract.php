<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class handleContract {
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
            echo '
            <script type="text/javascript">
            alert("You can not delete this contract!\nReason: Students of this contract still exists.");
            location.href=\'index.php?module=Contracts&action=DetailView&record='.$bean->id.'\';
            </script>';
            die();
        }
    }

    function listviewColor(&$bean, $event, $arguments){
        $bean->contract_id = "<span class='textbg_blue'><a href='index.php?module=Contracts&amp;action=DetailView&amp;record={$bean->id}' style='color: #ffffff;'>{$bean->contract_id}</a></span>";
    }

    function beforeSaveContract(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            global $timedate;
            $count_pmd = 5;
            foreach ($_POST['pmd_id'] as $index => $pmd_id){
                $pmd_amount    = unformat_number($_POST['payment_amount'][$index]);
                $indexx = $index+1;
                $pmd_date      = $timedate->to_db_date($_POST["payment_date_$indexx"],false);
                $pmd_invoice   = $_POST['invoice_no'][$index];
                if(empty($pmd_id) && $pmd_amount > 0){
                    //Save Payment Detail Contract
                    $pmd = BeanFactory::newBean('J_PaymentDetail');
                    $pmd->payment_no    = $indexx;
                    $pmd->name          = $bean->name."-$indexx";
                    $pmd->before_discount   = format_number($pmd_amount);
                    $pmd->discount_amount   = 0;
                    $pmd->sponsor_amount    = 0;
                    $pmd->status            = "Paid";
                    $pmd->payment_date      = $pmd_date;
                    $pmd->payment_method    = 'Other';
                    $pmd->invoice_number    = $pmd_invoice;

                    $pmd->type              = 'Normal';
                    $pmd->payment_amount    = format_number($pmd_amount);

                    $pmd->contract_id       = $bean->id;
                    $pmd->assigned_user_id  = $bean->assigned_user_id;
                    $pmd->team_id           = $bean->team_id;
                    $pmd->team_set_id       = $bean->team_id;
                    if($pmd->payment_amount != 0)
                        $pmd->save();

                    //Save Payment Detail Payment Corporate
                    $q20 = "SELECT DISTINCT
                    IFNULL(contacts.id, '') student_id,
                    IFNULL(l1.id, '') contract_id,
                    CONCAT(IFNULL(contacts.last_name, ''),
                    ' ',
                    IFNULL(contacts.first_name, '')) student_name,
                    IFNULL(l2.id, '') payment_id,
                    IFNULL(l3.id, '') team_id,
                    IFNULL(l2.name, '') payment_name,
                    l2.start_study start_study,
                    l2.end_study end_study,
                    l2.payment_date payment_date,
                    l2.payment_amount payment_amount
                    FROM
                    contacts
                    INNER JOIN
                    contracts_contacts l1_1 ON contacts.id = l1_1.contact_id
                    AND l1_1.deleted = 0
                    INNER JOIN
                    contracts l1 ON l1.id = l1_1.contract_id
                    AND l1.deleted = 0 AND (l1.id = '{$bean->id}')
                    INNER JOIN
                    contacts_j_payment_1_c l2_1 ON contacts.id = l2_1.contacts_j_payment_1contacts_ida
                    AND l2_1.deleted = 0
                    INNER JOIN
                    j_payment l2 ON l2.id = l2_1.contacts_j_payment_1j_payment_idb
                    AND l2.deleted = 0 AND (l2.payment_type = 'Corporate')
                    AND l2.contract_id = l1.id
                    INNER JOIN
                    teams l3 ON l2.team_id = l3.id
                    AND l3.deleted = 0
                    WHERE
                    contacts.deleted = 0";
                    $row_cops = $GLOBALS['db']->fetchArray($q20);

                    foreach($row_cops as $key => $row_cop){
                        $pmdd = BeanFactory::newBean('J_PaymentDetail');
                        $pmdd->payment_no    = $indexx;
                        $pmdd->name          = $bean->name."-$indexx";
                        $pmdd->before_discount   = format_number($pmd_amount / $bean->total_student);
                        $pmdd->discount_amount   = 0;
                        $pmdd->sponsor_amount    = 0;
                        $pmdd->status            = "Paid";
                        $pmdd->payment_date      = $pmd_date;
                        $pmdd->payment_method    = 'Other';

                        $pmdd->type              = 'Normal';
                        $pmdd->payment_amount    = $pmdd->before_discount;

                        $pmdd->payment_id        = $row_cop['payment_id'];
                        $pmdd->assigned_user_id  = $bean->assigned_user_id;
                        $pmdd->team_id           = $row_cop['team_id'];
                        $pmdd->team_set_id       = $row_cop['team_id'];
                        if($pmdd->payment_amount != 0)
                            $pmdd->save();
                    }
                }
            }
        }
    }
}
?>
