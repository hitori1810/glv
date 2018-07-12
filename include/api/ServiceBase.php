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


require_once('include/api/SugarApiException.php');
require_once('include/api/SugarApi.php');

abstract class ServiceBase {
    public $user;
    public $platform = 'base';
    public $action = 'view';
    
    abstract public function execute();
    abstract protected function handleException(Exception $exception);

    protected function loadServiceDictionary($dictionaryName) {
        require_once("include/api/{$dictionaryName}.php");

        $dict = new $dictionaryName();

        // Load the dictionary, because if the dictionary isn't there it will generate it.
        $dict->loadDictionary();
        return $dict;
    }

    protected function loadApiClass($route) {
        if (!SugarAutoLoader::requireWithCustom($route['file']) ) {
            throw new SugarApiException('Missing API file.');
        }

        if ( ! class_exists($route['className']) ) {
            throw new SugarApiException('Missing API class.');
        }

        $apiClassName = $route['className'];
        $apiClass = new $apiClassName();

        return $apiClass;
    }

    /**
     * This function loads various items needed to setup the user's environment (such as app_strings and app_list_strings)
     */
    protected function loadUserEnvironment()
    {
        global $current_user, $current_language;
        $current_language = $GLOBALS['sugar_config']['default_language'];

        // If the session has a language set, use that
        if(!empty($_SESSION['authenticated_user_language'])) {
            $current_language = $_SESSION['authenticated_user_language'];
        }

        // get the currrent person object of interest
        $apiPerson = $GLOBALS['current_user'];
        if (isset($_SESSION['type']) && $_SESSION['type'] == 'support_portal') {
            $apiPerson = BeanFactory::getBean('Contacts', $_SESSION['contact_id']);
        }
        // If they have their own language set, use that instead
        if (isset($apiPerson->preferred_language) && !empty($apiPerson->preferred_language)) {
            $current_language = $apiPerson->preferred_language;
        }

        $GLOBALS['app_strings'] = return_application_language($current_language);
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($current_language);
    }

    /**
     * This function loads various items when the user is not logged in
     */
    protected function loadGuestEnvironment()
    {
        global $current_language;
        $current_language = $GLOBALS['sugar_config']['default_language'];

        $GLOBALS['app_strings'] = return_application_language($current_language);
        $GLOBALS['app_list_strings'] = return_app_list_strings_language($current_language);
    }

}