<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicStudentSituations {

    function processRecording(&$bean, $event, $arguments){
        if ($_REQUEST['module']=='J_Class' && ($_REQUEST['action']=='DetailView' || $_REQUEST['action']=='SubPanelViewer')){
            global $timedate;
            require_once("custom/include/_helper/junior_revenue_utils.php");
            $sql_get_info = "
            SELECT
            Ifnull(l1.id, '')                          student_id,
            l1.phone_mobile                            student_phone,
            l1.birthdate                            student_birthdate,
            l1.full_student_name                       full_student_name,
            Ifnull(l7.name , '')                       student_parent_name,
            l2.full_lead_name                          full_lead_name,
            Ifnull(l2.id, '')                          lead_id,
            Ifnull(l2.guardian_name, '')               lead_parent_name,
            l2.phone_mobile                            lead_phone,
            l2.birthdate                            lead_birthdate,
            Ifnull(l6.id, '')                          class_id,
            Ifnull(l6.class_type, '')                  class_type,
            Ifnull(l4.id, '')                payment_id,
            Ifnull(l4.payment_date, '')                payment_date,
            IFNULL(SUM(l5.payment_amount), 0) paid_amount
            FROM   j_studentsituations
            LEFT JOIN contacts l1
            ON j_studentsituations.student_id = l1.id
            AND l1.deleted = 0
            LEFT JOIN leads l2
            ON j_studentsituations.lead_id = l2.id
            AND l2.deleted = 0
            LEFT JOIN
            j_payment l4 ON j_studentsituations.payment_id = l4.id
            AND l4.deleted = 0
            LEFT JOIN
            j_paymentdetail l5 ON l4.id = l5.payment_id AND l5.deleted = 0 AND (l5.status = 'Unpaid' )
            LEFT JOIN
            j_class l6 ON j_studentsituations.ju_class_id = l6.id
            AND l6.deleted = 0

            LEFT JOIN
            c_contacts_contacts_1_c l7_1 ON l1.id = l7_1.c_contacts_contacts_1contacts_idb
            AND l7_1.deleted = 0
            LEFT JOIN
            c_contacts l7 ON l7.id = l7_1.c_contacts_contacts_1c_contacts_ida
            AND l7.deleted = 0

            WHERE  ((( j_studentsituations.id = '".$bean->id."' )))
            AND j_studentsituations.deleted = 0";
            $result = $GLOBALS['db']->query($sql_get_info);
            $row = $GLOBALS['db']->fetchByAssoc($result);

            $payment_status = 'Unpaid';
            if($row['paid_amount'] == 0)
                $payment_status = 'Paid';

            $bean->custom_button = '<div style="display: inline-flex; float:right;">';
            $team_type = getTeamType($bean->team_id);
            //Generate button
            if($team_type == 'Junior'){
                //Normal Class
                if(($bean->type == 'Enrolled' || $bean->type == 'Moving In') && $row['class_type'] == 'Normal Class'){
                    if (checkDataLockDate($bean->end_study)){
                        $bean->custom_button .= '<input type="button" payment_status="'.$payment_status.'" class="button btn_delay" total_hour="'.format_number($bean->total_hour,2,2).'" total_amount="'.format_number($bean->total_amount).'" student_name="'.$row['full_student_name'].'" situa_id="'.$bean->id.'" end_study="'.$bean->end_study.'" payment_date="'.$timedate->to_display_date($row['payment_date'],false).'" student_id="'.$row['student_id'].'" start_study="'.$bean->start_study.'" value="'.translate('LBL_BTN_DELAY','J_StudentSituations').'" style="margin-right: 4px;" />';
                        $bean->custom_button .= '<input type="button" payment_status="'.$payment_status.'" class="button btn_moving_waiting" value="'.translate('LBL_MOVE_TO_CLASS_NAME','J_StudentSituations').'" onClick="window.open(\'index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&type=Moving%20Out&student_id='.$row['student_id'].'\',\'_blank\')" style="margin-right: 4px;" />';
                    }
                }
                elseif($bean->type == 'OutStanding')
                    $bean->custom_button .= '<input type="button" class="button btn_edit_outstd" student_id="'.$row['student_id'].'" student_name="'.$row['full_student_name'].'" end_study="'.$bean->end_study.'" start_study="'.$bean->start_study.'" situa_id="'.$bean->id.'" value="'.translate('LBL_EDIT','J_StudentSituations').'" style="margin-right: 4px;"/>
                    <input type="button" class="btn_delete_outstd" student_id="'.$row['student_id'].'" situa_id="'.$bean->id.'" value="Delete" style="margin-right: 4px;"/>';
                elseif($bean->type == 'Demo')
                    $bean->custom_button .= '<input type="button" class="button btn_delete_demo" situa_id="'.$bean->id.'" value="'.translate('LBL_DELETE','J_StudentSituations').'" style="margin-right: 4px;"/>';
                elseif($bean->type == 'Delayed'){
                    if($GLOBALS['current_user']->id == '1')
                        $bean->custom_button .= '<input type="button" class="button btn_undo_delay" value="'.translate('LBL_UNDO','J_StudentSituations').'" onclick="undoDelay(\''.$bean->id.'\');" style="margin-right: 4px;"/>';
                    $bean->custom_button .= '';
                } elseif($bean->type == 'Waiting Class' && $row['class_type'] == 'Waiting Class')
                    $bean->custom_button .= '<input type="button" class="button btn_del_waiting" value="'.translate('LBL_DELETE','J_StudentSituations').'" onclick="ajaxDeleteWaitingClass(\''.$bean->id.'\');" style="margin-right: 4px;"/>';

                $bean->custom_button .= '<input type="button" class="primary button btn_enroll" situa_id="'.$bean->id.'" value="'.translate('LBL_BTN_ENROLL','J_StudentSituations').'" onclick="window.open(\'index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Enrollment&student_id='.$row['student_id'].'\',\'_blank\');" style="margin-right: 4px;"/>';

                //Waiting Class
                if($bean->type == 'Enrolled' && $row['class_type'] == 'Waiting Class'){
                    $bean->custom_button = '<div style="display: inline-flex; float:right;">';
                    $bean->custom_button .= '<input type="button" payment_status="'.$payment_status.'" class="button btn_moving_waiting primary" value="'.translate('LBL_BTN_MOVING_CLASS','J_StudentSituations').'" onClick="location.href = \'index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&type=Moving%20Out&student_id='.$row['student_id'].'&from_class_id='.$row['class_id'].'&return_module=J_Class&return_action=DetailView&return_id='.$row['class_id'].'\'" style="margin-right: 4px;" />';
                    $bean->custom_button .= '<input type="button" payment_status="'.$payment_status.'" class="button btn_delay_waiting" total_hour="'.format_number($bean->total_hour,2,2).'" total_amount="'.format_number($bean->total_amount).'" student_name="'.$row['full_student_name'].'" situa_id="'.$bean->id.'" end_study="'.$bean->end_study.'" student_id="'.$row['student_id'].'" start_study="'.$bean->start_study.'" value="'.translate('LBL_BTN_DELAY','J_StudentSituations').'" style="margin-right: 4px;" />';
                }
            }else{
                if($bean->type == 'Waiting Class' && $row['class_type'] == 'Waiting Class'){
                    $bean->custom_button .= '<input type="button" class="button btn_del_waiting" value="'.translate('LBL_DELETE','J_StudentSituations').'" onclick="ajaxDeleteWaitingClass(\''.$bean->id.'\');" style="margin-right: 4px;"/>';
                }else{
                    $bean->custom_button .= '<input type="button"  class="button btn_remove_from_class" student_name="'.$row['full_student_name'].'" total_hour="'.format_number($bean->total_hour,2,2).'" end_study="'.$bean->end_study.'" start_study="'.$bean->start_study.'" situa_id="'.$bean->id.'" value="Remove From Class" style="margin-right: 4px;" />';
                    $bean->custom_button .= '<input type="button"  class="button btn_add_to_class" student_name="'.$row['full_student_name'].'" situa_id="'.$bean->id.'" value="'.translate('LBL_ADD_TO_ANOTHER_CLASS','J_StudentSituations').'" style="margin-right: 4px;" onclick="window.open(\'index.php?module=J_Payment&action=DetailView&record='.$row['payment_id'].'\')" />';
                }
            }
            if($bean->type == 'Waiting Class'){
                $bean->description = '<table width="100%" style="padding:0px!important;"><tbody><tr><td style="padding: 0px !important;"><input type="text" ss_id = "'.$bean->id.'" class = "studentsituations_description" name="description" style="position: relative;" size="50" value="'.$bean->description.'" title="Note"></td></tr></tbody></table>';
            }else{
                $bean->description = '<table width="100%" style="padding:0px!important;"><tbody><tr><td style="padding: 0px !important;"><input type="text" ss_id = "'.$bean->id.'" class = "studentsituations_description" name="description" style="position: relative;" size="20" value="'.$bean->description.'" title="Note"></td>';
                $bean->description .= "<td style='padding: 0px !important;'><select onchange=\"$(this).closest('tr').find('input.studentsituations_description').val($(this).val()+' ').trigger('change');\" class='reason_drop_out' style='width: 25px;position: relative;' title='Reasons for dropping out'>".get_select_options($GLOBALS['app_list_strings']['reason_situation_list'],'')."</select></td></tr></tbody></table>";
            }

            //fill student or lead in subpanel
            if($bean->student_type == 'Student'){
                $phone_mobile = $row['student_phone'];
                $_name = $row['full_student_name'];
                $_parent = $row['student_parent_name'];
                $_birthdate_fake = $row['student_birthdate'];
            }else{
                $phone_mobile = $row['lead_phone'];
                $_name = $row['full_lead_name'];
                $_parent = $row['lead_parent_name'];
                $_birthdate_fake = $row['lead_birthdate'];
            }
            $count_update   = 0;

            $phone_update   = '';
            if($phone_mobile != $bean->phone_situation){
                $phone_update = ", phone_situation = '$phone_mobile'";
                $bean->phone_situation = $phone_mobile;
                $count_update++;
            }

            $name_update    = '';
            if($_name != $bean->name){
                $name_update = ", name='$_name'";
                $bean->name = $_name;
                $count_update++;
            }

            $parent_update  = '';
            if($_parent != $bean->parent_name){
                $parent_update = ", parent_name='$_name'";
                $bean->parent_name = $_parent;
                $count_update++;
            }
            $bean->birthdate_fake = $timedate->to_display_date($_birthdate_fake,false);
            if($count_update > 0){
                $sql_update = "UPDATE j_studentsituations SET deleted = 0 $phone_update $name_update $parent_update WHERE id = '".$bean->id."'";
                $GLOBALS['db']->query($sql_update);
            }


            //Colorzing
            $today = $timedate->nowDbDate();
            $start_study = $timedate->to_db_date($bean->start_study,false);
            $end_study   = $timedate->to_db_date($bean->end_study,false);
            switch ($bean->type) {
                case "Delayed":
                case "Stopped":
                case "Demo":
                case "Moving Out":
                case "OutStanding":
                $cls = 'overdueTask';
                break;
            }
            switch ($bean->status) {
                case "Not Started":
                    $bean->status = "<span style='white-space: nowrap;' class='textbg_orange'>".translate('situation_error_status_list', '', 'Not Started')."</span>";
                    break;
                case "In Progress":
                    $bean->status = "<span style='white-space: nowrap;' class='textbg_bluelight'>".translate('situation_error_status_list', '', 'In Progress')."</span>";
                    break;
                case "Finished":
                    $bean->status = "<span style='white-space: nowrap;' class='textbg_green'>".translate('situation_error_status_list', '', 'Finished')."</span>";
                    break;
            }
            $bean->type = "<span class='$cls'>".translate('type_student_situation_list', '', $bean->type)."</span>";
            if($_REQUEST['action'] != 'Popup'){
                if(empty($bean->no_increasement) || $bean->no_increasement < 0){
                    $bean->name             = '<span style="font-size: 24px; padding-left:50px;">”</span>';
                    //    $bean->name             = '';
                    $bean->phone_situation  = '';
                    $bean->parent_name      = '';
                    $bean->no_increasement  = '';
                    $bean->parent_name      = '';
                    $bean->student_type     = '';
                    $bean->birthdate_fake     = '';
                }else{
                    if($bean->student_type == 'Student'){
                        $modS = 'Contacts';
                        $idS = $row['student_id'];
                    }elseif($bean->student_type == 'Lead'){
                        $modS = 'Leads';
                        $idS = $row['lead_id'];
                    }
                    $bean->name = '<a style="white-space: nowrap;" href="index.php?module='.$modS.'&action=DetailView&record='.$idS.'">'.$bean->name.'</a>';
                }
            }
        }
    }
    function beforeDelete(&$bean, $event, $arguments){
        require_once("custom/include/_helper/junior_class_utils.php");
        removeJunFromSession($bean->id);
    }
    function beforeSave(&$bean, $event, $arguments){
        global $timedate;
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            require_once("custom/include/_helper/junior_revenue_utils.php");
            require_once("custom/include/_helper/junior_class_utils.php");
            if($bean->type == 'Moving Out' && $_POST['is_ajax_call'] != '1'){
                //Make New Situation
                $student = BeanFactory::getBean('Contacts',$bean->student_id);

                $old_class = BeanFactory::getBean('J_Class', $_POST['ju_class_name']);
                $new_class = BeanFactory::getBean('J_Class', $_POST['move_to_class']);
                $related_situation = BeanFactory::getBean('J_StudentSituations', $_POST['situation_id']);

                if($old_class->class_type == 'Normal Class'){
                    $undo_arr = array();

                    //Update Related situation
                    $undo_arr['related_situation']['id']             = $related_situation->id;
                    $undo_arr['related_situation']['end_study']     = $related_situation->end_study;
                    $undo_arr['related_situation']['total_hour']     = $related_situation->total_hour;
                    $undo_arr['related_situation']['total_amount']     = $related_situation->total_amount;
                    $bean->before_hour      = $related_situation->total_hour;
                    $bean->before_amount    = $related_situation->total_amount;
                    $bean->start_studied    = $related_situation->start_study;
                    $bean->end_studied      = $related_situation->end_study;
                    $related_situation->end_study      = $bean->last_lesson_date;
                    $related_situation->total_hour     = $bean->used_hour;
                    $related_situation->total_amount   = $bean->used_amount;
                    $related_situation->save();


                    //Situation Moving Out
                    $undo_arr['moving_out']['id']     = $bean->id;
                    $bean->name = $student->last_name.' '.$student->first_name;
                    $bean->student_type     = 'Student';
                    $bean->payment_id         = $related_situation->payment_id;
                    $bean->total_hour     = $bean->moving_hour;
                    $bean->total_amount = $bean->moving_amount;
                    //lấy ngày Start và Finish của Moving Out
                    $start_check = $timedate->to_display_date($bean->last_lesson_date,false);
                    $sessions = get_list_revenue($bean->student_id, '', $start_check, '', $old_class->id, $related_situation->id);

                    $start_moving_out   = $timedate->to_display_date($sessions[1]['date_start']);
                    $end_moving_out     = $timedate->to_display_date($sessions[count($sessions)-1]['date_start']);
                    $bean->start_study  = $timedate->to_db_date($start_moving_out,false);
                    $bean->end_study    = $timedate->to_db_date($end_moving_out,false);
                    $bean->ju_class_id  = $old_class->id;
                    $bean->move_class_id = $new_class->id;
                    $bean->type         = 'Moving Out';
                    $bean->team_id      = $related_situation->team_id;
                    $bean->team_set_id  = $related_situation->team_set_id;

                    //Add học viên vào session của situation lớp mới
                    $ss_add = get_list_lesson_by_class($new_class->id, $timedate->to_display_date($bean->move_to_class_date,false), $timedate->to_display_date($bean->move_to_class_date_end,false));

                    //Situation Moving In
                    $stu_in = new J_StudentSituations();
                    $stu_in->name           = $student->last_name.' '.$student->first_name;
                    $stu_in->student_type   = 'Student';
                    $stu_in->student_id     = $student->id;
                    $stu_in->ju_class_id    = $new_class->id;
                    $stu_in->move_class_id = $old_class->id; // Move From Class ID
                    $stu_in->payment_id     = $bean->payment_id;
                    $stu_in->relate_situation_id     = $bean->id;
                    $stu_in->total_hour     = format_number($bean->remaining_hour,2,2);
                    $stu_in->total_amount   = format_number($bean->moving_amount);

                    //caculate start_hour
                    $first   = reset($ss_add);
                    $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                    $stu_in->start_hour     = $start_hour;

                    $stu_in->start_study    = $timedate->to_display_date($bean->move_to_class_date,false);
                    $stu_in->end_study      = $timedate->to_display_date($bean->move_to_class_date_end,false);
                    $stu_in->description    = $bean->description;

                    $stu_in->type           = 'Moving In';

                    $stu_in->assigned_user_id   = $bean->assigned_user_id;
                    $stu_in->team_id            = $related_situation->team_id;
                    $stu_in->team_set_id        = $related_situation->team_set_id;
                    $stu_in->save();
                    $undo_arr['moving_in']['id']     = $stu_in->id;
                    $bean->relate_situation_id         = $stu_in->id;

                    //Xoa hoc vien khoi session của situation lớp cũ
                    $ss_remove = get_list_revenue($bean->student_id, '', $start_moving_out, $end_moving_out, $old_class->id, $related_situation->id);
                    for($i = 0; $i < count($ss_remove); $i++){
                        removeJunFromSession($related_situation->id, $ss_remove[$i]['primaryid']);
                        $undo_arr['remove_session'][$i] = $ss_remove[$i]['primaryid'];
                    }

                    for($i = 0; $i < count($ss_add); $i++){
                        addJunToSession($stu_in->id , $ss_add[$i]['primaryid'] );
                        $undo_arr['add_session'][$i] = $ss_remove[$i]['primaryid'];
                    }
                    $bean->json_moving = json_encode($undo_arr);
                }
                elseif($old_class->class_type == 'Waiting Class'){
                    //Tạo situation enrolled
                    $bean->name           = $student->last_name.' '.$student->first_name;
                    $bean->student_type   = 'Student';
                    $bean->ju_class_id    = $new_class->id;
                    $bean->move_class_id  = $old_class->id; // Move From Class ID
                    $bean->payment_id     = $related_situation->payment_id;
                    $bean->relate_situation_id     = $related_situation->id;
                    $bean->total_hour     = $bean->moving_hour;
                    $bean->total_amount   = $bean->moving_amount;
                    $bean->start_study    = $bean->move_to_class_date;
                    $bean->end_study      = $bean->move_to_class_date_end;

                    $bean->type           = 'Enrolled';

                    $bean->team_id            = $related_situation->team_id;
                    $bean->team_set_id        = $related_situation->team_set_id;
                    //Add học viên vào session của situation lớp mới
                    $ss_add = get_list_lesson_by_class($new_class->id, $timedate->to_display_date($bean->move_to_class_date,false), $timedate->to_display_date($bean->move_to_class_date_end,false));

                    $first   = reset($ss_add);
                    $start_hour = format_number($first['till_hour'] - $first['delivery_hour'],2,2);
                    $bean->start_hour     = $start_hour;


                    for($i = 0; $i < count($ss_add); $i++)
                        addJunToSession($bean->id , $ss_add[$i]['primaryid'] , $student->id);
                    //Remove Relationship Old Class
                    $sqlRemoveClass = "DELETE FROM j_class_contacts_1_c WHERE j_class_contacts_1j_class_ida = '{$old_class->id}' AND j_class_contacts_1contacts_idb = '{$student->id}'";
                    $result = $GLOBALS['db']->query($sqlRemoveClass);

                    $sql_get_class="SELECT DISTINCT
                    IFNULL(j_class.class_code, '') class_code,
                    IFNULL(j_class.level, '') level,
                    IFNULL(j_class.id, '') primaryid,
                    IFNULL(l3.name, '') kind_of_course_name
                    FROM
                    j_class
                    INNER JOIN
                    j_kindofcourse l3 ON j_class.koc_id = l3.id AND l3.deleted = 0
                    WHERE
                    j_class.id = '{$new_class->id}'
                    AND j_class.deleted = 0";
                    $result_get_class = $GLOBALS['db']->query($sql_get_class);
                    $row = $GLOBALS['db']->fetchByAssoc($result_get_class);

                    $sqlUpdatePay = "UPDATE j_payment SET class_string = '{$row['class_code']}', kind_of_course_string = '{$row['kind_of_course_name']}', level_string = '{$row['level']}', start_study='{$bean->move_to_class_date}', end_study='{$bean->move_to_class_date_end}' WHERE id = '{$related_situation->payment_id}'";
                    $GLOBALS['db']->query($sqlUpdatePay);
                    //Xóa situation cũ
                    $related_situation->deleted = 1;
                    $related_situation->save();
                }
            }elseif( ($bean->type == 'Enrolled' || $bean->type == 'Settle') && $_POST['is_ajax_call'] == '1'){
                //Remove hết học viên ra khỏi buổi học
                removeJunFromSession($bean->id);

                //Add lại học viên vào lớp
                $ss = get_list_lesson_by_class($bean->ju_class_id, $timedate->to_display_date($bean->start_study,false), $timedate->to_display_date($bean->end_study,false), 'Standard');
                $hrs = 0;
                $count = 0;
                for($i = 0; $i < count($ss); $i++){
                    addJunToSession($bean->id, $ss[$i]['primaryid']);
                    $hrs += $ss[$i]['delivery_hour'];
                    $count++;
                }
                $bean->total_hour = $hrs;
                $first   = reset($ss);
                $bean->start_hour = ($first['till_hour'] - $first['delivery_hour']);

                //                //Tính lại hour Enrollment
                //                $sql = "SELECT DISTINCT
                //                IFNULL(l1.id, '') l1_id,
                //                l1.start_study start_study,
                //                l1.end_study end_study,
                //                SUM(IFNULL(IFNULL(j_studentsituations.total_amount, 0) / 1,
                //                IFNULL(j_studentsituations.total_amount, 0))) total_amount,
                //                SUM(IFNULL(j_studentsituations.total_hour, 0)) total_hour,
                //                COUNT(*) count
                //                FROM
                //                j_studentsituations
                //                INNER JOIN
                //                j_payment l1 ON j_studentsituations.payment_id = l1.id
                //                AND l1.deleted = 0
                //                WHERE
                //                (((l1.id = '{$bean->payment_id}')
                //                AND j_studentsituations.id <> '{$bean->id}'))
                //                AND j_studentsituations.deleted = 0
                //                GROUP BY l1.id
                //                ORDER BY l1_id ASC";
                //                $rs1 = $GLOBALS['db']->query($sql);
                //                $total = $GLOBALS['db']->fetchByAssoc($rs1);
                //
                //                //Update Payment
                //                $pm_hour = $total['total_hour'] + $hrs;
                //
                //                $pm_start = $bean->start_study;
                //
                //                $pm_end = $bean->end_study;
                //
                //                $GLOBALS['db']->query("UPDATE j_payment SET tuition_hours=$pm_hour, sessions=$count, total_hours=$pm_hour, start_study='$pm_start', end_study='$pm_end' WHERE id='{$bean->payment_id}'");
            }
        }
    }
}
?>