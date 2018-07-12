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


class ViewAdminsettings extends SugarView 
{	
    /**
	 * @see SugarView::_getModuleTab()
	 */
	protected function _getModuleTab()
    {
        return 'Administration';
    }
 	
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
    	   $mod_strings['LBL_MODULE_NAME'],
    	   );
    }
    
 	/** 
     * @see SugarView::display()
     */
 	public function display()
    {
        global $mod_strings, $app_strings;
        
        $admin = new Administration();
        $admin->retrieveSettings();
        
        // Handle posts
        if ( !empty($_REQUEST['process']) ) {
            // Check the cleanup logic hook, make sure it is still there
            check_logic_hook_file('Users','after_login', array(1, 'SugarFeed old feed entry remover', 'modules/SugarFeed/SugarFeedFlush.php', 'SugarFeedFlush', 'flushStaleEntries'));
        
            // We have data posted
            if ( $_REQUEST['process'] == 'true' ) {
                // They want us to process it, the false will just fall outside of this statement
                if ( $_REQUEST['feed_enable'] == '1' ) {
                    // The feed is enabled, pay attention to what categories should be enabled or disabled
        
                    $db = DBManagerFactory::getInstance();
                    $ret = $db->query("SELECT * FROM config WHERE category = 'sugarfeed' AND name LIKE 'module_%'");
                    $current_modules = array();
                    while ( $row = $db->fetchByAssoc($ret) ) {
                        $current_modules[$row['name']] = $row['value'];
                    }
                    
                    $active_modules = $_REQUEST['modules'];
                    if ( ! is_array($active_modules) ) { $active_modules = array(); }
                    
                    foreach ( $active_modules as $name => $is_active ) {
                        $module = substr($name,7);
                        
                        if ( $is_active == '1' ) {
                            // They are activating something that was disabled before
                            SugarFeed::activateModuleFeed($module);
                        } else {
                            // They are disabling something that was active before
                            SugarFeed::disableModuleFeed($module);
                        }
                    }
                    
                    $admin->saveSetting('sugarfeed','enabled','1');
                } else {
                    $admin->saveSetting('sugarfeed','enabled','0');
                    // Now we need to remove all of the logic hooks, so they don't continue to run
                    // We also need to leave the database alone, so they can enable/disable modules with the system disabled
                    $modulesWithFeeds = SugarFeed::getAllFeedModules();
                    
                    foreach ( $modulesWithFeeds as $currFeedModule ) {
                        SugarFeed::disableModuleFeed($currFeedModule,FALSE);
                    }
                }
        
                $admin->retrieveSettings(FALSE,TRUE);
                SugarFeed::flushBackendCache();
            } else if ( $_REQUEST['process'] == 'deleteRecords' ) {
                if ( ! isset($db) ) {
                    $db = DBManagerFactory::getInstance();
                }
                $db->query("UPDATE sugarfeed SET deleted = '1'");        
                echo(translate('LBL_RECORDS_DELETED','SugarFeed'));
            }
        
        
        
            if ( $_REQUEST['process'] == 'true' || $_REQUEST['process'] == 'false' ) {
                header('Location: index.php?module=Administration&action=index');
                return;
            }
        }
        
        $sugar_smarty	= new Sugar_Smarty();
        $sugar_smarty->assign('mod', $mod_strings);
        $sugar_smarty->assign('app', $app_strings);
        
        if ( isset($admin->settings['sugarfeed_enabled']) && $admin->settings['sugarfeed_enabled'] == '1' ) {
            $sugar_smarty->assign('enabled_checkbox','checked');
        }
        
        $possible_feeds = SugarFeed::getAllFeedModules();
        $module_list = array();
        $userFeedEnabled = 0;
        foreach ( $possible_feeds as $module ) {
            $currModule = array();
            if ( isset($admin->settings['sugarfeed_module_'.$module]) && $admin->settings['sugarfeed_module_'.$module] == '1' ) {
                $currModule['enabled'] = 1;
            } else {
                $currModule['enabled'] = 0;
            }
        
            $currModule['module'] = $module;
            if ( $module == 'UserFeed' ) {
                // Fake module, need to handle specially
                $userFeedEnabled = $currModule['enabled'];
                continue;
            } else {
                $currModule['label'] = $GLOBALS['app_list_strings']['moduleList'][$module];
            }
        
            $module_list[] = $currModule;
        }
        $sugar_smarty->assign('module_list',$module_list);
        $sugar_smarty->assign('user_feed_enabled',$userFeedEnabled);
        
        echo getClassicModuleTitle(
                "Administration", 
                array(
                    "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
                   $mod_strings['LBL_MODULE_NAME'],
                   ), 
                false
                );
        $sugar_smarty->display('modules/SugarFeed/tpls/AdminSettings.tpl');
    }
}

