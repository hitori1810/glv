<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}
require_once("custom/include/_helper/report_utils.php");
global $timedate,$current_user;                          
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);  
for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l2.id=") !== FALSE)                  $primary_team_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l2.idIN"))                           $primary_team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l1.id=") !== FALSE)                  $teacher_id    = get_string_between($parts[$i]);   
    if(strpos($parts[$i], "l1.idIN"))                           $teacher_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "meetings.date_start>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start='") !== FALSE){
        $start_date = get_string_between($parts[$i],0,10);
        $end_date   = $start_date;
    }
}

$start_date = date('Y-m-d', strtotime($start_date) + 3600*24);

$ext_team = '';
if (!empty($primary_team_id))
    $ext_team = "AND tc.team_id IN ('$primary_team_id')";

$ext_teacher_id = '';
if (!empty($teacher_id))
    $ext_teacher_id = "AND tc.id IN ('$teacher_id')";

$qTeam = "AND tc.team_set_id IN (SELECT 
tst.team_set_id
FROM
team_sets_teams tst
INNER JOIN
team_memberships team_memberships ON tst.team_id = team_memberships.team_id
AND team_memberships.user_id = '{$GLOBALS['current_user']->id}'
AND team_memberships.deleted = 0)";
$sql = "SELECT 
tc.id teacher_id,
tc.full_teacher_name,
tcc.contract_type,
tcc.working_hours_monthly,
t.id primary_id,
t.name  primary_team,
t.code_prefix primary_team_code,
t2.id teach_at_center_id,
t2.name teach_at_center_name,
t2.code_prefix teach_at_center_code,
m.meeting_type hour_type,
SUM(m.delivery_hour) total_hour
FROM
meetings m
INNER JOIN
c_teachers tc ON tc.id = m.teacher_id AND m.deleted = 0
AND m.session_status <> 'Cancelled'
AND CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) BETWEEN '$start_date' AND '$end_date'
AND m.meeting_type = 'Session'
$ext_teacher_id
$ext_team
$qTeam
INNER JOIN
c_teachers_j_teachercontract_1_c tcref ON tcref.c_teachers_j_teachercontract_1c_teachers_ida = tc.id
AND tcref.deleted = 0
INNER JOIN
j_teachercontract tcc ON tcc.id = tcref.c_teachers_j_teachercontract_1j_teachercontract_idb
AND tcc.contract_until >= '$start_date'  AND tcc.contract_date <= '$end_date' 
and convert(date_add(m.date_start, interval 7 hour), date) <= tcc.contract_until
and m.ju_contract_id = tcc.id
INNER JOIN
teams t ON t.id = tc.team_id
INNER JOIN
teams t2 ON t2.id = m.team_id
GROUP BY tc.id , tcc.id, m.team_id
#ORDER BY primary_team_code , tc.id , m.team_id
UNION
SELECT 
tc.id teacher_id,
tc.full_teacher_name,
tcc.contract_type,
tcc.working_hours_monthly,
t.id primary_id,
t.name primary_team,
t.code_prefix primary_team_code,
t2.id teach_at_center_id,
t2.name teach_at_center_name,
t2.code_prefix teach_at_center_code,
'admin' hour_type,
SUM(IFNULL(m.teaching_hour, 0)) total_hour
FROM
meetings m
INNER JOIN
c_teachers tc ON tc.id = m.teacher_id AND m.deleted = 0
AND m.session_status <> 'Cancelled'
AND CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) BETWEEN '$start_date' AND '$end_date'
$ext_teacher_id
$ext_team
$qTeam
INNER JOIN
c_teachers_j_teachercontract_1_c tcref ON tcref.c_teachers_j_teachercontract_1c_teachers_ida = tc.id
AND tcref.deleted = 0
INNER JOIN
j_teachercontract tcc ON tcc.id = tcref.c_teachers_j_teachercontract_1j_teachercontract_idb
AND tcc.contract_until >= '$start_date'
AND tcc.contract_date <= '$end_date'
and convert(date_add(m.date_start, interval 7 hour), date) <= tcc.contract_until
and m.ju_contract_id = tcc.id
INNER JOIN
teams t ON t.id = tc.team_id
INNER JOIN
teams t2 ON t2.id = m.team_id
INNER JOIN
c_timesheet ts ON ts.id = m.timesheet_id
AND ts.deleted = 0
GROUP BY tc.id , tcc.id, m.team_id
#ORDER BY primary_team_code , tc.id , m.team_id";
$rs = $GLOBALS['db']->fetchArray($sql);
/*$teach_at_center =  array_unique(array_map(function ($entry) {
return $entry['teach_at_center_code'];
}, $rs));
$teach_at_center  = array_orderby($teach_at_center,'', SORT_STRING);*/
$arr_center = array('Corp HN','BEP HN','Corp HCM','BEP HCM','BD1.THD','BH1.PVT','BN1.NSL','DN1.DD','HCM1.DBP','HCM10.LVV','HCM11.','HCM12.','HCM13.','HCM14.','HCM15.','HCM2.PNT','HCM4.TBT','HCM5.LDH','HCM6.BC','HCM7.PNT360','HCM8.PMH','HCM9.GV','HN1.PH','HN10.TG','HN11.HDT','HN12.NHT','HN13.','HN14.','HN15.','HN2.TH','HN3.HQV','HN4.LG','HN5.NVL','HN6.XT360','HN7.VQ','HN8.PH360','HN9.XD','HP1.LHP','HP2.HBT');


