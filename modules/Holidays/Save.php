<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is not bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/



require_once('include/formbase.php');

$focus = new Holiday();
global $current_user;

$focus->disable_row_level_security = true;
$focus->retrieve($_POST['record']);

$focus = populateFromPost('', $focus);

if ($_REQUEST['return_module'] != 'Project'){
    $focus->person_id = $_REQUEST['relate_id'];
    $focus->person_type = "Users";
}
else if ($_REQUEST['return_module'] == 'Project'){
    $focus->related_module = 'Project';
    $focus->related_module_id = $_REQUEST['relate_id'];
}

//custom save range of Holiday - Lap Nguyen
if($focus->type != 'Public Holiday'){
    $pos = stripos($focus->holidays_range, 'to');
    if ($pos !== false){
        global $timedate;
        $arr = explode(' to ',$focus->holidays_range);
        $start = strtotime($timedate->to_db_date($arr[0],false));
        $end = strtotime($timedate->to_db_date($arr[1],false));

        $days = array();
        $i = 0;
        while($start <= $end){
            $hl = new Holiday();
            $hl->holiday_date = date('Y-m-d',$start);
            $hl->description = $focus->description;
            $hl->type = $focus->type;
            if($_POST['return_module'] == 'C_Teachers'){
                $hl->teacher_id = $_POST['relate_id'];
                $existing = $GLOBALS['db']->getOne("SELECT id FROM holidays WHERE holiday_date = '{$hl->holiday_date}' AND teacher_id = '{$hl->teacher_id}'");
                if(!$existing)
                    $hl->save();
            }else{
                $hl->person_type = $_POST['return_module'];
                $hl->person_id = $_POST['relate_id'];
                $existing = $GLOBALS['db']->getOne("SELECT id FROM holidays WHERE person_type = '{$_POST['return_module']}' AND person_id = '{$_POST['relate_id']}' AND holiday_date = '{$hl->holiday_date}'");
                if(!$existing)
                    $hl->save();
            }
            $start = strtotime('+1 day', $start);
        }
        $focus->deleted = 1;
    }
}else{
    if($_POST['module'] == 'Holidays' && $_POST['action'] == 'Save'){
        $holi_list = explode(',',$focus->public_holiday);
        foreach($holi_list as $holiday_date){
            //edit function save by Tung Bui - 09/12/2015
            $existing = $GLOBALS['db']->getOne("SELECT id FROM holidays WHERE holiday_date = '{$holiday_date}' AND deleted=0 AND type='Public Holiday' and aplly_for = '{$focus->type}'");
            if(!empty($existing))
                $hl = BeanFactory::getBean("Holidays", $existing);
            else
                $hl = new Holiday();
            $hl->holiday_date = $holiday_date;
            $hl->description = $focus->description;
            $hl->apply_for = 'forall';
            $hl->type = $focus->type;
            $hl->save();
            //END - edit function save by Tung Bui - 09/12/2015
        }
        $focus->deleted = 1;
        header('Location: index.php?module=Holidays&action=index');
        die();
    }
}

//Custom Save range of holiday

$check_notify = FALSE;


$focus->save($check_notify);
$return_id = $Document->id;

if(isset($_POST['return_module']) && $_POST['return_module'] != "") $return_module = $_POST['return_module'];
else $return_module = "Holidays";
if(isset($_POST['return_action']) && $_POST['return_action'] != "") $return_action = $_POST['return_action'];
else $return_action = "DetailView";
if(isset($_POST['return_id']) && $_POST['return_id'] != "") $return_id = $_POST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

handleRedirect($return_id,'Holidays');
?>
