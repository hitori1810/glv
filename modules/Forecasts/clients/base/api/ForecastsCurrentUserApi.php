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


require_once('modules/Users/User.php');
require_once('clients/base/api/CurrentUserApi.php');

class ForecastsCurrentUserApi extends CurrentUserApi {
    public function registerApiRest() {
        return array(
            'retrieve' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts','me'),
                'pathVars' => array(),
                'method' => 'retrieveCurrentUser',
                'shortHelp' => 'Returns current user',
                'longHelp' => 'include/api/html/me.html',
            ),
            'init' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts','init'),
                'pathVars' => array(),
                'method' => 'forecastsInitialization',
                'shortHelp' => 'Returns current user data',
                'longHelp' => 'include/api/html/init.html',
            ),
            'selecteUserObject' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'user', '?'),
                'pathVars' => array('', '', 'userId'),
                'method' => 'retrieveSelectedUser',
                'shortHelp' => 'Returns selectedUser object for given user',
                'longHelp' => 'include/api/html/user.html',
            ),
        );
    }

    /**
     * Retrieves the current user info
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function retrieveCurrentUser($api, $args) {
        global $current_user;

        $data = parent::retrieveCurrentUser($api, $args);

        // Add Forecasts-specific items to returned data
        $data['current_user']['isManager'] = User::isManager($current_user->id);
        $data['current_user']['showOpps'] = false;
        $data['current_user']['first_name'] = $current_user->first_name;
        $data['current_user']['last_name'] = $current_user->last_name;

        return $data;
    }

    public function forecastsInitialization($api, $args) {
        global $current_user, $app_list_strings;

        if(!SugarACL::checkAccess('Forecasts', 'access')) {
            throw new SugarApiExceptionNotAuthorized();
        }

        $returnInitData = array();
        $defaultSelections = array();

        $data = $this->retrieveCurrentUser($api, array());
        $selectedUser = $data["current_user"];

        $returnInitData["initData"]["selectedUser"] = $selectedUser;
        $defaultSelections["selectedUser"] = $selectedUser;

        // TODO:  These should probably get moved in with the config/admin settings, or by themselves since this file will probably going away.
        $id = TimePeriod::getCurrentId();
        if(!empty($id)) {
            $timePeriod = new TimePeriod();
            $timePeriod->retrieve($id);
            $defaultSelections["timeperiod_id"] = array(
                'id' => $id,
                'label' => $timePeriod->name
            );
        } else {
            $defaultSelections["timeperiod_id"]["id"] = '';
            $defaultSelections["timeperiod_id"]["label"] = '';
        }

        // INVESTIGATE:  these need to be more dynamic and deal with potential customizations based on how filters are built in admin and/or studio
        $admin = BeanFactory::getBean("Administration");
        $forecastsSettings = $admin->getConfigForModule("Forecasts", "base");

        $returnInitData["initData"]['forecasts_setup'] = (isset($forecastsSettings['is_setup'])) ? $forecastsSettings['is_setup'] : 0;

        $defaultSelections["ranges"] = array("include");
        $defaultSelections["group_by"] = 'forecast';
        $defaultSelections["dataset"] = 'likely';

        // push in defaultSelections
        $returnInitData["defaultSelections"] = $defaultSelections;

        return $returnInitData;
    }


    /**
     * Retrieves a "selecteUser" object for a given user id
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function retrieveSelectedUser($api, $args) {
        global $locale;
        $uid = $args['userId'];
        $user = BeanFactory::getBean('Users', $uid);
        $data = array();
        $data['id'] = $user->id;
        $data['user_name'] = $user->user_name;
        $data['full_name'] = $locale->formatName($user);
        $data['first_name'] = $user->first_name;
        $data['last_name'] = $user->last_name;
        $data['isManager'] = User::isManager($user->id);
        return $data;
    }
}
