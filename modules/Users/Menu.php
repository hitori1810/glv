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

    global $mod_strings, $app_strings;
    global $current_user, $sugar_config;

    $module_menu=Array();
    if($GLOBALS['current_user']->isAdminForModule('Users')
    )
    {

        $module_menu = Array(
            Array("index.php?module=Users&action=EditView&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_USER'],"CreateUsers"),
            Array("index.php?module=Users&action=EditView&usertype=group&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_GROUP_USER'],"CreateUsers")
        );

        /**
        * Create User Template
        * 
        * @author Thuan Nguyen 
        */
        $module_menu[] = array(
            'index.php?module=Users&action=EditView&usertype=template&return_module=Users&return_action=DetailView',
            isset($mod_strings['LNK_NEW_USER_TEMPLATES']) ? $mod_strings['LNK_NEW_USER_TEMPLATES'] : '',
            'CreateUsers'
        );
        //END: Create User Template
        
        //add by Trung Nguyen 2015.10.01
        $isSuperAdmin = ($current_user->id == 1);
        $isDeveloper = ($_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1');
        if($isSuperAdmin || $isDeveloper) {     //end
            if (isset($sugar_config['enable_web_services_user_creation']) && $sugar_config['enable_web_services_user_creation']) {
                $module_menu[] = Array("index.php?module=Users&action=EditView&usertype=portal&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_PORTAL_USER'],"CreateUsers");
            }
        }
        $module_menu[] = Array("index.php?module=Users&action=EditView&usertype=portal&return_module=Users&return_action=DetailView", $mod_strings['LNK_NEW_PORTAL_USER'],"CreateUsers");
        $module_menu[] = Array("index.php?module=Users&action=ListView&return_module=Users&return_action=DetailView", $mod_strings['LNK_USER_LIST'],"Users");

        $module_menu[] = Array("index.php?module=Users&action=reassignUserRecords", $mod_strings['LNK_REASSIGN_RECORDS'],"ReassignRecords");


        $module_menu[] = Array("index.php?module=Import&action=Step1&import_module=Users&return_module=Users&return_action=index", $mod_strings['LNK_IMPORT_USERS'],"Import", 'Contacts');
    }
    /*
    array_push($module_menu, Array("index.php?module=Users&action=EditTabs&return_module=Users&return_action=DetailView", $mod_strings['LNK_EDIT_TABS'],"Users"))
    */
?>
