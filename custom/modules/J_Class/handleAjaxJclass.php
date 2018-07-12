<?php
require_once('custom/include/_helper/junior_class_utils.php');
require_once('custom/include/_helper/junior_schedule.php');
require_once("custom/include/_helper/junior_revenue_utils.php");
switch ($_POST['type']) {
    case 'ajaxMakeJsonSession':
        $result = ajaxMakeJsonSession($_POST['class_id'],$_POST['change_date_from'], $_POST['change_date_to'], $_POST['class_case'], $_POST['start_date'], $_POST['schedule'], $_POST['total_hours'], $_POST['change_reason'], $_POST['team_type']);
        if(!$result)
            echo json_encode(array(
                "success" => "0",
            ));
        else
            echo json_encode(array(
                "success"  => "1",
                "content"  => json_encode($result),
            ));
        break;
    case 'ajaxAddOutstanding':
        echo ajaxAddOutstanding($_POST['student_id'], $_POST['start_outstanding'], $_POST['end_outstanding'], $_POST['class_id'], $_POST['total_hours'], $_POST['add_type'], $_POST['situation_id'] );
        break;
    case 'addDemo':
        echo addDemo($_POST['dm_student_id'], $_POST['dm_type_student'], $_POST['dm_lesson_date'], $_POST['dm_class_id'] );
        break;
    case 'caculateFreeBalance':
        $result = caculateFreeBalance($_POST['situation_id'],$_POST['dl_from_date'],$_POST['dl_to_date']);
        echo $result;
        break;
    case 'handleCreateDelay':
        $result =  handleCreateDelay($_POST['dl_situation_id'], $_POST['dl_from_date'], $_POST['dl_to_date'], $_POST['dl_reason'], $_POST['dl_payment_date']);
        echo $result;
        break;
    case 'undoDelay':
        echo undoDelay($_POST['situation_delay_id']);
        break;
    case 'handleWaitingClass':
        $result = handleWaitingClass($_POST['act'], $_POST['student_id'], $_POST['class_id'], $_POST['parent']);
        echo $result;
        break;
    case 'ajaxGetWeekDay':
        $result = ajaxGetWeekDay( $_POST['from_date'],$_POST['to_date'],$_POST['class_id']);
        echo $result;
        break;
    case 'ajaxGetTeacherBySchedule':
        $result = ajaxGetTeacherBySchedule($_POST['class_id'],$_POST['from_date'],$_POST['to_date'],$_POST['day_of_week']);
        echo $result;
        break;
    case 'ajaxSaveTeacherSchedule':
        $result = ajaxSaveTeacherSchedule($_POST['teacher_id'],$_POST['contract_id'],$_POST['class_id'],$_POST['from_date'],$_POST['to_date'],$_POST['day_of_week'],$_POST['teaching_type'],$_POST['change_reason']);
        echo $result;
        break;
    case 'ajaxGetStudentList':
        $result = ajaxGetStudentList($_POST['class_id'],$_POST['session_id']);
        echo $result;
        break;
    case 'sendSMS':
        $result = sendSMS();
        echo $result;
        break;
    case 'ajaxSaveAttendance':
        $result = saveAttendance($_POST['session_id'],$_POST['student_id'],$_POST['attended'],$_POST['in_class'],$_POST['description'],$_POST['attend_id'], $_POST['absent_for_hour'], $_POST['homework']);
        echo $result;
        break;
    case 'ajaxSubmitInProgress':
        $result = submitInProgress($_POST['class_id']);
        echo $result;
        break;
    case 'ajaxSubmitClose':
        $result = ajaxSubmitClose($_POST['class_id'],$_POST['closed_date']);
        echo $result;
        break;
    case 'ajaxLoadClassInfo':
        $result = ajaxLoadClassInfo($_POST['class_id']);
        echo $result;
        break;
    case 'ajaxSaveSessionDescription':
        $result = ajaxSaveSessionDescription($_POST['session_id'],$_POST['description']);
        echo $result;
        break;
    case 'cancelSession' : // add by Trung Nguyen 2015.11.27
        $data = $_REQUEST;
        echo cancelSession($data);
        break;
    case 'getTeacherandRoom' :
        echo getTeacherAndRoom($_POST['date'],$_POST['start'], $_POST['end'], $_POST['class_id']);
        break;
    case 'getDataForCancelSession':
        echo getDataForCancelSession($_POST);
        break;
    case 'deleteSession':
        echo deleteSession($_POST);
        break;
    case 'saveStudentSituationDescription':
        echo saveStudentSituationDescription($_POST);
        break;
    case 'handleCreateDelayWaiting':
        echo  handleCreateDelayWaiting($_POST['dl_situation_id'], $_POST['dl_delay_hour'], $_POST['dl_delay_amount'], $_POST['dl_from_date'], $_POST['dl_reason']);
        break;
    case 'caculateFreeBalanceAdult':
        $result = caculateFreeBalanceAdult();
        echo $result;
        break;
    case 'handleRemoveFromClassAdult':
        $result = handleRemoveFromClassAdult();
        echo $result;
        break;
    case 'saveSyllabus':
        $result = saveSyllabus();
        echo $result;
        break;
    case 'saveHomework':
        $result = saveHomework();
        echo $result;
        break;
    case 'reloadSyllabus':
        $result = reloadSyllabus();
        echo $result;
        break;
    default:
        echo false;
        die;
}

