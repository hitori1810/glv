<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
global $current_user, $timedate;
require_once('custom/include/_helper/report_utils.php');
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);  
for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l1.idIN"))                           $team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l2.id=") !== FALSE)                  $teacher_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l2.idIN"))                           $teacher_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l3.id=") !== FALSE)                  $class_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l3.idIN"))                           $class_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "meetings.date_start>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start='") !== FALSE){
        $start_date = get_string_between($parts[$i],0,10);
        $end_date   = $start_date;
    }
}               
$ext_team_id = '';
if(empty($team_id)) $team_id = '';  else $ext_team_id = " AND t.id IN ('$team_id')";
if(empty($teacher_id)) $teacher_id = '';
if(empty($class_id)) $class_id = '';

$start_date = date('Y-m-d', strtotime($start_date) + 3600*24);
$first_date = get_first_payment_date($team_id);

$rowTeacher = get_class_list_by_teacher($teacher_id, $team_id, $class_id, $start_date, $end_date, $current_user);
if(empty($rowTeacher)) die("Khong tim thay du lieu thoa man.");
$classList = "'".implode("','", array_map(function ($entry) {
    return $entry['class_id'];
    }, $rowTeacher))."'";
$rowClass = get_class_time($classList);
$teacherList =  "'".implode("','", array_map(function ($entry) {
    return $entry['teacher_id'];
    }, $rowTeacher))."'";
$rowTaughHour =  get_taugh_hour($teacherList, $classList);

$dataClass = "'".implode("','",array_keys($rowClass))."'"; 

$row_student = get_list_student($ext_team_id, $start_date, $end_date, $dataClass); 

$stu_list = array_column($row_student,'student_id');
$str_student_list = "'".implode("','", array_map(function ($entry) {
    return $entry['student_id'];
    }, $row_student))."'";    

$arr_epp = array();

$row_return_in_month = get_retention_in_month($str_student_list, $start_date, $end_date);
$stu_id = '';                                        
foreach($row_student as $key=>$value){   
    $stu_id =    $value['student_id'];
    if(array_key_exists($stu_id, $row_return_in_month)){
        if($value['end_study'] <= $row_return_in_month[$stu_id]['payment_date'] && 
        $row_return_in_month[$stu_id]['payment_amount']>= 500000
        && $row_return_in_month[$stu_id]['team_id'] == $value['team_id']){
            $arr_epp[$stu_id]['student_id'] = $stu_id;
            $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];             
            $arr_epp[$stu_id]['notes'] = $value['description'];
            $arr_epp[$stu_id]['class_name'] = $value['class_name'];
            $arr_epp[$stu_id]['student_name'] = $value['student_name'];
            $arr_epp[$stu_id]['center_name'] = $value['center_name'];
            $arr_epp[$stu_id]['center_code'] = $value['center_code'];
            $arr_epp[$stu_id]['end_study'] = $value['end_study'];
            $arr_epp[$stu_id]['return_date'] = $row_return_in_month[$stu_id]['payment_date'];   
            $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
            $arr_epp[$stu_id]['count'] = 1;
            $arr_epp[$stu_id]['count_return'] = 1; 
            //unset($row_student[$key]); 
            $arr_search = array_keys($stu_list, $stu_id);
            if(!empty($arr_search)){
                foreach($arr_search as $k=>$v){
                    unset($row_student[$v]);
                    unset($stu_list[$v]);
                }
            }
        }      
    }   
}

//$row_student = array_values($row_student);    

$str_student_list = "'".implode("','", array_map(function ($entry) {
    return $entry['student_id'];
    }, $row_student))."'";  

$row_payment = get_list_payment($str_student_list, $start_date, $end_date);

$str_payment_list =   "'".implode("','", array_map(function ($entry) {
    return $entry['payment_id'];
    }, $row_payment))."'";   

$row_collected = get_collected($str_payment_list, $start_date, $end_date);

$row_cashin = get_cash_in($str_payment_list, $start_date, $end_date);

$row_cashout = get_cash_out($str_payment_list, $start_date, $end_date);

$row_revenue = get_revenue($team_id, $str_student_list, $str_payment_list, $first_date, $start_date, $end_date);

$row_settle = get_settle($team_id, $str_student_list, $str_payment_list, $first_date, $start_date, $end_date);

$arr_student = calculate_cf($row_payment, $row_collected, $row_cashin, $row_cashout, $row_revenue, $row_settle);

$row_retake = get_list_retake($str_student_list, $end);

$arr_retake = array_column($row_retake, 'student_id');

$arr_student_epp = array();                            
foreach($arr_student as $keys=>$values){         
    if( $values['carry_amount'] <= 500000 && $values['Enrollment'] < 1 && !in_array($keys, $arr_retake)){
        $arr_student_epp[] = $keys;                           
        /*if(in_array($keys, $stu_list)){
        unset($row_student[array_search($keys,$stu_list)]);  
        } */ 
    }                   
}

