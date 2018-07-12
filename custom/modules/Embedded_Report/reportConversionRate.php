<?php

$js =
<<<EOQ
        <script>
        SUGAR.util.doWhen(
        function() {
           return $('#rowid0').find('td').eq(3).length == 1;
        },
        function() {

            $('#rowid1').find('td').eq(3).text('This Period');
            $('#rowid2').find('td').eq(3).text('Last Period');
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
global $timedate,$current_user;
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);
for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "l1.id=") !== FALSE)
        $team_id        = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l1.idIN") !== FALSE)
        $team_id        = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "l2.id=") !== FALSE)
        $first_ec        = get_string_between($parts[$i]);
    if(strpos($parts[$i], "l2.idIN") !== FALSE)
        $first_ec        = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "leads.date_entered>='") !== FALSE)
        $this_start     = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "leads.date_entered<='") !== FALSE)
        $this_end       = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "leads.date_entered='") !== FALSE){
        $this_start     = substr(get_string_between($parts[$i]),0,10);
        $this_end       = $this_start;
    }
    if(strpos($parts[$i], "leads.date_modified>='") !== FALSE)
        $last_start     = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "leads.date_modified<='") !== FALSE)
        $last_end       = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "leads.date_modified='") !== FALSE){
        $last_start     = substr(get_string_between($parts[$i]),0,10);
        $last_end       = $last_start;
    }
}
if($this_start !== $this_end) {
    $this_start = date('Y-m-d', strtotime($this_start) + 3600*24);
}
if($last_start !== $last_end) {
    $last_start = date('Y-m-d', strtotime($last_start) + 3600*24);
}

$html = '';
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center Name</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center Code</th>';
$html .= '<th rowspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Source</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Total New Leads</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Registration</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Taker</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo Registration</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo Taker</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo Taker (without PT)</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Number of new students base on leads</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Enrol/Leads (%)</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Reg/Leads (%)</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Taker/PT Reg (%)</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Enrol/PT taker (%)</th>';
$html .= '<th colspan = 2 align="center" class="reportlistViewThS1" valign="middle" nowrap="">Enrol/demo taker (%)</th></tr><tr height="20">';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Actual Number</th>';
$html .= '<th align="center" class="" valign="middle" nowrap="">Vs same period</th></tr>';

$ext_team = '';
if(!empty($team_id))
    $ext_team = "AND t.id IN ('$team_id')";

$qTeam = "AND l.team_set_id IN
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

$ext_first_ec = '';
if(!empty($first_ec)){
    $ext_first_ec = "AND l.assigned_user_id IN ('$first_ec')";
}

$sql = "     SELECT
t.name center_name,
t.code_prefix,
t.id center_id,
CASE
WHEN l.lead_source = '' THEN 'Other'
ELSE l.lead_source
END AS lead_sources,
CASE
WHEN
CONVERT( DATE_ADD(l.date_entered,
INTERVAL 7 HOUR) , DATE) BETWEEN '$this_start' AND '$this_end'
THEN
'this_period'
WHEN
CONVERT( DATE_ADD(l.date_entered,
INTERVAL 7 HOUR) , DATE) BETWEEN '$last_start' AND '$last_end'
THEN
'last_period'
END AS period,
CASE m.meeting_type
WHEN 'Placement Test' THEN 'PT'
WHEN 'Demo' THEN 'Demo'
ELSE 'None'
END AS meeting_type,
CASE
WHEN IFNULL(payment.payment_date, '') <> '' THEN 'Student'
ELSE 'Lead'
END AS current_status,
IFNULL(pt.attended, '') taker,
l.id lead_id,
l.status,
ct.id contact_id,
payment.payment_date,
ifnull(ct.contact_id , '') student_code,
ifnull(ct.full_student_name, l.full_lead_name) full_student_name,
ifnull(ct.birthdate, l.birthdate) birthdate,
ifnull(ct.guardian_name, l.guardian_name) parent_name,
ifnull(ct.phone_mobile, l.phone_mobile) phone_mobile,
CONVERT( DATE_ADD(l.date_entered,
INTERVAL 7 HOUR) , DATE) date_created,
IFNULL(CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE),
'') pt_demo_register_date,
CASE
WHEN pt.attended = 1 THEN CONVERT( DATE_ADD(m.date_start, INTERVAL 7 HOUR) , DATE)
ELSE ''
END AS pt_demo_taker_date,
pt.ec_note,
IFNULL(u.full_user_name, '') assigned_to_ec ,
CONCAT(pt.result_koc, ' ', pt.result_lvl) result
FROM
leads l
INNER JOIN
teams t ON t.id = l.team_id AND l.deleted = 0
AND ((CONVERT( DATE_ADD(l.date_entered,
INTERVAL 7 HOUR) , DATE) BETWEEN '$this_start' AND '$this_end')
OR (CONVERT( DATE_ADD(l.date_entered,
INTERVAL 7 HOUR) , DATE) BETWEEN '$last_start' AND '$last_end'))
AND l.deleted = 0
AND LENGTH(t.code_prefix) > 1
$ext_team
$ext_first_ec
$qTeam
LEFT JOIN
(SELECT
leads_j_ptresult_1leads_ida lead_id,
leads_j_ptresult_1j_ptresult_idb pt_id
FROM
leads_j_ptresult_1_c
WHERE
deleted = 0 UNION SELECT
l2.id lead_id, cpt.contacts_j_ptresult_1j_ptresult_idb pt_id
FROM
leads l2
INNER JOIN contacts_j_ptresult_1_c cpt ON l2.contact_id = cpt.contacts_j_ptresult_1contacts_ida
AND cpt.deleted = 0) lpt ON lpt.lead_id = l.id
LEFT JOIN
j_ptresult pt ON pt.id = lpt.pt_id AND pt.deleted = 0
LEFT JOIN
meetings_j_ptresult_1_c mpt ON mpt.meetings_j_ptresult_1j_ptresult_idb = pt.id
AND mpt.deleted = 0
LEFT JOIN
meetings m ON m.id = mpt.meetings_j_ptresult_1meetings_ida
AND m.deleted = 0
AND m.meeting_type IN ('Demo' , 'Placement Test')
LEFT JOIN
(SELECT
cp.contacts_j_payment_1contacts_ida contact_id,
MIN(p.payment_date) payment_date
FROM
contacts_j_payment_1_c cp
INNER JOIN j_payment p ON cp.contacts_j_payment_1j_payment_idb = p.id
AND p.deleted = 0
AND cp.deleted = 0
AND p.payment_type <> 'Placement Test'
GROUP BY contact_id) payment ON payment.contact_id = l.contact_id
LEFT JOIN
contacts ct ON ct.id = l.contact_id AND ct.deleted = 0
LEFT JOIN
users u ON u.id = l.assigned_user_id
AND u.for_portal_only = 0
ORDER BY code_prefix , lead_sources , period , lead_id,CASE  meeting_type when 'PT' then 0 WHEN 'Demo' then 1 END, pt_demo_register_date, pt_demo_taker_date";

