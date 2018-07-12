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


global $beanList, $beanFiles, $locale;

if(isset($_REQUEST['module']) && isset($_REQUEST['action']) && isset($_REQUEST['record'])) {
	$currentModule = clean_string($_REQUEST['module']);
	$action = clean_string($_REQUEST['action']);
	$record = clean_string($_REQUEST['record']);
} else {
	die ("module, action, and record id all are required");
}

$entity = $GLOBALS['beanList'][$currentModule];
require_once($GLOBALS['beanFiles'][$entity]);
$GLOBALS['focus'] = new $entity();
$GLOBALS['focus']->retrieve(clean_string($_REQUEST['record']));

include("modules/$currentModule/$action.php");

?>
