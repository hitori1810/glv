<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
global $timedate,$current_user;                          
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);  
for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id        = get_string_between($parts[$i]);  
    if(strpos($parts[$i], "l2.contact_status=") !== FALSE)      $student_status = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.contact_statusIN") !== FALSE)      $student_status = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "j_class.id=") !== FALSE)             $class_id       = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l3.date_start>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "l3.date_start<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "l3.date_start='") !== FALSE){
        $start_date = substr(get_string_between($parts[$i]),0,10);
        $end_date   = $start_date;
    }
} 
if(empty($class_id)){
    die("Please select class!");
}
if($start_date !== $end_date) {
    $start_date = date('Y-m-d', strtotime($start_date) + 3600*24);
}

$qTeam = "AND m.team_set_id IN
(SELECT 
tst.team_set_id
FROM
team_sets_teams tst
INNER JOIN
team_memberships team_memberships ON tst.team_id = team_memberships.team_id
AND team_memberships.user_id = '{$current_user->id}'
AND team_memberships.deleted = 0)";
if ($current_user->isAdmin()){           
    $qTeam = "";
}
$ext_status = '';
$ext_status_2 = '';
if(!empty($student_status)){
    $ext_status = "INNER JOIN
    contacts ct ON ct.id = atd.student_id
    AND ct.deleted = 0
    AND REPLACE(ct.contact_status, ' ', '') IN ('$student_status')";
    $ext_status_2 = " AND REPLACE(ct.contact_status, ' ', '') IN ('$student_status')";
}

$ext_date = '';
if(!empty($start_date)){
    $ext_date = "AND CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) BETWEEN '$start_date' AND '$end_date'";
}

$sql_get_lesson = "SELECT DISTINCT
m.id,
m.lesson_number,
m.week_date,
CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) lesson_date
FROM
meetings m
INNER JOIN
c_attendance atd ON atd.meeting_id = m.id AND m.deleted = 0
AND atd.deleted = 0
AND m.session_status <> 'Cancelled'
AND m.ju_class_id = '$class_id'
$ext_date
$qTeam
$ext_status        
ORDER BY m.date_start";
$arr_lesson = $GLOBALS['db']->fetchArray($sql_get_lesson);

$sql_atd = "SELECT 
ct.id student_id,
ct.full_student_name,
ct.contact_status,
ct.birthdate,
ct.guardian_name,
ct.phone_mobile,
m.id meeting_id,
CASE atd.leaving_type
WHEN 'P' THEN 'X'
WHEN 'A' THEN ''
END as leaving_type,
c.start_date,
c.end_date
FROM
j_class c
INNER JOIN
meetings m ON m.ju_class_id = c.id AND m.deleted = 0
$ext_date
$qTeam
AND c.deleted = 0
AND m.session_status <> 'Cancelled'
AND c.id = '$class_id'
INNER JOIN
c_attendance atd ON atd.meeting_id = m.id
AND atd.deleted = 0
INNER JOIN
contacts ct ON ct.id = atd.student_id
AND ct.deleted = 0
$ext_status_2
INNER JOIN
meetings_contacts mc ON mc.meeting_id = atd.meeting_id
AND mc.contact_id = atd.student_id
AND mc.deleted = 0
ORDER BY ct.last_name";

$rs = $GLOBALS['db']->query($sql_atd);
$arr_atd = array();
$arr_total = array();
while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
    $arr_atd[$row['student_id']]['student_name'] = $row['full_student_name'];
    $arr_atd[$row['student_id']]['student_status'] = $row['contact_status'];
    $arr_atd[$row['student_id']]['parent_name'] = $row['guardian_name'];
    $arr_atd[$row['student_id']]['phone_mobile'] = $row['phone_mobile'];
    $arr_atd[$row['student_id']][$row['meeting_id']]['leaving_type'] = $row['leaving_type'];
    if($row['leaving_type'] == 'X') {            
        $arr_atd[$row['student_id']]['count_atd'] += 1;
        $arr_total[$row['meeting_id']]['count_atd'] += 1;       
    }                                                   
    else {
        $arr_atd[$row['student_id']]['count_abs'] += 1;           
        $arr_total[$row['meeting_id']]['count_abs'] += 1;     
    }
}

$html = '';
$html .= 'Kí tự X: học viên có đi học. <br>';
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Name</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Status</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Parent Name</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Mobile</th>'; 
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" >Total absent lessons</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" >Total attended lessons</th>';
$html .= '<th rowspan = 3 align="center" class="reportlistViewThS1" valign="middle" >% Attended </th>';
$html_lesson ='';
$html_weekday = '<tr height="20">';
$html_lesson_date = '<tr height="20">';
foreach($arr_lesson as $key=>$value){
    $html_lesson .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap=""> Lesson '.$value['lesson_number'].'</th>';
    $html_weekday .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">'.$value['week_date'].'</th>';
    $html_lesson_date .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">'.$value['lesson_date'].'</th>';
}
$html .= $html_lesson;
$html .= '</tr>';
$html .= $html_weekday;
$html .= '</tr>';
$html .= $html_lesson_date;
$html .= '</tr>';
$seq = 0;

foreach($arr_atd as $key=>$value){
    $seq++;
    $html .= "<tr><td>".$seq."</td>
    <td class='oddListRowS1' nowrap><a href='index.php?module=Contacts&action=DetailView&record={$key}' target='_blank'>".$value['student_name']."</td>
    <td>".$value['student_status']."</td>
    <td nowrap>".$value['parent_name']."</td>
    <td>".$value['phone_mobile']."</td>
    <td>".$value['count_abs']."</td>
    <td>".$value['count_atd']."</td>
    <td><b>".number_format($value['count_atd']/($value['count_atd'] + $value['count_abs'])*100,2)."%</b></td>";
    foreach($arr_lesson as $keys=>$values){
        $html .= "<td>".$value[$values['id']]['leaving_type']."</td>";
    }
    $html .= '</tr>';
} 
$html_count_abs = "<tr style='font-weight:bold'><td colspan = 8>Total absent students</td>";
$html_count_atd = "<tr style='font-weight:bold'><td colspan = 8>Total attendees</td>";

foreach($arr_lesson as $keys=>$values){
    $html_count_abs .= "<td>".format_number($arr_total[$values['id']]['count_abs'],0,0)."</td>";
    $html_count_atd .= "<td>".format_number($arr_total[$values['id']]['count_atd'],0,0)."</td>";
}

$html .= $html_count_abs."</tr>".$html_count_atd."</tr>";
echo $html;
?>