$list_stu_epp = "'".implode("','", $arr_student_epp)."'";

$arr_retention = check_retention($list_stu_epp, $end_date);

$stu_id = '';
foreach($row_student as $key=>$value){
    if($stu_id == $value['student_id']){
        unset($row_student[$key]);
        unset($stu_list[$key]);
    }else{
        $stu_id = $value['student_id'];
        if(in_array($stu_id, $arr_student_epp)){
            if(array_key_exists($stu_id, $arr_retention)){
                $arr_epp[$stu_id]['student_id'] = $stu_id;
                $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];
                $arr_epp[$stu_id]['notes'] = $value['description'];
                $arr_epp[$stu_id]['class_name'] = $value['class_name'];
                $arr_epp[$stu_id]['student_name'] = $value['student_name'];
                $arr_epp[$stu_id]['center_name'] = $value['center_name'];
                $arr_epp[$stu_id]['center_code'] = $value['center_code'];
                $arr_epp[$stu_id]['end_study'] = $value['end_study'];
                $arr_epp[$stu_id]['return_date'] = $arr_retention[$stu_id]['return_date'];    
                $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
                $arr_epp[$stu_id]['count'] = 1;
                $arr_epp[$stu_id]['count_return'] = 1; 
                unset($row_student[$key]);
                unset($stu_list[$key]); 
            }else{
                $arr_epp[$stu_id]['student_id'] = $stu_id;
                $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];
                $arr_epp[$stu_id]['notes'] = $value['description'];
                $arr_epp[$stu_id]['class_name'] = $value['class_name'];
                $arr_epp[$stu_id]['student_name'] = $value['student_name'];
                $arr_epp[$stu_id]['center_name'] = $value['center_name'];
                $arr_epp[$stu_id]['center_code'] = $value['center_code'];
                $arr_epp[$stu_id]['end_study'] = $value['end_study'];       
                $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
                $arr_epp[$stu_id]['count'] = 1;
                $arr_epp[$stu_id]['count_return'] = 0; 
            }  
        }    
    }  
}

$str_student_list = "'".implode("','", $stu_list)."'";
$arr_prepaid = check_pre_paid($str_student_list, $end_date);    
foreach($row_student as $key=>$value){
    $stu_id = $value['student_id'];
    if(array_key_exists($stu_id, $arr_prepaid) && $value['payment_date'] < $arr_prepaid[$stu_id]['payment_date']){
        $arr_epp[$stu_id]['student_id'] = $stu_id;
        $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];
        $arr_epp[$stu_id]['notes'] = $value['description'];
        $arr_epp[$stu_id]['class_name'] = $value['class_name'];
        $arr_epp[$stu_id]['student_name'] = $value['student_name'];
        $arr_epp[$stu_id]['center_name'] = $value['center_name'];
        $arr_epp[$stu_id]['center_code'] = $value['center_code'];
        $arr_epp[$stu_id]['end_study'] = $value['end_study'];
        $arr_epp[$stu_id]['return_date'] = $arr_prepaid[$stu_id]['payment_date'];   
        $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
        $arr_epp[$stu_id]['count'] = 1;
        $arr_epp[$stu_id]['count_return'] = 1;    
    }
}

$arr_epp = array_orderby($arr_epp, 'center_code', SORT_STRING, 'kind_of_course', SORT_STRING, 'class_name', SORT_STRING);

$count_class = array();
foreach($arr_epp as $key=>$value){
    $count_class[$value['left_class_id']]['count'] += 1;
    if($value['count_return'] == 1)
        $count_class[$value['left_class_id']]['count_return'] += 1;
    elseif(!empty($value['notes']))
        $count_class[$value['left_class_id']]['reason'] .= $value['student_name'].": ".$value['notes']."<br>";        
}

/*foreach ($rowTeacher as $keys=>$values){
$cur_ped = $values['current_period'];
if($class !== $values['class_id'] && array_key_exists($values['class_id'], $rowClass))
{
$class = $values['class_id'];
$dataClass[] = "(ss.ju_class_id = '{$values['class_id']}' AND ss.end_study = '{$rowClass[$class][$cur_ped]}')";
$dataClass['class_id'] = $values['class_id'];
$dataClass['current_end_date'] = $rowClass[$class][$cur_ped];
}     
} */


