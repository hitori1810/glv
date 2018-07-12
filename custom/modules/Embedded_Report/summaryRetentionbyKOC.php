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
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $team_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l3.kind_of_course=") !== FALSE)        $koc    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l3.level=") !== FALSE)               $level    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l1.idIN"))                           $team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "c_carryforward.month=") !== FALSE)   $month      = get_string_between($parts[$i]);
    if(strpos($parts[$i], "c_carryforward.year=") !== FALSE)    $year       = get_string_between($parts[$i]);
}

require_once("custom/include/_helper/report_utils.php");
$ext_team = '';
if(!empty($team_id)) $ext_team = " AND t.id IN ('$team_id')";

$start      = date('Y-m-01',strtotime("$year-$month-01"));
$start_run  = strtotime($start_cf);
$end        = date('Y-m-t',strtotime("$year-$month-01")); //Last date of filter mounth
$end_run    = strtotime($end);
$first_date = get_first_payment_date($team_id);
$first_run  = strtotime($first_date);
/*$start_retention = date('Y-m-26',strtotime("$year-$month-26"));
$end_retention =   date('Y-m-25',strtotime("$year-($month+1)-25"));   */

/*$qTeam = "AND j_studentsituations.team_set_id IN
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
} */

$row_student = get_list_student($ext_team, $start, $end, '', $koc, $level);    


$stu_list = array_column($row_student,'student_id');
$str_student_list = "'".implode("','", array_map(function ($entry) {
    return $entry['student_id'];
    }, $row_student))."'";    

$arr_epp = array();

$row_return_in_month = get_retention_in_month($str_student_list, $start, $end);
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
            $arr_epp[$stu_id]['class_assigned_to'] = $value['class_assigned_to'];
            $arr_epp[$stu_id]['student_name'] = $value['student_name'];
            $arr_epp[$stu_id]['center_name'] = $value['center_name'];
            $arr_epp[$stu_id]['center_code'] = $value['center_code'];
            $arr_epp[$stu_id]['end_study'] = $value['end_study'];
            $arr_epp[$stu_id]['return_date'] = $row_return_in_month[$stu_id]['payment_date'];   
            $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
            $arr_epp[$stu_id]['first_date'] = $value['first_date'];
            $arr_epp[$stu_id]['level'] = $value['level'];
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

$row_payment = get_list_payment($str_student_list, $start, $end);

$str_payment_list =   "'".implode("','", array_map(function ($entry) {
    return $entry['payment_id'];
    }, $row_payment))."'";   

$row_collected = get_collected($str_payment_list, $start, $end);

$row_cashin = get_cash_in($str_payment_list, $start, $end);

$row_cashout = get_cash_out($str_payment_list, $start, $end);

$row_revenue = get_revenue($team_id, $str_student_list, $str_payment_list, $first_date, $start, $end);

$row_settle = get_settle($team_id, $str_student_list, $str_payment_list, $first_date, $start, $end);

$arr_student = calculate_cf($row_payment, $row_collected, $row_cashin, $row_cashout, $row_revenue, $row_settle, $start);

$row_retake = get_list_retake($str_student_list, $end);

$arr_retake = array_column($row_retake, 'student_id');

$arr_student_epp = array();                            
foreach($arr_student as $keys=>$values){         
    if( $values['carry_amount'] <= 500000 && empty($values['Enrollment'])  && !in_array($keys, $arr_retake)){
        $arr_student_epp[] = $keys;                           
        /*if(in_array($keys, $stu_list)){
        unset($row_student[array_search($keys,$stu_list)]);  
        } */ 
    }                   
}

$list_stu_epp = "'".implode("','", $arr_student_epp)."'";

