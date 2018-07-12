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


require_once('clients/base/api/ModuleApi.php');

class ConfigModuleApi extends ModuleApi {

    public function registerApiRest()
    {
        //Extend with test method
        $parentApi= array (
            'config' => array(
                'reqType' => 'GET',
                'path' => array('<module>','config'),
                'pathVars' => array('module',''),
                'method' => 'config',
                'shortHelp' => 'forecasts config',
                'longHelp' => 'include/api/help/ConfigApi.html#config',
                'noLoginRequired' => true,
            ),
            'configCreate' => array(
                'reqType' => 'POST',
                'path' => array('<module>','config'),
                'pathVars' => array('module',''),
                'method' => 'configSave',
                'shortHelp' => 'create forecasts config',
                'longHelp' => 'include/api/help/ConfigApi.html#configCreate',
            ),
            'configUpdate' => array(
                'reqType' => 'PUT',
                'path' => array('<module>','config'),
                'pathVars' => array('module',''),
                'method' => 'configSave',
                'shortHelp' => 'Update forecasts config',
                'longHelp' => 'include/api/help/ConfigApi.html#configUpdate',
            ),
        );
        return $parentApi;
    }

    /**
     * Returns the config settings for the given module
     * @param $api
     * @param $args 'module' is required, 'platform' is optional and defaults to 'base'
     */
    public function config($api, $args) {
        $this->requireArgs($args,array('module'));
        $adminBean = BeanFactory::getBean("Administration");

        $platform = (isset($args['platform']) && !empty($args['platform']))?$args['platform']:'base';

        if (!empty($args['module'])) {
            return$adminBean->getConfigForModule($args['module'], $platform);
        }
        return;
    }

    /**
     * Save function for the config settings for a given module.
     * @param $api
     * @param $args 'module' is required, 'platform' is optional and defaults to 'base'
     */
    public function configSave($api, $args) {
        $this->requireArgs($args,array('module'));

        $module = $args['module'];
        $platform = (isset($args['platform']) && !empty($args['platform']))?$args['platform']:'base';

        // these are not part of the config values, so unset
        unset($args['module']);
        unset($args['platform']);
        unset($args['__sugar_url']);

        //acl check, only allow if they are module admin
        if(!$this->hasAccess($module)) {
            throw new SugarApiExceptionNotAuthorized("Current User not authorized to change ".$module." configuration settings");
        }

        $admin = BeanFactory::getBean('Administration');

        foreach ($args as $name => $value) {
            if(is_array($value)) {
                $admin->saveSetting($module, $name, json_encode($value), $platform);
            } else {
                $admin->saveSetting($module, $name, $value, $platform);
            }
        }

        MetaDataManager::clearAPICache(false);

        return $admin->getConfigForModule($module, $platform);
    }


    public function hasAccess($module) {
        global $current_user;
        return $current_user->isAdminForModule($module);
    }

}
