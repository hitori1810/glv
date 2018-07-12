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
    if(strpos($parts[$i], "l1.idIN"))                           $team_id    = get_string_between($parts[$i],"('","')");
    if(strpos($parts[$i], "j_payment.sale_type_date>='") !== FALSE) $start_date = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_payment.sale_type_date<='") !== FALSE) $end_date   = substr(get_string_between($parts[$i]),0,10);
    if(strpos($parts[$i], "j_payment.sale_type_date='") !== FALSE){
        $start_date = get_string_between($parts[$i],0,10);
        $end_date   = $start_date;
    }
}

$start_date = date('Y-m-d', strtotime($start_date) + 3600*24);

$ext_team_1 = '';
$ext_team_2 = '';
if(!empty($team_id)) {
    $ext_team_1 = "AND t.id IN ('$team_id')";
    $ext_team_2 = "AND team_id IN ('$team_id')";
}
$sql_user = "SELECT 
u.id,
u.user_name,
u.full_user_name,
ar.name role_name,
t.name center_name,
t.code_prefix
FROM
users u
INNER JOIN
acl_roles_users aru ON u.id = aru.user_id AND aru.deleted = 0
AND u.status = 'Active'
INNER JOIN
acl_roles ar ON ar.id = aru.role_id AND ar.deleted = 0
AND ar.name IN ('Junior - EC Supervisor (ECS)' , 'Junior - Education Consultant (EC)','Junior - Center Manager')
INNER JOIN
teams t ON u.default_team = t.id
$ext_team_1
ORDER BY code_prefix";

$sql_payment = "SELECT 
assigned_user_id, sum(payment_amount)  total_amount
FROM
j_payment
WHERE
sale_type_date BETWEEN '$start_date' AND '$end_date'
AND sale_type IN ('new sale','not set')
AND deleted = 0
$ext_team_2
GROUP BY assigned_user_id
HAVING SUM(payment_amount) > 0";

$rs_payment = $GLOBALS['db']->query($sql_payment); 
while($row_p = $GLOBALS['db']->fetchByAssoc($rs_payment)){
    $arr_payment[$row_p['assigned_user_id']]['amount'] = $row_p['total_amount'];
}

$rs = $GLOBALS['db']->query($sql_user);
while($row = $GLOBALS['db']->fetchByAssoc($rs)){
    if(!array_key_exists($row['id'], $arr_payment)){
        $arr_user[$row['id']]['center_name'] = $row['center_name'];
        $arr_user[$row['id']]['center_code'] = $row['code_prefix'];  
        $arr_user[$row['id']]['full_user_name'] = $row['full_user_name']; 
        $arr_user[$row['id']]['role_name'] = $row['role_name'];          
    }
}
$html = '';
$html .= '<table width="100%" border="0" cellpadding="0" cellspacing="0" class="reportlistView"><tbody>';
$html .= '<tr height="20"><th align="center" class="reportlistViewThS1" valign="middle" nowrap="">SEQ</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">User Name</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">User Role</th>';
$html .= '<th align="center" class="reportlistViewThS1" valign="middle" nowrap="">Center Code</th></tr>';  

$seq = 0;
foreach($arr_user as $key=>$value){
    $html .= "<tr>
        <td>".++$seq."</td>
        <td>".$value['full_user_name']."</td>
        <td>".$value['role_name']."</td>
        <td>".$value['center_code']."</td>
        </tr>";
}

echo $html;

?>