$arr_retention = check_retention($list_stu_epp, $end);

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
                $arr_epp[$stu_id]['class_assigned_to'] = $value['class_assigned_to'];
                $arr_epp[$stu_id]['student_name'] = $value['student_name'];
                $arr_epp[$stu_id]['center_name'] = $value['center_name'];
                $arr_epp[$stu_id]['center_code'] = $value['center_code'];
                $arr_epp[$stu_id]['end_study'] = $value['end_study'];
                $arr_epp[$stu_id]['return_date'] = $arr_retention[$stu_id]['return_date'];    
                $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
                $arr_epp[$stu_id]['first_date'] = $value['first_date'];
                $arr_epp[$stu_id]['level'] = $value['level'];
                $arr_epp[$stu_id]['count'] = 1;
                $arr_epp[$stu_id]['count_return'] = 1; 
                unset($row_student[$key]);
                unset($stu_list[$key]); 
            }else{
                $arr_epp[$stu_id]['student_id'] = $stu_id;
                $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];
                $arr_epp[$stu_id]['notes'] = $value['description'];
                $arr_epp[$stu_id]['class_name'] = $value['class_name'];
                $arr_epp[$stu_id]['class_assigned_to'] = $value['class_assigned_to'];
                $arr_epp[$stu_id]['student_name'] = $value['student_name'];
                $arr_epp[$stu_id]['center_name'] = $value['center_name'];
                $arr_epp[$stu_id]['center_code'] = $value['center_code'];
                $arr_epp[$stu_id]['end_study'] = $value['end_study'];       
                $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
                $arr_epp[$stu_id]['first_date'] = $value['first_date'];
                $arr_epp[$stu_id]['level'] = $value['level'];
                $arr_epp[$stu_id]['count'] = 1;
                $arr_epp[$stu_id]['count_return'] = 0; 
            }  
        }    
    }  
}

$str_student_list = "'".implode("','", $stu_list)."'";
$arr_prepaid = check_pre_paid($str_student_list, $end);    
foreach($row_student as $key=>$value){
    $stu_id = $value['student_id'];
    if(array_key_exists($stu_id, $arr_prepaid) && $value['payment_date'] < $arr_prepaid[$stu_id]['payment_date']){
        $arr_epp[$stu_id]['student_id'] = $stu_id;
        $arr_epp[$stu_id]['left_class_id'] = $value['ju_class_id'];
        $arr_epp[$stu_id]['notes'] = $value['description'];
        $arr_epp[$stu_id]['class_name'] = $value['class_name'];
        $arr_epp[$stu_id]['class_assigned_to'] = $value['class_assigned_to'];
        $arr_epp[$stu_id]['student_name'] = $value['student_name'];
        $arr_epp[$stu_id]['center_name'] = $value['center_name'];
        $arr_epp[$stu_id]['center_code'] = $value['center_code'];
        $arr_epp[$stu_id]['end_study'] = $value['end_study'];
        $arr_epp[$stu_id]['return_date'] = $arr_prepaid[$stu_id]['payment_date'];   
        $arr_epp[$stu_id]['kind_of_course'] = $value['kind_of_course'];
        $arr_epp[$stu_id]['first_date'] = $value['first_date'];
        $arr_epp[$stu_id]['level'] = $value['level'];
        $arr_epp[$stu_id]['count'] = 1;
        $arr_epp[$stu_id]['count_return'] = 1;    
    }
}

$arr_epp = array_orderby($arr_epp, 'center_code', SORT_STRING, 'kind_of_course', SORT_STRING, 'class_name', SORT_STRING);

$html = '';
//$diff = date_diff($end, $timedate->nowDbDate());                       
$count_koc = array();
foreach($arr_epp as $key=>$value){
    $count_koc[$value['center_name']][$value['kind_of_course']]['count'] += 1;
    $count_koc[$value['center_name']]['total']['count'] += 1;
    if($value['kind_of_course'] == 'Kindy' || $value['kind_of_course'] == 'Kids' || $value['kind_of_course'] == 'Kids Plus' || $value['kind_of_course'] == 'Kids Extra' || $value['kind_of_course'] == 'Teens')
        $count_koc[$value['center_name']]['total_yl']['count'] += 1;
    if($value['count_return'] == 1){
        $count_koc[$value['center_name']][$value['kind_of_course']]['count_return'] += 1;
        $count_koc[$value['center_name']]['total']['count_return'] += 1;
        if($value['kind_of_course'] == 'Kindy' || $value['kind_of_course'] == 'Kids' || $value['kind_of_course'] == 'Kids Plus' || $value['kind_of_course'] == 'Kids Extra' || $value['kind_of_course'] == 'Teens')
            $count_koc[$value['center_name']]['total_yl']['count_return'] += 1;    
    }    
}
if(round(abs(strtotime($timedate->nowDbDate())-strtotime($end))/86400,0) < 180)
    $html .= "Tháng được chọn chưa hết hạn retention, số liệu chỉ mang tính tham khảo. <br><br><br>";
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kindy</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kids</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kids Plus</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kids Extra</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Teens</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total YL</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">GE</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">BE</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total Adult</th>';
$html .= '<th colspan = 4 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total</th></tr>';