$data = array();  
foreach($rs as $key=>$row){
    $data[$row['teacher_id']]['full_teacher_name'] = $row['full_teacher_name'];    
    $data[$row['teacher_id']]['contract_type'] = $row['contract_type'];    
    $data[$row['teacher_id']]['working_hours_monthly'] = $row['working_hours_monthly'];    
    $data[$row['teacher_id']]['primary_id'] = $row['primary_id'];    
    $data[$row['teacher_id']]['primary_team'] = $row['primary_team'];    
    $data[$row['teacher_id']]['primary_team_code'] = $row['primary_team_code'];         
    $data[$row['teacher_id']][$row['teach_at_center_code']] += $row['total_hour']; 
    $data[$row['teacher_id']]['total_hour'] += $row['total_hour'];
    if($row['primary_team_code'] !== $row['teach_at_center_code'])  $data[$row['teacher_id']]['count_center'] += 1;
}

$data = array_orderby($data, 'primary_team_code', SORT_STRING, 'full_teacher_name', SORT_STRING);

$html = '';
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">Seq</th>';
$html .= '<th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">Full Teacher Name</th>';
$html .= '<th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">Contract Type</th>';
$html .= '<th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">Main Center</th>';
$html .= '<th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">Hours at main center</th>';
foreach($arr_center as $key=>$value){
    $html .=   '<th  align="center" class="reportlistViewThS1" valign="middle" nowrap="">'.$value.'</th>';
}
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total Hour</th></tr>';
$html .= '<tr>';


$seq = 0;
$arr_total = array();
foreach ($data as $key=>$value){
    if($value['count_center'] >= 1 || !empty($teacher_id) || !empty($primary_team_id)){
        $code_prefix = $value['primary_team_code'];
        $html .=  "<td nowrap=''>".++$seq."</td>
        <td><a href='index.php?module=C_Teachers&action=DetailView&record=$key' target='_blank'>".$value['full_teacher_name']."</td>
        <td>".$value['contract_type']."</td>
        <td>".$value['primary_team_code']."</td>  
        <td>".format_number($value[$code_prefix],2,2)."</td>";
        $arr_total['main_center'] += $value[$code_prefix];
        foreach($arr_center as $keys=>$values){
            if($values == $code_prefix ){
                $html .= "<td></td>";
            }else{
                $html .=   "<td>".$value[$values]."</td>";
                $arr_total[$values] += $value[$values];                
            }                                           
        }
        // $html .= "<td>".format_number($value['Session']['total_hour'],2,2)."</td>";    
        $html .= "<td>".format_number($value['total_hour'],2,2)."</td></tr>";
        $arr_total['total'] += $value['total_hour'];    
    }
}

$html .= "<td colspan= 4 ><h3><span>Total</span></h3></td>
<td>".format_number($arr_total['main_center'],2,2)."</td>";
foreach($arr_center as $keys=>$values){
    $html .= "<td>".format_number($arr_total[$values],2,2)."</td>";
}
$html .= "<td>".format_number($arr_total['total'],2,2)."</td>";

echo $html;  
?>
