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

class ForecastsFiltersApi extends ModuleApi
{

    public function registerApiRest()
    {
        $parentApi = parent::registerApiRest();
        //Extend with test method
        $parentApi = array(
            'timeperiod' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'timeperiod'),
                'pathVars' => array('', ''),
                'method' => 'timeperiod',
                'shortHelp' => 'forecast timeperiod',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastFiltersApi.html#timeperiod',
            ),
            'reportees' => array(
                'reqType' => 'GET',
                'path' => array('Forecasts', 'reportees', '?'),
                'pathVars' => array('', '', 'user_id'),
                'method' => 'getReportees',
                'shortHelp' => 'Gets reportees to a user by id',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastFiltersApi.html#reportees',
            )
        );
        return $parentApi;
    }

    /**
     * Return the dom of the current timeperiods.
     *
     * //TODO, move this logic to store the values in a custom language file that contains the timeperiods for the Forecast module
     *
     * @param array $api
     * @param array $args
     * @return array
     */
    public function timeperiod($api, $args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Filter/TimePeriodFilter.php';
        $klass = 'SugarForecasting_Filter_TimePeriodFilter';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        return $obj->process();
    }

    public function getReportees($api, $args)
    {
        $args['user_id'] = isset($args["user_id"]) ? $args["user_id"] : $GLOBALS["current_user"]->id;

        // base file and class name
        $file = 'include/SugarForecasting/ReportingUsers.php';
        $klass = 'SugarForecasting_ReportingUsers';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        return $obj->process();
    }

}