$rs = $GLOBALS['db']->query($sql);
$data = array();
$count = array();
$arr_student = array();
$lead_source  = '';
$center_id = '';
$lead_id = '';
$meeting_type = '';
$arr_taker = array();
while ($row = $GLOBALS['db']->fetchByAssoc($rs)){
    if($center_id !== $row['center_id']){
        if(!empty($center_id))
            $data[$center_id]['lead_source'][] = 'Total';
        $lead_source = '';
        $center_id = $row['center_id'];
    }
    $center_id = $row['center_id'];
    $data[$row['center_id']]['center_name'] = $row['center_name'];
    $data[$row['center_id']]['center_code'] = $row['code_prefix'];
    if($lead_source !== $row['lead_sources']){
        $data[$row['center_id']]['lead_source'][] = $row['lead_sources'];
        $lead_source = $row['lead_sources'];
    }

    if($lead_id !== $row['lead_id']){
        $lead_id = $row['lead_id'];
        $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['current_status']] += 1;
        $count[$row['center_id']]['Total'][$row['period']][$row['current_status']] += 1;
        $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']][$row['current_status']] += 1;
        $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['register'] += 1;
        $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['taker'] += $row['taker'];
        $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['register'] += 1;
        $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['taker'] += $row['taker'];
        $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']][$row['current_status']] += 1;
        $meeting_type = $row['meeting_type'];
        if($row['meeting_type']=='Demo'){
            $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['taker_wo_pt'] += $row['taker'];
            $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['taker_wo_pt'] += $row['taker'];
        }
    }else{
        if($meeting_type !== $row['meeting_type']){
            $meeting_type == $row['meeting_type'];
            $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']][$row['current_status']] += 1;
            $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['register'] += 1;
            $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['register'] += 1;
            $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']][$row['current_status']] += 1;
        }
        if(empty($arr_taker[$lead_id][$row['meeting_type']]['taker_date'])){
            $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['taker'] += $row['taker'];
            $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['taker'] += $row['taker'];
            if($row['meeting_type']=='Demo' && empty($arr_taker[$lead_id]['PT']['taker_date'])){
                $count[$row['center_id']]['Total'][$row['period']][$row['meeting_type']]['taker_wo_pt'] += $row['taker'];
                $count[$row['center_id']][$row['lead_sources']][$row['period']][$row['meeting_type']]['taker_wo_pt'] += $row['taker'];
            }
        }
    }

    $arr_taker[$lead_id][$row['meeting_type']]['taker_date'] = $row['pt_demo_taker_date'];
    if($row['period'] == 'this_period'){
        $arr_student[$row['lead_id']]['student_name'] = $row['full_student_name'];
        $arr_student[$row['lead_id']]['student_code'] = $row['student_code'];
        $arr_student[$row['lead_id']]['status']       = $row['status'];
        $arr_student[$row['lead_id']]['center_code']  = $row['code_prefix'];
        $arr_student[$row['lead_id']]['lead_source']  = $row['lead_sources'];
        $arr_student[$row['lead_id']]['birthdate']    = $row['birthdate'];
        $arr_student[$row['lead_id']]['parent_name']  = $row['parent_name'];
        $arr_student[$row['lead_id']]['phone_mobile'] = $row['phone_mobile'];
        $arr_student[$row['lead_id']]['date_created'] = $row['date_created'];
        $arr_student[$row['lead_id']]['payment_date'] = $row['payment_date'];
        $arr_student[$row['lead_id']]['assigned_to_ec'] = $row['assigned_to_ec'];
        $arr_student[$row['lead_id']][$row['meeting_type']]['register_date'] = $row['pt_demo_register_date'];
        $arr_student[$row['lead_id']][$row['meeting_type']]['taker_date'] = $row['pt_demo_taker_date'];
        $arr_student[$row['lead_id']][$row['meeting_type']]['EC_note'] = $row['ec_note'];
        $arr_student[$row['lead_id']][$row['meeting_type']]['result'] = $row['result'];
    }

}
$data[$center_id]['lead_source'][] = 'Total';

