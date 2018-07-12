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

 * Description:  TODO To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

global $mod_strings;
global $current_user;

if(ACLController::checkAccess('KBDocuments', 'edit', true))$module_menu[]=Array("index.php?module=KBDocuments&action=EditView&return_module=KBDocuments&return_action=DetailView", $mod_strings['LNK_NEW_ARTICLE'],"CreateKBArticle");
//if(ACLController::checkAccess('KBDocuments', 'list', true))$module_menu[]=Array("index.php?module=KBDocuments&action=index", $mod_strings['LNK_KBDOCUMENT_LIST'],"Documents");
if(ACLController::checkAccess('KBDocuments', 'edit', true)){
	
	$admin = new Administration();
	$admin->retrieveSettings();
	$user_merge = $current_user->getPreference('mailmerge_on');
	if ($user_merge == 'on' && isset($admin->settings['system_mailmerge_on']) && $admin->settings['system_mailmerge_on']){
		$module_menu[]=Array("index.php?module=MailMerge&action=index&reset=true", $mod_strings['LNK_NEW_MAIL_MERGE'],"Documents");
	}
}
if(ACLController::checkAccess('KBDocuments', 'list', true))$module_menu[]=Array("index.php?module=KBDocuments&action=SearchHome", $mod_strings['LBL_LIST_ARTICLES'],"KBArticle");
if($current_user->isAdminForModule('KBDocuments')){
	if(ACLController::checkAccess('KBDocuments', 'list', true))$module_menu[]=Array("index.php?module=KBDocuments&action=KBAdminView", $mod_strings['LBL_KNOWLEDGE_BASE_ADMIN_MENU'],"KB");
}
?>
