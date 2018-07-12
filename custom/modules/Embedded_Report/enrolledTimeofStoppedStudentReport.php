<?php
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
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.idIN") !== FALSE)                 $team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l3.id=") !== FALSE)                  $city_id    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l3.idIN") !== FALSE)                 $city_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l2.kind_of_course=") !== FALSE)      $koc    = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.level=") !== FALSE)               $level    = get_string_between($parts[$i]);
}

$ext_team = '';
if(!empty($team_id))
    $ext_team = "AND t.id IN ('$team_id')";

$ext_city = '';
if(!empty($city_id))
    $ext_city = "AND t.parent_id IN ('$city_id')";

$ext_koc = '';
if(!empty($koc))
    $ext_koc = "AND koc2.kind_of_course = '$koc'";

$ext_level = '';
if(!empty($level))
    $ext_level = "AND c2.level = '$level'";

$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html.= '<tr height="20"><th rowspan =2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Kind Of Course</th>';
$html .= '<th colspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">0-3 months</th>';
$html .= '<th colspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">3-6 months</th>';
$html .= '<th colspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">6-9 months</th>';
$html .= '<th colspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">9-12 months</th>';
$html .= '<th colspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">1-3 years</th>';
$html .= '<th colspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">More than 3 years</th></tr>';
$html .= '<tr height="20"><th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number Name</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Percentage</th></tr>';

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

$sql_student_list = "SELECT
part1.student_id,
part1.last_lesson,
part2.first_lesson,
ss2.ju_class_id,
koc2.kind_of_course,
c2.level,
case
when timestampdiff(month, part2.first_lesson, part1.last_lesson) < 3 then '0-3 months'
when  timestampdiff(month, part2.first_lesson, part1.last_lesson) < 6 then '3-6 months'
when  timestampdiff(month, part2.first_lesson, part1.last_lesson) < 9 then '6-9 months'
when  timestampdiff(month, part2.first_lesson, part1.last_lesson) < 12 then '9-12 months'
when  timestampdiff(month, part2.first_lesson, part1.last_lesson) < 36 then '1-3 year'
else 'over 3years' end as time_learn
FROM
(SELECT
ss.student_id, MAX(ss.end_study) last_lesson
FROM
j_studentsituations ss
INNER JOIN contacts ct ON ct.id = ss.student_id AND ss.deleted = 0
AND ct.deleted = 0
AND ss.type IN ('Enrolled' , 'Settle', 'Moving In')
INNER JOIN j_class c ON c.id = ss.ju_class_id AND c.deleted = 0
AND c.class_type = 'Normal Class'
INNER JOIN j_kindofcourse koc ON koc.id = c.koc_id
AND koc.kind_of_course NOT IN ('Cambridge' , 'Outing Trip')
INNER JOIN teams t ON t.id = ct.team_id
AND t.team_type = 'Junior'
$ext_team
$ext_city
$qTeam
GROUP BY student_id
HAVING last_lesson BETWEEN '$start_date' AND '$end_date') part1
INNER JOIN
j_studentsituations ss2 ON ss2.student_id = part1.student_id
AND ss2.end_study = part1.last_lesson
AND ss2.deleted = 0
AND ss2.type IN ('Enrolled' , 'Moving In', 'Settle')
INNER JOIN
j_class c2 ON c2.id = ss2.ju_class_id
INNER JOIN
j_kindofcourse koc2 ON koc2.id = c2.koc_id
$ext_koc
$ext_level
inner join (
select student_id, min(start_study) first_lesson
from j_studentsituations
inner join j_class on j_class.id = j_studentsituations.ju_class_id
and j_studentsituations.deleted = 0
and j_class.deleted = 0 and j_class.class_type = 'Normal Class'
and j_studentsituations.type in ('Enrolled', 'Moving In', 'Settle')
and j_class.kind_of_course not in ('Cambridge', 'Outing Trip')
group by student_id
) part2 on part2.student_id = part1.student_id
order by case koc2.kind_of_course  when  'Kindy' then 0
when 'kids' then 1
when 'kids plus' then 2
when 'Teens' then 3
when 'Kids Extra' then 4
when 'GE' then 5
when 'IELTS' then 6
when 'Premium' then 7
else 8
end , level";

$rs_student = $GLOBALS['db']->query($sql_student_list);
$arr_student = array();
$arr_time_learn = array('0-3 months',
    '3-6 months',
    '6-9 months',
    '9-12 months',
    '1-3 year',
    'over 3years');
