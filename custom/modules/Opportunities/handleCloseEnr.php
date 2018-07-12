<?php
    // echo $_POST['total_payment'];           
    require_once("custom/modules/C_DeliveryRevenue/DeliveryRevenue.php");
    require_once("custom/include/_helper/class_utils.php");
    global $timedate;
    $cr_user_id = $GLOBALS['current_user']->id;
    $enr_id = $_POST['enr_id'];
    $enr = BeanFactory::getBean('Opportunities',$enr_id);
    $pack = BeanFactory::getBean('C_Packages',$enr->c_packages_opportunities_1c_packages_ida);
    if($enr->sales_stage == 'Closed'){
        echo json_encode(array("success" => "0"));
        return false;   
    }

    $student_id = $GLOBALS['db']->getOne("SELECT DISTINCT IFNULL(l1.id,'') l1_id FROM opportunities INNER JOIN opportunities_contacts l1_1 ON opportunities.id=l1_1.opportunity_id AND l1_1.deleted=0 INNER JOIN contacts l1 ON l1.id=l1_1.contact_id AND l1.deleted=0 WHERE (((opportunities.id='$enr_id' ))) AND opportunities.deleted=0");
    $student = BeanFactory::getBean('Contacts',$student_id);
    $end_date = $timedate->to_db_date($_POST['end_date'],false);
    $drop_date = $timedate->to_db_date($_POST['drop_date'],false);
    $month_year  = date('F Y', strtotime($drop_date));

    //Tin toan so du
    if($_POST['type'] == 'caculate_balance'){
        calDelivery('2014-01-01', $end_date, '', $student_id);
        $delivery_amount = $GLOBALS['db']->getOne("SELECT IFNULL(SUM(c_deliveryrevenue.amount), 0) total_amount FROM c_deliveryrevenue INNER JOIN meetings l1 ON c_deliveryrevenue.session_id=l1.id AND l1.deleted=0 INNER JOIN opportunities_meetings_1_c l2_1 ON l1.id=l2_1.opportunities_meetings_1meetings_idb AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.opportunities_meetings_1opportunities_ida AND l2.deleted=0 INNER JOIN users l3 ON c_deliveryrevenue.created_by=l3.id AND l3.deleted=0 WHERE (((l2.id='$enr_id' ) AND (l3.id='$cr_user_id' ))) AND c_deliveryrevenue.deleted=0");   
        $balance = $_POST['total_payment'] - $delivery_amount;
        echo json_encode(array(
            "success" => "1",
            "delivery_amount" => format_number($delivery_amount,0,0),
            "balance" => format_number($balance,0,0),
            "team_name" => $enr->team_name,
        ));


    }elseif($_POST['type'] == 'drop_to_free_balance' || $_POST['type'] == 'drop_to_revenue'){

        $delivery_amount = $GLOBALS['db']->getOne("SELECT IFNULL(SUM(c_deliveryrevenue.amount), 0) total_amount FROM c_deliveryrevenue INNER JOIN meetings l1 ON c_deliveryrevenue.session_id=l1.id AND l1.deleted=0 INNER JOIN opportunities_meetings_1_c l2_1 ON l1.id=l2_1.opportunities_meetings_1meetings_idb AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.opportunities_meetings_1opportunities_ida AND l2.deleted=0 INNER JOIN users l3 ON c_deliveryrevenue.created_by=l3.id AND l3.deleted=0 WHERE (((l2.id='$enr_id' ) AND (l3.id='$cr_user_id' ))) AND c_deliveryrevenue.deleted=0");   
        $balance = $_POST['total_payment'] - $delivery_amount;

        //Xoa hoc vien khoi session sau ngay ket thuc
        $end_db   = date('Y-m-d H:i:s',strtotime("-7 hours ".$end_date." 23:59:59"));
        $q3 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid FROM meetings INNER JOIN opportunities_meetings_1_c l1_1 ON meetings.id=l1_1.opportunities_meetings_1meetings_idb AND l1_1.deleted=0 INNER JOIN opportunities l1 ON l1.id=l1_1.opportunities_meetings_1opportunities_ida AND l1.deleted=0 WHERE (((l1.id='$enr_id' ) AND (meetings.meeting_type = 'Session' ) AND (meetings.date_start > '$end_db' ))) AND meetings.deleted=0";
        $rs3 = $GLOBALS['db']->query($q3);
        while($row3 = $GLOBALS['db']->fetchByAssoc($rs3)){
            removeStudentFromSession($student_id, $row3['primaryid']);  
        }

        //update Enrollment closed
        $q1 = "UPDATE opportunities SET sales_stage = 'Closed' WHERE id='$enr_id'";
        $GLOBALS['db']->query($q1);

        if($_POST['type'] == 'drop_to_free_balance' && $balance > 0){
            //update Enrollment closed
            $q1 = "UPDATE opportunities SET description='This enrollment has been droped to Free Balance of {$student->name}', sales_stage = 'Closed', expire_date='$end_date', date_modified = '".$timedate->nowDb()."', modified_user_id = '$cr_user_id' WHERE id='$enr_id'";
            $GLOBALS['db']->query($q1);
            
            $q10 = "SELECT DISTINCT IFNULL(c_payments.id,'') primaryid ,c_payments.payment_amount c_payments_payment_amount ,c_payments.payment_attempt c_payments_payment_attempt ,IFNULL( c_payments.currency_id,'') C_PAYMENTS_PAYMENT_AMOF1CE92 ,IFNULL(c_payments.status,'') c_payments_status ,c_payments.payment_date c_payments_payment_date FROM c_payments INNER JOIN c_invoices_c_payments_1_c l1_1 ON c_payments.id=l1_1.c_invoices_c_payments_1c_payments_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_c_payments_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_opportunities_1_c l2_1 ON l1.id=l2_1.c_invoices_opportunities_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.c_invoices_opportunities_1opportunities_idb AND l2.deleted=0 WHERE (((l2.id='{$enr_id}' ))) AND c_payments_status <> 'Deleted' AND c_payments.deleted=0 ORDER BY c_payments_payment_attempt ASC";
            $rs10 = $GLOBALS['db']->query($q10);
            while($r10 = $GLOBALS['db']->fetchByAssoc($rs10)){
                if($r10['c_payments_status'] == 'Paid')                                
                    $q1 = "UPDATE c_payments SET description='The enrollment of this payment has been droped to Free Balance of {$student->name}', converted_to_revenue = 1 WHERE id='{$r10['primaryid']}'";
                else   //Delete payment unpaid                             
                    $q1 = "UPDATE c_payments SET description='The enrollment of this payment has been droped to Free Balance of {$student->name}', converted_to_revenue = 1, status='Deleted' WHERE id='{$r10['primaryid']}'";

                $GLOBALS['db']->query($q1);   
            }
            //Create Payment Free Balance
            $pm = new C_Payments();
            $pm->drop_from_enrollment_id = $enr->id;
            $pm->contacts_c_payments_1contacts_ida = $enr->parent_id;
            $pm->payment_type = 'FreeBalance';
            $pm->payment_method = 'Other';
            $pm->status = 'Paid';
            $pm->payment_amount = format_number($balance);
            $pm->remain = format_number($balance);
            $pm->payment_date = $drop_date;
            $pm->assigned_user_id = $cr_user_id; 
            $pm->team_id = $enr->team_id;
            $pm->team_set_id = $enr->team_id;
            $pm->save();

        }elseif($_POST['type'] == 'drop_to_revenue' && $balance > 0){

            $delivery = new C_DeliveryRevenue();
            $delivery->name = 'Converted Enrollment '.$enr->name.' to revenue';
            $delivery->student_id = $student_id;
            $delivery->enrollment_id = $enr->id;
            $delivery->duration = 0;
            $delivery->amount = format_number($balance);
            $delivery->date_input = $drop_date;
            $delivery->cost_per_hour = 0;
            $delivery->session_id = '1';
            $delivery->passed = 0;
            $delivery->team_id = $enr->team_id;
            $delivery->team_set_id = $enr->team_set_id;
            $delivery->assigned_user_id = '1';
            $delivery->created_by = '1';
            $delivery->modified_user_id = '1';
            $delivery->save();


            $q10 = "SELECT DISTINCT IFNULL(c_payments.id,'') primaryid ,c_payments.payment_amount c_payments_payment_amount ,c_payments.payment_attempt c_payments_payment_attempt ,IFNULL( c_payments.currency_id,'') C_PAYMENTS_PAYMENT_AMOF1CE92 ,IFNULL(c_payments.status,'') c_payments_status ,c_payments.payment_date c_payments_payment_date FROM c_payments INNER JOIN c_invoices_c_payments_1_c l1_1 ON c_payments.id=l1_1.c_invoices_c_payments_1c_payments_idb AND l1_1.deleted=0 INNER JOIN c_invoices l1 ON l1.id=l1_1.c_invoices_c_payments_1c_invoices_ida AND l1.deleted=0 INNER JOIN c_invoices_opportunities_1_c l2_1 ON l1.id=l2_1.c_invoices_opportunities_1c_invoices_ida AND l2_1.deleted=0 INNER JOIN opportunities l2 ON l2.id=l2_1.c_invoices_opportunities_1opportunities_idb AND l2.deleted=0 WHERE (((l2.id='{$enr_id}' ))) AND c_payments.deleted=0 ORDER BY c_payments_payment_attempt ASC";
            $rs10 = $GLOBALS['db']->query($q10);
            while($r10 = $GLOBALS['db']->fetchByAssoc($rs10)){
                $q1 = "UPDATE c_payments SET description='The enrollment of this payment has been droped to revenue of $month_year', converted_to_revenue = 1 WHERE id='{$r10['primaryid']}'";
                $GLOBALS['db']->query($q1);   
            }

            //update Enrollment closed
            $q1 = "UPDATE opportunities SET description='This enrollment has been droped to revenue of $month_year', sales_stage = 'Closed', expire_date='$end_date', date_modified = '".$timedate->nowDb()."', modified_user_id = '$cr_user_id' WHERE id='$enr_id'";
            $GLOBALS['db']->query($q1);

        }
        //Xoa cac payment Unpaid
        $q23 = "SELECT DISTINCT
        IFNULL(c_payments.id, '') primaryid,
        c_payments.payment_amount c_payments_payment_amount
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
        (((l2.id = '$enr_id')
        AND (c_payments.status = 'Unpaid')))
        AND c_payments.deleted = 0";
        while($r23 = $GLOBALS['db']->fetchByAssoc($GLOBALS['db']->query($q23))){
            $qx = "UPDATE c_payments SET deleted = 1 WHERE id='{$r23['primaryid']}'";
            $GLOBALS['db']->query($qx); 
        }

        echo json_encode(array(
            "success" => "1",
        ));
    }
?>
