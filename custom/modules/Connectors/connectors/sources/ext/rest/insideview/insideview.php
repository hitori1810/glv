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


require_once('include/connectors/sources/ext/rest/rest.php');
class ext_rest_insideview extends ext_rest {
	protected $_enable_in_wizard = false;
	protected $_enable_in_hover = false;
    protected $_enable_in_admin_properties = false;
    protected $_enable_in_admin_mapping = false;
    protected $_enable_in_admin_search = false;
	protected $_has_testing_enabled = false;

    protected $orgId;
    protected $orgName;
    protected $userId;
    public static $allowedModuleList;
    
    public function __construct() {
        
        global $app_list_strings;
        $this->allowedModuleList = array('Accounts' => $app_list_strings['moduleList']['Accounts'],
                                         'Contacts' => $app_list_strings['moduleList']['Contacts'],
                                         'Opportunities' => $app_list_strings['moduleList']['Opportunities'],
                                         'Leads' => $app_list_strings['moduleList']['Leads']);

        parent::__construct();
    }

    public function filterAllowedModules( $moduleList ) {
        // InsideView currently has no ability to talk to modules other than these four
        $outModuleList = array();
        foreach ( $moduleList as $module ) {
            if ( !in_array($module,$this->allowedModuleList) ) {
                continue;
            } else {
                $outModuleList[$module] = $module;
            }
        }
        return $outModuleList;
    }

    public function saveMappingHook($mapping) {

        $removeList = array();
        foreach ($this->allowedModuleList as $module_name=>$display_name) {
            $removeList[$module_name] = $module_name;
        }

        if ( is_array($mapping['beans']) ) {
            foreach($mapping['beans'] as $module => $ignore) {
                unset($removeList[$module]);
                
                check_logic_hook_file($module, 'after_ui_frame', array(1, $module. ' InsideView frame', 'modules/Connectors/connectors/sources/ext/rest/insideview/InsideViewLogicHook.php', 'InsideViewLogicHook', 'showFrame') );
            }
        }

        foreach ( $removeList as $module ) {
            remove_logic_hook($module, 'after_ui_frame', array(1, $module. ' InsideView frame', 'modules/Connectors/connectors/sources/ext/rest/insideview/InsideViewLogicHook.php', 'InsideViewLogicHook', 'showFrame') );
        }

        return parent::saveMappingHook($mapping);
    }

    

	public function getItem($args=array(), $module=null){}
	public function getList($args=array(), $module=null) {}


    public function ext_allowInsideView( $request ) {
        $GLOBALS['current_user']->setPreference('allowInsideView',1,0,'Connectors');
        return true;
    }
}