$html .= '<tr><th width = 60 align="center" valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>';  
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th>'; 
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total EPP student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total student re-enroll</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">Total loss student</th>';
$html .= '<th width = 60 align="center"  valign="middle" nowrap="">%</th></tr>';  

foreach($count_koc as $key=>$value){
    $html .=  "<td nowrap=''><strong>".$key."</strong></td>
    <td>".format_number($value['Kindy']['count'])."</td>
    <td>".format_number($value['Kindy']['count_return'])."</td>
    <td>".format_number($value['Kindy']['count']-$value['Kindy']['count_return'])."</td>
    <td>".format_number($value['Kindy']['count_return']/$value['Kindy']['count']*100,2,2)."%</td>
    <td>".format_number($value['Kids']['count'])."</td>
    <td>".format_number($value['Kids']['count_return'])."</td>
    <td>".format_number($value['Kids']['count']-$value['Kids']['count_return'])."</td>
    <td>".format_number($value['Kids']['count_return']/$value['Kids']['count']*100,2,2)."%</td>
    <td>".format_number($value['Kids Plus']['count'])."</td>
    <td>".format_number($value['Kids Plus']['count_return'])."</td>
    <td>".format_number($value['Kids Plus']['count']-$value['Kids Plus']['count_return'])."</td>
    <td>".format_number($value['Kids Plus']['count_return']/$value['Kids Plus']['count']*100,2,2)."%</td>
    <td>".format_number($value['Kids Extra']['count'])."</td>
    <td>".format_number($value['Kids Extra']['count_return'])."</td>
    <td>".format_number($value['Kids Extra']['count']-$value['Kids Extra']['count_return'])."</td>
    <td>".format_number($value['Kids Extra']['count_return']/$value['Kids Extra']['count']*100,2,2)."%</td>
    <td>".format_number($value['Teens']['count'])."</td>
    <td>".format_number($value['Teens']['count_return'])."</td>
    <td>".format_number($value['Teens']['count']-$value['Teens']['count_return'])."</td>
    <td>".format_number($value['Teens']['count_return']/$value['Teens']['count']*100,2,2)."%</td>
    <td>".format_number($value['total_yl']['count'])."</td>
    <td>".format_number($value['total_yl']['count_return'])."</td>
    <td>".format_number($value['total_yl']['count']-$value['total_yl']['count_return'])."</td>
    <td>".format_number($value['total_yl']['count_return']/$value['total_yl']['count']*100,2,2)."%</td>
    <td>".format_number($value['GE']['count'])."</td>
    <td>".format_number($value['GE']['count_return'])."</td>
    <td>".format_number($value['GE']['count']-$value['GE']['count_return'])."</td>
    <td>".format_number($value['GE']['count_return']/$value['GE']['count']*100,2,2)."%</td>
    <td>".format_number($value['BE']['count'])."</td>
    <td>".format_number($value['BE']['count_return'])."</td>
    <td>".format_number($value['BE']['count']-$value['BE']['count_return'])."</td>
    <td>".format_number($value['BE']['count_return']/$value['BE']['count']*100,2,2)."%</td>
    <td>".format_number($value['total']['count'] - $value['total_yl']['count'])."</td>
    <td>".format_number($value['total']['count_return'] - $value['total_yl']['count_return'])."</td>
    <td>".format_number($value['total']['count'] - $value['total_yl']['count'] - $value['total']['count_return'] + $value['total_yl']['count_return'])."</td>
    <td>".format_number(($value['total']['count_return'] - $value['total_yl']['count_return'])/($value['total']['count'] - $value['total_yl']['count'])*100,2,2)."%</td>
    <td>".format_number($value['total']['count'])."</td>
    <td>".format_number($value['total']['count_return'])."</td>
    <td>".format_number($value['total']['count']-$value['total']['count_return'])."</td>
    <td>".format_number($value['total']['count_return']/$value['total']['count']*100,2,2)."%</td></tr>";
}  
$html .= "</tbody></table>";                             
$html .= "<br><br>";
$html .= "<h3>Student List</h3><br><br>";
$html .= '<table width="59%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th width = 50 align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Name</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Class Name</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Assigned To EC</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Kind of Course</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Level</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">First Lesson</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Last Lesson Date</th>';
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Return Date</th>';  
$html .= '<th width = 100 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Notes</th>';  
$html .= '<th width = 50 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center Code</th></tr>';  
/*usort($arr_epp, function($a, $b) {
return strcmp($a['class_name'], $b['class_name']);
});*/ 
$seq = 0;
foreach($arr_epp as $key=>$value){
    $seq++;
    $html .= "<tr><td valign='TOP' class='oddListRowS1'>$seq</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=Contacts&action=DetailView&record={$value['student_id']}' target='_blank'>".$value['student_name']."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'><a href='index.php?module=J_Class&action=DetailView&record={$value['left_class_id']}' target='_blank'>".$value['class_name']."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>".$value['class_assigned_to']."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>".$value['kind_of_course']."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1'>".$value['level']."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: center;'>".$timedate->to_display_date($value['first_date'],false)."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: center;'>".$timedate->to_display_date($value['end_study'],false)."</td>";
    $html .= "<td valign='TOP' class='oddListRowS1' style='mso-number-format:\"Short Date\";text-align: center;'>".$timedate->to_display_date($value['return_date'],false)."</td>"; 
    if(empty($value['return_date']))
        $html .= "<td valign='TOP' class='oddListRowS1'>".$value['notes']."</td>"; 
    else $html .= "<td valign='TOP' class='oddListRowS1'></td>";             
    $html .= "<td valign='TOP' class='oddListRowS1'>".$value['center_code']."</td></tr>";              
}   
$html .= "</tbody></table>";