$html = '';
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Seq</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class name</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Current period</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Length of course</th>';   
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class</th>';
$html .= '<th colspan = 5 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Teacher</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Hours taught</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">No of students</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">No of students re-enroll</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">%</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Reason why student left</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Assigned To EC</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center Code</th></tr>';
$html .= '<tr><th width = 60 align="center" valign="middle" nowrap="">Start date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">F36 end date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">M36 end date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">L36 end date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Full Name</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Start date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">F36 end date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">M36 end date</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">L36 end date</th></tr>'; 
$seq = 1;
foreach($rowTeacher as $key=>$value){
    if($rowTaughHour[$value['teacher_id']][$value['class_id']][$value['current_period']]['taugh_hour'] > 10 && $count_class[$value['class_id']]>0){
        $class = $value['class_id'];
        $teacher = $value['teacher_id'];
        $current_period = $value['current_period'];
        switch($current_period){
            case "F36":
                $F36 = "<b>".$timedate->to_display_date($rowClass[$class]['F36'],false)."</b>";
                $M36 = $timedate->to_display_date($rowClass[$class]['M36'],false);
                $L36 = $timedate->to_display_date($rowClass[$class]['L36'],false);  
                $tcF36 = "<b>".$timedate->to_display_date($rowTaughHour[$teacher][$class]['F36']['end_date'],false)."</b>";
                $tcM36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['M36']['end_date'],false);
                $tcL36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['L36']['end_date'],false);
                break;
            case "M36":
                $F36 = $timedate->to_display_date($rowClass[$class]['F36'],false);
                $M36 = "<b>".$timedate->to_display_date($rowClass[$class]['M36'],false)."</b>";
                $L36 = $timedate->to_display_date($rowClass[$class]['L36'],false);  
                $tcF36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['F36']['end_date'],false);
                $tcM36 = "<b>".$timedate->to_display_date($rowTaughHour[$teacher][$class]['M36']['end_date'],false)."</b>";
                $tcL36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['L36']['end_date'],false);
                break;
            case "L36":
                $F36 = $timedate->to_display_date($rowClass[$class]['F36'],false);
                $M36 = $timedate->to_display_date($rowClass[$class]['M36'],false);
                $L36 = "<b>".$timedate->to_display_date($rowClass[$class]['L36'],false)."</b>";  
                $tcF36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['F36']['end_date'],false);
                $tcM36 = $timedate->to_display_date($rowTaughHour[$teacher][$class]['M36']['end_date'],false);
                $tcL36 = "<b>".$timedate->to_display_date($rowTaughHour[$teacher][$class]['L36']['end_date'],false)."</b>";
                break;

        }
        $html .= "<td nowrap=''>".$seq."</td>
        <td nowrap><a href='index.php?module=J_Class&action=DetailView&record=$class' target='_blank'>".$value['class_name']."</td>
        <td>".$value['current_period']."</td>
        <td>".$value['class_hours']."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$timedate->to_display_date($value['class_start'],false)."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$F36."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$M36."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$L36."</td>
        <td nowrap>".$value['full_teacher_name']."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$timedate->to_display_date($rowTaughHour[$teacher][$class]['start_date'],false)."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$tcF36."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$tcM36."</td>
        <td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: left;' >".$tcL36."</td>
        <td>".$rowTaughHour[$teacher][$class][$current_period]['taugh_hour']."</td>
        <td>".$count_class[$class]['count']."</td>
        <td>".$count_class[$class]['count_return']."</td>
        <td>".format_number($count_class[$class]['count_return']/$count_class[$class]['count']*100,2,2)."%</td>
        <td nowrap>".$count_class[$class]['reason']."</td>
        <td nowrap>".$value['assigned_to_ec']."</td>
        <td nowrap>".$value['center_name']."</td>
        <td>".$value['center_code']."</td></tr>" ;
        $seq++;
    }
}

echo $html;
//var_dump($rowClass);

function get_taugh_hour($teacherId, $classId){
    $sqlTeacher = "SELECT 
    m.teacher_id,
    m.ju_class_id,
    CASE
    WHEN m.till_hour - m.duration_cal < 36 THEN 'F36'
    WHEN
    m.till_hour > 36
    AND m.till_hour - m.duration_cal < 72
    AND c.hours = 108
    THEN
    'M36'
    ELSE 'L36'
    END AS period,
    MIN(CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE)) period_start,
    MAX(CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE)) period_end,
    SUM(m.delivery_hour) taugh_hour
    FROM
    meetings m
    INNER JOIN
    j_class c ON c.id = m.ju_class_id AND m.deleted = 0
    AND m.session_status <> 'Cancelled'
    AND m.ju_class_id IN ($classId)
    AND m.teacher_id IN ($teacherId)
    GROUP BY m.ju_class_id , CASE
    WHEN m.till_hour - m.duration_cal < 36 THEN 'F36'
    WHEN
    m.till_hour > 36
    AND m.till_hour - m.duration_cal < 72
    AND c.hours = 108
    THEN
    'M36'
    ELSE 'L36'
    END , m.teacher_id
    HAVING SUM(m.delivery_hour) > 10
    ORDER BY m.ju_class_id , teacher_id , period_start";
    $rs2 = $GLOBALS['db']->query($sqlTeacher);
    $data = array();
    $teacher = '';
    $class = '';
    while ($row = $GLOBALS['db']->fetchByAssoc($rs2)){  
        $data[$row['teacher_id']][$row['ju_class_id']][$row['period']]['taugh_hour'] = $row['taugh_hour'];
        $data[$row['teacher_id']][$row['ju_class_id']][$row['period']]['end_date'] = $row['period_end'];

        if($teacher !== $row['teacher_id'] || $class !== $row['class_id']){
            $teacher = $row['teacher_id'];
            $class = $row['class_id'];
            $data[$row['teacher_id']][$row['ju_class_id']]['start_date'] = $row['period_start'];
        }

    }
    return $data;
}

