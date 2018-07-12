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
    if(strpos($parts[$i], "l1.id=IN") !== FALSE)                $team_id        = get_string_between($parts[$i],"('","')");  
    if(strpos($parts[$i], "l2.id=") !== FALSE)                  $teacher_id = get_string_between($parts[$i]);
    if(strpos($parts[$i], "meetings.meeting_type=") !== FALSE)                  $meeting_type = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.idIN") !== FALSE)                 $teacher_id = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "meetings.meeting_typeIN") !== FALSE)  $meeting_type = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "meetings.date_start>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "meetings.date_start='") !== FALSE){
        $start_date = substr(get_string_between($parts[$i]),0,10);
        $end_date   = $start_date;
    }
}
$arr_meeting_type = array('Demo', 'PlacementTest', "Demo','PlacementTest", "PlacementTest','Demo", '');
if(!in_array($meeting_type, $arr_meeting_type)){
    die("Only Placement Test and Demo are accepted in Meeting Type");    
}                                                                    

if($start_date !== $end_date) {
    $start_date = date('Y-m-d', strtotime($start_date) + 3600*24);
}

$ext_team = '';
if(!empty($team_id))
    $ext_team = "AND m.team_id IN ('$team_id')";

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

$ext_teacher = '';
if(!empty($teacher_id)){
    $ext_teacher = "AND m.teacher_id IN ('$teacher_id')";
}

$sql = "SELECT 
m.id meeting_id,
m.name,
m.meeting_type,
convert(date_add(m.date_start, interval 7 hour),date) meeting_date,
convert(date_add(m.date_start, interval 7 hour), time) meeting_time, 
pt.parent module_name,
pt.student_id student_id,
IFNULL(ct.full_student_name, l.full_lead_name) student_name,
IFNULL(ct.contact_id, '') student_code,
IFNULL(ct.birthdate, l.birthdate) birthdate,
IFNULL(ct.phone_mobile, l.phone_mobile) phone_mobile,
IFNULL(ct.guardian_name, l.guardian_name) parent_name,
IFNULL(ct.lead_source, l.lead_source) income_source,
CONVERT( DATE_ADD(IFNULL(IFNULL(l.date_entered, l2.date_entered),
ct.date_entered),
INTERVAL 7 HOUR) , DATE) created_date,
pt.attended,
IFNULL(CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE),
'') pt_demo_register_date,
CASE
WHEN pt.attended = 1 THEN CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE)
ELSE ''
END AS pt_demo_taker_date,
pt.ec_note,
IFNULL(u.full_user_name, '') assigned_to_ec,
CONCAT(pt.result_koc, ' ', pt.result_lvl) result,
tc.id teacher_id,
tc.full_teacher_name,
m.team_id center_id,
t.name center_name,
t.code_prefix center_code,
payment.payment_date,
ifnull(ld.max_date, '') last_lesson_date
FROM
meetings_j_ptresult_1_c mpt
INNER JOIN
meetings m ON mpt.meetings_j_ptresult_1meetings_ida = m.id
AND m.deleted = 0
AND mpt.deleted = 0
AND REPLACE(m.meeting_type, ' ', '') IN ('PlacementTest' , 'Demo')
AND CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE) BETWEEN '$start_date' AND '$end_date'
$ext_team
$ext_teacher
$qTeam
INNER JOIN
teams t ON t.id = m.team_id
INNER JOIN
j_ptresult pt ON pt.id = mpt.meetings_j_ptresult_1j_ptresult_idb
AND pt.deleted = 0
LEFT JOIN
c_teachers tc ON tc.id = m.teacher_id AND tc.deleted = 0
LEFT JOIN
(SELECT 
cp.contacts_j_payment_1contacts_ida contact_id,
MAX(p.payment_date) payment_date
FROM
contacts_j_payment_1_c cp
INNER JOIN j_payment p ON cp.contacts_j_payment_1j_payment_idb = p.id
AND p.deleted = 0
AND cp.deleted = 0
AND p.payment_type <> 'Placement Test'
AND p.is_new_student = 1
GROUP BY contact_id) payment ON payment.contact_id = pt.student_id
AND pt.parent = 'Contacts'
LEFT JOIN
contacts ct ON ct.id = pt.student_id
AND pt.parent = 'Contacts'
LEFT JOIN
leads l ON l.id = pt.student_id
AND pt.parent = 'Leads'
LEFT JOIN
leads l2 ON l2.contact_id = ct.id
LEFT JOIN
users u ON u.id = IFNULL(IFNULL(l.assigned_user_id, l2.assigned_user_id),
ct.assigned_user_id)
AND u.for_portal_only = 0
LEFT JOIN 
(SELECT 
    mc.contact_id,
    MAX(CONVERT( DATE_ADD(m.date_end, INTERVAL 7 HOUR) , DATE)) max_date
FROM
    meetings_contacts mc
        INNER JOIN
    meetings m ON m.id = mc.meeting_id AND m.deleted = 0
    $ext_team
        AND mc.deleted = 0
        AND m.meeting_type = 'session'
        AND CONVERT( DATE_ADD(m.date_end, INTERVAL 7 HOUR) , DATE) < '$start_date'
GROUP BY mc.contact_id) ld on ld.contact_id = pt.student_id
ORDER BY center_code, case when m.meeting_type = 'Placement Test' then 0
when m.meeting_type = 'Demo' then 1 end, m.date_start, module_name";

