<?php
class logicPayment{
    //Before delete
    function deletedPayment(&$bean, $event, $arguments){
        require_once("custom/include/_helper/junior_revenue_utils.php");
        require_once("custom/include/_helper/junior_class_utils.php");
        global $current_user, $timedate;
        $team_type = getTeamType($bean->team_id);
        if(empty($bean->payment_type) || empty($bean->id)){
            echo '
            <script type="text/javascript">
            alert(" Something Wrong, Please, try again !!");
            location.href=\'index.php?module=J_Payment&action=DetailView&record='.$bean->id.'\';
            </script>';
            die();
        }

        if ($event == "before_delete"){
            $sqlPayDtl = "SELECT DISTINCT
            id,
            IFNULL(payment_no, '') payment_no,
            IFNULL(status, '') status,
            IFNULL(invoice_number, '') invoice_number,
            IFNULL(payment_amount, '0') payment_amount
            FROM j_paymentdetail
            WHERE payment_id = '{$bean->id}'
            AND deleted = 0
            AND status <> 'Cancelled'
            ORDER BY payment_no";
            $resultPayDtl = $GLOBALS['db']->query($sqlPayDtl);
            $countVAT       = 0;
            $countEmptyVAT  = 0;
            while($rowPayDtl = $GLOBALS['db']->fetchByAssoc($resultPayDtl)){
                if(empty($rowPayDtl['invoice_number']))
                    $countEmptyVAT++;
                elseif($rowPayDtl['status'] == 'Paid') $countVAT++;
            }
            $countUsed = $GLOBALS['db']->getOne("SELECT count(id) count FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_idb = '{$bean->id}' AND deleted = 0");
            $arr_Undel = array('Delay', 'Schedule Change', 'Transfer In', 'Transfer Out', 'Moving In', 'Moving Out', 'Refund', 'Book/Gift', 'Corporate', 'Transfer From AIMS', 'Merge AIMS');
            if( $current_user->isAdmin() || (!in_array($bean->payment_type, $arr_Undel) && ($countVAT == 0) && ($countUsed == 0) && checkDataLockDate($bean->payment_date)) ){
                $resultPayDtl = $GLOBALS['db']->query($sqlPayDtl);
                while($rowPayDtl = $GLOBALS['db']->fetchByAssoc($resultPayDtl)){
                    $sql = "UPDATE j_paymentdetail SET deleted = 1 WHERE id = '{$rowPayDtl['id']}'";
                    $GLOBALS['db']->query($sql);
                }
                //Delete Invoice
                $j_invID  = $GLOBALS['db']->getOne("SELECT IFNULL(id, '') id FROM j_invoice WHERE payment_id='{$bean->id}' AND deleted = 0");
                if(!empty($j_invID)){
                    $j_inv = BeanFactory::getBean('J_Invoice', $j_invID);
                    $j_inv->deleted = 1;
                    $j_inv->save();
                }
            }else{
                echo '
                <script type="text/javascript">
                alert("This transaction was completed and invoiced. You can not delete !");
                location.href=\'index.php?module=J_Payment&action=DetailView&record='.$bean->id.'\';
                </script>';
                die();
            }
        }

        if($bean->payment_type == 'Enrollment' || ($bean->payment_type == 'Cashholder' && $team_type == 'Adult') || ($bean->payment_type == 'Corporate' && $team_type == 'Adult')){
            // Get all Situation
            $q2 = "SELECT DISTINCT id, type, settle_from_outstanding_id, ju_class_id FROM j_studentsituations WHERE payment_id = '{$bean->id}' AND deleted = 0";
            $rs2 = $GLOBALS['db']->query($q2);
            while($rows = $GLOBALS['db']->fetchByAssoc($rs2)) {
                //Remove All Session From Payment
                removeJunFromSession($rows['id']);

                if($rows['type'] == 'Settle'){
                    $q1 = "SELECT DISTINCT id, start_study, end_study FROM j_studentsituations WHERE id = '{$rows['settle_from_outstanding_id']}'";
                    $rs1 = $GLOBALS['db']->query($q1);
                    $rowo = $GLOBALS['db']->fetchByAssoc($rs1);
                    if(!empty($rowo)){
                        $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted=0 WHERE id = '{$rowo['id']}'");

                        $sss = get_list_lesson_by_class($rows['ju_class_id'], $timedate->to_display_date($rowo['start_study'],false), $timedate->to_display_date($rowo['end_study'],false));
                        for($i = 0; $i < count($sss); $i++)
                            addJunToSession($rowo['id'] , $sss[$i]['primaryid'] );

                        //Update Situation Time
                        $ses = get_list_lesson_by_situation('', $rowo['id'], '', '', 'INNER');
                        $first = reset($ses);
                        $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

                        $last = end($ses);
                        $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

                        if(!empty($date_last) && !empty($date_first)){
                            $q3 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last' WHERE id='{$rowo['id']}'";
                            $GLOBALS['db']->query($q3);
                        }
                    }
                }
            }

            // Remove Student from "Enrolled" Situation
            $GLOBALS['db']->query("DELETE FROM j_studentsituations
                WHERE payment_id='{$bean->id}'");


            // Remove Discount
            $GLOBALS['db']->query("DELETE FROM j_payment_j_discount_1_c
                WHERE j_payment_j_discount_1j_payment_ida = '{$bean->id}'");

            // Remove partnership
            $GLOBALS['db']->query("DELETE FROM j_partnership_j_payment_1_c
                WHERE j_partnership_j_payment_1j_payment_idb = '{$bean->id}'");

            // Restore payments
            $payment_list_json = $GLOBALS['db']->getOne("SELECT payment_list FROM j_payment WHERE id = '{$bean->id}'");
            $old_payments = json_decode(html_entity_decode($payment_list_json),true);
            foreach($old_payments["paid_list"] as $pay_id => $value){
                $rs_rm = $GLOBALS['db']->query("SELECT
                    used_amount,
                    used_hours,
                    remain_amount,
                    remain_hours,
                    payment_date
                    FROM
                    j_payment
                    WHERE id = '$pay_id' AND deleted = 0");
                $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);
                if(!empty($row_rm)){
                    $used_amount    = $row_rm['used_amount'] - $value["used_amount"];
                    $used_hours     = $row_rm['used_hours'] - $value["used_hours"];
                    $remain_amount  = $row_rm['remain_amount'] + $value["used_amount"];
                    $remain_hours   = $row_rm['remain_hours'] + $value["used_hours"];

                    $GLOBALS['db']->query("UPDATE j_payment
                        SET
                        used_amount = $used_amount,
                        used_hours = $used_hours,
                        remain_amount = $remain_amount,
                        remain_hours = $remain_hours
                        WHERE id = '$pay_id'");
                    //                    //Restore Sale Type Related Payment - Fix tạm
                    if($event == 'before_delete' && ($bean->sale_type == 'New Sale' || $bean->sale_type == 'Retention')){
                        $countUsedCas = $GLOBALS['db']->getOne("SELECT count(id) count FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_idb = '$pay_id' AND deleted = 0");
                        if($countUsedCas <= 1){
                            $restore_sale_type = checkSaleType($pay_id);
                            $ext_sale_type_date = ", sale_type_date = '".$timedate->to_db_date($old_payment->payment_date,false)."'";
                            if(empty($old_payment->payment_date))
                                $ext_sale_type_date = '';
                            $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$restore_sale_type' $ext_sale_type_date WHERE id = '{$pay_id}'");
                        }
                    }
                }
            }

            foreach($old_payments["deposit_list"] as $pay_id => $value){
                $rs_rm = $GLOBALS['db']->query("SELECT
                    used_amount,
                    remain_amount,
                    payment_date
                    FROM
                    j_payment
                    WHERE id = '$pay_id' AND deleted = 0");
                $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);
                if(!empty($row_rm)){
                    $used_amount    = $row_rm['used_amount'] - $value["used_amount"];
                    $remain_amount  = $row_rm['remain_amount'] + $value["used_amount"];

                    $GLOBALS['db']->query("UPDATE j_payment
                        SET
                        used_amount = $used_amount,
                        remain_amount = $remain_amount
                        WHERE
                        id = '$pay_id'");
                    //Restore Sale Type Related Payment
                    if($event == 'before_delete' && ($bean->sale_type == 'New Sale' || $bean->sale_type == 'Retention')){
                        $countUsedDes = $GLOBALS['db']->getOne("SELECT count(id) count FROM j_payment_j_payment_1_c WHERE j_payment_j_payment_1j_payment_idb = '$pay_id' AND deleted = 0");
                        if( $countUsedDes <= 1){
                            $restore_sale_type = checkSaleType($pay_id);
                            $ext_sale_type_date = ", sale_type_date = '".$timedate->to_db_date($old_payment->payment_date,false)."'";
                            if(empty($old_payment->payment_date))
                                $ext_sale_type_date = '';
                            $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$restore_sale_type' $ext_sale_type_date WHERE id = '{$pay_id}'");
                        }
                    }
                }
            }

            // Remove Payments
            removeRelatedPayment($bean->id);

        }
        elseif($bean->payment_type == 'Cashholder' || $bean->payment_type == 'Deposit'){
            // Remove Discount
            $GLOBALS['db']->query("DELETE FROM j_payment_j_discount_1_c WHERE j_payment_j_discount_1j_payment_ida = '{$bean->id}'");
            // Remove partnership
            $GLOBALS['db']->query("DELETE FROM j_partnership_j_payment_1_c WHERE j_partnership_j_payment_1j_payment_idb = '{$bean->id}'");
        }elseif(($bean->payment_type == 'Delay' && $team_type == 'Junior')){
            $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE payment_id='{$bean->id}'");
        }
        if($bean->payment_type == 'Book/Gift'){
            $q101 = "SELECT DISTINCT
            IFNULL(l1.id, '') l1_id, IFNULL(l1.name, '') l1_name
            FROM
            j_payment
            INNER JOIN
            j_payment_j_inventory_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_inventory_1j_payment_ida
            AND l1_1.deleted = 0
            INNER JOIN
            j_inventory l1 ON l1.id = l1_1.j_payment_j_inventory_1j_inventory_idb
            AND l1.deleted = 0
            WHERE
            (((j_payment.id = '{$bean->id}')))
            AND j_payment.deleted = 0";
            $rs101 = $GLOBALS['db']->query($q101);
            while($row101 = $GLOBALS['db']->fetchByAssoc($rs101)){
                $GLOBALS['db']->query("DELETE FROM j_inventorydetail WHERE inventory_id = '{$row101['l1_id']}'");
                $GLOBALS['db']->query("DELETE FROM j_payment_j_inventory_1_c WHERE j_payment_j_inventory_1j_inventory_idb = '{$row101['l1_id']}'");
                $GLOBALS['db']->query("DELETE FROM j_inventory WHERE id = '{$row101['l1_id']}'");
            }
        }

        // Remove Payment Detail
        if(($event == 'before_save') && ($_POST['module'] == $bean->module_name) && ($_POST['action'] == 'Save')){
            $GLOBALS['db']->query("UPDATE j_paymentdetail SET deleted=1, date_modified='{$timedate->nowDb()}' WHERE payment_id='{$bean->id}' AND status <> 'Cancelled' AND deleted=0");
            //Delete Invoice
            $j_invID  = $GLOBALS['db']->getOne("SELECT IFNULL(id, '') id FROM j_invoice WHERE payment_id='{$bean->id}' AND deleted = 0");
            if(!empty($j_invID)){
                $j_inv = BeanFactory::getBean('J_Invoice', $j_invID);
                $j_inv->deleted = 1;
                $j_inv->save();
            }
        }

        $GLOBALS['db']->query("DELETE FROM j_sponsor WHERE payment_id = '{$bean->id}'");
        $GLOBALS['db']->query("DELETE FROM j_loyalty WHERE payment_id = '{$bean->id}'");

        // Remove Revenue Drop
        $GLOBALS['db']->query("DELETE FROM c_deliveryrevenue WHERE ju_payment_id = '{$bean->id}' AND deleted = 0 AND passed = 0");

        //change new student date
        if($bean->is_new_student == 1 && $event == 'before_delete'){
            $sql_get_invoice_date = "SELECT payment_date FROM j_paymentdetail WHERE payment_id = '{$bean->id}'
            AND status = 'Paid' AND deleted = 0 ORDER BY payment_date LIMIT 1";
            $inv_date = $GLOBALS['db']->getOne($sql_get_invoice_date);
            set_next_payment($bean->contacts_j_payment_1contacts_ida, $bean->id, $inv_date);
        }
        if($bean->payment_type != 'Corporate' && $bean->payment_type != 'Delay')
            update_remain_last_date($bean->contacts_j_payment_1contacts_ida);
    }
    //before save
    function handleBeforeSave($bean, $event, $arguments) {
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            if(empty($bean->payment_type)){
                if(!empty($bean->contacts_j_payment_1contacts_ida)){
                    echo '
                    <script type="text/javascript">
                    alert(" Something Wrong, Please, try again !!");
                    location.href=\'index.php?module=Contacts&action=DetailView&record='.$bean->contacts_j_payment_1contacts_ida.'\';
                    </script>';
                }else{
                    echo '
                    <script type="text/javascript">
                    alert(" Something Wrong, Please, try again !!");
                    location.href=\'index.php?module=Home&action=index\';
                    </script>';
                }
                die();
            }
            require_once('custom/include/_helper/junior_class_utils.php');
            require_once("custom/include/_helper/junior_revenue_utils.php");

            global $timedate, $app_list_strings, $current_user;
            $team_type = getTeamType($bean->team_id);

            //Delete relationship before edit
            if(!empty($bean->fetched_row)) logicPayment::deletedPayment($bean, $event, $arguments);

            if($bean->payment_type == 'Enrollment'){
                $student = BeanFactory::getBean('Contacts',$bean->contacts_j_payment_1contacts_ida);

                //Check Duplicate in Class và die
                foreach ($_POST['classes'] as $key => $class_id){
                    if(is_exist_in_class($student->id, $_POST['start_study'], $_POST['end_study'], $class_id, "'Enrolled', 'Moving In', 'Settle'")){
                        $class_name = $GLOBALS['db']->getOne("SELECT name FROM j_class WHERE id = '$class_id'");
                        sugar_die("<b>An error occurred while saving. This student is already exist in class <a href='index.php?module=J_Class&action=DetailView&record={$class_id}'>$class_name</a> from {$_POST['start_study']} to {$_POST['end_study']}. Please check the link or <a href='index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment&student_id={$student->id}'>click here to enroll again !</a></b>");
                    }
                }

                //Add To Class
                $bean->number_class = count($_POST['classes']);
                addToClass($bean, $_POST['classes']);

                //Add Relationship Payment - Payment, save used payments
                if(!empty($bean->payment_list)){
                    $json_payment = json_decode(html_entity_decode($bean->payment_list),true);
                    foreach($json_payment["paid_list"] as $pay_id => $value){
                        $rs_rm = $GLOBALS['db']->query("SELECT
                            used_amount,
                            used_hours,
                            remain_amount,
                            remain_hours,
                            payment_type,
                            delay_situation_id,
                            payment_date
                            FROM
                            j_payment
                            WHERE id = '$pay_id' AND deleted = 0");
                        $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);

                        if($row_rm['payment_type'] = 'Delay')
                            $bean->delay_situation_id =  $row_rm['delay_situation_id'];

                        $used_amount    = $row_rm['used_amount'] + $value["used_amount"];
                        $used_hours     = $row_rm['used_hours'] + $value["used_hours"];
                        $remain_amount  = $row_rm['remain_amount'] - $value["used_amount"];
                        $remain_hours   = $row_rm['remain_hours'] - $value["used_hours"];


                        $GLOBALS['db']->query("UPDATE j_payment
                            SET
                            used_amount = $used_amount,
                            used_hours = $used_hours,
                            remain_amount = $remain_amount,
                            remain_hours = $remain_hours
                            WHERE
                            id = '$pay_id'");

                        addRelatedPayment($bean->id, $pay_id, $value["used_amount"], $value["used_hours"]);
                    }
                    foreach($json_payment["deposit_list"] as $pay_id => $value){
                        $rs_rm = $GLOBALS['db']->query("SELECT
                            used_amount,
                            remain_amount,
                            payment_type,
                            delay_situation_id,
                            payment_date
                            FROM
                            j_payment
                            WHERE id = '$pay_id' AND deleted = 0");
                        $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);

                        if($row_rm['payment_type'] = 'Delay')
                            $bean->delay_situation_id =  $row_rm['delay_situation_id'];

                        $used_amount    = $row_rm['used_amount'] + $value["used_amount"];
                        $remain_amount  = $row_rm['remain_amount'] - $value["used_amount"];


                        $GLOBALS['db']->query("UPDATE j_payment
                            SET
                            used_amount = $used_amount,
                            remain_amount = $remain_amount
                            WHERE id = '$pay_id'");
                        addRelatedPayment($bean->id, $pay_id, $value["used_amount"], 0);
                    }

                }

                //Calculate Payment Expire
                $bean->payment_expired = date('Y-m-d',strtotime("+12 months ".$bean->end_study));
            }
            elseif($bean->payment_type == 'Cashholder' && $team_type == 'Junior'){
                $student = BeanFactory::getBean('Contacts',$bean->contacts_j_payment_1contacts_ida);

                //Nap so remain
                $bean->remain_amount    = $bean->payment_amount - $bean->used_amount;
                $bean->remain_hours     = $bean->tuition_hours - $bean->used_hours;
                $bean->tuition_fee      = $bean->amount_bef_discount;

                //Calculate Payment Expire
                $bean->payment_expired = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));

            }elseif($bean->payment_type == 'Cashholder' && $team_type == 'Adult'){
                $student = BeanFactory::getBean('Contacts',$bean->contacts_j_payment_1contacts_ida);
                $q10    = "SELECT number_of_practice, number_of_skill, number_of_connect FROM j_coursefee WHERE id = '{$bean->j_coursefee_j_payment_1j_coursefee_ida}' AND deleted = 0";
                $rs10   = $GLOBALS['db']->query($q10);
                $row_course_fee = $GLOBALS['db']->fetchByAssoc($rs10);
                //Nap so remain
                $bean->remain_amount    = $bean->payment_amount;
                $bean->remain_hours     = $bean->tuition_hours;
                //                $bean->tuition_fee      = $bean->amount_bef_discount;

                $bean->number_of_skill      = $row_course_fee['number_of_skill'];
                $bean->number_of_practice   = $row_course_fee['number_of_practice'];
                $bean->number_of_connect    = $row_course_fee['number_of_connect'];
                $bean->total_hours_adult    = ($row_course_fee['number_of_practice'] + $row_course_fee['number_of_practice'] + $row_course_fee['number_of_connect'])*3;
                //Calculate Payment Expire
                $bean->payment_expired  = '';
                $bean->start_study      = '';
                $bean->end_study        = '';
                //ReActive portal account
                $GLOBALS['db']->query("UPDATE users SET status = 'Active' WHERE portal_contact_id = '{$bean->contacts_j_payment_1contacts_ida}'");
                //Add Relationship Payment - Payment, save used payments
                if(!empty($bean->payment_list)){
                    $json_payment = json_decode(html_entity_decode($bean->payment_list),true);
                    foreach($json_payment["paid_list"] as $pay_id => $value){
                        $rs_rm = $GLOBALS['db']->query("SELECT
                            used_amount,
                            used_hours,
                            remain_amount,
                            remain_hours,
                            payment_type,
                            delay_situation_id,
                            payment_date
                            FROM
                            j_payment
                            WHERE id = '$pay_id' AND deleted = 0");
                        $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);

                        if($row_rm['payment_type'] = 'Delay')
                            $bean->delay_situation_id =  $row_rm['delay_situation_id'];

                        $used_amount    = $row_rm['used_amount'] + $value["used_amount"];
                        $used_hours     = $row_rm['used_hours'] + $value["used_hours"];
                        $remain_amount  = $row_rm['remain_amount'] - $value["used_amount"];
                        $remain_hours   = $row_rm['remain_hours'] - $value["used_hours"];

                        $bean->remain_amount += $value["used_amount"];
                        $GLOBALS['db']->query("UPDATE j_payment
                            SET
                            used_amount = $used_amount,
                            used_hours = $used_hours,
                            remain_amount = $remain_amount,
                            remain_hours = $remain_hours
                            WHERE
                            id = '$pay_id'");

                        addRelatedPayment($bean->id, $pay_id, $value["used_amount"], $value["used_hours"]);
                    }
                    foreach($json_payment["deposit_list"] as $pay_id => $value){
                        $rs_rm = $GLOBALS['db']->query("SELECT
                            used_amount,
                            remain_amount,
                            payment_type,
                            delay_situation_id,
                            payment_date
                            FROM
                            j_payment
                            WHERE id = '$pay_id' AND deleted = 0");
                        $row_rm         = $GLOBALS['db']->fetchByAssoc($rs_rm);

                        if($row_rm['payment_type'] = 'Delay')
                            $bean->delay_situation_id =  $row_rm['delay_situation_id'];

                        $used_amount    = $row_rm['used_amount'] + $value["used_amount"];
                        $remain_amount  = $row_rm['remain_amount'] - $value["used_amount"];

                        $bean->remain_amount += $value["used_amount"];
                        $GLOBALS['db']->query("UPDATE j_payment
                            SET
                            used_amount = $used_amount,
                            remain_amount = $remain_amount
                            WHERE id = '$pay_id'");
                        addRelatedPayment($bean->id, $pay_id, $value["used_amount"], 0);
                    }

                }
                //ReActive portal account
                $GLOBALS['db']->query("UPDATE users SET status = 'Active' WHERE portal_contact_id = '{$bean->contacts_j_payment_1contacts_ida}'");
            }
            elseif($bean->payment_type == 'Deposit'){
                if($bean->parent_type == "Leads")
                    $bean->lead_id = $bean->contacts_j_payment_1contacts_ida;
                //Nap so remain
                $bean->remain_amount    = $bean->payment_amount - $bean->used_amount;
                $bean->use_type         = 'Amount';
                $bean->start_study      = '';
                $bean->end_study        = '';

                //Calculate Payment Expire
                $bean->payment_expired = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));

                $bean->tuition_fee              = $bean->payment_amount;
                $bean->amount_bef_discount      = $bean->payment_amount;
                $bean->total_after_discount     = $bean->payment_amount;
            }elseif($bean->payment_type == 'Placement Test' || $bean->payment_type == 'Freeze Fee' || $bean->payment_type == 'Tutor Package' || $bean->payment_type == 'Travelling Fee' ){
                $bean->amount_bef_discount      = $bean->payment_amount;
                $bean->total_after_discount     = $bean->payment_amount;
                $bean->payment_expired = $bean->payment_date;
                if($bean->parent_type == "Leads")
                    $bean->lead_id = $bean->contacts_j_payment_1contacts_ida;


                //Vào doanh thu luôn
                ///****
                $delivery = new C_DeliveryRevenue();
                $delivery->name = 'Drop revenue from payment '.$bean->name;
                $delivery->student_id = $bean->contacts_j_payment_1contacts_ida;
                //Get Payment ID
                $delivery->ju_payment_id = $bean->id;
                $delivery->type = 'Junior';
                $delivery->amount = $bean->payment_amount;
                $delivery->duration = 0;
                $delivery->date_input = $bean->payment_expired;
                $delivery->session_id = '1';
                $delivery->passed = 0;
                $delivery->description = ' Dropped Revenue. Payment '.$bean->name.' expired at '.$timedate->to_display_date($bean->payment_expired,false);
                $delivery->team_id = $bean->team_id;
                $delivery->team_set_id = $bean->team_id;
                $delivery->cost_per_hour = 0;
                $delivery->assigned_user_id = $bean->assigned_user_id;
                $delivery->revenue_type = 'Enrolled';
                $delivery->save();  ///#####
            }
            elseif($bean->payment_type == 'Transfer Out') {
                // Load target student
                $target_student = BeanFactory::getBean("Contacts", $_POST["transfer_to_student_id"]);
                // Set some field in transfers out payment
                $ttotal_hour = 0;
                $bean->payment_date = $timedate->to_db_date($_POST["moving_tran_out_date"],false);
                // Save relationship to old payments
                $paymentList = json_decode(html_entity_decode($_POST["json_payment_list"]),true);
                foreach($paymentList as $pay_id => $value){

                    $relatedPayment = BeanFactory::getBean('J_Payment',$pay_id);
                    if($used_payment->payment_type = 'Delay'){
                        $bean->delay_situation_id = $relatedPayment->delay_situation_id;
                    }
                    $pay_amount     = unformat_number(format_number($relatedPayment->remain_amount));
                    $total_hours    = unformat_number(format_number($relatedPayment->remain_hours,2,2));
                    $ttotal_hour += $total_hours;
                    //Link payment Corporate
                    $bean->contract_id  = $relatedPayment->contract_id;

                    $bean->team_id      = $relatedPayment->team_id;
                    $bean->team_set_id  = $relatedPayment->team_id;
                    $GLOBALS['db']->query("UPDATE j_payment
                        SET
                        used_amount = used_amount + $pay_amount,
                        used_hours = used_hours + $total_hours,
                        remain_amount =remain_amount - $pay_amount,
                        remain_hours = remain_hours - $total_hours
                        WHERE id = '$pay_id'");
                    addRelatedPayment($bean->id, $pay_id, $pay_amount, $total_hours);
                }
                //Set Use_type
                $bean->use_type = "Amount";
                if($ttotal_hour > 0)
                    $bean->use_type     = 'Hour';
                $bean->total_hours      = $ttotal_hour;
                $bean->tuition_hours    = $bean->total_hours;
                // Create tranfer in payment
                $pay_in                     = BeanFactory::newBean("J_Payment");
                $pay_in->payment_type       = 'Transfer In';
                $pay_in->payment_amount     = $bean->payment_amount;
                $pay_in->remain_amount      = $bean->payment_amount;
                $pay_in->use_type           = $bean->use_type;
                $pay_in->description        = $bean->description;
                $pay_in->contract_id        = $bean->contract_id;
                $pay_in->total_hours        = $bean->total_hours;
                $pay_in->remain_hours       = $bean->total_hours;
                $pay_in->tuition_hours         = $bean->total_hours;
                $pay_in->payment_date       = $_POST["moving_tran_in_date"];
                $pay_in->assigned_user_id     = $target_student->assigned_user_id;
                $pay_in->team_id             = $target_student->team_id;
                $pay_in->team_set_id         = $target_student->team_id;
                $pay_in->payment_out_id     = $bean->id;
                $pay_in->contacts_j_payment_1contacts_ida = $target_student->id;
                //Calculate Payment Expire
                $pay_in->payment_expired = $timedate->to_display_date(date('Y-m-d',strtotime("+12 months ".$timedate->to_db_date($pay_in->payment_date))),false);
                $pay_in->start_study      = '';
                $pay_in->end_study        = '';
                $pay_in->save();
                //Moving From Center
                $bean->move_from_center_id  = $bean->team_id;
                // To Center
                $bean->move_to_center_id = $target_student->team_id;

                $bean->start_study      = '';
                $bean->end_study        = '';
            }
            elseif($bean->payment_type == 'Moving Out') {
                // Change team id for student
                $student = BeanFactory::getBean("Contacts", $_POST["contacts_j_payment_1contacts_ida"]);
                $ttotal_hour = 0;
                $student->load_relationship("teams");
                $student->teams->add($bean->move_to_center_id);
                $student->team_id = $bean->move_to_center_id;
                $student->save();
                // Set some field in moving out payment
                $bean->payment_type = 'Moving Out';
                $bean->payment_date = $timedate->to_db_date($_POST["moving_tran_out_date"],false);
                // Save relationship to old payments
                $paymentList = json_decode(html_entity_decode($_POST["json_payment_list"]),true);
                foreach($paymentList as $pay_id => $value){

                    $relatedPayment = BeanFactory::getBean('J_Payment',$pay_id);
                    if($used_payment->payment_type = 'Delay')
                        $bean->delay_situation_id = $relatedPayment->delay_situation_id;

                    $pay_amount     = unformat_number(format_number($relatedPayment->remain_amount));
                    $total_hours    = unformat_number(format_number($relatedPayment->remain_hours,2,2));
                    $ttotal_hour += $total_hours;
                    $bean->team_id      = $relatedPayment->team_id;
                    $bean->team_set_id  = $relatedPayment->team_id;

                    //Link payment Corporate
                    $bean->contract_id  = $relatedPayment->contract_id;

                    $GLOBALS['db']->query("UPDATE j_payment
                        SET
                        used_amount = used_amount + $pay_amount,
                        used_hours = used_hours + $total_hours,
                        remain_amount =remain_amount - $pay_amount,
                        remain_hours = remain_hours - $total_hours
                        WHERE
                        id = '$pay_id'");
                    addRelatedPayment($bean->id, $pay_id, $pay_amount, $total_hours);
                }
                //Set Use_type
                $bean->use_type = "Amount";
                if($ttotal_hour > 0)
                    $bean->use_type     = 'Hour';
                $bean->total_hours      = $ttotal_hour;
                $bean->tuition_hours    = $bean->total_hours;
                // Create moving in payment
                $pay_in = BeanFactory::newBean("J_Payment");
                $pay_in->payment_type         = 'Moving In';
                $pay_in->total_hours        = $bean->total_hours;
                $pay_in->remain_hours       = $bean->total_hours;
                $pay_in->tuition_hours         = $bean->total_hours;
                $pay_in->payment_amount        = $bean->payment_amount;
                $pay_in->remain_amount         = $bean->payment_amount;
                $pay_in->use_type           = $bean->use_type;
                $pay_in->description        = $bean->description;
                $pay_in->contract_id         = $bean->contract_id;
                $pay_in->payment_date         = $_POST["moving_tran_in_date"];
                $pay_in->assigned_user_id     = $bean->assigned_user_id;
                $pay_in->team_id             = $bean->move_to_center_id;
                $pay_in->team_set_id         = $bean->move_to_center_id;
                $pay_in->contacts_j_payment_1contacts_ida = $student->id;
                $pay_in->payment_out_id     = $bean->id;
                //Moving From Center
                $bean->move_from_center_id  = $bean->team_id;
                // To Student
                $bean->transfer_to_student_id = $student->id;

                $bean->tuition_hours = $bean->total_hours;
                //Calculate Payment Expire
                $pay_in->payment_expired     = $timedate->to_display_date(date('Y-m-d',strtotime("+12 months ".$timedate->to_db_date($pay_in->payment_date))),false);
                $pay_in->start_study      = '';
                $pay_in->end_study        = '';
                $pay_in->save();
                $bean->start_study      = '';
                $bean->end_study        = '';
            }
            elseif($bean->payment_type == 'Refund') {
                // Load bean of student
                $student = BeanFactory::getBean("Contacts", $_POST["contacts_j_payment_1contacts_ida"]);

                $bean->payment_date     = $timedate->to_db_date($_POST["moving_tran_out_date"],false);
                $bean->used_hours         = $bean->total_hours;
                $bean->used_amount         = $bean->payment_amount + $bean->refund_revenue;
                $bean->remain_hours     = 0;
                $bean->remain_amount     = 0;
                $bean->total_after_discount = $bean->refund_revenue + $bean->payment_amount; // Total Amount
                $bean->start_study      = '';
                $bean->end_study        = '';
                // Save relationship to old payments
                $old_payments = json_decode(html_entity_decode($_POST["json_payment_list"]),true);
                $refundHours = 0;
                foreach($old_payments as $pay_id => $value){
                    $old_payment = BeanFactory::getBean('J_Payment',$pay_id);
                    if($used_payment->payment_type = 'Delay')
                        $bean->delay_situation_id = $old_payment->delay_situation_id;

                    $rate_reduce    = round($old_payment->remain_amount / ($bean->payment_amount + $bean->refund_revenue),2);
                    $pay_amount     = unformat_number(format_number($old_payment->remain_amount));
                    $total_hours    = $pay_amount / (($old_payment->remain_amount) / ($old_payment->remain_hours));


                    if(empty($total_hours)) $total_hours = 0;
                    $refundHours += $value["used_hours"];

                    $bean->use_type = "Amount";
                    $bean->team_id      = $old_payment->team_id;
                    $bean->team_set_id  = $old_payment->team_id;

                    //Link payment Corporate
                    $bean->contract_id  = $old_payment->contract_id;

                    $GLOBALS['db']->query("UPDATE j_payment
                        SET
                        used_amount = used_amount + $pay_amount,
                        used_hours = used_hours + $total_hours,
                        remain_amount =remain_amount - $pay_amount,
                        remain_hours = remain_hours - $total_hours
                        WHERE
                        id = '{$pay_id}'
                        ");
                    //Cal amount / hour related payment
                    $payrel_amount = ($pay_amount - ($bean->refund_revenue * $rate_reduce) );
                    $payrel_hours  = ($payrel_amount/ ($pay_amount/$total_hours));
                    addRelatedPayment($bean->id, $pay_id, $payrel_amount , $payrel_hours);
                    $payment_drop_id = $pay_id;


                    //Drop revenue
                    $delivery = new C_DeliveryRevenue();
                    $delivery->name = 'Drop revenue from payment refund '.$bean->name;
                    $delivery->student_id = $student->id;
                    //Get Payment ID
                    $delivery->ju_payment_id = $payment_drop_id;
                    $delivery->type = 'Junior';
                    $delivery->amount = format_number($bean->refund_revenue * $rate_reduce,2,2);
                    $delivery->date_input = $timedate->to_db_date($_POST["moving_tran_out_date"],false);
                    $delivery->cost_per_hour = 0;
                    $delivery->session_id = '1';
                    $delivery->passed = 0;
                    $delivery->team_id = $bean->team_id;
                    $delivery->team_set_id = $bean->team_set_id;

                    //Refund Casholder
                    if ($refundHours > 0)  {
                        $revenueHours = ($bean->refund_revenue * $rate_reduce) / ($pay_amount/$total_hours);
                        $unit_price = format_number($pay_amount/$total_hours);
                        $delivery->duration = format_number($revenueHours,2,2);
                        $delivery->cost_per_hour = $unit_price;
                    }

                    $delivery->assigned_user_id = $current_user->id;
                    $delivery->created_by = '1';
                    $delivery->modified_user_id = '1';
                    $delivery->revenue_type = 'Enrolled';

                    if($delivery->amount > 0)
                        $delivery->save();
                }              
            }
            elseif($bean->payment_type == 'Book/Gift'){
                $bean->remain_amount = 0;
                $bean->remain_hours = 0;
                $bean->used_amount = 0;
                $bean->used_hours = 0;


                $student = BeanFactory::getBean('Contacts',$bean->contacts_j_payment_1contacts_ida);
                $inventory = BeanFactory::newBean("J_Inventory");
                $inventory->id = create_guid();
                $inventory->new_with_id = true;

                // ..and create new Inventory
                $inventory->name        = $bean->name;
                $inventory->status      = "Confirmed";
                $inventory->date_create = $bean->payment_date;
                $inventory->type        = "Sell";
                $inventory->description = $bean->description;
                $inventory->j_payment_j_inventory_1j_payment_ida = $bean->id;

                $inventory->team_id         = $bean->team_id;
                $inventory->team_set_id     = $bean->team_id;
                $inventory->assigned_user_id= $bean->assigned_user_id;
                $inventory->total_amount    = $bean->total_after_discount;

                $inventory_total_quantity = 0;
                // First element is null
                for ($i = 1; $i < count($_POST["book_id"]); $i++) {
                    $bookId         = $_POST["book_id"][$i];
                    $bookQuantity   = $_POST["book_quantity"][$i];
                    $bookPrice      = unformat_number($_POST["book_price"][$i]);
                    if ($bookId != ""){
                        // Create Inventory Detail
                        $inventoryDetail = BeanFactory::newBean("J_Inventorydetail");
                        $inventoryDetail->book_id       = $bookId;
                        $inventoryDetail->inventory_id  = $inventory->id;
                        $inventoryDetail->quantity      = $bookQuantity;
                        $inventoryDetail->price         = $bookPrice;
                        $inventoryDetail->amount        = $bookPrice * $bookQuantity;
                        $inventoryDetail->team_id       = $inventory->team_id;
                        $inventoryDetail->team_set_id   = $inventory->team_set_id;
                        $inventoryDetail->assigned_user_id = $inventory->assigned_user_id;
                        $inventory_total_quantity += $bookQuantity;
                        $inventoryDetail->save();
                    }
                }
                $inventory->total_quantity  = $inventory_total_quantity;
                $inventory->save();
                $bean->tuition_fee          = $bean->amount_bef_discount;
            }
            elseif($bean->payment_type == 'Cambridge' ){
                $bean->tuition_fee              = $bean->payment_amount;
                $bean->amount_bef_discount      = $bean->payment_amount;
                $bean->total_after_discount     = $bean->payment_amount;
            }
            elseif($bean->payment_type == 'Transfer From AIMS'){
                //From Student
                $target_student = BeanFactory::getBean("Contacts", $bean->contacts_j_payment_1contacts_ida);
                //From Center
                $bean->move_from_center_id  = $_POST['from_AIMS_center_id'];
                // To Student
                $bean->transfer_to_student_id = $bean->contacts_j_payment_1contacts_ida;
                // To Center
                $bean->move_to_center_id = $target_student->team_id;
                $bean->team_id           = $target_student->team_id;
                $bean->team_set_id       = $target_student->team_id;
                // Set some field in transfers out payment
                $bean->remain_amount    = $bean->payment_amount;
                $bean->remain_hours     = $bean->total_hours;
                $bean->tuition_hours    = $bean->total_hours;
                $bean->payment_expired = date('Y-m-d',strtotime("+6 months ".$bean->payment_date));
                //$bean->use_type = "Amount";
                $bean->start_study      = '';
                $bean->end_study        = '';
            }
            if($bean->payment_type == 'Outing Trip'|| $bean->payment_type == 'Cashholder' || $bean->payment_type == 'Enrollment' || $bean->payment_type == 'Book/Gift'){
                //Add Relationship Payment - Discount
                $json_discount = json_decode(html_entity_decode($_POST['discount_list']),true);
                $bean->load_relationship('j_payment_j_discount_1');
                $bean->load_relationship('j_partnership_j_payment_1');
                foreach($json_discount as $dis_id => $value){
                    $bean->j_payment_j_discount_1->add($dis_id);
                    $extDisName = '';
                    if($value['type'] == "Partnership" && !empty($value["partnership_id"])){
                        $GLOBALS['db']->query("INSERT INTO j_partnership_j_payment_1_c
                            (id, date_modified, deleted, j_partnership_j_payment_1j_partnership_ida, j_partnership_j_payment_1j_payment_idb, discount_id) VALUES
                            ('".create_guid()."','".$timedate->nowDb()."',0, '{$value["partnership_id"]}', '{$bean->id}', '{$dis_id}')");
                        $part = BeanFactory::getBean('J_Partnership', $value["partnership_id"]);
                        $extDisName = ': '.$part->name;
                    }

                    $dis = BeanFactory::getBean('J_Discount',$dis_id);
                    $spon = new J_Sponsor();
                    $spon->name             = $dis->name.$extDisName;
                    $spon->payment_id       = $bean->id;
                    $spon->discount_id      = $dis_id;
                    $spon->amount           = $value['dis_amount'];
                    $spon->percent          = $value['dis_percent'];
                    $spon->total_down       = $value['total_down'];
                    $spon->type             = 'Discount';
                    $spon->team_id          = $bean->team_id;
                    $spon->team_set_id      = $bean->team_id;
                    $spon->save();
                }
                //Create Sponsor
                $json_sponsor = json_decode(html_entity_decode($_POST['sponsor_list']),true);
                foreach($json_sponsor as $key => $value){
                    $spon = new J_Sponsor();
                    $spon->name = $value['foc_type'];
                    $spon->payment_id       = $bean->id;
                    $spon->voucher_id       = $value['voucher_id'];
                    if (empty($bean->fetched_row) && !empty($spon->voucher_id)){
                        $q5 = "SELECT DISTINCT
                        IFNULL(j_voucher.id, '') voucher_id,
                        IFNULL(j_voucher.student_id, '') student_id
                        FROM
                        j_voucher
                        WHERE
                        (((j_voucher.id = '{$spon->voucher_id}')))
                        AND j_voucher.deleted = 0";
                        $rs5 = $GLOBALS['db']->query($q5);
                        $row5 = $GLOBALS['db']->fetchByAssoc($rs5);
                        if($row5['student_id'] == $bean->contacts_j_payment_1contacts_ida){
                            $spon->is_owner = 1;
                            $GLOBALS['db']->query("UPDATE j_voucher SET redempt_time=redempt_time+1 WHERE id = '{$spon->voucher_id}'");
                        }
                        else
                            $GLOBALS['db']->query("UPDATE j_voucher SET used_time=used_time+1 WHERE id = '{$spon->voucher_id}'");

                    }

                    $spon->sponsor_number   = $value['sponsor_number'];
                    if($value['foc_type'] == 'Referral')
                        $spon->campaign_code    = $value['campaign_code'];
                    $spon->amount           = $value['sponsor_amount'];
                    $spon->percent          = $value['sponsor_percent'];
                    $spon->total_down       = $value['total_down'];
                    $spon->foc_type         = $value['foc_type'];
                    $spon->type             = 'Sponsor';
                    $spon->team_id          = $bean->team_id;
                    $spon->team_set_id      = $bean->team_id;
                    if(unformat_number($value['total_down']) > 0 || !empty($spon->voucher_id))
                        $spon->save();
                }

                //Add Relationship Payment - Loyalty
                $json_loyalty = json_decode(html_entity_decode($_POST['loyalty_list']),true);

                $loyalty = new J_Loyalty();
                $loyalty->point          = abs($json_loyalty['points_to_spend']);
                $loyalty->discount_amount= $json_loyalty['amount_to_spend'];
                $loyalty->rate_in_out   = $json_loyalty['rate_out'];
                $loyalty->type          = 'Redemption';
                $loyalty->student_id    = $student->id;
                $loyalty->payment_id    = $bean->id;
                $loyalty->target_id     = $json_loyalty['rate_out_id'];
                $loyalty->team_id       = $bean->team_id;
                $loyalty->team_set_id   = $bean->team_id;
                $loyalty->input_date    = $bean->payment_date;
                $loyalty->description   = 'Redemption Payments.';
                if($loyalty->point > 0 && unformat_number($json_loyalty['amount_to_spend']) > 0)
                    $loyalty->save();

            }


            //Check Company Info
            if($bean->is_corporate){
                $acc = BeanFactory::getBean('Accounts',$bean->account_id);
                if( (($acc->name != $bean->company_name)) || ($acc->billing_address_street != $bean->company_address) || ($acc->tax_code != $bean->tax_code) ){
                    $acc->name                         = $bean->company_name;
                    $acc->billing_address_street     = $bean->company_address;
                    $acc->tax_code                     = $bean->tax_code;
                    $acc->save();
                }
            }
        }
        //Update Entered Date
        $bean->date_entered = substr_replace($bean->date_entered, $bean->payment_date,0,10);
        //Update Settle Date
        if($bean->payment_type == 'Enrollment')
            $bean->settle_date  = $bean->payment_date;
    }

    function afterSavePayment($bean, $event, $arguments){
        require_once("custom/include/_helper/junior_revenue_utils.php");
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            global $timedate;
            //Add Payment Detail
            $count_pmd = (int)$bean->number_of_payment;

            //Find payment max amount
            $payDtlMax = 0;
            $payDtlMaxAmount = $_POST['pay_dtl_amount'][0];
            for($i = 1; $i < $count_pmd; $i++){
                if(unformat_number($_POST['pay_dtl_amount'][$i]) > unformat_number($payDtlMaxAmount)){
                    $payDtlMaxAmount     = $_POST['pay_dtl_amount'][$i];
                    $payDtlMax           = $i;
                }
            }

            //Customize chương trình FPT
            $count_dis_fpt = 0;
            $json_discount = json_decode(html_entity_decode($_POST['discount_list']),true);
            foreach($json_discount as $dis_id => $value){
                if($dis_id == '55760369-75c6-aa76-1392-58e456e2d74e' || $dis_id == '87724ca9-7020-35e6-3f32-58e47293ba7f')
                    $count_dis_fpt++;
            }
            if($count_dis_fpt > 0){
                $count_pmd = 2;
                $GLOBALS['db']->query("UPDATE j_payment SET number_of_payment = '$count_pmd' WHERE id = '{$bean->id}'");
            }
            //END

            $discount_amount        = ($bean->discount_amount);
            $final_sponsor          = ($bean->final_sponsor);
            $loyalty_amount         = ($bean->loyalty_amount);
            $count_BUV      = 0;
            $json_sponsor   = json_decode(html_entity_decode($_POST['sponsor_list']),true);
            foreach($json_sponsor as $key => $value){
                if($value['foc_type'] == 'BUV, BEP'){
                    $count_BUV++;
                }
            }

            for($i = 0; $i < $count_pmd; $i++){
                $pmd = BeanFactory::newBean('J_PaymentDetail');
                $index = $i+1;
                $pmd->payment_no    = $index;
                $pmd->name          = $bean->name."-$index";

                $payDtlAmount       = unformat_number($_POST['pay_dtl_amount'][$i]);
                $payDtlBefDiscount  = unformat_number($_POST['pay_dtl_bef_discount'][$i]);
                $payDtlDisAmount    = unformat_number($_POST['pay_dtl_dis_amount'][$i]);
                $payDtlType         = $_POST['pay_dtl_type'][$i];
                if($i == $payDtlMax){
                    $pmd->is_discount       = 1;
                    $pmd->before_discount   = format_number($payDtlAmount + $discount_amount + $final_sponsor + $loyalty_amount);
                    $pmd->discount_amount   = format_number($discount_amount);
                    $pmd->sponsor_amount    = format_number($final_sponsor);
                    $pmd->loyalty_amount    = format_number($loyalty_amount);
                    //                    //Bo sung Staff children
                    //                    if($bean->foc_type == 'Staff children'){
                    //                        $pmd->before_discount   = format_number($payDtlAmount);
                    //                        $pmd->discount_amount   = 0;
                    //                        $pmd->sponsor_amount    = 0;
                    //                    }

                }else{
                    $pmd->before_discount   = format_number($payDtlAmount);
                    $pmd->discount_amount   = 0;
                    $pmd->sponsor_amount    = 0;
                    $pmd->loyalty_amount    = 0;
                }

                if($count_BUV > 0){
                    $pmd->before_discount   = format_number($payDtlBefDiscount);
                    $pmd->discount_amount   = format_number($payDtlDisAmount);
                    $pmd->sponsor_amount    = 0;
                    $pmd->loyalty_amount    = 0;
                    if($payDtlDisAmount > 0)
                        $pmd->is_discount       = 1;
                    else
                        $pmd->is_discount       = 0;
                }



                $pmd->status                = "Unpaid";
                $pmd->payment_date          = $_POST['pay_dtl_invoice_date'][$i];
                if($payDtlAmount == 0){
                    $pmd->status                = "Paid";
                    $pmd->payment_date          = $_POST['pay_dtl_invoice_date'][$i];
                    $pmd->payment_method        = 'Other';
                    //SET SALE TYPE
                    if(($bean->payment_type == 'Cashholder' || $bean->payment_type == 'Deposit' || $bean->payment_type == 'Enrollment')){
                        $sale_type = checkSaleType($bean->id, $bean->payment_date, $bean->payment_type);
                        $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$sale_type', sale_type_date=payment_date WHERE id = '{$bean->id}'");
                    }
                }else{
                    if($bean->date_entered == $bean->date_modified)
                        $GLOBALS['db']->query("UPDATE j_payment SET sale_type = 'Not set', sale_type_date=payment_date WHERE id = '{$bean->id}'");

                }


                $pmd->type                  = $payDtlType;
                $pmd->payment_amount        = format_number($payDtlAmount);

                if($count_dis_fpt > 0){
                    if($i == 0){
                        $pmd->before_discount   = format_number(5000000);
                        $pmd->discount_amount   = format_number(500000);
                        $pmd->sponsor_amount    = 0;
                        $pmd->loyalty_amount    = 0;
                        $pmd->payment_amount    = format_number(4500000);
                        $pmd->status            = 'Paid';
                        $pmd->payment_method    = 'Bank Transfer';
                        $pmd->type              = 'Deposit';
                        $pmd->is_discount       = 1;
                        $pmd->description       = 'Kế toán xuất hóa đơn theo hợp đồng FPT';
                    }else{
                        $pmd->before_discount   = format_number($bean->payment_amount + $discount_amount + $final_sponsor - 5000000);
                        $pmd->discount_amount   = format_number($discount_amount - 500000);
                        $pmd->sponsor_amount    = format_number($final_sponsor);
                        $pmd->loyalty_amount    = format_number($loyalty_amount);
                        $pmd->payment_amount    = format_number(($bean->payment_amount + $discount_amount + $final_sponsor - 5000000) - ($discount_amount - 500000) - $final_sponsor);
                        $pmd->status            = "Unpaid";
                        $pmd->payment_method    = '';
                        $pmd->type              = 'Normal';
                        $pmd->is_discount       = 1;
                    }

                }

                $pmd->payment_id            = $bean->id;
                $pmd->student_id            = $bean->contacts_j_payment_1contacts_ida;
                $pmd->assigned_user_id      = $bean->assigned_user_id;
                $pmd->team_id               = $bean->team_id;
                $pmd->team_set_id           = $bean->team_id;
                if($pmd->payment_amount != 0 || ($pmd->payment_amount == 0 && unformat_number($pmd->before_discount) == ($discount_amount + $final_sponsor + $loyalty_amount)))
                    $pmd->save();
            }


            if($bean->payment_type == 'Enrollment'){
                //Get List Class Of Payment
                $sql_get_class="SELECT DISTINCT
                IFNULL(l2.id, '') l2_id,
                IFNULL(l2.name, '') l2_name,
                IFNULL(l2.class_code, '') class_code,
                IFNULL(l2.level, '') level,
                IFNULL(j_payment.id, '') primaryid,
                l3.kind_of_course kind_of_course,
                l2.kind_of_course kind_of_course_class,
                IFNULL(l3.name, '') kind_of_course_name
                FROM
                j_payment
                INNER JOIN
                j_studentsituations l1 ON j_payment.id = l1.payment_id
                AND l1.deleted = 0
                INNER JOIN
                j_class l2 ON l1.ju_class_id = l2.id
                AND l2.deleted = 0
                LEFT JOIN
                j_kindofcourse l3 ON l2.koc_id = l3.id AND l3.deleted = 0
                WHERE
                j_payment.id = '{$bean->id}'
                AND j_payment.deleted = 0
                ORDER BY l2.name";
                $result_get_class = $GLOBALS['db']->query($sql_get_class);
                $class  = '';
                $koc    = '';
                $kind_of_course    = '';
                while($row = $GLOBALS['db']->fetchByAssoc($result_get_class)){
                    if(empty($class)){
                        $class  .= $row['class_code'];
                        $koc    .= $row['kind_of_course_name'];
                        $level  .= $row['level'];
                        if(!empty($row['kind_of_course']))
                            $kind_of_course  = $row['kind_of_course'];
                        else $kind_of_course  = $row['kind_of_course_class'];
                    } else{
                        $class  .= ','.$row['class_code'];
                        $koc    .= ','.$row['kind_of_course_name'];
                        $level  .= ','.$row['level'];
                    }
                }
                $GLOBALS['db']->query("UPDATE j_payment SET class_string = '$class', kind_of_course_string = '$koc', kind_of_course='$kind_of_course', level_string = '$level' WHERE id = '{$bean->id}'");

            }elseif($bean->payment_type == 'Cashholder' || $bean->payment_type == 'Deposit')
                $GLOBALS['db']->query("UPDATE j_payment SET kind_of_course_string = '{$bean->kind_of_course}' WHERE id = '{$bean->id}'");
            else
                $GLOBALS['db']->query("UPDATE j_payment SET sale_type = 'Not set', sale_type_date = payment_date WHERE id = '{$bean->id}'");

        }
        if($bean->payment_type != 'Corporate' && $bean->payment_type != 'Delay')
            update_remain_last_date($bean->contacts_j_payment_1contacts_ida);

    }

    ///to mau id va status Quyen.Cao
    function listViewColorPayment(&$bean, $event, $arguments){
        global $timedate, $app_list_strings;
        //Total paid amount of payment detail
        $q1="SELECT DISTINCT
        IFNULL(j_paymentdetail.id, '') primaryid,
        IFNULL(j_paymentdetail.invoice_number, '') invoice_number,
        j_paymentdetail.payment_date payment_date,
        j_paymentdetail.status status,
        j_paymentdetail.payment_amount payment_amount
        FROM
        j_paymentdetail
        INNER JOIN
        j_payment l1 ON j_paymentdetail.payment_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$bean->id}')
        AND (j_paymentdetail.status <> 'Cancelled')
        ))
        AND j_paymentdetail.deleted = 0";
        $res = $GLOBALS['db']->query($q1);
        $total = 0;
        $htm_pmd = '<table width="100%"><tbody>';
        $count_pmd = 0;
        while($row = $GLOBALS['db']->fetchByAssoc($res)) {
            $_class = '';
            if($row['status'] == 'Unpaid')
                $_class = 'class="overdueTask"';
            if( ($row['status'] == 'Paid' && !empty($row['invoice_number']) || ($row['status'] == 'Paid' && $row['payment_amount'] > 0)) || ($row['status'] == 'Unpaid')){
                $count_pmd++;
                $htm_pmd .= "<tr class='oddListRowS1'><td style='width: 20%;'>{$row['invoice_number']}</td>";
                $htm_pmd .= "<td style='width: 30%;'><span $_class>".$timedate->to_display_date($row['payment_date'],false)."</span></td>";

                $htm_pmd .= "<td style='width: 30%;'><span $_class>".format_number($row['payment_amount'])."</span></td>";
                $htm_pmd .= "<td style='width: 20%;'><span $_class title='{$row['status']}'>".$app_list_strings['status_paymentdetail_list'][$row['status']]."</span></td>";
                $htm_pmd .= "</tr>";
            }

        }
        $htm_pmd .= "</tbody></table>";

        if($count_pmd > 0)
            $bean->number_of_payment = $htm_pmd;
        else $bean->number_of_payment = '';

        $bean->total_amount = ($bean->paid_amount + $bean->deposit_amount+ $bean->payment_amount);
        if($bean->payment_type == 'Enrollment' || $bean->payment_type == 'Delay' || $bean->payment_type == 'Corporate'){
            $sql_get_class="SELECT
            DISTINCT IFNULL(l2.id,'') l2_id ,
            IFNULL(l2.name,'') l2_name ,
            IFNULL(l2.class_code,'') class_code ,
            IFNULL(j_payment.id,'') primaryid
            FROM j_payment INNER JOIN j_studentsituations l1 ON j_payment.id=l1.payment_id
            AND l1.deleted=0 INNER JOIN j_class l2 ON l1.ju_class_id=l2.id
            AND l2.deleted=0 WHERE j_payment.id='{$bean->id}'
            AND j_payment.deleted=0
            ORDER BY  l2.name";
            $result_get_class = $GLOBALS['db']->query($sql_get_class);
            //    $bean->class_string = '';
            //style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
            while($row = $GLOBALS['db']->fetchByAssoc($result_get_class))
                $bean->class_string ='<a href=index.php?module=J_Class&offset=1&stamp=1441785563066827100&return_module=J_Class&action=DetailView&record='.$row['l2_id'].' target=_blank>'.$row['l2_name'].'</a><br>';
        }  

        //Payment type
        switch ($bean->payment_type) {
            case "Enrollment":
                $colorClass = "textbg_green";
                break;
            case "Deposit":
                $colorClass = "textbg_blue";
                break;
            case "Cashholder":
                $colorClass = "textbg_bluelight";
                break;
            case "Delay":
            case "Schedule Change":
                $colorClass = "textbg_blood";
                break;
            case "Transfer In":
                $colorClass = "textbg_yellow";
                break;
            case "Transfer From AIMS":
                $colorClass = "textbg_yellow_light";
                break;
            case "Merge AIMS":
                $colorClass = "textbg_dream";
                break;
            case "Transfer Out":
                $colorClass = "textbg_yellow";
                break;
            case "Moving In":
                $colorClass = "textbg_yellow_light";
                break;
            case "Moving Out":
                $colorClass = "textbg_yellow_light";
                break;
            case "Refund":
                $colorClass = "textbg_crimson";
                break;
            case "Corporate":
                $colorClass = "textbg_dream";
                break;
            case "Book/Gift":
            case "Placement Test":
            case "Tutor Package":
            case "Travelling Fee":
            case "Freeze Fee":
                $colorClass = "textbg_violet";
                break;

        }
        $bean->payment_type = "<span title='{$bean->description}' class='$colorClass'>".$app_list_strings['payment_type_payment_list'][$bean->payment_type]." </span>";
        //$bean->name = "<a href='index.php?module=J_Payment&action=DetailView&record={$bean->id}'>{$bean->name}</a><span id='adspan' style='position: relative;'><img vertical-align='middle' class='info' border='0' title='{$bean->description}' src='themes/OnlineCRM-Blue/images/info_inline.png'></span>";

        //Get Payment Related
        $q2 = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        IFNULL(l1.name, '') l1_name,
        IFNULL(l1.payment_type, '') l1_payment_type,
        l1_1.hours hours,
        l1_1.amount amount
        FROM
        j_payment
        INNER JOIN
        j_payment_j_payment_1_c l1_1 ON j_payment.id = l1_1.j_payment_j_payment_1j_payment_ida
        AND l1_1.deleted = 0
        INNER JOIN
        j_payment l1 ON l1.id = l1_1.j_payment_j_payment_1j_payment_idb
        AND l1.deleted = 0
        WHERE
        (((j_payment.id = '{$bean->id}')))
        AND j_payment.deleted = 0";

        $count_rel = 0;
        $res = $GLOBALS['db']->query($q2);
        $htm_rel = "<table><tbody>";
        while($row = $GLOBALS['db']->fetchByAssoc($res)) {
            $htm_rel .= "<tr class='oddListRowS1'><td style='width: 40%;'><a title='{$row['l1_payment_type']}' href='index.php?module=J_Payment&action=DetailView&record={$row['l1_id']}'>{$row['l1_name']}</a></td>";
            $htm_rel .= "<td style='width: 40%;'>".format_number($row['amount'])."</td>";
            $htm_rel .= "<td style='width: 20%;'>".format_number($row['hours'],2,2)."</td></tr>";
            $count_rel++;
        }
        $htm_rel .= "</tbody></table>";
        if($count_rel > 0)
            $bean->related_payment_detail = $htm_rel;
    }

    function addCode(&$bean, $event, $arguments){
        $code_field = 'name';
        if($bean->payment_type == 'Transfer Out' || $bean->payment_type == 'Moving Out' || $bean->payment_type == 'Refund'){
            $student = BeanFactory::getBean("Contacts", $bean->contacts_j_payment_1contacts_ida);
            $bean->team_id = $student->team_id;
            $bean->team_set_id = $student->team_id;
        }
        if( empty($bean->fetched_row[$code_field]) || strpos($bean->fetched_row[$code_field], $prefix) === false ){
            //Get Prefix
            $res        = $GLOBALS['db']->query("SELECT teams.code_prefix, team_type FROM teams WHERE id = '{$bean->team_id}'");
            $row        = $GLOBALS['db']->fetchByAssoc($res);
            $prefix     = $row['code_prefix'];
            $year       = date('y',strtotime('+ 7hours'. (!empty($bean->date_entered) ? $bean->date_entered : $bean->fetched_row['date_entered'])));
            $table      = $bean->table_name;
            $sep        = '-';
            $first_pad  = '00000';
            $padding    = 5;
            if($bean->payment_type == 'Enrollment' || $bean->payment_type == 'Corporate' || $bean->payment_type == 'Deposit' || $bean->payment_type == 'Cashholder' || $bean->payment_type == 'Placement Test' || $bean->payment_type == 'Cambridge' || $bean->payment_type == 'Outing Trip' || $bean->payment_type == 'Book/Gift'|| $bean->payment_type == 'Merge AIMS')
                $ext = 'P';
            else $ext = 'R';
            $str_code = $prefix .  $sep . $ext.$year . $sep;

            $query = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}' AND (LEFT($code_field, ".strlen($str_code).") = '".$str_code."') ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";
            $result = $GLOBALS['db']->query($query);

            if($row = $GLOBALS['db']->fetchByAssoc($result))
                $last_code = $row[$code_field];
            else
                //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                $last_code = $str_code. $first_pad;

            $num = substr($last_code, -$padding, $padding);
            $num++;
            $pads = $padding - strlen($num);
            $new_code = $str_code;

            //preform the lead padding 0
            for($i=0; $i < $pads; $i++)
                $new_code .= "0";
            $new_code .= $num;

            //write to database - Logic: Before Save
            $bean->$code_field = $new_code;
        }
    }

    function saveStatusPaid(&$bean, $event, $arguments){
        global $db;

        $sqlPayDtl = "SELECT SUM(IF(status = 'Unpaid',payment_amount,0)) as payment_amount
        FROM j_paymentdetail
        WHERE payment_id = '{$bean->id}'
        AND deleted = 0
        AND status <> 'Cancelled'";
        $resultPayDtl = $db->getOne($sqlPayDtl);

        if($resultPayDtl['payment_amount'] == 0)
            $db->query("UPDATE j_payment SET status_paid = 'Fully Paid' WHERE id ='$bean->id'");
        else 
            $db->query("UPDATE j_payment SET status_paid = 'Unpaid' WHERE id ='$bean->id'");
    }
}
?>
