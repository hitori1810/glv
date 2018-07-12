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

class ForecastsWorksheetApi extends ModuleApi
{

    public function registerApiRest()
    {
        //Extend with test method
        $parentApi = array(
            'forecastWorksheet' => array(
                'reqType' => 'GET',
                'path' => array('ForecastWorksheets'),
                'pathVars' => array('', ''),
                'method' => 'forecastWorksheet',
                'shortHelp' => 'Returns a collection of ForecastWorksheet models',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetApi.html#forecastWorksheet',
            ),
            'forecastWorksheetSave' => array(
                'reqType' => 'PUT',
                'path' => array('ForecastWorksheets', '?'),
                'pathVars' => array('module', 'record'),
                'method' => 'forecastWorksheetSave',
                'shortHelp' => 'Updates a ForecastWorksheet model',
                'longHelp' => 'include/api/html/modules/Forecasts/ForecastWorksheetApi.html#forecastWorksheet',
            )
        );
        return $parentApi;
    }


    /**
     * This method handles the /ForecastsWorksheet REST endpoint and returns an Array of worksheet data Array entries
     *
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API
     * @return Array of worksheet data entries
     * @throws SugarApiExceptionNotAuthorized
     */
    public function forecastWorksheet($api, $args)
    {
        // Load up a seed bean
        require_once('modules/Forecasts/ForecastWorksheet.php');
        $seed = new ForecastWorksheet();

        if (!$seed->ACLAccess('list')) {
            throw new SugarApiExceptionNotAuthorized('No access to view records for module: ' . $args['module']);
        }

        global $current_user;

        $args['timeperiod_id'] = isset($args['timeperiod_id']) ? $args['timeperiod_id'] : TimePeriod::getCurrentId();
        $args['user_id'] = isset($args['user_id']) ? $args['user_id'] : $current_user->id;

        $obj = $this->getClass($args);
        return $obj->process();
    }

    /**
     * This method handles saving data for the /ForecastsWorksheet REST endpoint
     *
     * @param $api ServiceBase The API class of the request, used in cases where the API changes how the fields are pulled from the args array.
     * @param $args array The arguments array passed in from the API
     * @return Array of worksheet data entries
     * @throws SugarApiExceptionNotAuthorized
     */
    public function forecastWorksheetSave($api, $args)
    {
        $obj = $this->getClass($args);
        return $obj->save();
    }


    /**
     * @param $args
     * @return SugarForecasting_Individual
     */
    protected function getClass($args)
    {
        // base file and class name
        $file = 'include/SugarForecasting/Individual.php';
        $klass = 'SugarForecasting_Individual';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($args);

        return $obj;
    }
}
