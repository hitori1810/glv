<?php
//JS
$js =
    <<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() {
           return $('#rowid0').find('td').eq(3).length == 1;
        },
        function() {
            $('#rowid0').find('td').eq(1).text('Last Lesson Date');
            $('#rowid0').find('td').eq(3).text('');
            });
        </script>
EOQ;

echo $js;

if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
global $current_user,$timedate;
if(($current_user->is_admin <> '1') && !(ACLController::checkAccess('C_Commission', 'Edit', false)) && !ACLController::checkAccess('J_StudentSitations', 'list', false) )
    die("You do not have permision to view this report.");
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "j_studentsituations.end_study>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_studentsituations.end_study<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_studentsituations.end_study='") !== FALSE){
        $start_date = get_string_between($parts[$i]);
        $end_date   = $start_date;
    }
}
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html.= '<tr height="20"><th colspan =2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">City</th>';
$html .= '<th colspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center</th>';
$html .= '<th rowspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Name</th>';
$html .= '<th rowspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Code</th>';
$html .= '<th rowspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Birthdate</th>';
$html .= '<th rowspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Parent Phone</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Last Lesson Date</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Notes</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Class Name</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Kind of Course</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Level</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center Code</th>';
$html.= '<tr height="20"><th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">City</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center Name</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total</th></tr>';

$qTeam = "AND ct.team_set_id IN

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

$sql_end_situ = "SELECT 
    ct.id contact_id,
    ct.full_student_name,
    ct.contact_id contact_code,
    ct.birthdate,
    ct.phone_mobile,
    t.id center_id,
    t.name center_name,
    t.team_type,
    t.code_prefix center_code,
    t2.id city_id,
    t2.name city_name,
    ct.stopped_date end_study,
    ss.description,
    c.id class_id,
    c.name class_name,
    c.kind_of_course,
    c.level,
    ct.remain_amount
FROM
    contacts ct
        INNER JOIN
    meetings_contacts mc ON mc.contact_id = ct.id AND mc.deleted = 0
    $qTeam
        INNER JOIN
    meetings m ON m.id = mc.meeting_id
        AND ct.stopped_date = CONVERT( DATE_ADD(m.date_end, INTERVAL 7 HOUR) , DATE)
        AND m.deleted = 0
        AND m.meeting_type = 'session'
        AND m.session_status <> 'cancelled'
        AND ct.remain_amount < 500000
        AND ct.stopped_date BETWEEN '$start_date' AND '$end_date'
        INNER JOIN
    j_studentsituations ss ON ss.student_id = ct.id
        AND ss.ju_class_id = m.ju_class_id
        AND ss.id = mc.situation_id
        AND ss.deleted = 0
        AND ss.type IN ('Moving In' , 'Enrolled', 'Settle')
        INNER JOIN
    j_class c ON c.id = m.ju_class_id AND c.deleted = 0
        AND c.kind_of_course NOT IN ('Outing Trip' , 'Cambridge')
        INNER JOIN
    teams t ON t.id = ct.team_id
        AND t.team_type = 'Junior'
        INNER JOIN
    teams t2 ON t2.id = t.parent_id
        AND t2.parent_id IN ('4e3de4c1-2c5e-6c00-3494-55667b495afe')
ORDER BY CASE city_name
    WHEN 'HCM' THEN 0
    WHEN 'HN' THEN 1
    WHEN 'HP' THEN 2
    WHEN 'BN' THEN 3
    WHEN 'DN' THEN 4
    WHEN 'BH' THEN 5
    WHEN 'BD' THEN 6
END , center_code , full_student_name";
$end_situ_rs = $GLOBALS['db']->fetchArray($sql_end_situ);
$row_span = array();
$student_id = '';
foreach($end_situ_rs as $key=>$value){
    if($student_id !== $value['contact_id']){
        $row_span[$value['city_id']]['total']+= 1;
        $row_span[$value['city_id']][$value['center_id']]+= 1;
        $row_span['total'] += 1;
        $student_id = $value['contact_id'];
    }
}
if(!empty($end_situ_rs)){
    $first = reset($end_situ_rs);
    $city_id = $first['city_id'];
    $center_id = $first['center_id'];
    $student_id = $first['contact_id'];
    $city_name = $first['city_name'] ;
    $total_of_city = $row_span[$first['city_id']]['total'];
    $center_name = $first['center_name'];
    $total_of_center = $row_span[$first['city_id']][$first['center_id']];
    $html .= '<tr height="20"><td rowspan = "'.$total_of_city.'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$city_name.'</b></td>';
    $html .= '<td rowspan = "'.$total_of_city.'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$total_of_city.'</td>';
    $html .= '<td rowspan = "'.$total_of_center.'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$center_name.'</td>';
    $html .= '<td rowspan = "'.$total_of_center.'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$total_of_center.'</td>';
    $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record=$student_id' target='_blank'>".$first['full_student_name']."</td>";
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['contact_code'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($first['birthdate'],false).'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['phone_mobile'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($first['end_study'],false).'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['description'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""><a href="index.php?module=J_Class&action=DetailView&record='.$first['class_id'].'" target=\'_blank\'>'.$first['class_name'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['kind_of_course'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['level'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['center_code'].'</td></tr>';


}

foreach($end_situ_rs as $key=>$row){
    if($city_id == $row['city_id']) {
        if($center_id == $row['center_id']){
            if($student_id !== $row['contact_id']){
                $student_id = $row['contact_id'];
                $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record=$student_id' target='_blank'>".$row['full_student_name'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['contact_code'].'</td>';
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['birthdate'],false).'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['phone_mobile'].'</td>';
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['end_study'],false).'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['description'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""><a href="index.php?module=J_Class&action=DetailView&record='.$row['class_id'].'" target=\'_blank\'>'.$row['class_name'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['kind_of_course'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['level'].'</td>';
                $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_code'].'</td>';
            }
        }else{
            $center_id = $row['center_id'];
            $student_id = $row['contact_id'];
            $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_name'].'</td>';
            $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id][$center_id].'</td>';
            $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record=$student_id' target='_blank'>".$row['full_student_name'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['contact_code'].'</td>';
            $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['birthdate'],false).'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['phone_mobile'].'</td>';
            $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['end_study'],false).'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['description'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""><a href="index.php?module=J_Class&action=DetailView&record='.$row['class_id'].'" target=\'_blank\'>'.$row['class_name'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['kind_of_course'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['level'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_code'].'</td>';
        }
    }else{
        $html.='</tr>';
        $city_id = $row['city_id'];
        $center_id = $row['center_id'];
        $student_id  = $row['contact_id'];
        $html .= '<tr height="20"><td rowspan = "'.$row_span[$city_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$row['city_name'].'</b></td>';
        $html .= '<td rowspan = "'.$row_span[$city_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id]['total'].'</td>';
        $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_name'].'</td>';
        $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id][$center_id].'</td>';
        $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record=$student_id' target='_blank'>".$row['full_student_name'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['contact_code'].'</td>';
        $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['birthdate'],false).'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['phone_mobile'].'</td>';
        $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$timedate->to_display_date($row['end_study'],false).'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['description'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""><a href="index.php?module=J_Class&action=DetailView&record='.$row['class_id'].'" target=\'_blank\'>'.$row['class_name'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['kind_of_course'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['level'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_code'].'</td>';
    }
    $html.='</tr>';
}
$html .= '<tr height="20"><th colspan = 4 scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>Total of Loss</b></th>';
$html .= '<th colspan = 10 scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$row_span['total'].'</b></th></tr>';

$html .= "</tbody></table>";
echo $html;



?>
