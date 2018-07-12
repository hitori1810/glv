<?php
//Add By Lam Hai
$ss = new Sugar_Smarty();
global $mod_strings, $current_user;
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

    if(strpos($parts[$i], "l4.absent_continuously=") !== FALSE){
    $absentContinously = strstr($parts[$i], 'absent_continuously');
    $absentContinously = strpos($absentContinously,'1');
    }
}
//get user center
if(!isset($centerID)) {
    $sqlGetCenter = "SELECT id, name, parent_id, description FROM teams WHERE teams.private <> 1 AND teams.deleted <> 1";

    if(!is_admin($current_user)){
        $sqlGetCenter .= " AND teams.id IN
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
}

//get date attendance report
$showData = '';
$i = 1;
$dataReport = getDataAbsentReport($startDate, $endDate, $centerID, $classID, $absentContinously);

foreach ($dataReport as $session => $studentId) {
    foreach ($studentId as $key => $value) {
        $showData .= '  <tr>
        <td>'. $i ++ .'</td>
        <td>'. $studentId[$key]['full_student_name'] .'</td>
        <td>'. $studentId[$key]['class_code'] .'</td>
        <td>'. $studentId[$key]['date_start'] .'</td>
        <td>'. $studentId[$key]['absent_reason'] .'</td>
        </tr>';
    }
}

//display to template attendance report
$ss->assign('MOD', $mod_strings);
$ss->assign('DATA', $showData);
$ss->display('custom/modules/Reports/tpls/AbsentReport.tpl');

function getDataAbsentReport($startDate, $endDate, $centerID, $classID, $absentContinuosly) {
    global $db, $timedate;

    //get class
    if(!isset($classID))
        $sqlGetClass = "";
    else
        $sqlGetClass = "cl.id IN ('". implode("','", $classID) ."') AND ";

    //get absent student in session
    $sql = "SELECT DISTINCT
    m.id meeting_id,
    m.date_start,
    ct.full_student_name,
    cl.class_code,
    att.absent_reason,
    m.lesson_number,
    att.leaving_type,
    att.student_id
    FROM
    meetings m
    JOIN j_class cl ON cl.id = m.ju_class_id
    AND cl.deleted = 0
    INNER JOIN teams t ON cl.team_id = t.id
    AND t.deleted = 0
    JOIN c_attendance att ON m.id = att.meeting_id
    AND att.deleted = 0
    INNER JOIN contacts ct ON ct.id = att.student_id
    AND ct.deleted = 0
    WHERE ". $sqlGetClass ."
    t.id IN ('". implode("','", $centerID) ."')
    AND DATE(m.date_start) >= '{$startDate}'
    AND DATE(m.date_start) <= '{$endDate}'
    AND m.meeting_type = 'Session'
    AND m.`status` <> 'Cancel'
    AND m.deleted = 0
    AND att.leaving_type = 'A'
    ORDER BY
    cl.class_code ASC,
    m.date_start ASC
    ";

    $arrAbsentStudent = array();
    $result2 = $db->query($sql);
    $data = array();
    $arrTemp = array();
    while ($row2 = $db->fetchByAssoc($result2)) {
        $arrAbsentStudent[$row2['student_id']][$row2['meeting_id']] = array(
            'date_start'=>$timedate->to_display_date($row2['date_start']),
            'absent_reason'=>$row2['absent_reason'],
            'full_student_name'=>$row2['full_student_name'],
            'class_code'=>$row2['class_code'],
            'lesson_number'=>$row2['lesson_number'],
        );
        if(!isset($arrTemp[$row2['student_id']])){
            $arrTemp[$row2['student_id']][$row2['meeting_id']][$row2['meeting_id']] =  $row2['lesson_number'];
        }
        else{
            $lastKey = key( array_slice( $arrTemp[$row2['student_id']], -1, 1, TRUE ) );
            $lastArrValue = $arrTemp[$row2['student_id']][$lastKey];
            if(in_array($row2['lesson_number'] - 1, $lastArrValue)){
                $arrTemp[$row2['student_id']][$lastKey][$row2['meeting_id']] = $row2['lesson_number'];
            }
            else{
                $arrTemp[$row2['student_id']][$row2['meeting_id']][$row2['meeting_id']] =  $row2['lesson_number'];
            }
        }

        /*$arrAbsentStudent2[$row2['class_code']][$row2['student_id']] = array(
        'date_start'=>$timedate->to_display_date($row2['date_start']),
        'absent_reason'=>$row2['absent_reason'],
        'full_student_name'=>$row2['full_student_name'],
        'meeting_id'=> array(
        'date_start'=>$timedate->to_display_date($row2['date_start']),
        'absent_reason'=>$row2['absent_reason'],
        'lesson_number'=>$row2['lesson_number'],
        ),

        );   */
    }
    if($absentContinuosly == false){
        $return  = $arrAbsentStudent;
    }
    else{
        foreach($arrTemp as $key=>$value){
            foreach($value as $key1 => $value1){
                if(count($value1) > 1){
                    foreach($value1 as $key2 => $value2){
                     $return[$key][$key2] = $arrAbsentStudent[$key][$key2];
                    }
                }
            }
        }
    }
    return $return;
}
//end
?>