function ajaxMakeJsonSession($class_id, $change_from, $change_to, $class_case, $start_date, $schedule, $total_hours, $change_reason, $team_type){
    global $timedate;

    //Make schedule
    $schedule_formated = array();
    $special_case_1 = 0;
    $special_case_2 = 0;
    $is_special_case = false;
    foreach($schedule as $weekdate => $schl){
        foreach($schl as $key => $time_slot){
            $schedule_formated[$weekdate][$key]['start_time']     = $timedate->to_db_time($time_slot['start_time'],false);
            $schedule_formated[$weekdate][$key]['end_time']       = $timedate->to_db_time($time_slot['end_time'],false);
            $_duration = unformat_number($time_slot['duration_hour']);
            $schedule_formated[$weekdate][$key]['revenue_hour']   = unformat_number($time_slot['revenue_hour']);
            $schedule_formated[$weekdate][$key]['duration_hour']  = $_duration;
            $schedule_formated[$weekdate][$key]['teaching_hour']  = unformat_number($time_slot['teaching_hour']);
            // Kiểm tra lịch có thuộc loại 2h và 1.5h ko
            if($_duration == '1.5')
                $special_case_1++;
            if($_duration == '2')
                $special_case_2++;
        }
    }
    if($special_case_1 >= 1 && $special_case_2 >= 1)
        $is_special_case = true;
    $is_special_case = false;  //Bỏ rule tạo buổi 1h - TẠM THỜI

    // schedule history


    if($class_case == 'create' || $class_case == 'change_startdate'){
        $start          = strtotime($timedate->to_db_date($start_date,false));
        $holiday_list   = getPublicHolidays($start_date, '', $team_type);

        //Public Holiday found
        $holidays       = array();
        $count_holidays = 0;
        $changeStartDate = 0;
        $newStartDate = "";

        //Session found
        $sessions       = array();
        $count_sessions = 1;

        $madeHour = 0;
        //foreach days check weekdate
        while($total_hours > 0){
            $chck_day   = date('D', $start);
            $run_date   = date('Y-m-d', $start);

            if (array_key_exists($chck_day, $schedule)){
                if (array_key_exists($run_date, $holiday_list)){
                    $holidays[$run_date]      = $holiday_list[$run_date];
                    $count_holidays++;
                    if($run_date == $timedate->to_db_date($start_date,false))
                        $changeStartDate = 1;
                }
                else{
                    foreach($schedule[$chck_day] as $key => $time_slot){
                        if($total_hours > 0){
                            $revenue_hour   = unformat_number($time_slot['revenue_hour']);
                            $teacher_hour   = unformat_number($time_slot['teaching_hour']);
                            $duration       = unformat_number($time_slot['duration_hour']);
                            //Kiểm tra trường hợp đặc biệt
                            if($is_special_case && ($total_hours == 1 || $total_hours == 37 || $total_hours == 73)){
                                $revenue_hour = 1;
                                $teacher_hour = 1;
                                $duration     = 1;
                            }
                            if($revenue_hour < 0)
                                return false;

                            $total_hours= $total_hours - $revenue_hour;

                            //Kiểm tra trường hợp hết giờ
                            if($total_hours<0){
                                $revenue_hour = $revenue_hour - abs($total_hours);
                                $teacher_hour = $teacher_hour - abs($total_hours);
                                $duration = $duration - abs($total_hours);
                            }

                            $start_time = $timedate->to_db($timedate->to_display_date($run_date,false).' '.$time_slot['start_time']);
                            $minutes    = $duration * 60;
                            $end_time   = date('Y-m-d H:i:s',strtotime("+$minutes minutes $start_time"));

                            $ss_date = array();
                            $ss_date['lesson']       = $count_sessions;
                            $ss_date['date']         = $run_date;
                            $ss_date['week_date']    = $chck_day;
                            $ss_date['start_time']   = $start_time;
                            $ss_date['end_time']     = $end_time;
                            $ss_date['revenue_hour'] = $revenue_hour;
                            $ss_date['teaching_hour']= $teacher_hour;
                            $sessions[$chck_day][$run_date."-$key"] = $ss_date;
                            $count_sessions++;

                            //Set new startdate if have holidays
                            if ($changeStartDate == 1 && $newStartDate == ""){
                                $newStartDate = $timedate->to_display_date($run_date,false);
                            }
                        }
                    }

                }
            }

            $start = strtotime('+1 day', $start);
        }
        //Sort array with weekday keys
        $arr2=array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');
        foreach($arr2 as $v){
            if(array_key_exists($v,$sessions)){
                $session_sorted[$v]=$sessions[$v];
            }
        }
        //fix array $holidays
        $holidaysArr = array();
        foreach($holidays as $key => $value){
            $holidaysArr[$timedate->to_display_date($key,false)] = $value;
        }
        if($class_case == 'change_startdate' && $team_type == 'Junior'){
            //Get situation affected
            $sss_list = array();
            foreach($session_sorted as $key => $value){
                foreach($value as $ss_date => $ss_value)
                    $sss_list[$ss_date] = $ss_value;
            }
            ksort($sss_list);
            $situationArr   = GetStudentsProcessInClass($class_id);
            $pays_unpaid    = getUnpaidPaymentByClass($class_id);
            $count_unpaid   = 0;
            $sit_err        = array();
            $html           = '<table id="config_content" class="table-border" width="100%" cellpadding="0" cellspacing="0" style="max-height: 350px; display: inline-block; width: 100%; overflow: auto;">';
            $html           .= '<thead><tr><td class="center no-bottom-border"><b>Tên học viên</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Loại</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Số giờ trước đổi lịch</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Số giờ sau đổi lịch</b></td>';
            $html           .= '<td class="center no-bottom-border"><b style="color:red;">Số giờ dư</b></td></tr></thead><tbody>';

            foreach($situationArr as $si_id => $si_value){
                $first  = '';
                $last   = '';
                $begin_total_hour = $si_value['total_hour'];
                $till = 0;
                foreach($sss_list as $ss_id => $ss_value){
                    $till += $ss_value['revenue_hour'];
                    $ss_start_till = $till - $ss_value['revenue_hour'];
                    if( ($si_value['total_hour']  > 0 ) &&  ($si_value['start_hour'] <= $ss_start_till)){
                        //Caculate First - Last Session
                        $si_value['total_hour'] -= $ss_value['revenue_hour'];
                        if($si_value['total_hour'] >= 0) {
                            if(empty($first))
                                $first = $ss_value['start_time'];
                            $last = $ss_value['end_time'];
                        }elseif($si_value['total_hour'] < 0 && $si_value['payment_status'] != 'Closed'){
                            $_err = array();
                            $_err['situation_id']   = $si_id;
                            $_err['delay_hour']     = $si_value['total_hour'] + $ss_value['revenue_hour'];
                            $html .= '<tr>';
                            $html .= '<td class="center">'.$si_value['student_name'].'</td>';
                            $html .= '<td class="center">'.$si_value['situa_type'];
                            if(in_array($si_value['payment_id'], $pays_unpaid)){
                                $html .= '&nbsp;<span class="textbg_redlight" >Unpaid</span></td>';
                                $count_unpaid++;
                            }else $html .= '</td>';

                            $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour,2,2).' </b> <br>'.$timedate->to_display_date($si_value['start_study']).'->'.$timedate->to_display_date($si_value['end_study']).'</td>';
                            $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour - $_err['delay_hour'],2,2).' </b> <br>'.$timedate->to_display_date($first).'->'.$timedate->to_display_date($last).'</td>';
                            $html .= '<td class="center"><b style="color:red;">'.format_number($_err['delay_hour'],2,2).' </b></td>';
                            $html .= '</tr>';
                            $sit_err[$si_value['student_id']][] = $_err;
                            break;
                        }
                    }
                }
            }

            $html .= '</tbody></table>';
            if(count($sit_err) == 0) $html ='';
        }else{
            $html = '';
            $count_unpaid = 0;
        }
        return array(
            'holidays'              => $holidaysArr,
            'count_holidays'        => $count_holidays,
            'new_start_Date'        => $newStartDate,
            'sessions'              => $session_sorted,
            'schedule'              => $schedule_formated,
            'count_sessions'        => $count_sessions - 1,
            'end_date'              => $run_date,
            'html_situation'        => $html,
            'count_unpaid'          => $count_unpaid,
        );

    }
    elseif($class_case == 'change_schedule'){
        $class  = BeanFactory::getBean('J_Class', $class_id);
        $change_from_db = date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($change_from,false)." 00:00:00"));
        $change_to_db   = date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($change_to,false)." 23:59:59"));
        $total_remain = $total_hours;
        $json = json_decode(html_entity_decode($class->content_2),true);

        //get list sessions remove
        $remove_session = array();
        $q1 = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        IFNULL(l1.name, '') l1_name,
        l1.date_start l1_date_start,
        l1.date_end l1_date_end,
        l1.delivery_hour l1_delivery_hour,
        l1.teaching_hour l1_teaching_hour,
        l1.duration_cal l1_duration_cal,
        l1.duration_hours l1_duration_hours,
        l1.duration_minutes l1_duration_minutes
        FROM
        j_class
        INNER JOIN
        meetings l1 ON j_class.id = l1.ju_class_id
        AND l1.deleted = 0
        WHERE
        (((j_class.id = '$class_id')
        AND ((l1.date_start >= '$change_from_db'
        AND l1.date_start <= '$change_to_db'))))
        AND j_class.deleted = 0
        ORDER BY l1_date_start ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
            $remove_session[] = $row['l1_id'];
        }

        //Make new list sessions
        $class_start_db =  date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($class->start_date,false)." 00:00:00"));
        $class_end_db   =  date('Y-m-d H:i:s',strtotime("-7 hours ".$timedate->to_db_date($class->end_date,false)." 23:59:59"));
        $sessions = array();
        $lesson = 0;
        $run_date = '';
        //List 1
        $q1 = "SELECT DISTINCT
        IFNULL(l1.id, '') l1_id,
        IFNULL(l1.name, '') l1_name,
        l1.date_start l1_date_start,
        l1.date_end l1_date_end,
        l1.delivery_hour l1_delivery_hour,
        l1.teaching_hour l1_teaching_hour,
        l1.duration_cal l1_duration_cal,
        l1.duration_hours l1_duration_hours,
        l1.duration_minutes l1_duration_minutes
        FROM
        j_class
        INNER JOIN
        meetings l1 ON j_class.id = l1.ju_class_id
        AND l1.deleted = 0
        WHERE
        (((j_class.id = '$class_id')
        AND ((l1.date_start >= '$class_start_db'
        AND l1.date_end < '$change_from_db'))))
        AND j_class.deleted = 0
        AND l1.session_status <> 'Cancelled'
        ORDER BY l1_date_start ASC";
        $rs1 = $GLOBALS['db']->query($q1);
        while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
            $total_remain = $total_remain - $row['l1_delivery_hour'];
            $run_date = date('Y-m-d',strtotime("+7 hours ".$row['l1_date_start']));
            $lesson++;
            $sessions[$row['l1_id']]['lesson'] = $lesson;
            $sessions[$row['l1_id']]['date']= $run_date;
            $sessions[$row['l1_id']]['week_date']   = date('D',strtotime($run_date));
            $sessions[$row['l1_id']]['start_time']  = $row['l1_date_start'];
            $sessions[$row['l1_id']]['end_time']    = $row['l1_date_end'];
            $sessions[$row['l1_id']]['revenue_hour']    = (double)$row['l1_delivery_hour'];
            $sessions[$row['l1_id']]['teaching_hour']   = (double)$row['l1_teaching_hour'];
            $end_time   = $sessions[$row['l1_id']]['end_time'];
        }

        //List 2
        $holiday_list  = getPublicHolidays($change_from, '', $team_type);
        $date_in_range = get_array_weekdates_db_junior($change_from, $change_to, $schedule);
        foreach($date_in_range as $ind=>$date){
            if(!array_key_exists($date, $holiday_list) && $total_remain > 0){
                $arr_schedule = $schedule[date('D',strtotime($date))];
                foreach($arr_schedule as $key => $time_slot){
                    if($total_remain > 0){
                        $revenue_hour = unformat_number($time_slot['revenue_hour']);
                        $teacher_hour = unformat_number($time_slot['teaching_hour']);
                        $duration     = unformat_number($time_slot['duration_hour']);
                        //Kiểm tra trường hợp đặc biệt
                        if($is_special_case && ($total_remain == 1 || $total_remain == 37 || $total_remain == 73)){
                            $revenue_hour = 1;
                            $teacher_hour = 1;
                            $duration     = 1;
                        }
                        $total_remain = $total_remain - $revenue_hour;

                        //Kiểm tra trường hợp hết giờ
                        if($total_remain < 0){
                            $revenue_hour   = $revenue_hour - abs($total_remain);
                            $teacher_hour   = $teacher_hour - abs($total_remain);
                            $duration       = $duration - abs($total_remain);
                            $total_remain   = 0;
                        }
                        $start_time = $timedate->to_db($timedate->to_display_date($date,false).' '.$time_slot['start_time']);
                        $minutes    = $duration * 60;
                        $end_time   = date('Y-m-d H:i:s',strtotime("+$minutes minutes $start_time"));

                        $lesson++;
                        $sessions[$date."-$key"]['lesson']      = $lesson;
                        $sessions[$date."-$key"]['date']        = $date;
                        $sessions[$date."-$key"]['week_date']   = date('D',strtotime($date));
                        $sessions[$date."-$key"]['start_time']  = $start_time;
                        $sessions[$date."-$key"]['end_time']    = $end_time;
                        $sessions[$date."-$key"]['revenue_hour']    = $revenue_hour;
                        $sessions[$date."-$key"]['teaching_hour']   = $teacher_hour;
                        $run_date = $date;
                    }
                }
            }
        }

        //List 3
        if($total_remain > 0){
            $q1 = "SELECT DISTINCT
            IFNULL(l1.id, '') l1_id,
            IFNULL(l1.name, '') l1_name,
            l1.date_start l1_date_start,
            l1.date_end l1_date_end,
            l1.delivery_hour l1_delivery_hour,
            l1.teaching_hour l1_teaching_hour,
            l1.duration_cal l1_duration_cal,
            l1.duration_hours l1_duration_hours,
            l1.duration_minutes l1_duration_minutes
            FROM
            j_class
            INNER JOIN
            meetings l1 ON j_class.id = l1.ju_class_id
            AND l1.deleted = 0
            WHERE
            (((j_class.id = '$class_id')
            AND ((l1.date_start >= '$change_to_db'))))
            AND j_class.deleted = 0
            AND l1.session_status <> 'Cancelled'
            ORDER BY l1_date_start ASC";
            $rs1 = $GLOBALS['db']->query($q1);
            while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                if($total_remain > 0){
                    $revenue_hour = $row['l1_delivery_hour'];
                    $teacher_hour = $row['l1_teaching_hour'];
                    $total_remain = $total_remain - $revenue_hour;
                    $start_time = $row['l1_date_start'];
                    $end_time   = $row['l1_date_end'];
                    if($total_remain<0){
                        $revenue_hour   = $revenue_hour - abs($total_remain);
                        $teacher_hour   = $teacher_hour - abs($total_remain);
                        $minutes        = $revenue_hour * 60;
                        $end_time       = date('Y-m-d H:i:s',strtotime("+$minutes minutes $start_time"));
                        $total_remain   = 0;
                    }

                    $run_date = date('Y-m-d',strtotime("+7 hours ".$row['l1_date_start']));
                    $lesson++;
                    $sessions[$row['l1_id']]['lesson'] = $lesson;
                    $sessions[$row['l1_id']]['date']   = $run_date;
                    $sessions[$row['l1_id']]['week_date']   = date('D',strtotime($run_date));
                    $sessions[$row['l1_id']]['start_time']  = $start_time;
                    $sessions[$row['l1_id']]['end_time']    = $end_time;
                    $sessions[$row['l1_id']]['revenue_hour']    = $revenue_hour;
                    $sessions[$row['l1_id']]['teaching_hour']   = $teacher_hour;
                }else{
                    $remove_session[] = $row['l1_id'];
                }
            }
        }
        //List 4 - Tạo tiếp session nếu chưa đủ
        $flag_list_4 = true;
        while($total_remain > 0){
            if($change_to_db == $class_end_db){
                $run_init   = strtotime('+1 day', strtotime($run_date));
                $date       = date('Y-m-d', $run_init);
                $run_date   = $date;
                if(array_key_exists(date('D',$run_init), $schedule) && !array_key_exists(date('Y-m-d',$run_init), $holiday_list)){
                    //Nếu change lịch đến ngày cuối cùng của lớp - thì generate tiếp lịch mới đổi
                    $arr_schedule = $schedule[date('D',$run_init)];
                    foreach($arr_schedule as $key => $time_slot){
                        if($total_remain > 0){
                            $revenue_hour = unformat_number($time_slot['revenue_hour']);
                            $teacher_hour = unformat_number($time_slot['teaching_hour']);
                            $duration     = unformat_number($time_slot['duration_hour']);
                            //Kiểm tra trường hợp đặc biệt
                            if($is_special_case && ($total_remain == 1 || $total_remain == 37 || $total_remain == 73)){
                                $revenue_hour = 1;
                                $teacher_hour = 1;
                                $duration     = 1;
                            }
                            $total_remain = $total_remain - $revenue_hour;

                            //Kiểm tra trường hợp hết giờ
                            if($total_remain < 0){
                                $revenue_hour   = $revenue_hour - abs($total_remain);
                                $teacher_hour   = $teacher_hour - abs($total_remain);
                                $duration       = $duration - abs($total_remain);
                                $total_remain   = 0;
                            }
                            $start_time = $timedate->to_db($timedate->to_display_date($date,false).' '.$time_slot['start_time']);
                            $minutes    = $duration * 60;
                            $end_time   = date('Y-m-d H:i:s',strtotime("+$minutes minutes $start_time"));

                            $lesson++;
                            $sessions[$date."-$key"]['lesson']      = $lesson;
                            $sessions[$date."-$key"]['date']        = $date;
                            $sessions[$date."-$key"]['week_date']   = date('D',strtotime($date));
                            $sessions[$date."-$key"]['start_time']  = $start_time;
                            $sessions[$date."-$key"]['end_time']    = $end_time;
                            $sessions[$date."-$key"]['revenue_hour']    = $revenue_hour;
                            $sessions[$date."-$key"]['teaching_hour']   = $teacher_hour;
                        }
                    }
                }
            }elseif(count($sessions) > 1){
                if($flag_list_4){
                    $last_item  = array_slice($sessions, -1, 1, true);//Get 1 session cuối
                    $last_ss_id = key($last_item); //Nếu lịch buổi cuối bị lẻ giờ thì gom chung
                    $last_ss    = reset($last_item);
                    if(!empty($json['schedule'][$last_ss['week_date']])){
                        $run_date       = $last_ss['date'];
                        $run_init       =  strtotime($run_date);
                        $date           = date('Y-m-d', $run_init);
                        $lesson        -= 1;
                        $total_remain  += $last_ss['revenue_hour'];
                        $date_key = $last_ss_id;
                    }
                    $flag_list_4    = false;
                }else{
                    $run_init   = strtotime('+1 day', strtotime($run_date));
                    $date       = date('Y-m-d', $run_init);
                    $run_date   = $date;
                    $date_key = $date."-$key";
                }
                if(array_key_exists(date('D',$run_init), $json['schedule']) && !array_key_exists(date('Y-m-d',$run_init), $holiday_list)){
                    $arr_schedule = $json['schedule'][date('D',strtotime($date))];
                    foreach($arr_schedule as $key => $time_slot){
                        //Nếu tạo trong khoảng giữa thì lịch phát sinh sẽ tạo theo Main Schedule
                        if($total_remain > 0){
                            $revenue_hour = unformat_number($time_slot['revenue_hour']);
                            $teacher_hour = unformat_number($time_slot['teaching_hour']);
                            $duration     = unformat_number($time_slot['duration_hour']);
                            //Kiểm tra trường hợp đặc biệt
                            if($is_special_case && ($total_remain == 1 || $total_remain == 37 || $total_remain == 73)){
                                $revenue_hour = 1;
                                $teacher_hour = 1;
                                $duration     = 1;
                            }
                            $total_remain = $total_remain - $revenue_hour;

                            //Kiểm tra trường hợp hết giờ
                            if($total_remain < 0){
                                $revenue_hour   = $revenue_hour - abs($total_remain);
                                $teacher_hour   = $teacher_hour - abs($total_remain);
                                $duration       = $duration - abs($total_remain);
                                $total_remain   = 0;
                            }
                            $start_time_display = $timedate->to_display_time($time_slot['start_time'],true,false);

                            $start_time = $timedate->to_db($timedate->to_display_date($date,false).' '.$start_time_display);
                            $minutes    = $duration * 60;
                            $end_time   = date('Y-m-d H:i:s',strtotime("+$minutes minutes $start_time"));

                            $lesson++;
                            $sessions[$date_key]['lesson']      = $lesson;
                            $sessions[$date_key]['date']        = $date;
                            $sessions[$date_key]['week_date']   = date('D',strtotime($date));
                            $sessions[$date_key]['start_time']  = $start_time;
                            $sessions[$date_key]['end_time']    = $end_time;
                            $sessions[$date_key]['revenue_hour']    = $revenue_hour;
                            $sessions[$date_key]['teaching_hour']   = $teacher_hour;
                            if($lesson > 1000){
                                die();
                            }
                        }
                    }
                }

            }else break;
        }

        //Add Session Remove After Finish Date - Modified By Lap Nguyen - Addded by Tung Bui
        if(!empty($end_time) && !empty($class_id) && $total_remain <= 0){
            $sqlDeleteSession = "SELECT DISTINCT
            IFNULL(meetings.id, '') session_id
            FROM meetings
            WHERE deleted = 0
            AND date_start > '$end_time'
            AND ju_class_id = '$class_id'";
            $rsDeleteSession = $GLOBALS['db']->query($sqlDeleteSession);
            while($row = $GLOBALS['db']->fetchByAssoc($rsDeleteSession))
                if(!in_array($row['session_id'],$remove_session)) $remove_session[] = $row['session_id'];

        }

        if( $team_type == 'Junior'){
            //Get situation affected
            $situationArr   = GetStudentsProcessInClass($class_id, $change_from);
            $pays_unpaid    = getUnpaidPaymentByClass($class_id);
            $count_unpaid   = 0;
            $sit_err        = array();
            $html           = '<table id="config_content" class="table-border" width="100%" cellpadding="0" cellspacing="0" style="max-height: 350px; display: inline-block; width: 100%; overflow: auto;">';
            $html           .= '<thead><tr><td class="center no-bottom-border"><b>Tên học viên</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Loại</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Số giờ trước đổi lịch</b></td>';
            $html           .= '<td class="center no-bottom-border"><b>Số giờ sau đổi lịch</b></td>';
            $html           .= '<td class="center no-bottom-border"><b style="color:red;">Số giờ dư</b></td></tr></thead><tbody>';

            //render session by date
            $sessions_date = array();
            foreach($sessions as $key=>$value){
                $sessions_date[$value['date']]['lesson']     =  $value['lesson'];
                $sessions_date[$value['date']]['date']       =  $value['date'];
                $sessions_date[$value['date']]['week_date']  =  $value['week_date'];
                $sessions_date[$value['date']]['start_time'] =  $value['start_time'];
                $sessions_date[$value['date']]['end_time']   =  $value['end_time'];
                $sessions_date[$value['date']]['revenue_hour']  +=  $value['revenue_hour'];
                $sessions_date[$value['date']]['teaching_hour'] +=  $value['teaching_hour'];
            }

            foreach($situationArr as $si_id => $si_value){
                $first  = '';
                $last   = '';
                $begin_total_hour = $si_value['total_hour'];
                $till = 0;
                foreach($sessions_date as $ss_id => $ss_value){
                    $till += $ss_value['revenue_hour'];
                    $ss_start_till = $till - $ss_value['revenue_hour'];
                    if( ($si_value['total_hour']  > 0 ) &&  ($si_value['start_hour'] <= $ss_start_till)){
                        //Caculate First - Last Session
                        $si_value['total_hour'] -= $ss_value['revenue_hour'];
                        if($si_value['total_hour'] >= 0) {
                            if(empty($first))
                                $first = $ss_value['start_time'];
                            $last = $ss_value['end_time'];
                        }elseif($si_value['total_hour'] < 0 && $si_value['payment_status'] != 'Closed'){
                            $_err = array();
                            $_err['situation_id']   = $si_id;
                            $_err['delay_hour']     = $si_value['total_hour'] + $ss_value['revenue_hour'];
                            $html .= '<tr>';
                            $html .= '<td class="center">'.$si_value['student_name'].'</td>';
                            $html .= '<td class="center">'.$si_value['situa_type'];
                            if(in_array($si_value['payment_id'], $pays_unpaid)){
                                $html .= '&nbsp;<span class="textbg_redlight" >Unpaid</span></td>';
                                $count_unpaid++;
                            }else $html .= '</td>';

                            $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour,2,2).' </b> <br>'.$timedate->to_display_date($si_value['start_study']).'->'.$timedate->to_display_date($si_value['end_study']).'</td>';
                            $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour - $_err['delay_hour'],2,2).' </b> <br>'.$timedate->to_display_date($first).'->'.$timedate->to_display_date($last).'</td>';
                            $html .= '<td class="center"><b style="color:red;">'.format_number($_err['delay_hour'],2,2).' </b></td>';
                            $html .= '</tr>';
                            $sit_err[$si_value['student_id']][] = $_err;
                            break;
                        }
                    }
                }
            }

            $html .= '</tbody></table>';
            if(count($sit_err) == 0) $html ='';
        }else{
            $html = '';
            $count_unpaid = 0;
        }

        return array(
            'sessions'              => $sessions,
            'sessions_remove'       => $remove_session,
            'count_sessions'        => $lesson - 1,
            'schedule'              => $schedule_formated,
            'end_date'              => $run_date,
            'html_situation'        => $html,
            'count_unpaid'          => $count_unpaid,
        );
    }
}

