<?php
global $timedate;
if(!empty($_POST['enrollment_id'])){
    require_once("custom/include/_helper/junior_class_utils.php");

    $enrollment = BeanFactory::getBean('Opportunities', $_POST['enrollment_id']);
    $pk =  BeanFactory::getBean('C_Packages',$enrollment->c_packages_opportunities_1c_packages_ida);
    if(!empty($enrollment->payment_id)){
        $casholder_id = $enrollment->payment_id;
        $delay_id     = $GLOBALS['db']->getOne("SELECT `j_payment_j_payment_1j_payment_idb` FROM `j_payment_j_payment_1_c` WHERE `j_payment_j_payment_1j_payment_ida` = '$casholder_id'");
        $GLOBALS['db']->query("DELETE FROM j_paymentdetail WHERE payment_id='{$casholder_id}' AND deleted = 0");
        if(!empty($delay_id)){
            $GLOBALS['db']->query("DELETE FROM j_payment WHERE id='$delay_id' AND deleted = 0");
            $GLOBALS['db']->query("DELETE FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_ida='$casholder_id' AND j_payment_j_payment_1j_payment_idb='$delay_id' AND deleted = 0");
        }
    }

    //    if(!empty($enrollment->id))
    //        $GLOBALS['db']->query("DELETE FROM j_payment WHERE enrollment_id='{$enrollment->id}' AND deleted = 0");
    $map_koc = array(
    'Atlantic 360° Flexi'     => 'Flexi',
    'Atlantic 360° Mobile'    => 'Mobile',
    'Atlantic 360° 1.2.1'     => 'Premium',
    'Atlantic 360° Premium'   => 'Premium',
    'Atlantic 360° Premium + Travel Expense' => 'Premium',
    'Ising Course'      => '',
    'Placement Test'    => '',
    'Other'             => '',
    );

    $payment_date = $timedate->to_db_date($_POST['payment_date'],false);
    $_POST['unpaid_amount'] = unformat_number($_POST['unpaid_amount']);
    $_POST['remain_amount'] = unformat_number($_POST['remain_amount']);

    $price = ($_POST['unpaid_amount'] + $_POST['remain_amount']) / $_POST['remain_days'];

    //Create new Delay
    if($_POST['remain_amount'] > 0){
        $pm_delay = new J_Payment();
        $pm_delay->contacts_j_payment_1contacts_ida = $enrollment->contact_id;
        $pm_delay->payment_type = 'Delay';
        $pm_delay->use_type = "Hour";

        $pm_delay->payment_date      = $timedate->to_db_date($_POST['payment_date'],false);
        $pm_delay->payment_expired   = date('Y-m-d',strtotime("+6 months ".$timedate->to_db_date($pm_delay->payment_date,false)));
        $pm_delay->payment_amount       = ($_POST['remain_amount']);
        $pm_delay->kind_of_course_360   = $map_koc[$pk->kind_of_course];

        $pm_delay->tuition_hours     = ($_POST['remain_amount']/$price);
        $pm_delay->total_hours       = ($_POST['remain_amount']/$price);
        $pm_delay->remain_amount     = 0;
        $pm_delay->remain_hours      = 0;
        //        $pm_delay->used_hours        = ($_POST['remain_amount']/$price);
        //        $pm_delay->used_amount       = ($_POST['remain_amount']);
        $pm_delay->remain_amount     = ($_POST['remain_amount']);
        $pm_delay->remain_hours      = ($_POST['remain_amount']/$price);


        $pm_delay->note              = 'Chuyển từ phân hệ cũ sang. ID: '.$enrollment->oder_id;
        $pm_delay->assigned_user_id     = $enrollment->assigned_user_id;
        $pm_delay->enrollment_id        = $enrollment->id;
        $pm_delay->team_id              = $enrollment->team_id;
        $pm_delay->team_set_id          = $enrollment->team_id;
        $pm_delay->save();
        $link_pm = $pm_delay->id;
    }

    //Tạo Cashholder
        if(!empty($casholder_id))
            $bean = BeanFactory::getBean('J_Payment',$casholder_id);
        else
            $bean = new J_Payment();
        $bean->payment_type      = 'Cashholder';
        $bean->contacts_j_payment_1contacts_ida = $enrollment->contact_id;
        $bean->remain_amount        = $_POST['remain_amount'] + $_POST['unpaid_amount'];
        $bean->tuition_fee          = $_POST['remain_amount'] + $_POST['unpaid_amount'];
        $bean->amount_bef_discount  = $_POST['unpaid_amount'];
        $bean->total_after_discount = $_POST['unpaid_amount'];
        $bean->payment_amount       = $_POST['unpaid_amount'];
        $bean->paid_amount          = $_POST['remain_amount'];
        $bean->paid_hours           = ($_POST['remain_amount']/$price);
        $bean->payment_date         = $timedate->to_db_date($_POST['payment_date'],false);
        if($enrollment->opportunity_type == 'New Business')
            $bean->sale_type            = 'New Sale';
        else $bean->sale_type            = 'Retention';
        $bean->sale_type_date       = $bean->payment_date;
        $bean->tuition_hours        = $_POST['remain_days'];
        $bean->total_hours          = $_POST['remain_days'] - $bean->paid_hours;
        $bean->remain_hours         = $_POST['remain_days'];
        $bean->enrollment_id        = $enrollment->id;
        $bean->kind_of_course_360   = $map_koc[$pk->kind_of_course];

        $bean->number_of_skill      = 0;
        $bean->number_of_practice   = 0;
        $bean->number_of_connect    = 0;

        $bean->payment_expired  = '';
        if(empty($bean->end_study)){
            $bean->start_study      = '';
            $bean->end_study        = '';
        }


        $bean->note                 = 'Chuyển từ phân hệ cũ sang. ID: '.$enrollment->oder_id;
        $bean->assigned_user_id     = $enrollment->assigned_user_id;
        $bean->team_id              = $enrollment->team_id;
        $bean->team_set_id          = $enrollment->team_id;
        $bean->save();
        $link_pm = $bean->id;
        if($_POST['remain_amount'] > 0)
            addRelatedPayment($bean->id, $pm_delay->id, $_POST['remain_amount'], ($_POST['remain_amount']/$price));

        //CREATE UNPAID PAYMENT
        $q1 = "SELECT DISTINCT IFNULL(c_payments.id,'') primaryid ,c_payments.payment_amount c_payments_payment_amount ,c_payments.payment_attempt c_payments_payment_attempt ,IFNULL( c_payments.currency_id,'') C_PAYMENTS_PAYMENT_AMOF1CE92 ,IFNULL(c_payments.status,'') c_payments_status ,c_payments.payment_date c_payments_payment_date, c_payments.serial_num, c_payments.invoice_num, c_payments.payment_method FROM c_payments INNER JOIN c_invoices_c_payments_1_c l1_1 ON c_payments.id=l1_1.c_invoices_c_payments_1c_payments_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_c_payments_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_opportunities_1_c l2_1 ON l1.id=l2_1.c_invoices_opportunities_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.c_invoices_opportunities_1opportunities_idb AND l2.deleted=0 WHERE (((l2.id='{$enrollment->id}' ))) AND c_payments.deleted=0 AND c_payments.remain=0  ORDER BY c_payments_payment_attempt ASC";
        $rs1 = $GLOBALS['db']->query($q1);

        $countt = 1;
        while($r1 = $GLOBALS['db']->fetchByAssoc($rs1)){
            if($r1['c_payments_status'] == "Unpaid" || ($r1['c_payments_payment_date'] >= $payment_date && $r1['c_payments_status'] == "Paid")){
                $pay = BeanFactory::getBean('C_Payments',$r1['primaryid']);

                $pmd = BeanFactory::newBean('J_PaymentDetail');
                $pmd->payment_no        = $countt;
                $pmd->name              = $bean->name."-$countt";
                $pmd->is_discount       = 1;
                $countt++;
                //DOAN CODE XUAT HOA DON
                if($pay->payment_type == 'Normal' && $pay->payment_attempt != '1'){
                    $count = 0;
                    $count_des = 0;
                    //Get Number Of Payment
                    $sql7 = "SELECT DISTINCT
                    IFNULL(c_payments.id, '') primaryid,
                    IFNULL(c_payments.payment_type, '') payment_type
                    FROM
                    c_payments
                    INNER JOIN
                    c_invoices_c_payments_1_c l1_1 ON c_payments.id = l1_1.c_invoices_c_payments_1c_payments_idb
                    AND l1_1.deleted = 0
                    INNER JOIN
                    c_invoices l1 ON l1.id = l1_1.c_invoices_c_payments_1c_invoices_ida
                    AND l1.deleted = 0
                    INNER JOIN
                    c_invoices_opportunities_1_c l2_1 ON l1.id = l2_1.c_invoices_opportunities_1c_invoices_ida
                    AND l2_1.deleted = 0
                    INNER JOIN
                    opportunities l2 ON l2.id = l2_1.c_invoices_opportunities_1opportunities_idb
                    AND l2.deleted = 0
                    WHERE
                    (((l2.id = '".$enrollment->id."')))
                    AND c_payments.deleted = 0";
                    $rs7 = $GLOBALS['db']->query($sql7);
                    while($row7 = $GLOBALS['db']->fetchByAssoc($rs7)){
                        $count++;
                        if($row7['payment_type'] == 'Deposit')
                            $count_des++;
                    }
                    if($count == 2 && $count_des == 1){
                        $pay->payment_attempt = '1';
                        $q5 = "UPDATE c_payments SET payment_attempt='1' WHERE id='{$pay->id}'";
                        $GLOBALS['db']->query($q5);
                    }
                }

                $price_temp = 'payment_price_'.$pay->payment_attempt;
                if($pay->payment_attempt == '0'){
                    $before_discount = $pay->payment_amount;
                    $total_discount = 0;
                    $after_discount = $pay->payment_amount;
                }elseif($pay->payment_attempt == '1'){
                    // kiểm tra trước đó có deposit ko
                    $q7 = "SELECT DISTINCT l2.payment_amount l2_payment_amount, IFNULL(l2.id,'') l2_id ,IFNULL( l2.currency_id,'') l2_payment_amount_currency FROM opportunities INNER JOIN c_invoices_opportunities_1_c l1_1 ON opportunities.id=l1_1.c_invoices_opportunities_1opportunities_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_opportunities_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_c_payments_1_c l2_1 ON l1.id=l2_1.c_invoices_c_payments_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN c_payments l2 ON l2.id=l2_1.c_invoices_c_payments_1c_payments_idb AND l2.deleted=0 WHERE (((opportunities.id='".$enrollment->id."' ) AND (l2.payment_type = 'Deposit' ))) AND opportunities.deleted=0";
                    $deposit = $GLOBALS['db']->getOne($q7);

                    $before_discount= ($pk->$price_temp - $deposit);
                    $total_discount = (($pk->$price_temp - $pay->payment_amount - $deposit) + $pay->discount_in_payment);
                    $after_discount = (($pk->$price_temp - $deposit) - $total_discount);

                }else{
                    // kiểm tra trước đó có tranfer moving ko
                    $q7 = "SELECT DISTINCT IFNULL(SUM(l2.payment_amount), 0) amount FROM opportunities INNER JOIN c_invoices_opportunities_1_c l1_1 ON opportunities.id=l1_1.c_invoices_opportunities_1opportunities_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_opportunities_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_c_payments_1_c l2_1 ON l1.id=l2_1.c_invoices_c_payments_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN c_payments l2 ON l2.id=l2_1.c_invoices_c_payments_1c_payments_idb AND l2.deleted=0 WHERE (((opportunities.id='".$enrollment->id."' ) AND ((l2.payment_type = 'Transfer in' ) OR (l2.payment_type = 'Moving in' ) OR (l2.payment_type = 'FreeBalance' )))) AND opportunities.deleted=0";
                    $transfer_moving_amount = $GLOBALS['db']->getOne($q7);
                    if($transfer_moving_amount > 0){
                        $q9 = "SELECT IFNULL(SUM(amount), 0) amount FROM opportunities WHERE id = '".$enrollment->id."'";
                        $enrollment_price   = $GLOBALS['db']->getOne($q9);
                        $before_discount    = ($enrollment_price - $transfer_moving_amount);
                        $total_discount     = ($subtotal - $pay->payment_amount);
                        $after_discount     = ($pay->payment_amount);
                    }else{
                        $before_discount = ($pk->$price_temp);
                        $total_discount = (($pk->$price_temp - $pay->payment_amount) + $pay->discount_in_payment);
                        $after_discount = ($pk->$price_temp  - $total_discount);
                    }
                }



                $pmd->before_discount   = $before_discount;
                $pmd->discount_amount   = $total_discount;
                $pmd->sponsor_amount    = 0;
                $pmd->payment_amount    = $after_discount;
                if($r1['c_payments_status'] == "Unpaid"){
                    $pmd->status            = "Unpaid";
                    $pmd->payment_date      = '';
                    $pmd->invoice_number    = '';
                    $pmd->serial_no         = '';
                    $pmd->payment_method    = '';
                    $pmd->type              = 'Normal';
                }else{
                    $pmd->status            = "Paid";
                    $pmd->payment_date      = $r1['c_payments_payment_date'];
                    $pmd->invoice_number    = $r1['invoice_num'];
                    $pmd->serial_no         = $r1['serial_num'];
                    $pmd->payment_method    = $r1['payment_method'];
                    if($pmd->payment_method == 'CreditDebitCard')
                        $pmd->payment_method = 'Card';
                    if($pmd->payment_method == 'BankTranfer')
                        $pmd->payment_method = 'Bank Transfer';
                    $pmd->type              = 'Normal';
                }
                $pmd->payment_id            = $bean->id;
                $pmd->assigned_user_id      = $bean->assigned_user_id;
                $pmd->modified_user_id      = $bean->assigned_user_id;
                $pmd->created_by            = $bean->assigned_user_id;
                $pmd->date_entered          = $bean->date_entered;
                $pmd->date_modified         = $bean->date_modified;
                $pmd->team_id               = $bean->team_id;
                $pmd->team_set_id           = $bean->team_id;
                $pmd->save();
            }
        }

    $GLOBALS['db']->query("UPDATE opportunities SET payment_id='$link_pm' WHERE id='{$enrollment->id}'");
    echo json_encode(array(
        "success" => "1"
    ));
}else{
    echo json_encode(array(
        "success" => "0",
        "html" => '',
    ));
}
?>