foreach($data as $key=>$value){
    foreach($value['lead_source'] as $leads=>$lead_source){
        $total_leads_this = $count[$key][$lead_source]['this_period']['Student'] + $count[$key][$lead_source]['this_period']['Lead'];
        $total_leads_last = $count[$key][$lead_source]['last_period']['Student'] + $count[$key][$lead_source]['last_period']['Lead'];
        if($lead_source == 'Total'){
            $html .= "<tr style = 'font-weight:bold'>";
        }else
            $html .= "<tr>";
        $html .=  "<td nowrap>".$value['center_name']."</td>
        <td>".$value['center_code']."</td>
        <td nowrap>".$lead_source."</td>
        <td>".format_number($total_leads_this,0,0)."</td>
        <td>".format_number($total_leads_last,0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['PT']['register'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['PT']['register'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['PT']['taker'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['PT']['taker'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Demo']['register'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Demo']['register'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Demo']['taker'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Demo']['taker'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Demo']['taker_wo_pt'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Demo']['taker_wo_pt'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Student'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Student'],0,0)."</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Student']/$total_leads_this*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Student']/$total_leads_last*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['PT']['register']/$total_leads_this*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['PT']['register']/$total_leads_last*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['PT']['taker']/$count[$key][$lead_source]['this_period']['PT']['register']*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['PT']['taker']/$count[$key][$lead_source]['last_period']['PT']['register']*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['PT']['Student']/$count[$key][$lead_source]['this_period']['PT']['taker']*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['PT']['Student']/$count[$key][$lead_source]['last_period']['PT']['taker']*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['this_period']['Demo']['Student']/$count[$key][$lead_source]['this_period']['Demo']['taker']*100,2,2)."%</td>
        <td>".format_number($count[$key][$lead_source]['last_period']['Demo']['Student']/$count[$key][$lead_source]['last_period']['Demo']['taker']*100,2,2)."%</td>
        </tr>";
    }
}

$html .= "</tbody></table>";
$html .= "<br> <br>";
$html .= "Leads List";

$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Student Code</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Leads Name</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Lead Source</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Status</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Date of birth</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Parent</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Mobile</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Created date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT register date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT taker date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Result</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">PT Notes</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo register date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo taker date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Demo Notes</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">New sales/Deposit date</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap>First EC</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center code</th></tr>';

$seq = 0;
foreach($arr_student as $key=>$value){
    $html .= "<tr>
    <td>".++$seq."</td>
    <td>".$value['student_code']."</td>
    <td class='oddListRowS1' nowrap><a href='index.php?module=Leads&action=DetailView&record={$key}' target='_blank'>".$value['student_name']."</td>
    <td nowrap>".$value['lead_source']."</td>
    <td>".$value['status']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['birthdate'],false)."</td>
    <td>".$value['parent_name']."</td>
    <td>".$value['phone_mobile']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['date_created'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['PT']['register_date'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['PT']['taker_date'],false)."</td>
    <td>".$value['PT']['result']."</td>
    <td>".$value['PT']['EC_note']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Demo']['register_date'],false)."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['Demo']['taker_date'],false)."</td>
    <td>".$value['Demo']['EC_note']."</td>
    <td style='mso-number-format:\"Short Date\";text-align: left;'>".$timedate->to_display_date($value['payment_date'],false)."</td>
    <td nowrap >".$value['assigned_to_ec']."</td>
    <td>".$value['center_code']."</td>
    </tr>";
}

echo $html;


?>
