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


/*
 ARGS:

 $_REQUEST['module'] : the module associated with this Bean instance (will be used to get the class name)
 $_REQUEST['prospect_lists'] : the id of the prospect list
 $_REQUEST['uids'] : the ids of the records to be added to the prospect list, separated by ','

 */
require_once('custom/modules/Users/UserTemplateHelper.php');
require_once 'include/formbase.php';

global $beanFiles,$beanList;
$bean_name = $beanList[$_REQUEST['module']];
require_once($beanFiles[$bean_name]);
$focus = new $bean_name();

$uids = array();
if($_REQUEST['select_entire_list'] == '1'){
	$order_by = '';

	require_once('include/MassUpdate.php');
	$mass = new MassUpdate();
	$mass->generateSearchWhere($_REQUEST['module'], $_REQUEST['current_query_by_page']);
	$ret_array = create_export_query_relate_link_patch($_REQUEST['module'], $mass->searchFields, $mass->where_clauses);
	$query = $focus->create_export_query($order_by, $ret_array['where'], $ret_array['join']);
	$result = $GLOBALS['db']->query($query,true);
	$uids = array();
	while($val = $GLOBALS['db']->fetchByAssoc($result,false))
	{
		array_push($uids, $val['id']);
	}
}
else {
	$uids = explode ( ',', $_POST['uids'] );
}

//apply user template
UserTemplateHelper::applyUserTemplate($_REQUEST['user_template'], $uids);
  
handleRedirect();
exit;