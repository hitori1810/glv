<?php
class logicImport{
    //Import Payment
    function importError(&$bean, $event, $arguments){
        global $timedate;
        if($_POST['module'] == 'Import'){
            $bean->deleted       = 1;   
            require_once("custom/include/_helper/junior_revenue_utils.php");
            require_once("custom/include/_helper/junior_class_utils.php");
            //Get ID Student

            $qclass  = $GLOBALS['db']->query("SELECT DISTINCT
                IFNULL(j_class.id, '') id,
                IFNULL(j_class.class_code, '') class_code,
                IFNULL(j_class.aims_id, '') aims_id,
                IFNULL(j_class.kind_of_course, '') kind_of_course,
                j_class.start_date start_date,
                j_class.end_date end_date,
                IFNULL(j_class.level, '') level,
                IFNULL(l1.id, '') koc_id,
                IFNULL(l1.name, '') koc_name
                FROM
                j_class
                LEFT JOIN
                j_kindofcourse l1 ON j_class.koc_id = l1.id
                AND l1.deleted = 0
                WHERE
                (((j_class.aims_id = '{$bean->class_aims_id}')))
                AND j_class.deleted = 0");
            $class = $GLOBALS['db']->fetchByAssoc($qclass);
            $class_id   = $class['id'];

            $qpayment  = $GLOBALS['db']->query("SELECT DISTINCT
                IFNULL(l1.id, '') l1_id,
                CONCAT(IFNULL(l1.last_name, ''),
                ' ',
                IFNULL(l1.first_name, '')) l1_full_name,
                IFNULL(j_payment.id, '') primaryid,
                IFNULL(j_payment.name, '') j_payment_name,
                j_payment.total_hours total_hours,
                j_payment.tuition_hours tuition_hours,
                j_payment.payment_amount payment_amount,
                j_payment.start_study start_study,
                j_payment.end_study end_study,
                j_payment.payment_date payment_date,
                j_payment.date_entered date_entered,
                IFNULL(l2.id, '') l2_id,
                IFNULL(l3.id, '') l3_id,
                IFNULL(l4.id, '') l4_id
                FROM
                j_payment
                INNER JOIN
                contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
                AND l1_1.deleted = 0
                INNER JOIN
                contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
                AND l1.deleted = 0
                INNER JOIN
                users l2 ON j_payment.assigned_user_id = l2.id
                AND l2.deleted = 0
                INNER JOIN
                users l3 ON j_payment.created_by = l3.id
                AND l3.deleted = 0
                INNER JOIN
                teams l4 ON j_payment.team_id = l4.id
                AND l4.deleted = 0
                WHERE
                (((j_payment.id = '{$bean->payment_id}')))
                AND j_payment.deleted = 0");
            $payment = $GLOBALS['db']->fetchByAssoc($qpayment);
            //DELETE situation
            $q2 = "SELECT id FROM j_studentsituations WHERE payment_id='{$payment['primaryid']}' AND deleted = 0";
            $rs2 = $GLOBALS['db']->query($q2);
            while($rows = $GLOBALS['db']->fetchByAssoc($rs2)) {
                removeJunFromSession($rows['id']); 
                $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id='{$rows['id']}'"); 
            }

            if(!empty($class_id)){

                $payment_date_init  = strtotime($bean->payment_date);
                $start_study_init   = strtotime($bean->start_study);
                $end_study_init     = strtotime($bean->end_study);

                $sss    = array();
                $ss     = array();

                if($payment_date_init <= $start_study_init){
                    //TH1: Payment Date <= Start. Tinh doanh thu
                    $start_enroll = $bean->start_study;
                    $ss = get_list_lesson_by_class($class_id, $timedate->to_display_date($start_enroll,false), $timedate->to_display_date($bean->end_study,false), 'Standard', '');

                }elseif( ($payment_date_init > $start_study_init) && ($payment_date_init <= $end_study_init) ){
                    // TH2: start < Payment date <= finish. Tinh Settle va` Doanh thu
                    $settle_date = date('Y-m-d',strtotime('-1 day '.$bean->payment_date));
                    $sss = get_list_lesson_by_class($class_id, $timedate->to_display_date($bean->start_study,false), $timedate->to_display_date($settle_date,false), 'Standard', '');

                    $start_enroll = $bean->payment_date;
                    $ss = get_list_lesson_by_class($class_id, $timedate->to_display_date($start_enroll,false), $timedate->to_display_date($bean->end_study,false), 'Standard', '');
                }else{
                    // TH3:  Payment Date > finish. Tinh Settle
                    $settle_date = $bean->end_study;
                    $sss = get_list_lesson_by_class($class_id, $timedate->to_display_date($bean->start_study,false), $timedate->to_display_date($settle_date,false), 'Standard', '');
                }

                //get Doanh thu + Settle
                $settle = array(
                    'hour'  => 0,
                    'count'  => 0,
                    'start' => '',
                    'end'   => '',
                );

                $enroll = array(
                    'hour'  => 0,
                    'count'  => 0,
                    'start' => '',
                    'end'   => '',
                );

                //Loop Settle
                for($i = 0; $i < count($sss); $i++){
                    if($sss[$i]['session_status'] != 'Cancelled'){
                        $settle['hour'] += $sss[$i]['delivery_hour'];
                        $settle['count'] += 1;
                    }

                    if($i == 0)
                        $settle['start'] =  $timedate->to_display_date($sss[$i]['date_start']);  
                    if($i == count($sss) - 1)
                        $settle['end']   =  $timedate->to_display_date($sss[$i]['date_start']); 
                }

                //Loop doanh thu
                for($i = 0; $i < count($ss); $i++){
                    if($ss[$i]['session_status'] != 'Cancelled'){
                        $enroll['hour'] += $ss[$i]['delivery_hour']; 
                        $enroll['count'] += 1; 
                    }

                    if($i == 0)
                        $enroll['start'] =  $timedate->to_display_date($ss[$i]['date_start']);  
                    if($i == count($ss) - 1)
                        $enroll['end']   =  $timedate->to_display_date($ss[$i]['date_start']); 
                }

                //Set Price
                if(!empty($settle['hour'] + $enroll['hour']))
                    $unit_price = $bean->amount / ($settle['hour'] + $enroll['hour']); 
                else $unit_price = 0;

                //Create situation Settle
                if(!empty($settle['hour'])){
                    //caculate start_hour
                    $first   = reset($sss);
                    $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                    $stu_si                   = new J_StudentSituations();
                    $stu_si->name             = $payment['l1_full_name'];
                    $stu_si->student_type     = 'Student';
                    $stu_si->type             = 'Settle';
                    $stu_si->total_hour       = format_number($settle['hour'],2,2);
                    $stu_si->total_amount     = format_number($settle['hour'] * $unit_price);
                    $stu_si->start_study      = $settle['start'];
                    $stu_si->end_study        = $settle['end'];
                    $stu_si->student_id       = $payment['l1_id'];
                    $stu_si->ju_class_id      = $class_id;
                    $stu_si->payment_id       = $payment['primaryid'];
                    $stu_si->start_hour       = $start_hour;

                    $stu_si->assigned_user_id = $payment['l2_id']; 
                    $stu_si->team_id          = $payment['l4_id'];
                    $stu_si->team_set_id      = $payment['l4_id'];

                    $stu_si->date_entered     = $payment['date_entered'];
                    $stu_si->date_modified    = $payment['date_entered'];
                    $stu_si->created_by       = $payment['l3_id'];
                    $stu_si->modified_user_id = $payment['l3_id'];

                    $stu_si->save();
                    for($i = 0; $i < count($sss); $i++)
                        if($sss[$i]['session_status'] != 'Cancelled')
                            addJunToSession($stu_si->id , $sss[$i]['primaryid'] );        
                }
                //Create situation Enroll
                if(!empty($enroll['hour'])){
                    //caculate start_hour
                    $first   = reset($ss);
                    $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                    
                    $situ                   = new J_StudentSituations();
                    $situ->name             = $payment['l1_full_name'];
                    $situ->student_type     = 'Student';
                    $situ->type             = 'Enrolled';
                    $situ->total_hour       = format_number($enroll['hour'],2,2);
                    $situ->total_amount     = format_number($enroll['hour'] * $unit_price);
                    $situ->start_study      = $enroll['start'];
                    $situ->end_study        = $enroll['end'];
                    $situ->student_id       = $payment['l1_id'];
                    $situ->ju_class_id      = $class_id;
                    $situ->payment_id       = $payment['primaryid'];
                    $situ->start_hour       = $start_hour;

                    $situ->assigned_user_id = $payment['l2_id']; 
                    $situ->team_id          = $payment['l4_id'];
                    $situ->team_set_id      = $payment['l4_id'];

                    $situ->date_entered     = $payment['date_entered'];
                    $situ->date_modified    = $payment['date_entered'];
                    $situ->created_by       = $payment['l3_id'];
                    $situ->modified_user_id = $payment['l3_id'];

                    $situ->save();
                    for($i = 0; $i < count($ss); $i++)
                        if($ss[$i]['session_status'] != 'Cancelled')
                            addJunToSession($situ->id , $ss[$i]['primaryid'] );         
                }
            }
            //UPDATE Payment
            $tuition_hours        = $enroll['hour'] + $settle['hour'];
            $sessions             = $enroll['count'] + $settle['count'];
            $GLOBALS['db']->query("UPDATE j_payment SET tuition_hours=$tuition_hours, total_hours=$tuition_hours, sessions=$sessions WHERE id='{$payment['primaryid']}'");
        }
    }
}
?>