function get_class_time($classId){
    $sqlClass = "SELECT 
    c.id class_id,
    CASE
    WHEN
    m.till_hour >= 36
    AND m.till_hour - m.duration_cal < 36
    THEN
    'F36'
    WHEN
    m.till_hour >= 72
    AND m.till_hour - m.duration_cal < 72
    AND c.hours = 108
    THEN
    'M36'
    ELSE 'L36'
    END AS period,
    CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) date_start
    FROM
    j_class c
    INNER JOIN
    meetings m ON m.ju_class_id = c.id AND m.deleted = 0
    AND ((m.till_hour >= 36
    AND m.till_hour - m.duration_cal < 36)
    OR (m.till_hour >= 72
    AND m.till_hour - m.duration_cal < 72)
    OR (m.till_hour = 108))
    AND m.ju_class_id IN ($classId)
    ORDER BY c.id , m.date_start";
    $rs = $GLOBALS['db']->query($sqlClass);
    $data  = array();
    while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
        $data[$row['class_id']][$row['period']] = $row['date_start'];
    }
    return $data;
}   

function get_class_list_by_teacher($teacherId, $teamId, $classId, $start, $end, $user){

    $qTeam = "AND c.team_set_id IN
    (SELECT 
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$user->id}'
    AND team_memberships.deleted = 0)";
    if ($GLOBALS['current_user']->isAdmin()){           
        $qTeam = ""; 
    }
    $ext_team = '';
    if(!empty($teamId))
        $ext_team = "AND c.team_id IN ('$teamId')";
    $ext_class = '';
    if(!empty($classId))
        $ext_class = "AND c.id IN ('$classId')";
    $ext_teacher_id = '';
    if(!empty($teacherId))
        $ext_teacher_id = "AND ctc.j_class_c_teachers_1c_teachers_idb IN ('$teacherId')";

    $sql = "SELECT 
    c.id class_id,
    c.name class_name,
    c.start_date class_start,
    c.hours class_hours,
    m.till_hour,
    m.date_start,
    IFNULL(u.id, '') assign_to_id,
    IFNULL(u.full_user_name, '') assigned_to_ec,
    c.status,
    CASE
    WHEN
    m.till_hour >= 36
    AND m.till_hour - m.duration_cal < 36
    THEN
    'F36'
    WHEN
    m.till_hour >= 72
    AND m.till_hour - m.duration_cal < 72
    AND c.hours = 108
    THEN
    'M36'
    ELSE 'L36'
    END AS current_period,
    ctc.j_class_c_teachers_1c_teachers_idb teacher_id,
    tc.full_teacher_name,
    t.name center_name,
    t.code_prefix center_code
    FROM
    j_class_c_teachers_1_c ctc
    INNER JOIN
    j_class c ON c.id = ctc.j_class_c_teachers_1j_class_ida
    AND ctc.deleted = 0
    AND c.deleted = 0
    INNER JOIN
    c_teachers tc ON tc.id = ctc.j_class_c_teachers_1c_teachers_idb
    AND c.deleted = 0
    INNER JOIN
    teams t ON t.id = c.team_id
    AND t.team_type = 'Junior'
    INNER JOIN
    meetings m ON m.ju_class_id = c.id AND m.deleted = 0
    AND ((m.till_hour >= 36
    AND m.till_hour - m.duration_cal < 36)
    OR (m.till_hour >= 72
    AND m.till_hour - m.duration_cal < 72)
    OR (m.till_hour = 108))
    AND m.session_status <> 'Cancelled'
    AND CONVERT(DATE_ADD(m.date_start, interval 7 hour), DATE) BETWEEN '$start' AND '$end'
    $ext_team
    $qTeam
    $ext_teacher_id
    $ext_class
    LEFT JOIN users u on u.id = c.assigned_user_id and u.deleted = 0
    ORDER BY teacher_id , class_id";
    return $GLOBALS['db']->fetchArray($sql);
} 
?>
