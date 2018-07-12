<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

class ForecastsCommittedApi extends ModuleApi
{

    public function registerApiRest()
    {
        $parentApi = parent::registerApiRest();
        $parentApi = array(
            'forecastsCommitted' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'committed'),
                'pathVars' => array('', ''),
                'method' => 'forecastsCommitted',
                'shortHelp' => 'A list of forecasts entries matching filter criteria',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetApi.html#forecastsCommitted',
            ),
            'forecastsCommittedMgrNeedsCommitted' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'committed', 'mgrNeedsCommitted'),
                'pathVars' => array('', ''),
                'method' => 'forecastsCommittedMgrNeedsCommitted',
                'shortHelp' => 'True or False if the manager worksheet commit is older than the rep worksheet committed date.',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetApi.html#forecastsCommittedDateCheck',
            ),
            'forecastsCommit' => array(
                'reqType' => 'POST',
                'path' => array('Forecasts', 'committed'),
                'pathVars' => array('', ''),
                'method' => 'forecastsCommit',
                'shortHelp' => 'Commit a forecast',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetApi.html#forecastsCommit',
            )
        );
        return $parentApi;
    }

    /**
     * forecastsCommitted
     *
     * @param $api
     * @param $args
     * @return array
     */
    public function forecastsCommitted($api, $args)
    {
        global $current_user, $mod_strings, $current_language;
        $mod_strings = return_module_language($current_language, 'Forecasts');

        $db = DBManagerFactory::getInstance();

        $user_id = $current_user->id;
        if (isset($args['user_id']) && $args['user_id'] != $current_user->id) {
            $user_id = $args['user_id'];
            if (!User::isManager($current_user->id)) {
                $GLOBALS['log']->error(string_format($mod_strings['LBL_ERROR_NOT_MANAGER'], array($current_user->id, $user_id)));
                return array();
            }
        }

        $args['user_id'] = $user_id;

        $args['forecast_type'] = (isset($args['forecast_type'])) ? $args['forecast_type'] : (User::isManager($user_id) ? 'Rollup' : 'Direct');
        $args['timeperiod_id'] = (isset($args['timeperiod_id'])) ? $args['timeperiod_id'] : TimePeriod::getCurrentId();
        $args['include_deleted'] = (isset($args['show_deleted']) && $args['show_deleted'] === true);

        $obj = $this->getClass($args);
        return $obj->process();
    }


    public function forecastsCommit($api, $args)
    {
        $obj = $this->getClass($args);
        return $obj->save();
    }

    /**
     * Get the Committed Class
     *
     * @param array $args
     * @return SugarForecasting_Committed
     */
    protected function getClass($args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Committed.php';
        $klass = 'SugarForecasting_Committed';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);

        return $obj;
    }
}
