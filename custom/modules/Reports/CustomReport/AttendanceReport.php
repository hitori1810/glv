<?php
    //Add By Lam Hai
    $ss = new Sugar_Smarty();
    global $timedate, $app_list_strings, $mod_strings, $current_language, $current_user;
    $timedate->get_time_format($current_user);
    $filter = str_replace("\n","", $this->where);
    $parts = explode("AND", $filter);
    //get date from WHERE clause
    for($i = 0; $i < count($parts); $i++){
        if(strpos($parts[$i], "l1.id=") !== FALSE){
            $centerID[] = get_string_between($parts[$i]);
        }

        if(strpos($parts[$i], "j_class.id=") !== FALSE){
            $classID[] = get_string_between($parts[$i]);
        }

        if(strpos($parts[$i], "l2.date_start>=") !== FALSE){
            $startDate = get_string_between($parts[$i]);
            $startDate = str_replace("00:00:00","",$startDate);
        }

        if(strpos($parts[$i], "l2.date_start<=") !== FALSE){
            $endDate = get_string_between($parts[$i]);
            $endDate = str_replace("23:59:59","",$endDate);
        }

        /*if(strpos($parts[$i], "l2.date_start") !== FALSE){
            $studyDate = get_string_between($parts[$i]);
            //$studyDate = str_replace("00:00:00","",$studyDate);
            $studyDate = $timedate->to_display_date_time($studyDate);
            $studyDate = substr($studyDate,0,10);
        } */

        /*if(strpos($parts[$i], "l4.absent_continuously=") !== FALSE){
            $absentContinously = strstr($parts[$i], 'absent_continuously');
            $absentContinously = str_replace('absent_continuously', '', $absentContinously);
            $absentContinously = str_replace(')', '', $absentContinously);
        } */
    }
    //get user center
    /*if(!isset($centerID)) {
        $sqlGetCenter = "SELECT id, name, parent_id, description FROM teams WHERE teams.private <> 1 AND teams.deleted <> 1";

        if(!is_admin($current_user)){
            $sqlGetCenter .= "AND teams.id IN
            (SELECT tst.team_set_id
            FROM team_sets_teams tst
            INNER JOIN
            team_memberships team_memberships ON tst.team_id = team_memberships.team_id
            AND team_memberships.user_id = '{$current_user->id}'
            AND team_memberships.deleted = 0)";
        }

    $result = $GLOBALS['db']->query($sqlGetCenter);
        $centerID = array();

        while($row = $GLOBALS['db']->fetchByAssoc($result)) {
            $centerID[] = $row['id'];
        }
    }*/
    //get date attendance report
    $modStrings = return_module_language($current_language, 'Reports', true);
    $showData = '';
    $i = 1;
    $dataReport = getDataAttendanceReport($startDate, $endDate, $centerID, $classID);

    foreach ($dataReport as $key => $value) {
        $showData .= '  <tr>
                            <td>'. $i ++ .'</td>
                            <td>'. $dataReport[$key]['full_student_name'] .'</td>
                            <td>'. $dataReport[$key]['class_code'] .'</td>
                            <td>'. $dataReport[$key]['date_start'] .'</td>
                            <td>'. $dataReport[$key]['absent_reason'] .'</td>
                        </tr>';
    }

    //display to template attendance report
    $ss->assign('MOD', $modStrings);
    $ss->assign('DATA', $showData);
    $ss->display('custom/modules/Reports/tpls/AttendanceReport.tpl');
    //End
    function getDataAttendanceReport($startDate, $endDate, $centerID, $classIDs) {
        global $timedate, $current_user, $sugar_config, $db;
        /*$studyDateStart = $studyDate . ' 12:00 AM';
        $studyDateStart = $timedate->to_db($studyDateStart);
        $studyDateEnd = $studyDate .' 11:59:59 PM';
        $studyDateEnd = $timedate->to_db($studyDateEnd); */
        /*$sql = "SELECT l4.id student_id, l4.full_student_name,j_class.class_code, l2.date_start, l5.absent_reason
        FROM j_class
        INNER JOIN  teams l1 ON j_class.team_id=l1.id AND l1.deleted=0
        INNER JOIN  meetings l2 ON j_class.id=l2.ju_class_id AND l2.deleted=0
        INNER JOIN meetings_contacts l3 ON l3.meeting_id = l2.id AND l3.deleted = 0
        INNER JOIN  j_studentsituations l6 ON l3.situation_id=l6.id AND l6.deleted=0
        INNER JOIN  contacts l4 ON l4.id=l3.contact_id AND l4.deleted=0
        INNER JOIN  c_attendance l5 ON l4.id=l5.student_id AND l5.meeting_id = l2.id  AND l5.deleted=0
        WHERE l1.id IN ('". implode("','", $centerID) ."')
        AND j_class.id IN ('". implode("','", $classID) ."')
        AND l2.date_start >= '{$startDate}' AND l2.date_start <= '{$endDate}'
        AND  j_class.deleted=0
        AND l2.meeting_type = 'Session' AND l2.`status`<> 'Cancel'
        AND l5.attended = 0
        ";  */

        $sql = "SELECT DISTINCT
        meetings.id,
        meetings.date_start,
        contacts.id student_id,
        contacts.full_student_name,
        j_class.class_code,
        a.absent_reason
        FROM
        meetings
        INNER JOIN j_class ON j_class.id = meetings.ju_class_id
        AND j_class.deleted = 0
        AND meetings.deleted = 0
        INNER JOIN meetings_contacts mc ON mc.meeting_id = meetings.id
        AND mc.deleted = 0
        INNER JOIN contacts ON contacts.id = mc.contact_id
        INNER JOIN c_attendance a ON contacts.id = a.student_id
        AND a.deleted = 0
        WHERE
        j_class.id IN ('". implode("','", $classIDs) ."')
        AND meetings.date_start >= '{$startDate}'
        AND meetings.date_start <= '{$endDate}'
        AND meetings.meeting_type = 'Session'
        AND meetings.`status` <> 'Cancel'
        AND a.attended = 0
        ORDER BY
        meetings.date_start asc
        ";

        $arrAbsentStudent = array();
        $result2 = $db->query($sql);
        $data = array();
        while ($row2 = $db->fetchByAssoc($result2)) {
            $arrAbsentStudent[$row2['student_id']] = array(
            'date_start'=>$row2['date_start'],
            'absent_reason'=>$row2['absent_reason'],
            'full_student_name'=>$row2['full_student_name'],
            'class_code'=>$row2['class_code'],
            'meeting_id'=> $row2['id']
            );
        }
        return $arrAbsentStudent;
    }
    //end
?>


