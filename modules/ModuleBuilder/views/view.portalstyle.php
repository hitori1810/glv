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


require_once('modules/ModuleBuilder/MB/AjaxCompose.php');

class ViewPortalStyle extends SugarView 
{
	function ViewPortalStyle()
	{
	    $GLOBALS['log']->debug('in ViewPortalStyle');
	}

	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   translate('LBL_MODULE_NAME','Administration'),
    	   ModuleBuilderController::getModuleTitle(),
    	   );
    }

	// DO NOT REMOVE - overrides parent ViewEdit preDisplay() which attempts to load a bean for a non-existent module
	function preDisplay() 
	{
	}

	function display() 
	{
        $smarty = new Sugar_Smarty();
        //$smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SP_UPLOADSTYLE']);
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        if (isset($_REQUEST['label']))
        {
        	$GLOBALS['log']->debug('ViewPortalStyle->display(): label = '.$_REQUEST['label']);
            $smarty->assign('label',$_REQUEST['label']);
        }
        $ajax = new AjaxCompose();
        $ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(translate('LBL_UP_STYLE_SHEET', 'ModuleBuilder'), 'ModuleBuilder.getContent("module=ModuleBuilder&action=portalstyle")');
        $ajax->addSection('center', translate('LBL_UP_STYLE_SHEET', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/portalstyle.tpl'));
		$GLOBALS['log']->debug('ViewPortalStyle->display(): '.$ajax->getJavascript());
		echo $ajax->getJavascript();
	}
}