function ajaxAddOutstanding($student_id, $start, $end, $class_id, $total_hours, $add_type, $situation_id){
    global $timedate;

    //HANDLE DELETE
    if($add_type == 'Delete'){
        //Remove Delete new Stuatiaton
        removeJunFromSession($situation_id);
        $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '$situation_id'");

        return json_encode(array(
            "success" => "1",
            "notify" => 'Outstanding has been deleted !',
        ));
    }
    if($add_type == 'Create'){
        $count_ot_in_class = $GLOBALS['db']->getOne("SELECT DISTINCT
            COUNT(j_studentsituations.id) allcount
            FROM
            j_studentsituations
            INNER JOIN
            j_class l1 ON j_studentsituations.ju_class_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            contacts l2 ON j_studentsituations.student_id = l2.id
            AND l2.deleted = 0
            WHERE
            (((j_studentsituations.type = 'OutStanding')
            AND (l1.id = '$class_id')
            AND (l2.id = '$student_id')))
            AND j_studentsituations.deleted = 0");
        if($count_ot_in_class > 0)
            return json_encode(array(
                "success" => "0",
                "error" => 'Failed to add Outstanding. Outstanding is existing in class!',
            ));
    }


    if($total_hours == 0)
        return json_encode(array(
            "success" => "0",
            "error" => 'Failed to add Outstanding,. Please try again!',
        ));

    //HANDLE EDIT
    $ext_sql = '';
    if($add_type == 'Edit'){
        //Chuyển trạng thái deleted để pass việc check situation
        $today_time = $timedate->nowDb();
        $_sql = "UPDATE meetings_contacts SET deleted='1', date_modified='$today_time' WHERE situation_id='$situation_id' AND deleted = 0";
        $GLOBALS['db']->query($_sql);
    }
    if(is_exist_in_class($student_id, $start, $end, $class_id, "'Enrolled', 'Moving In', 'Settle', 'Outstanding'")){
        //HANDLE EDIT: Nếu có lỗi thì cập nhật lại trạng thái deleted = 0
        if($add_type == 'Edit')
            $GLOBALS['db']->query("UPDATE meetings_contacts SET deleted='0' WHERE situation_id='$situation_id' AND deleted = 1 AND date_modified='$today_time'");

        return json_encode(array(
            "success" => "0",
            "error" => 'Failed to add Outstanding! Student already exist in the class',
        ));
    }else{
        $student     = BeanFactory::getBean("Contacts", $student_id);
        $class       = BeanFactory::getBean("J_Class", $class_id);
        //Delete Waiting class hoặc Demo trong lớp trước khi add Outstanding
        $q1 = "SELECT DISTINCT IFNULL(id, '') situation_id
        FROM j_studentsituations
        WHERE (type IN ('Demo' , 'Waiting Class')) AND (student_id = '$student_id') AND (ju_class_id = '$class_id') AND deleted = 0";
        $rs1 = $GLOBALS['db']->query($q1);
        while($row1= $GLOBALS['db']->fetchByAssoc($rs1)){
            removeJunFromSession($row1['situation_id']);
            $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted=1 WHERE id='{$row1['situation_id']}'");
        }


        //save situation
        $arr_ss = get_list_lesson_by_class($class_id, $start, $end);
        //HANDLE EDIT
        if($add_type == 'Create')
            $situ                      = new J_StudentSituations();
        elseif($add_type == 'Edit')
            $situ                      = BeanFactory::getBean('J_StudentSituations',$situation_id);
        $situ->name             = $student->full_student_name;
        $situ->student_type     = 'Student';
        $situ->type             = 'OutStanding';
        $situ->total_hour       = $total_hours;
        //caculate start_hour
        $first   = reset($arr_ss);
        $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
        $situ->start_hour       = $start_hour;
        $situ->total_amount        = 0;
        $situ->start_study         = $start;
        $situ->end_study           = $end;
        $situ->student_id          = $student_id;
        $situ->ju_class_id         = $class_id;

        $situ->assigned_user_id = $GLOBALS['current_user']->id;
        $situ->team_id          = $class->team_id;
        $situ->team_set_id      = $class->team_id;
        $situ->save();

        //Add relationship class - student
        $class->load_relationships('j_class_contacts_1');
        $class->j_class_contacts_1->add($student_id);
        for($i = 0; $i < count($arr_ss); $i++)
            addJunToSession($situ->id , $arr_ss[$i]['primaryid'] );
        //Update Is Stopped
        if(!empty($student->id))
            $GLOBALS['db']->query("UPDATE contacts SET is_stopped=0 WHERE id='{$student->id}' AND is_stopped=1 AND deleted=0");

        return json_encode(array(
            "success" => "1",
            "notify" => 'Add Outstanding successfully !',
        ));
    }
}

function addDemo($dm_student_id, $dm_type_student, $dm_lesson_date, $dm_class_id){
    global $timedate, $current_user;
    $stu_delay = new J_StudentSituations();
    $class 	= BeanFactory::getBean('J_Class', $dm_class_id);
    $stu     = BeanFactory::getBean($dm_type_student, $dm_student_id);
    if(($_POST['action_demo'] == 'delete')){
        //GET INFO
        $sql = "SELECT lead_id, student_id, student_type, ju_class_id FROM j_studentsituations WHERE id = '{$_POST['situa_id']}' AND deleted = 0";
        $res = $GLOBALS['db']->query($sql);
        $row = $GLOBALS['db']->fetchByAssoc($res);

        if($row['student_type'] == 'Student')
            removeJunFromSession($_POST['situa_id']);
        elseif($row['student_type'] == 'Lead')
            removeLeadFromSession($_POST['situa_id']);

        $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$_POST['situa_id']}'");
        return json_encode(array(
            "success" => "1",
            "notify" => $GLOBALS['mod_strings']['LBL_DELETED_DEMO'],
        ));

    }elseif($_POST['action_demo'] == 'create'){
        if($dm_type_student == 'Leads'){
            $res_demo = is_exist_lead_in_class($dm_student_id, $dm_class_id);
            if($res_demo)
                return json_encode(array(
                    "success" => "0",
                    "error" => $GLOBALS['mod_strings']['LBL_ERROR_LEAD_ALREADY_EXIST_IN_CLASS'],
                ));
            //end
            //check lead dã convert chưa nểu rồi thì đổi type để add Student- ??
            $sql = "SELECT contact_id FROM leads WHERE id = '{$dm_student_id}' AND deleted = 0";
            $student = $GLOBALS['db']->getOne($sql);
            if(!empty($student)){
                $dm_student_id = $student;
                $dm_type_student = 'Contacts';
                $stu     = BeanFactory::getBean($dm_type_student, $dm_student_id);
            }else{
                $stu_delay->student_type    = 'Lead';
                $stu_delay->lead_id         = $stu->id;
            }
        }
        if($dm_type_student == 'Contacts'){
            //Check Existing in situation
            $res = is_exist_in_class($dm_student_id, $dm_lesson_date, $dm_lesson_date, $dm_class_id);
            if($res)
                return json_encode(array(
                    "success" => "0",
                    "error" => $GLOBALS['mod_strings']['LBL_ERROR_STUDENT_ALREADY_EXIST_IN_CLASS'],
                ));

            //Check học viên đã add demo chưa
            $res_demo = is_exist_in_class($dm_student_id, $class->start_date, $class->end_date, $dm_class_id, "'Demo'");
            if($res_demo)
                return json_encode(array(
                    "success" => "0",
                    "error" => $GLOBALS['mod_strings']['LBL_ERROR_STUDENT_ALREADY_DEMO_IN_CLASS'],
                ));

            $stu_delay->student_type = 'Student';
            $stu_delay->student_id = $stu->id;
        }
        $ss = get_list_lesson_by_class($dm_class_id, $dm_lesson_date, $dm_lesson_date);
        $first   = reset($ss);
        $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
        $stu_delay->name           = $stu->last_name.' '.$stu->first_name;
        $stu_delay->ju_class_id    = $dm_class_id;
        $stu_delay->total_hour     = 0;
        $stu_delay->total_amount   = 0;
        $stu_delay->start_study    = $dm_lesson_date;
        $stu_delay->end_study      = $dm_lesson_date;
        $stu_delay->start_hour     = $start_hour;

        $stu_delay->type           = 'Demo';

        $stu_delay->assigned_user_id 	= $current_user->id;
        $stu_delay->team_id            = $class->team_id;
        $stu_delay->team_set_id        = $class->team_set_id;
        $stu_delay->save();


        if($stu_delay->student_type == 'Student')
            for($i = 0; $i < count($ss); $i++)
                addJunToSession($stu_delay->id, $ss[$i]['primaryid']);
        elseif($stu_delay->student_type == 'Lead')
            for($i = 0; $i < count($ss); $i++)
                addLeadToSession($stu_delay->id, $ss[$i]['primaryid']);


        return json_encode(array(
            "success" => "1",
            "notify" => $GLOBALS['mod_strings']['LBL_ADD_DEMO_SUCCESSFULLY'],
        ));

    }
}


