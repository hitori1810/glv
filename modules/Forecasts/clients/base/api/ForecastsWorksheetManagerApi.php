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
require_once('modules/Forecasts/clients/base/api/ForecastsChartApi.php');

class ForecastsWorksheetManagerApi extends ForecastsChartApi
{

    public function registerApiRest()
    {
        //Extend with test method
        $parentApi = array(
            'forecastManagerWorksheet' => array(
                'reqType' => 'GET',
                'path' => array('ForecastManagerWorksheets'),
                'pathVars' => array('', ''),
                'method' => 'forecastManagerWorksheet',
                'shortHelp' => 'Returns a collection of ForecastManagerWorksheet models',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetManagerApi.html#forecastWorksheetManager',
            ),
            'forecastManagerWorksheetSave' => array(
                'reqType' => 'PUT',
                'path' => array('ForecastManagerWorksheets', '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'forecastManagerWorksheetSave',
                'shortHelp' => 'Update a ForecastManagerWorksheet model',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetManagerApi.html#forecastWorksheetManagerSave',
            )
        );
        return $parentApi;
    }

    public function forecastManagerWorksheet($api, $args)
    {
        // Load up a seed bean
        require_once('modules/Forecasts/ForecastManagerWorksheet.php');
        $seed = new ForecastManagerWorksheet();

        if (!$seed->ACLAccess('list')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: ' . $args['module']);
        }

        $args['timeperiod_id'] = isset($args['timeperiod_id']) ? $args['timeperiod_id'] : TimePeriod::getCurrentId();

        $obj = $this->getClass($args);
        return $obj->process();
    }


    public function forecastManagerWorksheetSave($api, $args)
    {
        $obj = $this->getClass($args);
        return $obj->save();
    }

    /**
     * @param $args
     * @return SugarForecasting_Manager
     */
    protected function getClass($args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Manager.php';
        $klass = 'SugarForecasting_Manager';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);
        return $obj;
    }

}
