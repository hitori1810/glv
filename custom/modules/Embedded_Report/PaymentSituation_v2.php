<?php
global $current_user, $timedate;

$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                           $team_id =   get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.id=") !== FALSE)                           $class_id =   get_string_between($parts[$i]);
    if(strpos($parts[$i], "l3.id=") !== FALSE)                           $assigned_to_id =   get_string_between($parts[$i]);
    if(strpos($parts[$i], "j_studentsituations.end_study>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_studentsituations.end_study<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_studentsituations.end_study='") !== FALSE){
        $start_date = get_string_between($parts[$i]);
        $end_date   = $start_date;
    }
}
$ext_team = '';
$ext_class = '';
$ext_assigned_to = '';
if(!empty($team_id))  $ext_team = " AND ss.team_id IN ('$team_id')";
if(!empty($class_id))   $ext_class = " AND ss.ju_class_id IN ('$class_id') ";
if(!empty($assigned_to_id)) $ext_assigned_to = " AND c.assigned_user_id = '$assigned_to_id' " ;
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html.= "<tr height='20'><th scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>No</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Student Code</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Student Name</th>";
$html .= "<th  scope='col'class='reportlistViewMatrixThS1'>Parent</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Mobile</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Class Code</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Class Name</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' nowrap>Start Study</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Lass Lesson Date</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Class End Date</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Unused Hours</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Unused Amount</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Kind Of Course</th>";
$html .= "<th  scope='col' align='center' class='reportlistViewMatrixThS1' valign='middle' wrap=''>Class Assigned</th></tr>";

$qTeam = "AND ss.team_set_id IN
(SELECT
tst.team_set_id
FROM
team_sets_teams tst
INNER JOIN
team_memberships team_memberships ON tst.team_id = team_memberships.team_id
AND team_memberships.user_id = '{$current_user->id}'
AND team_memberships.deleted = 0)";
if ($GLOBALS['current_user']->isAdmin()){
    $qTeam = "";
}
$sql = "SELECT
ss.student_id,
ct.contact_id student_code,
ct.full_student_name student_name,
ct.guardian_name parent_name,
ct.phone_mobile,
c.name class_name,
c.class_code,
MIN(ss.start_study) start_study,
MAX(ss.end_study) last_lesson_date,
c.end_date class_end_date,
IFNULL(remain.remain_amount, 0) unused_amount,
    IFNULL(remain.remain_hours, 0) unused_hours,
c.kind_of_course,
u.full_user_name class_assigned
FROM
j_studentsituations ss
INNER JOIN
j_class c ON c.id = ss.ju_class_id AND c.deleted = 0
$ext_team
$qTeam
$ext_class
$ext_assigned_to
AND c.kind_of_course not in ('Cambridge','Other','Outing Trip','Premium')
AND ss.deleted = 0
AND ss.type IN ('Enrolled' , 'Settle', 'Moving In')
AND c.status <> 'Planning'
INNER JOIN teams t on t.id = c.team_id AND t.team_type = 'Junior'
INNER JOIN
contacts ct ON ct.id = ss.student_id AND c.deleted = 0
LEFT JOIN
    (SELECT
        cp.contacts_j_payment_1contacts_ida contact_id,
            SUM(p.remain_amount) remain_amount,
            SUM(p.remain_hours) remain_hours
    FROM
        contacts_j_payment_1_c cp
    INNER JOIN j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
        AND p.deleted = 0
        AND p.payment_type <> 'Enrollment'
    GROUP BY contact_id) remain ON remain.contact_id = ss.student_id
LEFT JOIN
users u ON u.id = c.assigned_user_id
AND u.deleted = 0
GROUP BY ss.student_id , ss.ju_class_id
HAVING MAX(ss.end_study) BETWEEN '$start_date' AND '$end_date'
ORDER BY kind_of_course , class_code , full_student_name;
";

$result = $GLOBALS['db']->query($sql);
$count = 0;
while($row = $GLOBALS['db']->fetchbyAssoc($result)){
    $count++;
    $html.= "<tr height='20'><td scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>$count</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['student_code']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record={$row['student_id']}' target='_blank'>{$row['student_name']}</td>";
    $html .= "<td  scope='col' class='oddListRowS1'>{$row['parent_name']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['phone_mobile']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['class_code']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['class_name']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$timedate->to_display_date($row['start_study'],false)}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$timedate->to_display_date($row['last_lesson_date'],false)}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$timedate->to_display_date($row['class_end_date'],false)}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>".format_number($row['unused_hours'],2,2)."</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>".format_number($row['unused_amount'],2,2)."</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['kind_of_course']}</td>";
    $html .= "<td  scope='col' align='center' class='oddListRowS1' valign='middle' wrap=''>{$row['class_assigned']}</td></tr>";
    $html.='</tr>';
}
$html .= "</tbody></table>";
echo $html;
?>