//====================== Some funtion In Subpanel Situation By Hoang Quyen======================//
function caculateFreeBalance($situation_id, $from_date, $to_date){
    $situa = BeanFactory::getBean('J_StudentSituations', $situation_id);

    //Lấy số revenue (Collect và Delivery)
    $row2 = get_total_revenue($situa->student_id, "'{$situa->type}'", $from_date, $to_date,'',$situation_id);
    $total_hour 	= 0;
    $total_amount 	= 0;
    $delay_hour     = 0;
    $delay_amount   = 0;
    for($i = 0; $i < count($row2); $i++){
        $total_hour 	+= $row2[$i]['total_hour_situa'];
        $total_amount 	+= $row2[$i]['total_amount_situa'];
        $delay_hour 	+= $row2[$i]['total_revenue_hour'];
        $delay_amount 	+= $row2[$i]['total_revenue'];
    }
    return json_encode(array(
        "success" 		=> "1",
        "total_hour" 	=> format_number($total_hour,2,2),
        "total_amount" 	=> format_number($total_amount),
        "delay_hour" 	=> format_number($delay_hour,2,2),
        "delay_amount" 	=> format_number($delay_amount),
        "used_hour" 	=> format_number($total_hour - $delay_hour,2,2),
        "used_amount" 	=> format_number($total_amount - $delay_amount),
    ));

}

function handleCreateDelay($situation_id, $from_date, $to_date, $dl_reason, $dl_payment_date){
    global $timedate, $current_user;

    $situa = BeanFactory::getBean('J_StudentSituations', $situation_id);
    $student = BeanFactory::getBean('Contacts',$situa->student_id);
    $ss_remove = get_list_revenue($situa->student_id,'', $from_date, $to_date, '', $situation_id);
    //Caculate Free Balance
    $res = json_decode(caculateFreeBalance($situation_id, $from_date, $to_date), true);

    $undo_arr = array();

    //Payment Delay
    require_once('custom/include/_helper/junior_class_utils.php');
    $pm_delay= new J_Payment();
    $pm_delay->contacts_j_payment_1contacts_ida = $situa->student_id;

    $pm_delay->payment_type = 'Delay';
    if(empty($dl_payment_date))
        $dl_payment_date = date('Y-m-d');

    $pm_delay->payment_date     = $dl_payment_date;
    $pm_delay->payment_expired 	= date('Y-m-d',strtotime("+6 months ".$timedate->to_db_date($dl_payment_date,false)));
    $pm_delay->payment_amount 	= $res['delay_amount'];
    $pm_delay->remain_amount 	= $res['delay_amount'];
    $pm_delay->tuition_hours 	= $res['delay_hour'];
    $pm_delay->total_hours 		= $res['delay_hour'];
    $pm_delay->remain_hours     = $res['delay_hour'];
    $pm_delay->used_hours       = 0;
    $pm_delay->used_amount   	= 0;
    $pm_delay->assigned_user_id = $current_user->id;
    $pm_delay->team_id 			= $situa->team_id;
    $pm_delay->team_set_id 		= $situa->team_set_id;
    $pm_delay->description      = $dl_reason;
    $pm_delay->save();
    addRelatedPayment($pm_delay->id, $situa->payment_id, unformat_number($res['delay_amount']), unformat_number($res['delay_hour']));
    $undo_arr['payment_delay_id']	= $pm_delay->id;

    //Tạo Situation Delay
    $stu_delay = new J_StudentSituations();
    $stu_delay->id                = create_guid();
    $stu_delay->new_with_id     = true;
    $stu_delay->name           = $student->last_name.' '.$student->first_name;
    $stu_delay->student_type   = 'Student';
    $stu_delay->student_id     = $student->id;
    $stu_delay->ju_class_id    = $situa->ju_class_id;
    $stu_delay->payment_id     = $pm_delay->id;
    $stu_delay->relate_situation_id     = $situa->id;
    $stu_delay->before_hour         = $res['total_hour'];
    $stu_delay->before_amount       = $res['total_amount'];
    $stu_delay->total_hour          = $res['delay_hour'];
    $stu_delay->total_amount        = $res['delay_amount'];
    $stu_delay->used_hour           = $res['used_hour'];
    $stu_delay->used_amount         = $res['used_amount'];
    $stu_delay->start_studied       = $situa->start_study;
    $stu_delay->end_studied         = $situa->end_study;
    $stu_delay->start_study         = $from_date;
    $stu_delay->end_study           = $to_date;
    $stu_delay->description         = $dl_reason;

    $stu_delay->type           = 'Delayed';

    $stu_delay->assigned_user_id   = $current_user->id;
    $stu_delay->team_id            = $situa->team_id;
    $stu_delay->team_set_id        = $situa->team_set_id;
    $undo_arr['situation_delay_id']     = $stu_delay->id;

    //Xóa học viên khỏi các session delay
    for($i = 0; $i < count($ss_remove); $i++){
        $undo_arr['remove_session'][$i] = $ss_remove[$i]['primaryid'];
        removeJunFromSession($situation_id, $ss_remove[$i]['primaryid']);
    }
    //Chia lại Situation
    $ss_revenue1 		= get_total_revenue($situa->student_id, "'{$situa->type}'", $situa->start_study, $from_date,'',$situation_id);
    if($ss_revenue1[0]['total_revenue_hour'] > 0){
        $ss_list1 	= get_list_revenue($situa->student_id,'', $situa->start_study, $from_date, '', $situation_id);
        //Tạo Situation List 1
        $stu_1 = new J_StudentSituations();
        $stu_1->name           = $student->last_name.' '.$student->first_name;
        $stu_1->student_type   = 'Student';
        $stu_1->student_id     = $student->id;
        $stu_1->ju_class_id    = $situa->ju_class_id;
        $stu_1->payment_id     = $situa->payment_id;
        $stu_1->relate_situation_id     = $situa->id;
        $stu_1->total_hour     = format_number($ss_revenue1[0]['total_revenue_hour'],2,2);

        //caculate start_hour
        $first   = reset($ss_list1);
        $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
        $stu_1->start_hour     = $start_hour;

        $stu_1->total_amount   = format_number($ss_revenue1[0]['total_revenue']);
        $stu_1->start_study    = $timedate->to_display_date($ss_list1[0]['date_start']);
        $stu_1->end_study      = $timedate->to_display_date($ss_list1[count($ss_list1)-1]['date_start']);

        $stu_1->type           = $situa->type;

        $stu_1->assigned_user_id   = $situa->assigned_user_id;
        $stu_1->team_id            = $situa->team_id;
        $stu_1->team_set_id        = $situa->team_set_id;
        $stu_1->save();
        $undo_arr['situation_new_id'][1] = $stu_1->id;
        for($i = 0; $i < count($ss_list1); $i++){
            addJunToSession($stu_1->id , $ss_list1[$i]['primaryid'] );
        }
    }

    $ss_revenue2 		= get_total_revenue($situa->student_id, "'{$situa->type}'",$to_date , $situa->end_study,'',$situation_id);
    if($ss_revenue2[0]['total_revenue_hour'] > 0){
        $ss_list2 	= get_list_revenue($situa->student_id,'',$to_date , $situa->end_study, '', $situation_id);
        //Tạo Situation List 2
        $stu_2 = new J_StudentSituations();
        $stu_2->name           = $student->last_name.' '.$student->first_name;
        $stu_2->student_type   = 'Student';
        $stu_2->student_id     = $student->id;
        $stu_2->ju_class_id    = $situa->ju_class_id;
        $stu_2->payment_id     = $situa->payment_id;
        $stu_2->relate_situation_id     = $situa->id;
        $stu_2->total_hour     = format_number($ss_revenue2[0]['total_revenue_hour'],2,2);
        $stu_2->total_amount   = format_number($ss_revenue2[0]['total_revenue']);

        //caculate start_hour
        $first   = reset($ss_list2);
        $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
        $stu_2->start_hour     = $start_hour;

        $stu_2->start_study    = $timedate->to_display_date($ss_list2[0]['date_start']);
        $stu_2->end_study      = $timedate->to_display_date($ss_list2[count($ss_list2)-1]['date_start']);

        $stu_2->type           = $situa->type;

        $stu_2->assigned_user_id   = $situa->assigned_user_id;
        $stu_2->team_id            = $situa->team_id;
        $stu_2->team_set_id        = $situa->team_set_id;
        $stu_2->save();
        $undo_arr['situation_new_id'][2] = $stu_2->id;
        for($i = 0; $i < count($ss_list2); $i++){
            addJunToSession($stu_2->id , $ss_list2[$i]['primaryid'] );
        }
    }
    //Xóa situation cũ
    $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted = 1 WHERE id='{$situa->id}'");
    $undo_arr['situation_remove_id'] = $situa->id;
    //Remove quan hệ Session cũ
    if(!empty($undo_arr['situation_remove_id']))
        removeJunFromSession($undo_arr['situation_remove_id']);
    //Lưu Json Undo vào session delay
    $stu_delay->json_moving = json_encode($undo_arr);
    $stu_delay->save();
    update_remain_last_date($situa->student_id);
    return json_encode(array(
        "success" 		=> "1",
    ));
}

