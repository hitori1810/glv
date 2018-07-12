<?php
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


require_once('include/SugarForecasting/Export/AbstractExport.php');
require_once('include/SugarForecasting/Manager.php');
class SugarForecasting_Export_Manager extends SugarForecasting_Export_AbstractExport
{
    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args)
    {
        parent::__construct($args);
    }


    public function process()
    {
        global $current_user;

        // base file and class name
        $file = 'include/SugarForecasting/Manager.php';
        $klass = 'SugarForecasting_Manager';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class

        /* @var $obj SugarForecasting_AbstractForecast */
        $obj = new $klass($this->args);
        $data = $obj->process();

        //We need to set the keys for the adjusted values to be the same as the vardefs' name so that we may
        //associate them with the appropriate types for formatting
        foreach($data as $key=>$row) {
            $data[$key]['best_case_adjusted'] = $row['best_adjusted'];
            $data[$key]['likely_case_adjusted'] = $row['likely_adjusted'];
            $data[$key]['worst_case_adjusted'] = $row['worst_adjusted'];
        }

        $fields_array = array(
            'quota'=>'quota',
            'name'=>'name'
        );

        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');

        if ($settings['show_worksheet_best'])
        {
            $fields_array['best_case'] = 'best_case';
            $fields_array['best_case_adjusted'] = 'best_case_adjusted';
        }

        if ($settings['show_worksheet_likely'])
        {
            $fields_array['likely_case'] = 'likely_case';
            $fields_array['likely_case_adjusted'] = 'likely_case_adjusted';
        }

        if ($settings['show_worksheet_worst'])
        {
            $fields_array['worst_case'] = 'worst_case';
            $fields_array['worst_case_adjusted'] = 'worst_case_adjusted';
        }

        $seed = BeanFactory::getBean('ForecastManagerWorksheets');

        return $this->getContent($data, $seed, $fields_array);
    }


    /**
     * getFilename
     *
     * @return string name of the filename to export contents into
     */
    public function getFilename()
    {
        return sprintf("%s_manager_forecast.csv", parent::getFilename());
    }

}
