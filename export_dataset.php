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

require_once('modules/DataSets/DataSet.php');

if($GLOBALS['sugar_config']['disable_export'] || (!empty($GLOBALS['sugar_config']['admin_export_only']) && !is_admin($current_user))){
	die("Exports Disabled");
}

$export_object = new DataSet();
$export_object->retrieve($_REQUEST['record']);
$csv_output = $export_object->export_csv();

header("Pragma: cache");
header("Content-Disposition: inline; filename={$_REQUEST['module']}.csv");
header("Content-Type: text/csv; charset=UTF-8");
header( "Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . TimeDate::httpTime() );
header( "Cache-Control: post-check=0, pre-check=0", false );
header("Content-Length: ".strlen($csv_output));
print $csv_output;
sugar_cleanup();
exit;
?>
