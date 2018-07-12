<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
include_once("custom/include/_helper/junior_schedule.php");
class handleSaveMeetings {
    function handleSaveMeetings(&$bean, $event, $arguments)
    {
        //Event create new Session when click Cover and Make-up 1 Session
        if($bean->date_modified != $bean->date_entered && $bean->meeting_type == "Session"){
            if(isset($_GET['session_status']) && !empty($_GET['session_status']))
                $bean->session_status = $_GET['session_status'];
            //Catch condition if checked then get POST value to save
            if($bean->teacher_id != $bean->fetched_row['teacher_id']){
                $bean->teacher_cover_id = $bean->fetched_row['teacher_id'];
            }

            if($_POST['check_all'] == "1"){
                //if check all is checked then get all session of present class with date start > now to update
                $q1 = "SELECT DISTINCT IFNULL(meetings.id,'') primaryid ,meetings.date_start meetings_date_start
                FROM meetings
                INNER JOIN c_classes l1 ON meetings.class_id=l1.id AND l1.deleted=0
                WHERE (((l1.id='{$bean->class_id}' ) AND (meetings.date_start > '{$bean->date_start}' )))
                AND meetings.deleted=0";
                $rs1 = $GLOBALS['db']->query($q1);

                while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
                    //update teacher and room when Make-up all Session
                    $sql = "UPDATE meetings SET teacher_id = '{$bean->teacher_id}', room_id='{$bean->room_id}', teacher_cover_id = '{$bean->teacher_cover_id}' WHERE id='{$row['primaryid']}'";
                    $GLOBALS['db']->query($sql);
                }
            }
        }

        //Event create new Session when click Cancel Session button
        if($_POST['duplicateSave'] == "true"){

            //get old session to get old class_id
            $old_session = BeanFactory::getBean("Meetings", $_POST['duplicateId']);
            $bean->class_id = $old_session->class_id;

            //Delete old session when cancel 1 Session
            $sql = "UPDATE meetings SET deleted = 1 WHERE id='{$_POST['duplicateId']}'";
            $GLOBALS['db']->query($sql);
        }

        $bean->duration_cal = $bean->duration_hours + $bean->duration_minutes/60;

        //Cap nhat teaching hour va delivery hour
        if(empty($bean->delivery_hour))
            $bean->delivery_hour = $bean->duration_cal;