$rs = $GLOBALS['db']->query($sql);

$data = array();
$arr_student = array();


while($row = $GLOBALS['db']->fetchByAssoc($rs)){
    if(array_key_exists($row['student_id'], $arr_student))
        continue;
    $data[$row['meeting_id']]['meeting_date'] = $row['meeting_date'];
    $data[$row['meeting_id']]['meeting_type'] = $row['meeting_type'];
    $data[$row['meeting_id']]['meeting_time'] = $row['meeting_time'];
    $data[$row['meeting_id']]['teacher_id']      = $row['teacher_id'];
    $data[$row['meeting_id']]['teacher_name']      = $row['full_teacher_name'];
    $data[$row['meeting_id']]['count_register']      += 1;

    $data[$row['meeting_id']]['count_taker']  += $row['attended'];
    if(empty($row['last_lesson_date']) || $row['last_lesson_date'] <= $row['payment_date'] ){
        $data[$row['meeting_id']]['count_taker_new']  += $row['attended'];
        $arr_student[$row['student_id']]['payment_date']    = $row['payment_date'];
    }elseif(date('Y-m-d', strtotime('+6 month',strtotime($row['last_lesson_date']))) > $start_date)
        $arr_student[$row['student_id']]['is_current'] = 'Yes';


    $data[$row['meeting_id']]['center_code']  = $row['center_code'];

    $arr_student[$row['student_id']]['student_code']    = $row['student_code'];       
    $arr_student[$row['student_id']]['student_name']    = $row['student_name'];       
    $arr_student[$row['student_id']]['module_name']     = $row['module_name'];       
    $arr_student[$row['student_id']]['birthdate']       = $row['birthdate'];       
    $arr_student[$row['student_id']]['parent_name']     = $row['parent_name'];       
    $arr_student[$row['student_id']]['phone_mobile']    = $row['phone_mobile'];       
    $arr_student[$row['student_id']]['income_source']    = $row['income_source'];       
    $arr_student[$row['student_id']]['created_date']    = $row['created_date'];
    $arr_student[$row['student_id']]['assigned_to_ec']    = $row['assigned_to_ec'];       
    $arr_student[$row['student_id']]['center_code']    = $row['center_code'];       
    $arr_student[$row['student_id']][$row['meeting_type']]['register_date'] = $row['pt_demo_register_date'];    
    $arr_student[$row['student_id']][$row['meeting_type']]['taker_date'] = $row['pt_demo_taker_date'];
    $arr_student[$row['student_id']][$row['meeting_type']]['EC_note'] = $row['ec_note'];                      
    $arr_student[$row['student_id']][$row['meeting_type']]['result'] = $row['result'];  

    if(!empty($row['payment_date']) && date('Y-m-d', strtotime($row['payment_date'],"+3 month") >= $date_start) && empty($row['last_lesson_date'])){
        $data[$row['meeting_id']]['count_newsale'] += 1;               
    }    
}