echo $html;

function get_list_student_teacher_rate($ext_team, $start, $end){
    $qTeam = "AND c.team_set_id IN
    (SELECT 
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$GLOBALS['current_user']->id}'
    AND team_memberships.deleted = 0)";
    if ($GLOBALS['current_user']->isAdmin()){           
        $qTeam = "";
    }
    $sql = "SELECT 
    c.id class_id,
    c.end_date,
    ct.id student_id,
    ct.full_student_name,
    c.name class_name
    FROM
    j_class c
    INNER JOIN
    j_class_contacts_1_c cc ON cc.j_class_contacts_1j_class_ida = c.id
    AND c.deleted = 0
    AND cc.deleted = 0
    $qTeam
    AND c.end_date BETWEEN '$start' AND '$end'
    INNER JOIN
    contacts ct ON ct.id = cc.j_class_contacts_1contacts_idb
    AND ct.deleted = 0
    INNER JOIN
    (SELECT 
    ju_class_id, student_id
    FROM
    j_studentsituations
    WHERE
    deleted = 0
    AND type IN ('Moving In' , 'Enrolled', 'Settle')
    GROUP BY ju_class_id , student_id) ss ON ss.student_id = ct.id
    AND ss.ju_class_id = c.id";
    return $GLOBALS['db']->fetchArray($sql);  
}


$js = 
<<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() { 
           return $('#rowid1').find('td').eq(3).length == 1;
        },
        function() {
            $('#rowid1').find('td').eq(1).html('<b style="margin:18px;">EPP</b>');   
            $('#rowid2').find('td').eq(1).html('<b style="margin:18px;">EPP</b>'); 
            });
        </script>
EOQ;
echo $js;  

?>