$count = array();
$arr_koc = array('Kindy', 'Kids', 'Kids Plus');
$arr_koc_yl = array('Kindy', 'Kids', 'Kids Plus', 'Teens', 'Kids Extra');
while ($row = $GLOBALS['db']->fetchByAssoc($rs_student)){
    $arr_student[$row['student_id']]['student_id']  = $row['student_id'];
    $arr_student[$row['student_id']]['first_lesson']  = $row['first_lesson'];
    $arr_student[$row['student_id']]['last_lesson']  = $row['last_lesson'];
    $arr_student[$row['student_id']]['ju_class_id']  = $row['ju_class_id'];
    $arr_student[$row['student_id']]['kind_of_course']  = $row['kind_of_course'];
    $arr_student[$row['student_id']]['level']  = $row['level'];
    $arr_student[$row['student_id']]['time_learn']  = $row['time_learn'];
    $count[$row['kind_of_course']]['Total'][$row['time_learn']] += 1;
    if (in_array($row['kind_of_course'], $arr_koc))
        $count[$row['kind_of_course']][$row['level']][$row['time_learn']] += 1;
    if (in_array($row['kind_of_course'], $arr_koc_yl))
        $count_yl[$row['time_learn']] += 1;
}
$arr_std_list = array_keys($arr_student);

$sql_check_amount = "SELECT
cp.contacts_j_payment_1contacts_ida students_id,
SUM(IFNULL(part1.total_amount, p.remain_amount)) total_remain
FROM
contacts_j_payment_1_c cp
INNER JOIN
j_payment p ON p.id = cp.contacts_j_payment_1j_payment_idb
AND p.deleted = 0
AND cp.deleted = 0
AND (p.payment_type IN ('Delay' , 'Transef In',
'Moving In',
'Deposit',
'Cashholder',
'Merge Aims',
'Transfer From Aims')
OR (p.payment_type = 'Enrollment'
AND p.class_string LIKE '%-W'))
AND cp.contacts_j_payment_1contacts_ida IN ('".implode("','",$arr_std_list)."')
LEFT JOIN
(SELECT
ss.student_id, ss.total_amount
FROM
j_studentsituations ss
INNER JOIN j_class c ON c.id = ss.ju_class_id AND ss.deleted = 0
AND c.class_type = 'Waiting Class'
AND ss.total_amount > 0) part1 ON part1.student_id = cp.contacts_j_payment_1contacts_ida
GROUP BY students_id
HAVING total_remain < 500000";

$rs_lost_list = $GLOBALS['db']->query($sql_check_amount);
while ($row = $GLOBALS['db']->fetchByAssoc($rs_lost_list)){
    $arr_loss[$row['students_id']]['remain_amount'] = $row['total_remain'];
}

foreach($arr_student as $key=>$value){
    if(!array_key_exists($key, $arr_loss)){
        unset($arr_student[$key]);
        $count[$value['kind_of_course']]['Total'][$value['time_learn']] -= 1;
        if (in_array($value['kind_of_course'], $arr_koc))
            $count[$value['kind_of_course']][$value['level']][$value['time_learn']] -= 1;
        if (in_array($value['kind_of_course'], $arr_koc_yl))
            $count_yl[$value['time_learn']] -= 1;
    }
}

$kind_of_course = '';
$koc_html = '';
foreach($count as $kc => $vc){
    if($kc != $kind_of_course && in_array($kc, $arr_koc_yl)){
        foreach ($vc as $kl => $vl){
            /*if ($kl == 'Total')
            $koc_html = $kc;
            else $koc_html = "-".$kc." ".$kl;
            $html .= "<tr><td>".$koc_html."</td>";*/
            $kind_of_course = $kc;
            if($kl == 'Total'){
                $html .= '<tr ><td style= "font-weight:bold; text-align:left">'.$kc."</td>";
                foreach($arr_time_learn as $time){
                    $html .= '<td style= "font-weight:bold;">'.format_number($vl[$time]).'</td>
                    <td style= "font-weight:bold;">'.format_number($vl[$time]/$count_yl[$time]*100,2,2)."%</td>";
                }
                $html .= "</tr>";
            }else{
                $html .= "<tr><td>-".$kc." ".$kl."</td>";
                foreach($arr_time_learn as $time){
                    $html .= "<td>".format_number($vl[$time])."</td><td>".format_number($vl[$time]/$count_yl[$time]*100,2,2)."%</td>";
                }
                $html .= "</tr>";
            }
        }
    }else{
        if(in_array($kind_of_course,$arr_koc_yl)){
            $html .= "<tr><td style = 'color:blue !important; font-weight: bold; text-align: left;'>Total YL</td>";
            foreach($arr_time_learn as $time){
                $html .= "<td style = 'color:blue !important; font-weight: bold;'>".format_number($count_yl[$time])."</td><td></td>";
            }
            $html .= "</tr>";
        }
        $kind_of_course = $kc;
        foreach ($vc as $kl => $vl){
            $html .= '<tr ><td style= "font-weight:bold; text-align:left">'.$kc."</td>";
            foreach($arr_time_learn as $time){
                $html .= '<td style= "font-weight:bold;">'.format_number($vl[$time]).'</td>
                <td style= "font-weight:bold;"></td>';
                $count_yl[$time] += $vl[$time];
            }
            $html .= "</tr>";
        }
    }

}
$html .= "<tr><td style = 'color:blue !important; font-weight: bold; text-align: left;'>Total</td>";
foreach($arr_time_learn as $time){
    $html .= "<td style = 'color:blue !important; font-weight: bold;'>".format_number($count_yl[$time])."</td><td></td>";
}
$html .= "</tr>";

echo $html;

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

?>
