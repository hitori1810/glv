<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class ListviewLogicHookMeetings {
    //Changing color of listview rows according to Session Status - 24/07/2014 - by MTN
    function listviewcolor_Meetings(&$bean, $event, $arguments) {

        global $timedate;

        $date = $timedate->to_db_date($bean->date_start, false);
        $week_date =  date('l',strtotime($date));
        $bean->week_date = $GLOBALS['app_list_strings']['week_frame_class_list'][substr($week_date,0,3)];


        //////////--- Junior -- Add Button Remove By Quyen.Cao----/////////
        if ($_REQUEST['module']=='Leads' || $_REQUEST['module']=='Contacts'){
            $bean->custom_button = '
            <div style="display: inline-flex;">
            <input type="button" class="remove_demo" class="button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_REMOVE'].'">
            </div>';
        }
        //Count Attended and number of student
        if($bean->meeting_type == 'Demo' || $bean->meeting_type == 'Placement Test'){
            if($_REQUEST['module'] == 'Meetings'){
                $now_int    = strtotime($timedate->nowDb());
                $start_int  = strtotime($timedate->to_db($bean->date_start));
                if($now_int >= $start_int){
                    $q1 = "SELECT DISTINCT
                    IFNULL(COUNT(DISTINCT j_ptresult.id), 0) j_ptresult__count
                    FROM
                    j_ptresult
                    INNER JOIN
                    meetings_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.meetings_j_ptresult_1j_ptresult_idb
                    AND l1_1.deleted = 0
                    INNER JOIN
                    meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida
                    AND l1.deleted = 0
                    WHERE
                    (((l1.id = '{$bean->id}')
                    AND ((j_ptresult.attended LIKE 'on'
                    OR j_ptresult.attended = '1'))))
                    AND j_ptresult.deleted = 0
                    GROUP BY l1.id";
                    $count_attented = $GLOBALS['db']->getOne($q1);
                }
                if(empty($count_attented)) $count_attented = 0;


                $q2 = "SELECT DISTINCT
                IFNULL(COUNT(DISTINCT j_ptresult.id), 0) j_ptresult__count
                FROM
                j_ptresult
                INNER JOIN
                meetings_j_ptresult_1_c l1_1 ON j_ptresult.id = l1_1.meetings_j_ptresult_1j_ptresult_idb
                AND l1_1.deleted = 0
                INNER JOIN
                meetings l1 ON l1.id = l1_1.meetings_j_ptresult_1meetings_ida
                AND l1.deleted = 0
                WHERE
                (((l1.id = '{$bean->id}')
                AND ((COALESCE(LENGTH(j_ptresult.attended), 0) > 0))))
                AND j_ptresult.deleted = 0
                GROUP BY l1.id";
                $count_student = $GLOBALS['db']->getOne($q2);
                if(empty($count_student)) $count_student = 0;
                $bean->attended             = $count_attented;
                $bean->number_of_student    = $count_student;
            }
        }
        elseif($bean->meeting_type == 'Session'){
            $rowC   = count(preg_split('/\n|\r/',$bean->syllabus_custom));
            $rowH   = count(preg_split('/\n|\r/',$bean->homework));
            $bean->syllabus_custom = '<table width="100%" style="padding:0px!important;"><tbody><tr><td style="padding: 0px !important;">
            <textarea onkeyup="textAreaAdjust(this)" style="overflow:hidden" meeting_id = "'.$bean->id.'" class = "syllabus_custom" name="syllabus_custom" title="'.translate('Meeting','LBL_SYLLABUS_CUSTOM').'" rows="'.(($rowC > 2) ? $rowC : 2).'" cols="30">'.$bean->syllabus_custom.'</textarea>
            </td>';
            $bean->syllabus_custom .= "</tr></tbody></table>";
            $bean->homework = '<table width="100%" style="padding:0px!important;"><tbody><tr><td style="padding: 0px !important;">
            <textarea onkeyup="textAreaAdjust(this)" style="overflow:hidden" meeting_id = "'.$bean->id.'" class = "homework" name="homework" title="'.translate('Meeting','LBL_HOMEWORK').'" rows="'.(($rowH > 2) ? $rowH : 2).'" cols="30">'.$bean->homework.'</textarea></td>';
            $bean->homework .= "</tr></tbody></table>";
        }

        //Add button delete Session, Cover Session, Make-up Session on Subpanel
        require_once('custom/include/_helper/class_utils.php');
        if (checkDataLockDate($bean->date_end)) {
            if($bean->type_of_class == 'Junior'){
                if($bean->session_status != 'Cancelled')
                    $bean->subpanel_button = "<div style=\"display: inline-flex;\">
                    <input type=\"button\" class=\"button primary btn_cancel_session\" id=\"{$bean->id}\"
                    value=\"{$GLOBALS['mod_strings']['LBL_BTN_CANCEL']}\" onclick=\"cancelSession($(this));\"></input></div>";
                else{ //redmine#27 ko phai admin ko hien nut xoa
                    if($GLOBALS['current_user']->isAdmin())
                        $bean->subpanel_button = "<div style=\"display: inline-flex;\">
                        <input type=\"button\" class=\"button btn_deleted_session\" id=\"{$bean->id}\"
                        value=\"{$GLOBALS['app_strings']['LBL_DELETE_BUTTON']}\" onclick=\"deleteSession($(this));\"></input></div>";
                    else $bean->subpanel_button = '';
                }
            }else{
                $bean->subpanel_button = '<div style="display: inline-flex;">
                <input type="button" class="button" name="make_up_button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_MAKE_UP_BUTTON'].'">&nbsp
                <input type="button" class="button" name="cover_button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_COVER_BUTTON'].'">&nbsp
                <input type="button" class="button primary" name = "delete_button" id="'.$bean->id.'" value="'.$GLOBALS['mod_strings']['LBL_DELETE_BUTTON'].'"></div>';
            }
        }else $bean->subpanel_button = '';
        //Colorzide
        $date_start   = strtotime($timedate->to_db($bean->date_start));
        $date_end     = strtotime($timedate->to_db($bean->date_end));
        $now          = strtotime($timedate->nowDb());
        if($bean->session_status == 'Make-up') {
            $bean->session_status = "<span class='textbg_violet'>".$GLOBALS['app_list_strings']['session_status_list']['Make-up']."</span>";
        } elseif($bean->session_status == 'Cancelled') {
            $bean->session_status = "<span class='textbg_black'>".$GLOBALS['app_list_strings']['session_status_list']['Cancelled']."</span>";
        }elseif ( $now < $date_start) {
            $bean->session_status = "<span class='textbg_orange'>".$GLOBALS['app_list_strings']['session_status_list']['Not Started']."</span>";
        } elseif ($now >= $date_start && $now <= $date_end){
            $bean->session_status = "<span class='textbg_bluelight'>".$GLOBALS['app_list_strings']['session_status_list']['In progress']."In progress</span>";
        } elseif ($now > $date_end){
            $bean->session_status = "<span class='textbg_green'>".$GLOBALS['app_list_strings']['session_status_list']['Finish']."</span>";
        }
    }
}
?>