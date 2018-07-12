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

/*********************************************************************************

 * Description:  
 ********************************************************************************/





$workflow = new WorkFlow();
if(isset($_REQUEST['workflow_id']) && isset($_REQUEST['record_id']))
{
	$alert_shell = new WorkFlowAlertShell();
	$alert_shell->retrieve($_REQUEST['record_id']);
	$alert_shell->copy($_REQUEST['workflow_id']);
	
	$javascript = "<script>window.opener.document.DetailView.action.value = 'DetailView';";
	$javascript .= "window.opener.document.DetailView.submit();";
	$javascript .= "window.close();</script>";
	echo $javascript;
}
?>
