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

 * Description:  controls which link show up in the upper right hand corner of the app
 ********************************************************************************/
if(!isset($global_control_links)){
  $global_control_links = array();
}

if(!empty($_SESSION['current_user_id'])){
	$global_control_links['users'] = array(
	'linkinfo' => array($app_strings['LBL_MY_ACCOUNT'] => 'index.php?module=Users&action=DetailView&id='.(empty($_SESSION['current_user_id']) ? '' : $_SESSION['current_user_id']) ,
	                    $app_strings['LBL_LOGOUT'] => 'index.php?module=Users&action=Logout'),
	'submenu' => ''
	);
}
?>