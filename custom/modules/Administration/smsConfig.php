<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('modules/Administration/Administration.php');

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $db;
if (!is_admin($current_user)) sugar_die("Unauthorized access to administration.");
//Fix Notice error
$mod_id = "";
$mod_name = "";
if(isset($mod_strings['LBL_MODULE_ID'])) {
    $mod_id = $mod_strings['LBL_MODULE_ID'];
}
if(isset($mod_strings['LBL_MODULE_NAME'])) {
    $mod_name = $mod_strings['LBL_MODULE_NAME'];
}
echo "\n<p>\n";
echo get_module_title($mod_id,$mod_strings['LBL_MODULE_NAME'], true);
echo "\n</p>\n";
global $currentModule;

$ss =  new Sugar_Smarty();
$focus = new Administration();
$focus->retrieveSettings(); //retrieve all admin settings.
$GLOBALS['log']->info("SMS Configure Settings view");

$ss->assign("MOD", $mod_strings);
$ss->assign("APP", $app_strings);

$team_options   = "<select tabindex='0' name='team_id' id='team_id' onchange='myFunction()' >";
$team_options  .= "<option value=''>- none -</option>";

$q1 = "SELECT DISTINCT
IFNULL(teams.id, '') id,
IFNULL(teams.name, '') name
FROM
teams
LEFT JOIN
teams l1 ON teams.id = l1.parent_id
AND l1.deleted = 0
AND ((l1.private IS NULL OR l1.private = '0'))
WHERE
((((l1.id IS NULL OR l1.id = ''))
AND ((teams.private IS NULL
OR teams.private = '0'))))
AND teams.deleted = 0
AND teams.id <> '1'
ORDER BY name, teams.team_type";
$rs1 = $GLOBALS['db']->query($q1);
while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
    if($_POST['team_id'] == $row['id']){
        $team_options  .= "<option value='{$row['id']}' selected>{$row['name']}</option>";
    }else
        $team_options  .= "<option value='{$row['id']}'>{$row['name']}</option>";
}
$team_options .= "</select>";
$ss->assign('team_options',$team_options);
$ss->assign('supplier_options',  $GLOBALS['app_list_strings']['sms_supplier_api']);

//--------------SAVE----------------------------
if($_POST['btn_submit'] == 'Save'){
    // Edit by Lap Nguyen 28/03/2017
    if(!empty($_POST['team_id'])){
        $team = BeanFactory::getBean('Teams', $_POST['team_id']);

        $smsConfigArray = array();
        $smsConfigArray['sms_ws_link']      = $_POST['sms_ws_link'];
        $smsConfigArray['sms_ws_account']   = $_POST['sms_ws_account'];
        $smsConfigArray['sms_ws_pass']      = $_POST['sms_ws_pass'];
        $smsConfigArray['sms_ws_brandname'] = $_POST['sms_ws_brandname'];
        $smsConfigArray['sms_ws_groupid']   = $_POST['sms_ws_groupid'];
        $smsConfigArray['sms_ws_deptid']    = $_POST['sms_ws_deptid'];
        $smsConfigArray['sms_ws_supplier']  = $_POST['sms_ws_supplier'];

        $team->sms_config = json_encode($smsConfigArray);
        $team->save();
    }
}

//--------------DISPLAY----------------------------
if($_POST['load_again'] || $_POST['btn_submit'] == 'Save'){
    // Edit by Lap Nguyen 28/03/2017
    $smsConfigArray = array(
        'sms_ws_link'       => '',
        'sms_ws_account'    => '',
        'sms_ws_pass'       => '',
        'sms_ws_brandname'  => '',
        'sms_ws_groupid'    => '',
        'sms_ws_deptid'     => '',
        'sms_ws_supplier'   => '',
    );
    if(!empty($_POST['team_id'])){
        $team = BeanFactory::getBean('Teams', $_POST['team_id']);
        $smsConfigArray = json_decode(html_entity_decode($team->sms_config),true);
    }

    $ss->assign('sms_ws_link',$smsConfigArray['sms_ws_link']);
    $ss->assign('sms_ws_account',$smsConfigArray['sms_ws_account']);
    $ss->assign('sms_ws_pass',$smsConfigArray['sms_ws_pass']);
    $ss->assign('sms_ws_brandname',$smsConfigArray['sms_ws_brandname']);
    $ss->assign('sms_ws_groupid',$smsConfigArray['sms_ws_groupid']);
    $ss->assign('sms_ws_deptid',$smsConfigArray['sms_ws_deptid']);
    $ss->assign('sms_ws_supplier',$smsConfigArray['sms_ws_supplier']);
}

$ss->display("custom/modules/Administration/templates/sms_config.tpl");
?>