function handleCreateDelayWaiting($dl_situation_id, $dl_delay_hour, $dl_delay_amount, $dl_from_date, $dl_reason){
    global $timedate, $current_user;

    $situa = BeanFactory::getBean('J_StudentSituations', $dl_situation_id);

    if($dl_delay_hour <= 0  || $dl_delay_amount < 0 || empty($situa->id)|| empty($dl_from_date))
        return json_encode(array(
            "success"         => "0",
        ));
    //Tao Payment Delay
    $pm_delay= new J_Payment();
    $pm_delay->contacts_j_payment_1contacts_ida = $situa->student_id;
    $pm_delay->payment_type = 'Delay';
    $pm_delay->payment_date = $dl_from_date;
    $pm_delay->payment_expired      = date('Y-m-d',strtotime("+6 months ".$timedate->to_db_date($dl_from_date,false)));
    $pm_delay->payment_amount       = format_number($dl_delay_amount);
    $pm_delay->remain_amount        = format_number($dl_delay_amount);
    $pm_delay->tuition_hours        = format_number($dl_delay_hour,2,2);
    $pm_delay->total_hours          = format_number($dl_delay_hour,2,2);
    $pm_delay->remain_hours         = format_number($dl_delay_hour,2,2);
    $pm_delay->used_hours           = 0;
    $pm_delay->used_amount          = 0;
    $pm_delay->description          = $dl_reason;
    $pm_delay->assigned_user_id     = $current_user->id;
    $pm_delay->team_id              = $situa->team_id;
    $pm_delay->team_set_id          = $situa->team_set_id;
    $pm_delay->save();
    addRelatedPayment($pm_delay->id, $situa->payment_id, unformat_number($dl_delay_amount),unformat_number($dl_delay_hour));

    //Tru tien sitiation
    $situa->total_hour      = $situa->total_hour - $dl_delay_hour;
    $situa->total_amount    = $situa->total_amount - $dl_delay_amount;
    if($situa->total_hour <= 0)
        $situa->deleted = 1;
    $situa->save();

    return json_encode(array(
        "success"         => "1",
    ));
}

function undoDelay($situation_delay_id){
    $situation = BeanFactory::getBean('J_StudentSituations', $situation_delay_id);
    $undo_obj = json_decode(html_entity_decode($situation->json_moving), true);

    //Undelete situation cũ
    $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted=0 WHERE id='{$undo_obj['situation_remove_id']}'");


    //Add Học viên vào lại situation cũ - Remove List
    for($i = 0; $i < count($undo_obj['remove_session']); $i++)
        addJunToSession($undo_obj['situation_remove_id'] , $undo_obj['remove_session'][$i] );
    // List 1
    if(!empty($undo_obj['situation_new_id'][1])){
        $ss_list1 	= get_list_revenue('','','' , '', '', $undo_obj['situation_new_id'][1]);
        for($i = 0; $i < count($ss_list1); $i++)
            addJunToSession($undo_obj['situation_remove_id'] , $ss_list1[$i]['primaryid'] );

        //Remove Delete new Stuatiaton 1
        removeJunFromSession($undo_obj['situation_new_id'][1]);
        $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$undo_obj['situation_new_id'][1]}'");

    }
    // List 2
    if(!empty($undo_obj['situation_new_id'][2])){
        $ss_list2 	= get_list_revenue('','','' , '', '', $undo_obj['situation_new_id'][2]);
        for($i = 0; $i < count($ss_list2); $i++)
            addJunToSession($undo_obj['situation_remove_id'] , $ss_list2[$i]['primaryid'] );

        //Remove Delete new Stuatiaton 2
        removeJunFromSession($undo_obj['situation_new_id'][2]);
        $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$undo_obj['situation_new_id'][2]}'");

    }

    //Update Situation Time
    $ses = get_list_lesson_by_situation('', $undo_obj['situation_remove_id'], '', '', 'INNER');
    $first = reset($ses);
    $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

    $last = end($ses);
    $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

    if(!empty($date_last) && !empty($date_first)){
        $q3 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last' WHERE id='{$undo_obj['situation_remove_id']}'";
        $GLOBALS['db']->query($q3);
    }

    //Remove relation Payment Delay - Enrollment
    removeRelatedPayment($undo_obj['payment_delay_id']);

    //Remove Payment Delay
    $GLOBALS['db']->query("DELETE FROM j_payment WHERE id = '{$undo_obj['payment_delay_id']}'");

    //Remove Situation Delay
    $GLOBALS['db']->query("DELETE FROM j_studentsituations WHERE id = '{$undo_obj['situation_delay_id']}'");

    return json_encode(array(
        "success" => "1",
        "start_study" => $situation->start_study,
    ));
}

function handleWaitingClass($act, $student_id, $class_id, $parent){
    if($act == 'addWaitingClass'){
        //kiem tra neu student or lead do da co trong situation thi bo qua khong them nua
        $sql_check = "SELECT id FROM j_studentsituations WHERE  (student_id = '$student_id' OR lead_id= '$student_id') AND ju_class_id = '$class_id' AND deleted = 0";
        $id = $GLOBALS['db']->getOne($sql_check);

        if(empty($id)){
            $class = BeanFactory::getBean("J_Class", $class_id);
            $stu   = BeanFactory::getBean($parent, $student_id);
            //save situation
            $situation          = new J_StudentSituations();

            if($parent == "Contacts"){
                $student = BeanFactory::getBean("Contacts", $student_id);
                $situation->name = $student->last_name.' '.$student->first_name;
                $situation->student_type   = 'Student';
                $situation->student_id      = $student_id;
            }else{
                $lead = BeanFactory::getBean("Leads", $student_id);
                $situation->name = $lead->last_name.' '.$lead->first_name;
                $situation->student_type   = 'Lead';
                //Add relationship class - lead
                $situation->lead_id      = $student_id;
            }

            $situation->type            = 'Waiting Class';
            $situation->start_study     = $class->start_date;
            $situation->end_study       = $class->start_date;
            $situation->ju_class_id     = $class_id;
            $situation->assigned_user_id= $class->assigned_user_id;
            $situation->team_id         = $class->team_id;
            $situation->team_set_id     = $class->team_id;
            $situation->save();
        }
    }elseif($act == 'deleteWaitingClass'){
        $GLOBALS['db']->query("UPDATE j_studentsituations SET deleted='1' WHERE id='{$_POST['situation_id']}' AND deleted = 0");
    }
    return json_encode(array(
        "success" => "1",
    ));
}

//====================== End Some funtion Schedule By Hoang Quyen======================//