        if(empty($bean->teaching_hour)){
            $bean->teaching_hour = $bean->duration_cal;
        }
        //if($bean->teacher_id != $bean->fetched_row['teacher_id']){
        $bean->ju_contract_id = checkTeacherWorking($bean->teacher_id, $bean->date_start, $bean->date_end, 'id');
        // }
    }


    /////////////////////////////-------------Apolo Junior------------------/////////////////////////////////////

    function  handleSavePT(&$bean, $event, $arguments){
        /////cover or makeup testing,demo
        if($bean->date_modified != $bean->date_entered && ($bean->meeting_type == "Placement Test" || $bean->meeting_type == "Demo")){
            if(isset($_GET['session_status']) && !empty($_GET['session_status']))
                $bean->session_status = $_GET['session_status'];
        }
        else{///save new meeting
            global $timedate;
            if($bean->meeting_type == "Session"||$bean->meeting_type == "Meeting"||$bean->meeting_type == "Consultant"){

            }
            elseif($bean->meeting_type == "Placement Test"){
                $bean->first_time=$bean->date_start;
                $bean->first_duration= $bean->duration_hours + $bean->duration_minutes/60;
                $bean->time_range= 10;

                saveDemoPTReturnModule($bean, $_REQUEST['return_module'], $_REQUEST['return_id']);
            }else{
                saveDemoPTReturnModule($bean, $_REQUEST['return_module'], $_REQUEST['return_id']);
            }
        }

        //2016.04.15
        if($bean->date_modified != $bean->date_entered && $bean->meeting_type == "Placement Test"){
            if($bean->meeting_type == "Placement Test" && $bean->first_time != $bean->date_start) {
                global $timedate;
                $duration =  (strtotime($bean->date_start) - strtotime($bean->first_time));
                $sql = "UPDATE j_ptresult pt
                INNER JOIN meetings_j_ptresult_1_c mpt ON mpt.deleted = 0
                AND mpt.meetings_j_ptresult_1j_ptresult_idb = pt.id AND mpt.meetings_j_ptresult_1meetings_ida = '{$bean->id}'
                SET pt.time_start = DATE_ADD(pt.time_start, INTERVAL $duration SECOND),
                pt.time_end = DATE_ADD(pt.time_end, INTERVAL $duration SECOND) ";

                $GLOBALS['db']->query($sql);
                $bean->first_time = $bean->date_start;
                //update name
                $dates = explode(' ', $bean->date_start);
                $sql_get_team_name = "SELECT short_name FROM teams WHERE id = '{$bean->team_id}' and deleted = 0";
                $team_name = ($GLOBALS['db']->getOne($sql_get_team_name)).' ';
                $bean->name = $team_name.'PT'.'-'.date('d/m/Y',strtotime($dates[0]));
            }
        }
        //end
    }
    function addCode(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save')
            if($bean->meeting_type == "Placement Test" || $bean->meeting_type == "Demo"){
                //Get Prefix
                $q1 = "SELECT
                teams.id primary_id,
                teams.code_prefix code_prefix,
                teams.team_type team_type,
                IFNULL(l2.code_prefix, '') parent_prefix
                FROM
                teams
                LEFT JOIN
                teams l2 ON teams.parent_id = l2.id
                AND l2.deleted = 0
                WHERE
                teams.id = '{$bean->team_id}'";

                $res = $GLOBALS['db']->query($q1);
                $row         = $GLOBALS['db']->fetchByAssoc($res);
                $sep           = '-';
                $prefix     = $row['code_prefix'];
                $date       = date('y');
                $code_field = 'name';
                $padding     = 2;
                $table         = $bean->table_name;
                if($bean->meeting_type == "Demo")
                    $ext = 'DEMO';
                elseif($bean->meeting_type == "Placement Test")
                    $ext = 'PT';

                $dates = date('d/m/Y',strtotime('+7 hours'.$bean->date_start));
                //Edit by Lap Nguyen
                if( empty($bean->fetched_row[$code_field]) &&  ($bean->meeting_type == "Demo" || $bean->meeting_type == "Placement Test") ) {
                    $left_code = $prefix .  $sep . $ext .  $sep  . $dates . $sep;

                    $query = "SELECT $code_field FROM $table WHERE ( $code_field <> '' AND $code_field IS NOT NULL) AND id != '{$bean->id}'  AND LEFT($code_field , ".strlen($left_code).") = '$left_code' ORDER BY RIGHT($code_field, $padding) DESC LIMIT 1";
                    $result = $GLOBALS['db']->query($query);

                    if($row = $GLOBALS['db']->fetchByAssoc($result)){
                        $last_code = $row[$code_field];
                    }else{
                        //no codes exist, generate default - PREFIX + CURRENT YEAR +  SEPARATOR + FIRST NUM
                        $last_code = $left_code . '00';
                    }

                    $num = substr($last_code, -$padding, $padding);
                    $num++;
                    $pads = $padding - strlen($num);
                    $new_code = $left_code;

                    //preform the lead padding 0
                    for($i=0; $i < $pads; $i++)
                        $new_code .= "0";
                    $new_code .= $num;

                    //write to database - Logic: Before Save
                    $bean->$code_field = $new_code;
                }
            }
    }

    function beforeDeleteSchedule(&$bean, $event, $arguments){
        if($bean->meeting_type == "Placement Test" || $bean->meeting_type == "Demo"){
            $q1 = "SELECT
            l1.id primaryid
            FROM
            j_ptresult l1
            INNER JOIN
            meetings_j_ptresult_1_c l2_1 ON l1.id = l2_1.meetings_j_ptresult_1j_ptresult_idb
            AND l2_1.deleted = 0
            INNER JOIN
            meetings ON meetings.id = l2_1.meetings_j_ptresult_1meetings_ida
            AND meetings.deleted = 0
            AND meetings.id = '{$bean->id}'";
            $rows = $GLOBALS['db']->fetchArray($q1);
            $mc_rm = array();
            foreach($rows as $key => $ss_rm){
                $mc_rm[] =   $ss_rm['primaryid'];
            }

            if(!empty($mc_rm)){
                $GLOBALS['db']->query("DELETE FROM j_ptresult WHERE id IN ('".implode("','",$mc_rm)."')");
                $GLOBALS['db']->query("DELETE FROM meetings_j_ptresult_1_c WHERE meetings_j_ptresult_1j_ptresult_idb IN ('".implode("','",$mc_rm)."')");
            }

        }
    }
}
function saveDemoPTReturnModule($meeting, $return_module, $student_id ){
    global $timedate;
    //////////////Save PT/Demo From Create PT Demo From Leads
    if($return_module == "Leads" || $return_module == 'Contacts'){
        //save PT IN Case Create PT From Lead
        $result = new J_PTResult();
        $result->type_result = $meeting->meeting_type;
        if($result->type_result == "Placement Test"){
            $first_time_mt = $meeting->date_start;
            $result->time_start = $first_time_mt;
            $result->time_end = date('Y-m-d H:i:s',strtotime("$first_time_mt +10 minutes"));
            $result->pt_order = 1;
        }
        $result->team_id = $meeting->team_id;
        $result->team_set_id = $meeting->team_set_id;
        $result->assigned_user_id = $meeting->assigned_user_id;
        $result->attended = 0;
        $result->parent = $return_module;

        $rela_student = BeanFactory::getBean($result->parent, $student_id);
        $result->name = $meeting->name.' - '.$rela_student->last_name.' '.$rela_student->first_name;

        $result->student_id = $student_id;
        $result->meetings_j_ptresult_1meetings_ida = $meeting->id;

        if($result->parent == "Leads"){
            $result->leads_j_ptresult_1leads_ida = $rela_student->id;
            if($rela_student->status != 'Converted'){
                $rela_student->status = 'PT/Demo';
                $rela_student->save();
            }
        } else
            $result->contacts_j_ptresult_1contacts_ida = $rela_student->id;

        $result->save();
    }
}

/////////////////////////////-------------End Apolo Junior------------------/////////////////////////////////////


?>
