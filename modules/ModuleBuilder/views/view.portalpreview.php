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




class ViewPortalPreview extends SugarView 
{
	function ViewPortalPreview()
	{
	    $this->options['show_title'] = false;
		$this->options['show_header'] = false;
        $this->options['show_footer'] = false;
        $this->options['show_javascript'] = false;
        $GLOBALS['log']->debug('in ViewPortalStyleView');
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
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        $smarty->assign('app', $GLOBALS['app_strings']);
        $smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SP_UPLOADSTYLE']);
        $smarty->assign('useCustomFile', file_exists('custom/portal/custom/style.css'));
        $smarty->assign('langHeader', get_language_header());
        echo $smarty->fetch('modules/ModuleBuilder/tpls/portalpreview.tpl');
	}
}