//Function get sutdent list for Send SMS Form by Tung Bui
function ajaxGetStudentList($classId,$sessionId){
    global $timedate;                                          
    $student_list = "";

    //check session in date
    $sqlCheckSes = "SELECT id, description, duration_cal 
    FROM meetings
    WHERE ju_class_id = '{$classId}'
    AND deleted = 0
    AND id = '{$sessionId}'
    LIMIT 1";
    $result     = $GLOBALS['db']->query($sqlCheckSes);
    $rowSession = $GLOBALS['db']->fetchByAssoc($result);
    $sessionId  = $rowSession['id'];
    $description= $rowSession['description'];
    $duration   = floor($rowSession['duration_cal']);
    //	$sessionId = $GLOBALS['db']->getOne($sqlCheckSes);

    if (empty($sessionId)){
        return json_encode(array(
            "success" => "1",
            "session_id" => $sessionId,
            "content" => "<tr><td colspan='10' style='text-align:center;'>No session in {$lessonDate}</td></tr>",
        ));
    }

    //Get All student have relationship with this class
    $sqlAllStudent = "SELECT DISTINCT
    c.id student_id,
    IFNULL(c.full_student_name, '') student_name,
    IFNULL(c.phone_mobile, '') student_phone,
    CASE
    WHEN c.birthdate = '0000-00-00' THEN ''
    ELSE c.birthdate
    END birthdate,
    c.guardian_name parent_name,
    mc.meeting_id session_id,
    a.id atd_id,
    IFNULL(a.attended, 0) attended,
    IFNULL(a.homework, 0) homework,
    IFNULL(a.absent_for_hour, 0) absent_for_hour,
    a.description atd_desc,
    a.attended
    FROM
    contacts c
    INNER JOIN
    j_class_contacts_1_c cc ON cc.j_class_contacts_1contacts_idb = c.id
    AND c.deleted = 0
    AND cc.deleted = 0
    AND cc.j_class_contacts_1j_class_ida = '$classId'
    LEFT JOIN
    meetings_contacts mc ON mc.contact_id = c.id AND mc.deleted = 0
    AND mc.meeting_id = '$sessionId'
    LEFT JOIN
    c_attendance a ON a.student_id = c.id
    AND a.meeting_id = '$sessionId'
    AND a.deleted <> 1
    ORDER BY session_id DESC , c.first_name , c.last_name";
    $rsAllStudent = $GLOBALS['db']->query($sqlAllStudent);
    //$allStu = array();
    //$stuInSession = array();
    while($row = $GLOBALS['db']->fetchByAssoc($rsAllStudent)){
        //Create option Duration call
        $default = $row['absent_for_hour']+0;
        $optionAbs = array('0'=>'0');
        for($i = 1; $i <= $duration; $i++)
            $optionAbs["$i"] = "$i";

        $oneMoreOption = $rowSession['duration_cal'] - $duration;
        if($oneMoreOption > 0)
            $optionAbs[format_number($rowSession['duration_cal'],2,2)] =  format_number($rowSession['duration_cal'],2,2);

        if (empty($row['atd_id'])){
            $optionE = end($optionAbs);

            $attendanceBean = new C_Attendance();
            $attendanceBean->student_id = $row["student_id"];
            $attendanceBean->meeting_id = $sessionId;
            $attendanceBean->team_id        = '1';
            $attendanceBean->team_set_id    = '1';
//            $attendanceBean->attended   = 1;
//            $attendanceBean->homework   = 1;
//            $attendanceBean->absent_for_hour   = $optionE;
//            $row['attended'] = 1;
//            $row['homework'] = 1;
//            $row['absent_for_hour'] = $optionE;
            $attendanceBean->save();
            $attend_id = $attendanceBean->id;
        }else $attend_id = $row['atd_id'];

        $ss = new Sugar_Smarty();
        $ss->assign("IN_CLASS_TYPE",    (empty($row['session_id'])) ? "tr_not_in_class" : "");
        $ss->assign("STUDENT_ID",       $row["student_id"]);
        $ss->assign("STUDENT_NAME",     $row["student_name"]);
        $ss->assign("STUDENT_NAME_EN",  viToEn($row["student_name"]));
        $ss->assign("ATTENDANCE_ID",    $attend_id);
        $ss->assign("ABSENT_FOR_HOUR_OPTION",get_select_options_with_id($optionAbs,$row['absent_for_hour']));
        $ss->assign("STUDENT_PHONE",    $row["student_phone"]);
        $ss->assign("BIRTHDATE",        $timedate->to_display_date($row['birthdate'],false));
        $ss->assign("PARENTNAME",       $row['parent_name']);
        $ss->assign("ATTENDANCE_CHECKED",   $row['attended'] == 1 ? "checked" : "");
        $ss->assign("HOMEWORK_CHECKED",     $row['homework'] == 1 ? "checked" : "");
        $ss->assign("DESCRIPTION",          $row['atd_desc']);
        $ss->assign('MOD', return_module_language($GLOBALS['current_language'], 'J_Class'));
        $student_list .= $ss->fetch('custom/modules/J_Class/tpls/send_sms_screen_item.tpl');
    }
    $sqlLeads = "SELECT
    l.id lead_id,
    IFNULL(l.full_lead_name,
    CONCAT(l.last_name, ' ', l.first_name)) lead_name,
    l.phone_mobile lead_phone,
    CASE
    WHEN l.birthdate = '0000-00-00' THEN ''
    ELSE l.birthdate
    END birthdate,
    l.guardian_name parent_name,
    ml.meeting_id session_id
    FROM
    j_studentsituations ss
    INNER JOIN
    meetings_leads ml ON ml.lead_id = ss.lead_id
    AND ss.deleted = 0
    AND ss.type = 'Demo'
    AND ml.deleted = 0
    AND ml.meeting_id = '$sessionId'
    INNER JOIN
    leads l ON l.id = ss.lead_id AND l.deleted = 0";
    $rsLeads = $GLOBALS['db']->query($sqlLeads);
    while($row = $GLOBALS['db']->fetchByAssoc($rsLeads)){
        $ss = new Sugar_Smarty();
        $ss->assign("IN_CLASS_TYPE", "tr_not_in_class");
        $ss->assign("STUDENT_ID", $row["lead_id"]);
        $ss->assign("STUDENT_NAME", $row["lead_name"]);
        $ss->assign("STUDENT_NAME_EN", viToEn($row["lead_name"]));
        $ss->assign("ATTENDANCE_ID","");
        $ss->assign("STUDENT_PHONE", $row["lead_phone"]);
        $ss->assign("BIRTHDATE", $timedate->to_display_date($row['birthdate'],false));
        $ss->assign("PARENTNAME", $row['parent_name']);
        $ss->assign('MOD', return_module_language($GLOBALS['current_language'], 'J_Class'));
        $student_list .= $ss->fetch('custom/modules/J_Class/tpls/send_sms_screen_item.tpl');
    }
    return json_encode(array(
        "success"       => "1",
        "session_id"    => $sessionId,
        "session_description"   => $description,
        "content"       => $student_list,
    ));
}

function saveAttendance($sessionId, $studentId, $attended, $inClass, $description, $attend_id, $absent_for_hour, $homework){
    $attendanceBean = BeanFactory::getBean("C_Attendance", $attend_id);
    $attendanceBean->attended               = $attended;
    $attendanceBean->in_class               = $inClass;
    $attendanceBean->absent_for_hour        = $absent_for_hour;
    $attendanceBean->homework               = $homework;
    $attendanceBean->description            = $description;
    $attendanceBean->team_id                = '1';
    $attendanceBean->team_set_id            = '1';
    $attendanceBean->save();

    return json_encode(array(
        "success" => "1",
    ));
}

function submitInProgress($classId){
    $bean = BeanFactory::getBean('J_Class',$classId);
    // prepare an array to audit the changes in parent module’s audit table

    if($bean->field_name_map['status']['audited'] && $bean->fetched_row['status'] != 'In Progress'){
        $aChange = array();
        $aChange['field_name']  = 'status';
        $aChange['data_type']   = 'enum';
        $aChange['before']      = $bean->fetched_row['status'];
        $aChange['after']       = 'In Progress';
        // save audit entry
        $bean->db->save_audit_records($bean, $aChange);
    }

    $GLOBALS['db']->query("UPDATE j_class SET status= 'In Progress' WHERE id='$classId'");
    return json_encode(array(
        "success" => "1",
    ));
}
function ajaxSubmitClose($classId, $closed_date){
    //Kiểm tra học viên còn học trong lớp từ hôm nay đến cuối lớp
    global $timedate;
    $reve = get_total_revenue('','',$closed_date,'',$classId);
    if(count($reve) > 0 ){
        return json_encode(array(
            "success" => "0",
        ));
    }else{
        $closed_date_db = $timedate->to_db_date($closed_date,false);
        $GLOBALS['db']->query("UPDATE j_class SET status= 'Closed', closed_date='$closed_date_db' WHERE id='$classId'");
        //Remove Teacher ID From Date
        $closed_datetime_db =  date('Y-m-d H:i:s',strtotime("-7 hours $closed_date_db 00:00:00"));
        $GLOBALS['db']->query("UPDATE meetings SET deleted = 1 WHERE ju_class_id = '$classId' AND date_start >= '$closed_datetime_db'");

        return json_encode(array(
            "success" => "1",
        ));
    }
}

function ajaxGetWeekDay($fromDate, $toDate,$classId){
    global $timedate;
    $fromDateDb = $timedate->to_db_date($fromDate,false);
    $toDateDb = $timedate->to_db_date($toDate,false);

    $sqlGetDate = "SELECT
    DAYNAME(DATE(CONVERT_TZ(meetings.date_end, '+00:00', '+7:00'))) dayname,
    CONCAT(DATE_FORMAT(CONVERT_TZ(meetings.date_start, '+00:00', '+7:00'),
    '%H:%i'),
    ' - ',
    DATE_FORMAT(CONVERT_TZ(meetings.date_end, '+00:00', '+7:00'),
    '%H:%i')) timeslot
    FROM
    meetings
    WHERE
    meetings.ju_class_id = '$classId'
    AND DATE(CONVERT_TZ(meetings.date_end,'+00:00','+7:00')) between '$fromDateDb' and '$toDateDb'
    AND meetings.deleted = 0
    AND session_status <> 'Cancelled'
    GROUP BY dayname , timeslot" ;
    $rs = $GLOBALS['db']->query($sqlGetDate);
    $arrayWeekDate = array();
    while($row = $GLOBALS['db']->fetchByAssoc($rs)){
        $arrayWeekDate[$row['dayname']]['timeslot'][] = $row['timeslot'];
    }
    echo json_encode(array(
        "success" => "1",
        "array_date" => json_encode($arrayWeekDate)
    ));
}

function sendSMS(){
    require_once('custom/modules/C_SMS/SMS/sms.php');

    $q1 = "SELECT team_id FROM ".strtolower($_POST['ptype'])." WHERE id = '{$_POST['pid']}' AND deleted = 0";
    $team_id = $GLOBALS['db']->getOne($q1);
    $sms = new sms();
    $result = (int)$sms->send_message($_POST["num"], $_POST["sms_msg"], $_POST['ptype'], $_POST['pid'], $GLOBALS['current_user']->id, $_POST['template_id'],$_POST['date_in_content'], $team_id);
    $status  = 'RECEIVED';
    if($result <= 0)
        $status  = 'FAILED';

    return json_encode(array(
        "status"       => $status,
    ));
}

//Edit by Tung Bui
function ajaxGetTeacherBySchedule($classId, $from_date, $to_date, $day_of_week){
    global $timedate;
    $start_date     = $timedate->to_db_date($from_date,false);
    $end_date       = $timedate->to_db_date($to_date,false);
    $teacherList    = checkTeacherInClass($classId, $from_date, $to_date, $day_of_week);
    array_unshift($teacherList, array(
        "teacher_id"      => '',
        "teacher_name"    => '--Clear Teacher--',
        "contract_id"     => '',
        "contract_type"   => '',
        "require_hours"   => '',
        "total_hour"      => '',
        "contract_until"  => '',
        "contract_until_span"  => '',
        "day_off"         => '',
        "holiday"         => '',
        "priority"        => '2',
    ));
    $teacherListHtml = "";
    foreach($teacherList as $key => $value){
        $ss = new Sugar_Smarty();
        $ss->assign("TEACHER_ID",       $value["teacher_id"]);
        $ss->assign("NAME",             $value["teacher_name"]);
        $ss->assign("CONTRACT_ID",      $value["contract_id"]);
        $ss->assign("CONTRACT_TYPE",    $value["contract_type"]);
        $ss->assign("REQUIRED_HOURS",   $value["require_hours"]);
        $ss->assign("TAUGHT_HOURS",     $value["total_hour"]);
        $ss->assign("EXPIRE_DAY",       $value["contract_until"]);
        $ss->assign("EXPIRE_DAY_SPAN",  $value["contract_until_span"]);
        $ss->assign("DAY_OFF",          $value["day_off"]);
        $ss->assign("HOLIDAYS",         $value["holiday"]);
        $ss->assign("PRIORITY_LEVEL",   "schedule_teacher_priority_".$value["priority"]);

        $teacherListHtml .= $ss->fetch('custom/modules/J_Class/tpls/teacher_schedule_screen_item.tpl');
    }
    return $teacherListHtml;
}

