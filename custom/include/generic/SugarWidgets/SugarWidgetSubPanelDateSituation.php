<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
//
class SugarWidgetSubPanelDateSituation extends SugarWidgetSubPanelTopSelectButton
{
    //This default function is used to create the HTML for a simple button
    function display($layout_def)
    {
        global $timedate, $current_user;
        if($layout_def['focus']->class_type == "Waiting Class"){
            $html = '
            <select style="margin-left: 10px; float:left" id="waiting_class_parent_type">
            <option value = "Leads">'.translate('situation_student_type_list', '', 'Lead').' </option>
            <option value = "Contacts">'.translate('situation_student_type_list', '', 'Student').' </option>
            </select>
            <input type="button" style="margin-left: 10px;" onclick="handleAddWaiting();" value="Add To Waiting Class"/>';
        }else{
            $html ='
            <select style="float:left; margin-left: 5px;" id="parent_demo"> <option value = "Leads">'.translate('situation_student_type_list', '', 'Lead').'</option> <option value = "Contacts">'.translate('situation_student_type_list', '', 'Student').'</option>
            <input style="margin-left: 5px;" type="button" id="btn_open_popup_demo" value="'.translate('LBL_CLASS_DEMO').'" onclick="open_popup_demo($(this));"/>
            <div class="vr"></div>
            ';
            if($current_user->team_type == 'Junior'){
//                $html .= '<input type="button" name="add_out_btn" id="add_out_btn" class="button" onclick="open_popup_add_outstanding();" title="Add OutStanding" value="Add OutStanding">
//                <div class="vr"></div>';
            }

            //Điều chỉnh Status
            $q1 = "SELECT DISTINCT
            IFNULL(j_studentsituations.id, '') primaryid,
            IFNULL(j_studentsituations.type, '') type,
            IFNULL(j_studentsituations.status, '') status,
            IFNULL(j_studentsituations.student_id, '') student_id,
            IFNULL(l1.class_type, '') class_type,
            j_studentsituations.start_study start_study
            FROM
            j_studentsituations
            INNER JOIN
            j_class l1 ON j_studentsituations.ju_class_id = l1.id
            AND l1.deleted = 0 AND  j_studentsituations.ju_class_id = '{$layout_def['focus']->id}'
            WHERE j_studentsituations.deleted = 0";
            $rs1 = $GLOBALS['db']->query($q1);
            $count_delay            = 0;
            $count_outstanding      = 0;
            $count_inprocess        = 0;
            $count_finish           = 0;
            $count_notstarted       = 0;
            $count_waiting          = 0;
            $count_demo             = 0;
            $today = $GLOBALS['timedate']->nowDbDate();
            while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                if($row['class_type'] == 'Normal Class'){
                    switch ($row['type']) {
                        case "Delayed":
                        case "Stopped":
                        case "Moving Out":
                            $count_delay++;
                            break;
                        case "OutStanding":
                            $count_outstanding++;
                            break;
                        case "Demo":
                            $count_demo++;
                            break;
                        default:
                            if($row['status'] == 'In Progress') $count_inprocess++;
                            if($row['status'] == 'Not Started') $count_notstarted++;
                            if($row['status'] == 'Finished') $count_finish++;

                    }
                }elseif($row['class_type'] == 'Waiting Class'){
                    switch ($row['type']) {
                        case "Enrolled":
                            $count_waiting++;
                    }
                }
            }
            if(!empty($count_inprocess))
                $html .= "<span style='color: #04ADEC; font-size: 20px;'>".translate('situation_error_status_list', '', 'In progress').": $count_inprocess </span> &nbsp;&nbsp;";
            if(!empty($count_notstarted))
                $html .= "<span style='color: #FF8C00; font-size: 20px;'>".translate('situation_error_status_list', '', 'Not Started').": $count_notstarted </span> &nbsp;&nbsp;";
                 if(!empty($count_finish))
                $html .= "<span style='color: #468931; font-size: 20px;'>".translate('situation_error_status_list', '', 'Finished').": $count_finish</span> &nbsp;&nbsp;";
            if(!empty($count_waiting))
                $html .= "<span style='color: #FF8C00; font-size: 20px;'>".translate('type_student_situation_list', '', 'Waiting').": $count_waiting </span> &nbsp;&nbsp;";
            if(!empty($count_outstanding))
                $html .= "<span style='color: #FF0000; font-size: 20px;'>".translate('type_student_situation_list', '', 'OutStanding').": $count_outstanding </span> &nbsp;&nbsp;";
            if(!empty($count_delay))
                $html .= "<span style='color: #FF0000; font-size: 20px;'>".translate('type_student_situation_list', '', 'Delayed').": $count_delay </span> &nbsp;&nbsp;";
            if(!empty($count_demo))
                $html .= "<span style='color: #000000; font-size: 20px;'>".translate('type_student_situation_list', '', 'Demo').": $count_demo </span> &nbsp;&nbsp;";

        }
        return  $html;
    }
}
?>
