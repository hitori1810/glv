<?php
global $current_user;
$module = $_POST['module_lap'];
if($module == 'self'){
    $module = $GLOBALS['db']->getOne("SELECT module FROM saved_reports WHERE id = '{$_POST['report_id']}'");
}
$module = strtolower($module);

if($module == 'contacts' || $module == 'c_teachers' || $module == 'leads' || $module == 'targets' || $module == 'users')
    $fields = "CONCAT(IFNULL(last_name,''),' ',IFNULL(first_name,'')) name";
else
    $fields = 'name';
$ext_w = '';
if($module == 'teams')
    $ext_w= "AND private <>'1'";
else $ext_w= "AND team_set_id IN
    (SELECT
    tst.team_set_id
    FROM
    team_sets_teams tst
    INNER JOIN
    team_memberships team_memberships ON tst.team_id = team_memberships.team_id
    AND team_memberships.user_id = '{$current_user->id}'
    AND team_memberships.deleted = 0)";
$q1     = "SELECT $fields, id FROM $module WHERE deleted = 0 $ext_w";
$rs1    = $GLOBALS['db']->query($q1);
$count=0;
$opt_arrar = new ArrayObject();
while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
    $opt_arrar[$count]->text = $row['name'];
    $opt_arrar[$count]->value = $row['id'];
    $count++;
}
echo json_encode(array(
    "success" => "1",
    "opt_arrar" => $opt_arrar,
));