function ajaxSaveTeacherSchedule($teacherId, $contractId, $classId, $from_date, $to_date, $day_of_week, $teaching_type, $change_reason){
    global $timedate;
    $day_of_week = explode(",",$day_of_week);

    $sessionList = getClassSession($classId, $from_date, $to_date, $day_of_week);
    //check teacher trong lop de add va remove quan he j_class_c_teacher
    $arr_session_list = array();
    foreach($sessionList as $key => $value){
        $arr_session_list[] = $value['meeting_id'];
    }

    $str_session_list =   "'".implode("','",$arr_session_list)."'";
    $sqlUpdateSession = "UPDATE meetings
    SET teacher_id = '{$teacherId}',
    teaching_type = '{$teaching_type}',
    change_teacher_reason = '{$change_reason}',
    ju_contract_id = '{$contractId}'
    WHERE id IN ({$str_session_list})";
    $sql_get_teacher = "SELECT DISTINCT teacher_id FROM ";
    $rs = $GLOBALS['db']->query($sqlUpdateSession);
    if(!$rs) return $rs;
    if(!empty($teacherId)){
        $rel_rs = $GLOBALS['db']->fetchArray("SELECT id, deleted FROM j_class_c_teachers_1_c WHERE j_class_c_teachers_1j_class_ida = '$classId' AND j_class_c_teachers_1c_teachers_idb = '$teacherId'");
        if(!empty($rel_rs)){
            $rel = reset($rel_rs);
            if($rel['deleted'] == 1)
                $GLOBALS['db']->query("UPDATE j_class_c_teachers_1_c SET deleted = 0 WHERE id = '{$rel['id']}'");
        }else{
            $date_modified = $timedate->nowDb();
            $insert_rel = "INSERT INTO j_class_c_teachers_1_c VALUES ('".create_guid()."','$date_modified',0,'$classId','$teacherId')";
            $GLOBALS['db']->query($insert_rel);
        }

        /*$class = BeanFactory::getBean('J_Class', $classId);
        $class->load_relationship('j_class_c_teachers_1');
        $class->j_class_c_teachers_1->add($teacherId); */
    }
    $rmv_tch_rel_sql = "SELECT
    id rel_id
    FROM
    j_class_c_teachers_1_c
    WHERE
    j_class_c_teachers_1j_class_ida = '$classId'
    AND deleted = 0
    AND j_class_c_teachers_1c_teachers_idb NOT IN (SELECT DISTINCT
    teacher_id
    FROM
    meetings
    WHERE
    ju_class_id = '$classId'
    AND deleted = 0
    AND session_status <> 'Cancelled')";
    $rmv_rel = $GLOBALS['db']->fetchArray($rmv_tch_rel_sql);
    if(!empty($rmv_rel)){
        $rmv_rel_arr = array();
        foreach($rmv_rel as $key => $value){
            $rmv_rel_arr[] = $value['rel_id'];
        }
        $rmv_rel_str = "'".implode("','", $rmv_rel_arr)."'";
        return $GLOBALS['db']->query("UPDATE j_class_c_teachers_1_c SET deleted = 1 WHERE id in ($rmv_rel_str)");
    }
    else {
        return true;
    }
}

function ajaxLoadClassInfo($classId){
    $classBean = BeanFactory::getBean("J_Class", $classId);
    $htmls = '';
    $short_schedule = json_decode(html_entity_decode($classBean->short_schedule));
    foreach($short_schedule as $key => $value ){
        $htmls .= '<li>';
        $htmls .= $value.': '.$key;
        $htmls .= '</li>';
    }

    $ssClass = new Sugar_Smarty();
    $ssClass->assign('MOD', return_module_language($GLOBALS['current_language'], 'J_Class'));
    $ssClass->assign("MAIN_SCHEDULE", $htmls);
    $ssClass->assign("START_DATE", $classBean->start_date);
    $ssClass->assign("END_DATE", $classBean->end_date);
    $html = $ssClass->fetch('custom/modules/J_Class/tpls/ClassInfoFieldset.tpl');

    return json_encode(array(
        "success" => "1",
        "html" => $html
    ));
}

function ajaxSaveSessionDescription($sessionId, $description){
    $sql = "UPDATE meetings
    SET description = '{$description}'
    WHERE id = '{$sessionId}'
    AND deleted <> 1
    ";
    $result = $GLOBALS['db']->query($sql);
    return json_encode(array(
        "success" => "1",
    ));
}

function cancelSession($data){
    global $timedate;

    //tranfer student - Lap Nguyen
    $class_id = $data['record'];
    if(empty($class_id))
        return json_encode(
            array(
                'success' => 0
            )
        );
    // TODO - get Quá trình học của học viên từ ngày change lịch đến cuối để add lại

    $class = BeanFactory::getBean('J_Class',$class_id);
    $team_type = getTeamType($class->team_id);
    if ($team_type == 'Junior'){
        $sql_std = "SELECT
        contact_id FROM meetings_contacts WHERE meeting_id = '{$data['session_id']}'
        AND deleted = 0 ";
        $rs_std = $GLOBALS['db']->query($sql_std);
    }
    $situationArr = array();
    //Check Situation Error
    if($data['makeup_type'] == 'this_schedule' && $team_type == 'Junior' && $data['accept_schedule_change'] != 1){
        $ss_news = get_list_lesson_by_class($class_id);
        //Make new Schedule
        $mk_start_date  = $timedate->to_db($data['date']." ".$data['start']);
        $mk_end_date    = $timedate->to_db($data['date']." ".$data['end']);
        $new_ss = array(
            'primaryid'        => $mk_end_date,
            'delivery_hour'    => (strtotime($mk_end_date) - strtotime($mk_start_date))/(3600),
            'date_start'         => $mk_start_date,
            'date_end'       => $mk_end_date,
        );
        array_push($ss_news,$new_ss);
        foreach($ss_news as $key => $ss_value){
            if($ss_value['primaryid'] == $data['session_id'])
                unset($ss_news[$key]);
        }
        $ss_news = array_values($ss_news);

        //Get situation affected
        $situationArr   = GetStudentsProcessInClass($class_id, $data['canceldate']);
        $pays_unpaid    = getUnpaidPaymentByClass($class_id);
        $count_unpaid   = 0;
        $sit_err        = array();
        $html           = '<table id="config_content" class="table-border" width="100%" cellpadding="0" cellspacing="0" style="max-height: 350px; display: inline-block; width: 100%; overflow: auto;">';
        $html           .= '<thead><tr><td class="center no-bottom-border"><b>Tên học viên</b></td>';
        $html           .= '<td class="center no-bottom-border"><b>Loại</b></td>';
        $html           .= '<td class="center no-bottom-border"><b>Số giờ trước đổi lịch</b></td>';
        $html           .= '<td class="center no-bottom-border"><b>Số giờ sau đổi lịch</b></td>';
        $html           .= '<td class="center no-bottom-border"><b style="color:red;">Số giờ dư</b></td></tr></thead><tbody>';

        foreach($situationArr as $si_id => $si_value){
            $first  = '';
            $last   = '';
            $begin_total_hour = $si_value['total_hour'];
            $till = 0;
            foreach($ss_news as $ss_id => $ss_value){
                $till += $ss_value['delivery_hour'];
                $ss_start_till = $till - $ss_value['delivery_hour'];

                if( ($si_value['total_hour']  > 0 ) &&  ($si_value['start_hour'] <= $ss_start_till)){
                    //Caculate First - Last Session
                    $si_value['total_hour'] -= $ss_value['delivery_hour'];
                    if($si_value['total_hour'] >= 0) {
                        if(empty($first))
                            $first = $ss_value['date_start'];
                        $last = $ss_value['date_end'];
                    }elseif($si_value['total_hour'] < 0 && $si_value['payment_status'] != 'Closed'){
                        $_err = array();
                        $_err['situation_id']   = $si_id;
                        $_err['delay_hour']     = $si_value['total_hour'] + $ss_value['delivery_hour'];
                        $html .= '<tr>';
                        $html .= '<td class="center">'.$si_value['student_name'].'</td>';
                        $html .= '<td class="center">'.$si_value['situa_type'];
                        if(in_array($si_value['payment_id'], $pays_unpaid)){
                            $html .= '&nbsp;<span class="textbg_redlight" >Unpaid</span></td>';
                            $count_unpaid++;
                        }else $html .= '</td>';

                        $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour,2,2).' </b> <br>'.$timedate->to_display_date($si_value['start_study']).'->'.$timedate->to_display_date($si_value['end_study']).'</td>';
                        $html .= '<td class="center" nowrap ><b>'.format_number($begin_total_hour - $_err['delay_hour'],2,2).' </b> <br>'.$timedate->to_display_date($first).'->'.$timedate->to_display_date($last).'</td>';
                        $html .= '<td class="center"><b style="color:red;">'.format_number($_err['delay_hour'],2,2).' </b></td>';
                        $html .= '</tr>';
                        $sit_err[$si_value['student_id']][] = $_err;
                        break;
                    }
                }
            }
        }

        $html .= '</tbody></table>';
        if(count($sit_err) == 0) $html ='';
    }else{
        $html = '';
        $count_unpaid = 0;
    }
    if(!empty($html)){
        return json_encode(
            array(
                'success' => 0,
                'html_situation'        => $html,
                'count_unpaid'          => $count_unpaid,
            )
        );
    }

    $thisSession = new Meeting();
    $thisSession->retrieve($data['session_id']);
    $newSession = new Meeting();
    //set value for new session
    $unset_array = array('id', 'date_entered','date_modified','modified_user_id','modified_by_name','created_by', 'created_by_name',
        'created_by_link','modified_user_link');
    foreach($thisSession as $key => $val) {
        if(!in_array($key,$unset_array)) {
            $newSession->$key = $val;
        }
    }
    $start_date = $data['date']." ".$data['start'];
    $end_date = $data['date']." ".$data['end'];
    // $newSession->name .= " [MAKE-UP]";
    $newSession->session_status = "Make-up";
    $newSession->room_id = $data['room'];
    $newSession->teacher_id = $data['teacher'];
    $newSession->date_start = $start_date;
    $newSession->date_end   = $end_date;
    $newSession->ju_contract_id = checkTeacherWorking($newSession->teacher_id, '', '', 'id');
    $newSession->teaching_type = $data['cancel_teaching_type'];
    $newSession->change_teacher_reason = $data['cancel_change_teacher_reason'];
    $newSession->save();
    //update old session
    $q10 = "UPDATE meetings SET
    session_status='Cancelled',
    cancel_by='{$data['cancel_by']}',
    cancel_reason='{$data['cancel_reason']}',
    description='{$data['cancel_reason']}',
    cancel_date='{$timedate->to_db_date($data['canceldate'],false)}',
    makeup_session_id='{$newSession->id}',
    lesson_number='',
    sso_code=''
    WHERE id='{$thisSession->id}'";
    $GLOBALS['db']->query($q10);

    //update lesson number
    $resClass = updateClassSession($class_id, $class->class_type_adult, $class->level, $class->modules);
    $q11 = "UPDATE j_class SET short_schedule='".generateSmartSchedule($class_id)."', start_date='{$resClass['start_date']}', end_date='{$resClass['end_date']}' WHERE id='$class_id'";
    $GLOBALS['db']->query($q11);

    if($data['makeup_type'] == 'this_schedule') {
        if(empty($situationArr))
            $situationArr = GetStudentsProcessInClass($class_id, $data['canceldate']);
        //GET - Danh sách các buổi học sau khi đã change. TỪ ngày change đến cuối lớp
        addStudentToNewSessions($situationArr, $class_id, $data['canceldate']);
    }else{
        $getInfoSQL = " SELECT DISTINCT situation_id, contact_id
        FROM meetings_contacts
        WHERE deleted = 0 AND meeting_id = '{$thisSession->id}'";
        $result = $GLOBALS['db']->query($getInfoSQL);
        while ($row = $GLOBALS['db']->fetchByAssoc($result)) {
            addJunToSession($row['situation_id'], $newSession->id);
        }
    }
    if ($team_type == 'Junior'){
        while($row = $GLOBALS['db']->fetchByAssoc($rs_std)){
            update_remain_last_date($row['contact_id']);
        }
    }

    return json_encode(
        array(
            'success' => 1,
            'start_date' => $timedate->to_display_date($resClass['start_date'],false),
            'end_date' => $timedate->to_display_date($resClass['end_date'],false)
        )
    );
}

