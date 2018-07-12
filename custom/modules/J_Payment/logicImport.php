<?php
class logicImport{
    //Import Payment
    function importPayment(&$bean, $event, $arguments){
        global $timedate;
        if($_POST['module'] == 'Import'){
            require_once("custom/include/_helper/junior_revenue_utils.php");
            require_once("custom/include/_helper/junior_class_utils.php");
            //Get ID Student
            $student_id = $GLOBALS['db']->getOne("SELECT id FROM contacts WHERE aims_id = '{$bean->student_aims_id}' AND deleted = 0");
            $user_id = $GLOBALS['db']->getOne("SELECT id FROM users WHERE user_name = '{$bean->created_by}' AND deleted = 0");
            //            $num = substr($bean->name, -6,6);
            //            $bean->name = $num.$bean->aims_id;
            if(!empty($user_id))
                $bean->created_by = $user_id;

            if($bean->payment_type == 'Deposit' || $bean->payment_type =='Placement Test' ){
                //Payment
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);

                if($bean->payment_type =='Placement Test'){
                    $bean->payment_expired  = $bean->payment_date;
                    $bean->remain_amount    = $bean->payment_amount;
                }
                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }
                $bean->modified_user_id     = $bean->created_by;
                $bean->team_set_id          = $bean->team_id;
                $bean->date_modified        = $bean->date_entered;
                $bean->number_of_payment    = 1;
                $bean->tuition_fee          = $bean->amount_bef_discount;

                $bean->discount_percent     = $bean->discount_amount / $bean->amount_bef_discount * 100;
                if(empty($bean->discount_percent)) $bean->discount_percent = 0;

                $bean->final_sponsor_percent      = $bean->final_sponsor / $bean->amount_bef_discount * 100;
                if(empty($bean->final_sponsor_percent)) $bean->final_sponsor_percent = 0;

                $bean->sale_type_date       = $bean->payment_date;
                $bean->used_amount          = $bean->payment_amount - $bean->remain_amount;

                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;

                //Payment Detail
                $pmd = BeanFactory::newBean('J_PaymentDetail');
                $pmd->payment_no        = 1;
                $pmd->name              = $bean->name."-1";
                $pmd->is_discount       = 1;
                $pmd->before_discount   = $bean->amount_bef_discount;
                $pmd->discount_amount   = $bean->discount_amount;
                $pmd->sponsor_amount    = $bean->final_sponsor;
                $pmd->status            = "Paid";
                $pmd->payment_date      = $bean->invoice_date;
                if(empty($pmd->payment_date))
                    $pmd->payment_date      = $bean->payment_date;
                if(empty($pmd->payment_date))
                    $pmd->payment_date      = $bean->date_entered;
                $pmd->invoice_number    = str_pad($bean->invoice_number, 5, '0', STR_PAD_LEFT);
                $pmd->serial_no         = $bean->serial_no;
                $pmd->payment_method    = $bean->payment_method;
                $pmd->type              = 'Normal';
                $pmd->payment_amount    = $bean->payment_amount;

                $pmd->card_type         = $bean->card_type;

                $pmd->payment_id            = $bean->id;
                $pmd->assigned_user_id      = $bean->assigned_user_id;
                $pmd->modified_user_id      = $bean->modified_user_id;
                $pmd->created_by            = $bean->created_by;
                $pmd->date_entered          = $bean->date_entered;
                $pmd->date_modified         = $bean->date_modified;
                $pmd->team_id               = $bean->team_id;
                $pmd->team_set_id           = $bean->team_id;
                $pmd->save();
                /**               $q1 = "SELECT DISTINCT
                IFNULL(l1.id, '') l1_id,
                IFNULL(l1.name, '') l1_name,
                l1.amount_bef_discount l1_amount_bef_discount,
                IFNULL(j_paymentdetail.id, '') primaryid,
                j_paymentdetail.payment_amount payment_amount,
                l1.final_sponsor final_sponsor,
                l1.final_sponsor_percent final_sponsor_percent
                FROM
                j_paymentdetail
                INNER JOIN
                j_payment l1 ON j_paymentdetail.payment_id = l1.id
                AND l1.deleted = 0
                WHERE
                (((l1.aims_id = '{$bean->aims_id}')))
                AND j_paymentdetail.deleted = 0";
                $query = $GLOBALS['db']->query($q1);
                while($row = $GLOBALS['db']->fetchByAssoc($query)){
                $bean->final_sponsor_percent      = $bean->final_sponsor / $bean->amount_bef_discount * 100;
                if(empty($bean->final_sponsor_percent)) $bean->final_sponsor_percent = 0;

                $GLOBALS['db']->query("UPDATE j_paymentdetail SET sponsor_amount={$bean->final_sponsor} WHERE id='{$row['primaryid']}'");
                $GLOBALS['db']->query("UPDATE j_payment SET final_sponsor={$bean->final_sponsor}, final_sponsor_percent=".format_number($bean->final_sponsor_percent,2,2)." WHERE id='{$row['l1_id']}'");
                }
                $bean->deleted = 1;*/
            }
            elseif($bean->payment_type == 'Delay' && empty($bean->from_payment_aims_id)){
                //Payment
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);
                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }

                $bean->team_set_id          = $bean->team_id;
                $bean->modified_user_id     = $bean->created_by;
                $bean->date_modified        = $bean->date_entered;
                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;
                $bean->used_amount          = $bean->payment_amount - $bean->remain_amount;
                $bean->sale_type            = 'Not Set';
                $bean->sale_type_date       = '';

            }
            elseif($bean->payment_type == 'Delay' && !empty($bean->from_payment_aims_id)){
                //Add Related
                $rs = $GLOBALS['db']->query("SELECT id, payment_amount, deposit_amount, tuition_hours FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type = 'Enrollment' AND deleted = 0");
                $enrollment = $GLOBALS['db']->fetchByAssoc($rs);

                $delay_id           = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->aims_id}' AND payment_type = 'Delay' AND deleted = 0");

                if(!empty($enrollment['id']) && !empty($delay_id)){
                    $bean->deleted = 1;

                    // + tiền, + giờ cho Enrollment
                    $price = ($enrollment['deposit_amount']) / $enrollment['tuition_hours'];
                    $enrollment['deposit_amount'] += $bean->payment_amount;
                    //    $enrollment['tuition_hours'] += ($bean->payment_amount / $price);
                    $enrollment['tuition_hours'] += 0;     //Fix tạm bỏ số giờ

                    addRelatedPayment($delay_id, $enrollment['id'], $bean->payment_amount, 0);//FIX tạm

                    $update1 = "UPDATE j_payment SET
                    tuition_fee          = {$enrollment['deposit_amount']},
                    amount_bef_discount  = {$enrollment['deposit_amount']},
                    total_after_discount = {$enrollment['deposit_amount']},
                    deposit_amount       = {$enrollment['deposit_amount']}
                    WHERE id = '{$enrollment['id']}'";
                    $GLOBALS['db']->query($update1);

                    // + tiền + giờ lấy từ Deposit
                    $update2 = "UPDATE j_payment_j_payment_1_c SET
                    amount={$enrollment['deposit_amount']}
                    WHERE j_payment_j_payment_1j_payment_ida = '{$enrollment['id']}' AND deleted = 0";
                    $GLOBALS['db']->query($update2);
                }
            }
            elseif($bean->payment_type == 'Transfer In' || $bean->payment_type == 'Transfer From AIMS'){
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);

                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }
                $bean->team_set_id          = $bean->team_id;
                $bean->modified_user_id     = $bean->created_by;
                $bean->date_modified        = $bean->date_entered;
                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;
                $bean->used_amount          = $bean->payment_amount - $bean->remain_amount;
                $bean->sale_type            = 'Not Set';
                $bean->sale_type_date       = '';

            }
            elseif($bean->payment_type == 'Transfer Out'){
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);
                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }
                $bean->team_set_id          = $bean->team_id;
                $bean->modified_user_id     = $bean->created_by;
                $bean->date_modified        = $bean->date_entered;
                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;
                $bean->sale_type            = 'Not Set';
                $bean->sale_type_date       = '';

                //Add Related
                if(!empty($bean->transfer_in_aims_id)){
                    $from_transfer_in_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->transfer_in_aims_id}' AND payment_type = 'Transfer In'");

                    if(!empty($from_transfer_in_id)){
                        $transfer_in = BeanFactory::getBean("J_Payment", $from_transfer_in_id);
                        //addRelatedPayment($transfer_in->id, $bean->id, $bean->payment_amount);

                        //Update thằng In
                        $GLOBALS['db']->query("UPDATE j_payment SET payment_out_id = '{$bean->id}' WHERE id = '$from_transfer_in_id'");

                        //Update thằng Out
                        $bean->transfer_to_student_id = $transfer_in->contacts_j_payment_1contacts_ida;
                        $bean->move_from_center_id    = $transfer_in->team_id;
                        $bean->move_to_center_id      = $transfer_in->team_id;
                    }
                }

                if(!empty($bean->from_payment_aims_id)){
                    $from_payment_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                    if(!empty($from_payment_id)){
                        addRelatedPayment($bean->id, $from_payment_id, $bean->payment_amount);
                    }
                }
            }
            elseif($bean->payment_type == 'Merge AIMS' && empty($bean->from_payment_aims_id)){
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);
                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }
                $bean->team_set_id          = $bean->team_id;
                $bean->modified_user_id     = $bean->created_by;
                $bean->date_modified        = $bean->date_entered;
                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;
                $bean->sale_type            = 'Not Set';
                $bean->sale_type_date       = '';

                //Create Payment Detail
                $pmd = BeanFactory::newBean('J_PaymentDetail');
                $pmd->payment_no        = 1;
                $pmd->name              = $bean->name."-1";
                $pmd->is_discount       = 1;
                $pmd->before_discount   = 0;
                $pmd->discount_amount   = 0;
                $pmd->sponsor_amount    = 0;
                $pmd->status            = "Paid";
                $pmd->payment_date      = $bean->payment_date;
                if(empty($pmd->payment_date))
                    $pmd->payment_date      = $bean->date_entered;
                $pmd->invoice_number    = '';
                $pmd->serial_no         = '';
                $pmd->payment_method    = 'Other';
                $pmd->type              = 'Normal';
                $pmd->payment_amount    = 0;

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
            elseif($bean->payment_type == 'Merge AIMS' && !empty($bean->from_payment_aims_id)){
                //Add Related
                $from_payment_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");
                $merge_id        = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                if(!empty($from_payment_id) && !empty($merge_id)){
                    $bean->deleted = 1;
                    addRelatedPayment($merge_id, $from_payment_id, $bean->payment_amount);
                }
            }
            elseif($bean->payment_type == 'Refund' && empty($bean->from_payment_aims_id)){
                if($bean->load_relationship('contacts_j_payment_1'))
                    $bean->contacts_j_payment_1->add($student_id);
                if(empty($bean->payment_expired)){
                    $bean->do_not_drop_revenue = 1;
                    $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                }
                $bean->team_set_id          = $bean->team_id;
                $bean->modified_user_id     = $bean->created_by;
                $bean->date_modified        = $bean->date_entered;
                $bean->status               = 'Success';
                $bean->use_type             = 'Amount';
                $bean->tuition_hours        = 0;
                $bean->total_hours          = 0;
                $bean->sale_type            = 'Not Set';
                $bean->sale_type_date       = '';

            }
            elseif($bean->payment_type == 'Refund' && !empty($bean->from_payment_aims_id)){
                //Add Related
                $from_payment_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");
                $refund_id          = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                if(!empty($from_payment_id) && !empty($refund_id)){
                    $bean->deleted = 1;
                    addRelatedPayment($refund_id, $from_payment_id, $bean->payment_amount);
                }
            }
            elseif($bean->payment_type == 'Enrollment'){
                if(empty($bean->class_aims_id)){
                    // create Cashholder

                    $bean->deleted       = 1;

                    if(!empty($bean->from_payment_aims_id)){
                        $deposit_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                        $q100 = "UPDATE j_payment SET
                        kind_of_course_string   = '{$bean->kind_of_course}',
                        kind_of_course          = '{$bean->kind_of_course}',
                        tuition_fee             = amount_bef_discount,
                        tuition_hours           = {$bean->total_hours},
                        total_hours             = {$bean->total_hours},
                        remain_amount           = {$bean->payment_amount},
                        remain_hours            = {$bean->total_hours},
                        payment_type            = 'Cashholder'
                        WHERE id = '$deposit_id'";
                        $GLOBALS['db']->query($q100);
                    }
                }else{
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
                    $bean->kind_of_course           = $class['kind_of_course'];
                    $bean->kind_of_course_string    = $class['koc_name'];
                    $bean->class_string             = $class['class_code'];
                    $bean->level_string             = $class['level'];
                    $bean->class_start             = $class['start_date'];
                    $bean->class_end               = $class['end_date'];

                    $payment_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                    $q_student  = $GLOBALS['db']->query("SELECT id, CONCAT(IFNULL(last_name, ''),' ',IFNULL(first_name, '')) full_student_name FROM contacts WHERE aims_id = '{$bean->student_aims_id}' AND deleted = 0");
                    $student    = $GLOBALS['db']->fetchByAssoc($q_student);

                    //   Create Payment
                    if($bean->load_relationship('contacts_j_payment_1'))
                        $bean->contacts_j_payment_1->add($student_id);
                    if(empty($bean->payment_expired)){
                        $bean->do_not_drop_revenue = 1;
                        $bean->payment_expired      = date('Y-m-d',strtotime("+12 months ".$bean->payment_date));
                    }

                    $bean->modified_user_id     = $bean->created_by;
                    $bean->team_set_id          = $bean->team_id;
                    $bean->date_modified        = $bean->date_entered;
                    $bean->number_of_payment    = 1;
                    $bean->tuition_fee          = $bean->payment_amount;
                    $bean->amount_bef_discount  = $bean->payment_amount;
                    $bean->total_after_discount = $bean->payment_amount;

                    $bean->discount_percent     = 0;
                    $bean->final_sponsor_percent= 0;
                    $bean->final_sponsor        = 0;
                    $bean->discount_amount      = 0;
                    $bean->deposit_amount       = $bean->payment_amount;


                    $bean->sale_type_date       = $bean->payment_date;
                    $bean->used_amount          = 0;

                    $bean->status               = 'Success';
                    $bean->total_hours          = 0;

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
                        $_total_hour = $settle['hour'] + $enroll['hour'];
                        if(!empty($_total_hour))
                            $unit_price = $bean->payment_amount / ($_total_hour);
                        else $unit_price = 0;

                        //Create situation Settle
                        if(!empty($settle['hour'])){
                            $stu_si                   = new J_StudentSituations();
                            $stu_si->name             = $student['full_student_name'];
                            $stu_si->student_type     = 'Student';
                            $stu_si->type             = 'Settle';
                            $stu_si->total_hour       = format_number($settle['hour'],2,2);
                            $stu_si->total_amount     = format_number($settle['hour'] * $unit_price);

                            //caculate start_hour
                            $first   = reset($sss);
                            $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                            $stu_si->start_hour       = $start_hour;

                            $stu_si->start_study      = $settle['start'];
                            $stu_si->end_study        = $settle['end'];
                            $stu_si->student_id       = $student['id'];
                            $stu_si->ju_class_id      = $class_id;
                            $stu_si->payment_id       = $bean->id;

                            $stu_si->assigned_user_id = $bean->assigned_user_id;
                            $stu_si->team_id          = $bean->team_id;
                            $stu_si->team_set_id      = $bean->team_id;

                            $stu_si->date_entered     = $bean->date_entered;
                            $stu_si->date_modified    = $bean->date_entered;
                            $stu_si->created_by       = $bean->created_by;
                            $stu_si->modified_user_id = $bean->created_by;

                            $stu_si->save();
                            for($i = 0; $i < count($sss); $i++)
                                if($sss[$i]['session_status'] != 'Cancelled')
                                    addJunToSession($stu_si->id , $sss[$i]['primaryid'] );
                        }
                        //Create situation Enroll
                        if(!empty($enroll['hour'])){
                            $situ                   = new J_StudentSituations();
                            $situ->name             = $student['full_student_name'];
                            $situ->student_type     = 'Student';
                            $situ->type             = 'Enrolled';
                            $situ->total_hour       = format_number($enroll['hour'],2,2);
                            $situ->total_amount     = format_number($enroll['hour'] * $unit_price);

                            //caculate start_hour
                            $first   = reset($ss);
                            $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                            $situ->start_hour       = $start_hour;

                            $situ->start_study      = $enroll['start'];
                            $situ->end_study        = $enroll['end'];
                            $situ->student_id       = $student['id'];
                            $situ->ju_class_id      = $class_id;
                            $situ->payment_id       = $bean->id;

                            $situ->assigned_user_id = $bean->assigned_user_id;
                            $situ->team_id          = $bean->team_id;
                            $situ->team_set_id      = $bean->team_id;

                            $situ->date_entered     = $bean->date_entered;
                            $situ->date_modified    = $bean->date_entered;
                            $situ->created_by       = $bean->created_by;
                            $situ->modified_user_id = $bean->created_by;

                            $situ->save();
                            for($i = 0; $i < count($ss); $i++)
                                if($ss[$i]['session_status'] != 'Cancelled')
                                    addJunToSession($situ->id , $ss[$i]['primaryid'] );
                        }

                        $bean->tuition_hours        = $enroll['hour'] + $settle['hour'];
                        $bean->total_hours          = $bean->tuition_hours;
                        $bean->sessions             = $enroll['count'] + $settle['count'];
                    }
                    //Map related
                    if(!empty($bean->from_payment_aims_id)){
                        $from_payment_id = $GLOBALS['db']->getOne("SELECT id FROM j_payment WHERE aims_id = '{$bean->from_payment_aims_id}' AND payment_type != 'Enrollment' AND deleted = 0");

                        if(!empty($from_payment_id)){
                            $GLOBALS['db']->query("UPDATE j_payment SET kind_of_course = '{$bean->kind_of_course}' WHERE id = '$from_payment_id'");
                            addRelatedPayment($bean->id, $from_payment_id, $bean->deposit_amount);
                        }
                    }
                    $bean->payment_amount       = 0;
                }

            }
        }
    }
}
?>
