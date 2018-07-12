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

class ViewPortalConfig extends SugarView
{
	function ViewPortalSync()
	{
	    $GLOBALS['log']->debug('ViewPortalSync constructor');
	    parent::SugarView();
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

    /**
     * This function loads portal config vars from db and sets them for the view
     * @see SugarView::display() for more info
   	 */
	function display() 
	{
        $portalFields = array('appStatus'=>'offline', 'logoURL'=>
        '', 'maxQueryResult'=>'20', 'fieldsToDisplay'=>'5', 'maxSearchQueryResult'=>'5', 'defaultUser'=>'');
        $userList = get_user_array();
        $userList[''] = '';
        require_once("modules/MySettings/TabController.php");
        $controller = new TabController();
        $tabs = $controller->get_tabs_system();
        $disabledModulesFlag = false;
        $disabledModules = array();
        // TODO: maybe consolidate this with the portal module list in install/install_utils.php
        $maybeDisabledModules = array('Bugs','KBDocuments', 'Cases');
        foreach ($maybeDisabledModules as $moduleName) {
          if (in_array($moduleName, $tabs[1])) {
              $disabledModules[]=translate($moduleName);
              $disabledModulesFlag = true;
          }
        };

        $admin = new Administration();

        $portalConfig = $admin->getConfigForModule('portal','support', true);
        $smarty = new Sugar_Smarty();
        $smarty->assign('disabledDisplayModulesList', $disabledModules);
        $smarty->assign('disabledDisplayModules', $disabledModulesFlag);
        foreach ($portalFields as $fieldName=>$fieldDefault) {
            if (isset($portalConfig[$fieldName])) {
                $smarty->assign($fieldName, html_entity_decode($portalConfig[$fieldName]));
            } else {
                $smarty->assign($fieldName,$fieldDefault);
            }
        }
        $smarty->assign('userList', $userList);
        $smarty->assign('welcome', $GLOBALS['mod_strings']['LBL_SYNCP_WELCOME']);
        $smarty->assign('mod', $GLOBALS['mod_strings']);
        $smarty->assign('siteURL', $GLOBALS['sugar_config']['site_url']);
        if (isset($_REQUEST['label']))
        {
            $smarty->assign('label',$_REQUEST['label']);
        }
        $options = (!empty($GLOBALS['system_config']->settings['system_portal_url'])) ? $GLOBALS['system_config']->settings['system_portal_url'] : 'https://';
        $smarty->assign('options',$options);
        $ajax = new AjaxCompose();
        $ajax->addCrumb(translate('LBL_SUGARPORTAL', 'ModuleBuilder'), 'ModuleBuilder.main("sugarportal")');
        $ajax->addCrumb(ucwords(translate('LBL_PORTAL_CONFIGURE')), '');
        $ajax->addSection('center', translate('LBL_SUGARPORTAL', 'ModuleBuilder'), $smarty->fetch('modules/ModuleBuilder/tpls/portalconfig.tpl'));
		$GLOBALS['log']->debug($smarty->fetch('modules/ModuleBuilder/tpls/portalconfig.tpl'));
        echo $ajax->getJavascript();
	}
}