function getTeacherAndRoom($date, $start, $end, $class_id) {
    global $timedate;
    $sql = "SELECT team_id FROM j_class WHERE id = '$class_id' " ;
    $center_id = $GLOBALS['db']->getOne($sql);
    $start_time = $timedate->to_db($date." ".$start);
    $end_time = $timedate->to_db($date." ".$end);
    $data = array();
    $teacher_list = getTeacherOfCenter($center_id);
    foreach($teacher_list as $key => $val) {
        if(!checkTeacherInDateime($key, $start_time, $end_time)) {
            unset($teacher_list[$key]);
            continue;
        }
        if(!checkTeacherWorking($key, $start_time, $end_time)) {
            unset($teacher_list[$key]);
            continue;
        }
        if(checkTeacherHolidays($key, array(array('date_start'=> date('Y-m-d H:i:s', strtotime('-7 hours '.$start_time)))))) {
            unset($teacher_list[$key]);
            continue;
        }
    }
    //$teacher_list = array_merge(array(''=>'--None--'),$teacher_list);

    $data['teacher_options']  = get_select_options_with_id($teacher_list,'');


    $room_list = getRoomOfCenter($center_id);
    foreach($room_list as $key => $val) {
        if(!checkRoomInDateime($key, $start_time, $end_time)) {
            unset($room_list[$key]);
        }
    }

    $room_list = array_merge(array(''=>'--None--'),$room_list);
    $data['room_options']  = get_select_options_with_id($room_list,'');
    return json_encode($data);
}

function getDataForCancelSession($data) {
    global $timedate,$current_user;
    $this_session = BeanFactory::getBean('Meetings',$data['session_id']);
    $this_session_time = substr($this_session->date_start, 11,5).' - '.substr($this_session->date_end, 11);
    //Lấy buổi trước và buổi sau
    $ssList = get_list_lesson_by_class($this_session->ju_class_id);
    $before_db  = $ssList[$this_session->lesson_number - 2]['date_start'];
    $after_db   = $ssList[$this_session->lesson_number]['date_start'];
    if(!empty($before_db)) $before  = $timedate->to_display_date_time($before_db);
    else $before = '';
    if(!empty($after_db)) $after    = $timedate->to_display_date_time($after_db);
    else $after = '';

    $next_date = getEndNextTimeSession($data['class_id'], $this_session->duration_cal);

    $data_array = array(
        'id'            => $this_session->id,
        'this_session_date' => $timedate->to_display_date($timedate->to_db_date($this_session->date_start,false),false),
        'this_session_time' => $this_session_time,
        'hour'          => $this_session->duration_cal,
        'session_date'  => date($timedate->get_date_format($current_user),strtotime($next_date)),
        'start_time'    => date($timedate->get_time_format($current_user),strtotime($next_date)),
        'end_time'      => date($timedate->get_time_format($current_user),strtotime("+".($this_session->duration_cal * 60)." minutes $next_date")),
        'dutilem'       => $this_session->duration_cal,
        'before'        => $before,
        'after'         => $after
    );
    return json_encode($data_array);
}

function deleteSession($data) {
    $thisSession = new Meeting();
    $thisSession->retrieve($data['session_id']);
    $thisSession->deleted = 1;
    $thisSession->save();
    return $thisSession->save();
}

function saveStudentSituationDescription($data) {
    //$thisStudentSituation = new J_StudentSituations();
    //$thisStudentSituation->retrieve($data[''])
    $sql = "UPDATE j_studentsituations SET description = '{$data['description']}' WHERE id = '{$data['studentsituation_id']}' ";
    return $GLOBALS['db']->query($sql);
}

// ------------------------------------ADULT--------------------------------------------------\\

function caculateFreeBalanceAdult(){
    $situa = BeanFactory::getBean('J_StudentSituations', $_POST['rfc_situation_id']);

    //Lấy số revenue (Collect và Delivery)
    $row2 = get_total_revenue($situa->student_id, "'{$situa->type}'", $_POST['rfc_from_date'], $_POST['rfc_to_date'],'',$situa->id);
    $total_hour             = 0;
    $total_session = $GLOBALS['db']->getOne("SELECT IFNULL(COUNT(meetings_contacts.id), 0) count
        FROM
        meetings_contacts
        INNER JOIN
        j_studentsituations l1 ON meetings_contacts.situation_id = l1.id
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$situa->id}')))
        AND meetings_contacts.deleted = 0");
    $delay_hour             = 0;
    $delay_session          = 0;
    for($i = 0; $i < count($row2); $i++){
        $total_hour         += $row2[$i]['total_hour_situa'];
        $delay_hour         += $row2[$i]['total_revenue_hour'];
        $delay_session      += $row2[$i]['count_session'];
    }
    return json_encode(array(
        "success"           => "1",
        "delay_hour"        => format_number($delay_hour,2,2),
        "delay_session"     => format_number($delay_session),
        "studied_hour"      => format_number($total_hour - $delay_hour,2,2),
        "studied_session"   => format_number($total_session - $delay_session),
    ));

}


function handleRemoveFromClassAdult(){
    $res        = caculateFreeBalanceAdult();
    $res_obj    = json_decode($res,true);
    $situa      = BeanFactory::getBean('J_StudentSituations', $_POST['rfc_situation_id']);
    $contract_id= $GLOBALS['db']->getOne("SELECT IFNULL(contract_id, '') contract_id FROM j_payment WHERE id = '{$situa->payment_id}'");
    $q2     = "SELECT id, start_study, end_study, tuition_hours, status FROM j_payment WHERE id='{$situa->payment_id}'";
    $rs2    = $GLOBALS['db']->query($q2);
    $row2   = $GLOBALS['db']->fetchByAssoc($rs2);

    if($res_obj['studied_session'] <= 0){

        $q1 = "UPDATE j_studentsituations SET deleted = 1 WHERE id='{$_POST['rfc_situation_id']}'";
        $GLOBALS['db']->query($q1);
        removeJunFromSession($_POST['rfc_situation_id']);

        $count_situation = $GLOBALS['db']->getOne("SELECT DISTINCT
            IFNULL(COUNT(j_studentsituations.id), 0) count
            FROM
            j_studentsituations
            INNER JOIN
            j_payment l1 ON j_studentsituations.payment_id = l1.id
            AND l1.deleted = 0
            WHERE
            (((l1.id = '{$situa->payment_id}')
            AND (j_studentsituations.type IN ('Enrolled' , 'Settle', 'Moving In'))))
            AND j_studentsituations.deleted = 0");
        if($count_situation == 0 && $row2['status'] != 'Closed'){
            $q2 = "UPDATE j_payment SET start_study = '', end_study = '' WHERE id='{$situa->payment_id}'";
            $GLOBALS['db']->query($q2);
        }

        //Delete LMS Result
        if(!empty($situa->student_id) && !empty($situa->ju_class_id)){
            $GLOBALS['db']->query("DELETE gd.* from j_gradebookdetail gd
                inner join j_gradebook g
                on g.id = gd.gradebook_id and g.deleted = 0 and g.type = 'LMS'
                and gd.j_class_id = '{$situa->ju_class_id}'
                and gd.student_id = '{$situa->student_id}'");
        }


    }elseif($res_obj['studied_session'] > 0){
        $sss = get_list_lesson_by_class($_POST['rfc_class_id'], $_POST['rfc_from_date'], $_POST['rfc_to_date']);
        for($i = 0; $i < count($sss); $i++)
            removeJunFromSession($_POST['rfc_situation_id'] , $sss[$i]['primaryid'] );

        $ses = get_list_lesson_by_situation($_POST['rfc_class_id'], $_POST['rfc_situation_id'], '', '', 'INNER');

        //Total Hour remain of situation
        $remain_hours = 0;
        foreach($ses as $key=>$value)
            $remain_hours += $value['delivery_hour'];

        $remain_amount = $remain_hours * ($situa->total_amount / $situa->total_hour);

        $first = reset($ses);
        $date_first = date('Y-m-d',strtotime("+7 hours ".$first['date_start']));

        $last = end($ses);
        $date_last = date('Y-m-d',strtotime("+7 hours ".$last['date_start']));

        $q1 = "UPDATE j_studentsituations SET start_study = '$date_first', end_study = '$date_last', total_amount=$remain_amount , total_hour=$remain_hours WHERE id='{$_POST['rfc_situation_id']}'";
        $GLOBALS['db']->query($q1);
        if(!empty($situa->payment_id)){
            $class_type = $GLOBALS['db']->getOne("SELECT class_type_adult FROM j_class WHERE id = '{$situa->ju_class_id}'");
            $situa->start_study = $GLOBALS['timedate']->to_db_date($situa->start_study,false);
            if($row2['status'] != 'Closed' && !empty($row2['start_study']) && ($row2['start_study'] == $situa->start_study) && ($date_first > $situa->start_study) && ($class_type == 'Practice')){
                $pay_start_db   = $date_first;
                $run_remain     = $row2['tuition_hours'];
                $pay_end_db     = cal_finish_date_adult($pay_start_db, $run_remain);
                $GLOBALS['db']->query("UPDATE j_payment SET start_study='$pay_start_db', end_study='$pay_end_db' WHERE id='{$row2['id']}'");
            }
        }


    }else{
        return json_encode(array(
            "success"         => "0",
        ));
    }

    //Xử lý remove quan hệ Contract - Class với học viên Corporate
    if(!empty($contract_id)){
        $rs11 = $GLOBALS['db']->query("SELECT DISTINCT
            l3.id contract_id,
            COUNT(j_studentsituations.id) _allcount
            FROM
            j_studentsituations
            INNER JOIN
            j_class l1 ON j_studentsituations.ju_class_id = l1.id
            AND l1.deleted = 0
            INNER JOIN
            j_payment l2 ON j_studentsituations.payment_id = l2.id
            AND l2.deleted = 0
            INNER JOIN
            contracts l3 ON l2.contract_id = l3.id
            AND l3.deleted = 0
            WHERE
            (((l1.id = '{$situa->ju_class_id}')
            AND (l3.id = '$contract_id')
            AND (l2.payment_type = 'Corporate')))
            AND j_studentsituations.deleted = 0");
        $row11   = $GLOBALS['db']->fetchByAssoc($rs11);
        if(($row11['_allcount']) == 0){
            $GLOBALS['db']->query("DELETE FROM contracts_j_class_1_c WHERE contracts_j_class_1contracts_ida = '$contract_id' AND contracts_j_class_1j_class_idb = '{$situa->ju_class_id}'");
        }
    }

    return json_encode(array(
        "success"         => "1",
    ));
}

function saveSyllabus() {
    if($_POST['meeting_id']){
        $sql = "UPDATE meetings SET syllabus_custom = '{$_POST['syllabus_custom']}' WHERE id = '{$_POST['meeting_id']}'";
        return $GLOBALS['db']->query($sql);
    }
}

function reloadSyllabus() {
    if($_POST['class_id']){
        $class = BeanFactory::getBean('J_Class',$_POST['class_id']);
        if($class->class_type == 'Normal Class' && !empty($class->koc_id))
            $resClass           = updateClassSession($class->id, $class->koc_id);
        return json_encode(array(
            "success"         => "1",
        ));
    }
}
function saveHomework() {
    if($_POST['meeting_id']){
        $sql = "UPDATE meetings SET homework = '{$_POST['homework']}' WHERE id = '{$_POST['meeting_id']}'";
        return $GLOBALS['db']->query($sql);
    }
}