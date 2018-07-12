<?php
if(!isset($_POST['record']) || empty($_POST['record'])){
    echo "Please, Select filter options and click Apply to show this report. ";
    $sv = new SugarView();
    $sv->displayFooter();
    die();
}

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
$html.= '<tr height="20"><th colspan =2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">City</th>';
$html .= '<th colspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center</th>';
$html .= '<th rowspan = 2 scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Student Name</th>';
$html .= '<th rowspan = 2  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Sale Type Date</th>';
$html.= '<tr height="20"><th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">City</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total</th>';
$html .= '<th   scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Center Name</th>';
$html .= '<th  scope="col" align="center" class="reportlistViewMatrixThS1" valign="middle" wrap="">Total</th></tr>';

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
    t2.id   city_id,
    t2.name city_name,
    t.id center_id,
    t.name center_name,
    ct.id contact_id,
    ct.full_student_name,
    p.id payment_id,
    p.sale_type_date,
    p.payment_date,
    p.tuition_hours,
    c.type_of_course_fee,
    c.name course_fee,
    p.amount_bef_discount,
    p.discount_amount,
    p.final_sponsor,
    p.payment_amount,
    p.deposit_amount
FROM
    j_payment p
        INNER JOIN
    teams t ON t.id = p.team_id AND p.deleted = 0
        $qTeam
        AND t.team_type = 'Junior'
        INNER JOIN
    teams t2 ON t2.id = t.parent_id
        AND t2.parent_id IN ('4e3de4c1-2c5e-6c00-3494-55667b495afe')
        INNER JOIN
    j_coursefee_j_payment_1_c cp ON cp.j_coursefee_j_payment_1j_payment_idb = p.id
        AND cp.deleted = 0
        INNER JOIN
    j_coursefee c ON c.id = cp.j_coursefee_j_payment_1j_coursefee_ida
        AND p.payment_type IN ('Cashholder' , 'Enrollment')
        AND p.paid_amount = 0
        AND p.paid_hours = 0
        AND p.sale_type_date BETWEEN '$start_date' AND '$end_date'
        AND p.sale_type = 'New Sale'
        INNER JOIN
    contacts_j_payment_1_c cpm ON cpm.contacts_j_payment_1j_payment_idb = p.id
        AND cpm.deleted = 0
        INNER JOIN
    contacts ct ON cpm.contacts_j_payment_1contacts_ida = ct.id
        AND ct.deleted = 0
ORDER BY CASE city_name
    WHEN 'HCM' THEN 0
    WHEN 'HN' THEN 1
    WHEN 'HP' THEN 2
    WHEN 'BN' THEN 3
    WHEN 'DN' THEN 4
    WHEN 'BH' THEN 5
    WHEN 'BD' THEN 6
END , center_name , p.payment_date";
$result = $GLOBALS['db']->fetchArray($sql);
$row_span = array();
foreach($result as $key=>$value){
    $row_span[$value['city_id']]['total']+= 1;
    $row_span[$value['city_id']][$value['center_id']]+= 1;
}
if(!empty($result)){
    $first = reset($result);
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
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['full_student_name'].'</td>';
    $html .= '<td scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$first['sale_type_date'].'</td>';
}

foreach($result as $key=>$row){
    if($city_id == $row['city_id']) {
        if($center_id == $row['center_id']){
            if($student_id !== $row['contact_id']){
                $student_id = $row['contact_id'];
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['full_student_name'].'</td>';
                $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['sale_type_date'].'</td>';
            }
        }else{
            $center_id = $row['center_id'];
            $student_id = $row['contact_id'];
            $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_name'].'</td>';
            $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id][$center_id].'</td>';
            $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['full_student_name'].'</td>';
            $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['sale_type_date'].'</td>';
        }
    }else{
        $html.='</tr>';
        $city_id = $row['city_id'];
        $center_id = $row['center_id'];
        $student_id  = $row['student_id'];
        $html .= '<tr height="20"><td rowspan = "'.$row_span[$city_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.$row['city_name'].'</b></td>';
        $html .= '<td rowspan = "'.$row_span[$city_id]['total'].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id]['total'].'</td>';
        $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['center_name'].'</td>';
        $html .= '<td rowspan = "'.$row_span[$city_id][$center_id].'" scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row_span[$city_id][$center_id].'</td>';
        $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['full_student_name'].'</td>';
        $html .= '<td  scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap="">'.$row['sale_type_date'].'</td>';
    }
    $html.='</tr>';
}
$html .= '<tr height="20"><th colspan = 4 scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>Total New Sales</b></th>';
$html .= '<th colspan = 2 scope="col" align="center" class="reportGroupByDataMatrixEvenListRowS1" valign="middle" wrap=""  ><b>'.count($result).'</b></th></tr>';

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