$html = "";
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Type</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Time</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Teacher</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Number of registers</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Number of takers</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Number of new takers</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Number of new sales</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Taker / Register (%)</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >New sales / register (%)</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">New sales / taker (%)</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center code</th></tr>'; 

$seq = 0;
$total_reg = 0;
$total_taker = 0;
$total_newsale = 0;

foreach($data as $key=>$value){
    $html .= "<tr>
    <td>".++$seq."</td>
    <td>".$value['meeting_date']."</td>
    <td class='oddListRowS1' nowrap><a href='index.php?module=Meetings&action=DetailView&record={$key}' target='_blank'>".$value['meeting_type']."</td>
    <td>".$value['meeting_time']."</td>
    <td>".$value['teacher_name']."</td> 
    <td>".$value['count_register']."</td>
    <td>".$value['count_taker']."</td>
    <td>".$value['count_taker_new']."</td>
    <td>".$value['count_newsale']."</td>
    <td>".format_number($value['count_taker']/$value['count_register']*100,2,2)."%</td>
    <td>".format_number($value['count_newsale']/$value['count_register']*100,2,2)."%</td>
    <td>".format_number($value['count_newsale']/$value['count_taker_new']*100,2,2)."%</td>
    <td>".$value['center_code']."</td>
    </tr>";
    $total_reg += $value['count_register'];
    $total_taker += $value['count_taker'];
    $total_taker_new += $value['count_taker_new'];
    $total_newsale += $value['count_newsale'];
}

$html .= "<tr style='font-weight: bold'>
    <td colspan='5'><h3><span>Total</span></h3></td>
    
    <td>".$total_reg."</td>
    <td>".$total_taker."</td>
    <td>".$total_taker_new."</td>
    <td>".$total_newsale."</td>
    <td>".format_number($total_taker/$total_reg*100,2,2)."%</td>
    <td>".format_number($total_newsale/$total_reg*100,2,2)."%</td>
    <td>".format_number($total_newsale/$total_taker_new*100,2,2)."%</td>
    <td></td>
    </tr>";

$html .= "</tbody></table>";
$html .= "<br> <br>";
$html .= "Students List";


$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Code</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Leads Name</th>'; 
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Leads Source';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Date of birth</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Parent</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Mobile</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Created Date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >PT register date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >PT taker date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Result</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Notes</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Demo register date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Demo taker date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo Notes</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >New sales / Deposit date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" >Is Current</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap>First EC</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center code</th></tr>';

$seq = 0;
foreach($arr_student as $key=>$value){
    $html .= "<tr>
    <td>".++$seq."</td>
    <td>".$value['student_code']."</td>
    <td class='oddListRowS1' nowrap><a href='index.php?module={$value['module_name']}&action=DetailView&record={$key}' target='_blank'>".$value['student_name']."</td>
     <td>".$value['income_source']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['birthdate'],false)."</td>
    <td>".$value['parent_name']."</td>
    <td>".$value['phone_mobile']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['created_date'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Placement Test']['register_date'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Placement Test']['taker_date'],false)."</td>
    <td>".$value['Placement Test']['result']."</td>   
    <td>".$value['Placement Test']['EC_note']."</td>   
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Demo']['register_date'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Demo']['taker_date'],false)."</td>
    <td>".$value['Demo']['EC_note']."</td>   
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['payment_date'],false)."</td>
    <td nowrap >".$value['is_current']."</td>   
    <td nowrap >".$value['assigned_to_ec']."</td>   
    <td>".$value['center_code']."</td>   
    </tr>";
}

echo $html;
?>
