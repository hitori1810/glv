<?php
global $current_user;
if(($current_user->is_admin <> '1') && !(ACLController::checkAccess('C_Commission', 'Edit', false)) )
    die("You do not have permision to view this report.");
$filter = str_replace(' ','',$this->where);
$parts  = explode("AND", $filter);

for($i = 0; $i < count($parts); $i++){
    if(strpos($parts[$i], "j_payment.sale_type_date>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_payment.sale_type_date<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_payment.sale_type_date='") !== FALSE){
        $start_date = get_string_between($parts[$i]);
        $end_date   = $start_date;
    }
}
$html = '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th width = "40%" scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center</th>';
$html .= '<th   scope="col" align="center" width = "10%" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center Code</th>';
$html .= '<th   scope="col" align="center" width = "10%" class="reportlistViewMatrixThS1" valign="middle" wrap="">Course Fee</th>';
$html .= '<th   scope="col" align="center" width = "10%" class="reportlistViewMatrixThS1" valign="middle" wrap="">Number of Payments</th>';
$html .= '<th   scope="col" align="center" width = "15%" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total Hours</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total Amount</th>';


$qTeam = "AND p.team_set_id IN
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
t.id    center_id,
t.name center_name,
t.code_prefix,
CASE c.type_of_course_fee
WHEN 36 THEN '36 Hours'
WHEN 46 THEN '46 Hours'
WHEN 72 THEN '72 Hours'
WHEN 108 THEN '108 Hours'
END AS course_fee,
COUNT(c.type_of_course_fee) AS quantity,
SUM(p.tuition_hours) total_hours,
SUM(p.payment_amount + p.deposit_amount) total_amount
FROM
j_payment p
INNER JOIN
teams t ON t.id = p.team_id AND p.deleted = 0
$qTeam
AND t.team_type = 'Junior'
INNER JOIN
j_coursefee_j_payment_1_c cp ON cp.j_coursefee_j_payment_1j_payment_idb = p.id
AND cp.deleted = 0
INNER JOIN
j_coursefee c ON c.id = cp.j_coursefee_j_payment_1j_coursefee_ida
AND p.payment_type IN ('Cashholder' , 'Enrollment')
AND p.paid_amount = 0
AND p.paid_hours = 0
AND p.kind_of_course <> 'Premium'
AND p.sale_type_date BETWEEN '$start_date' AND '$end_date'
AND p.sale_type IN ('Not Set', 'New Sale', 'Retention')
GROUP BY center_id , course_fee
ORDER BY center_name , CASE course_fee
WHEN '36 Hours' THEN 1
WHEN '46 Hours' THEN 2
WHEN '72 Hours' THEN 3
WHEN '108 Hours' THEN 4
END";
$result = $GLOBALS['db']->fetchArray($sql);
$row_span = array();
foreach($result as $key=>$value){
    $row_span[$value['center_id']]['total']+= 1;
}
if(!empty($result)){
    /*$first = reset($result);
    $center_id = $first['center_id'];
    $center_name = $first['center_name'];
    $course_fee = $first['course_fee'];
    $html .= '<tr height="20"><td rowspan = "'.$row_span[$center_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$center_name.'</b></td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$course_fee.'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['quantity'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($first['total_hours'],2,2).'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($first['total_amount'],2,2).'</td>';
} */
foreach($result as $key=>$row){
    /*if($center_id == $row['center_id']){
        if($course_fee !== $row['course_fee']) {
            $course_fee = $row['course_fee'];
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['course_fee'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['quantity'].'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($row['total_hours'],2,2).'</td>';
            $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($row['total_amount'],2,2).'</td>';
        }
    }else{
        $center_id = $row['center_id'];
        $course_fee = $row['course_fee']; */
        $html .= '<tr height="20"><td rowspan = "'.$row_span[$center_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$row['center_name'].'</b></td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['code_prefix'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['course_fee'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['quantity'].'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($row['total_hours'],2,2).'</td>';
        $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.format_number($row['total_amount'],2,2).'</td></tr>';
    /*}
    $html.='</tr>'; */
}
}

$html .= "</tbody></table>";
echo $html;
//JS
/*$js =
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
echo $js;*/

?>
