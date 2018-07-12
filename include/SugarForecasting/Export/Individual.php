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
require_once('include/SugarForecasting/Individual.php');
class SugarForecasting_Export_Individual extends SugarForecasting_Export_AbstractExport
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
        // base file and class name
        $file = 'include/SugarForecasting/Individual.php';
        $klass = 'SugarForecasting_Individual';

        // check for a custom file exists
        SugarAutoLoader::requireWithCustom($file);
        $klass = SugarAutoLoader::customClass($klass);
        // create the class
        $obj = new $klass($this->args);
        $data = $obj->process();

        $fields_array = array(
            'date_closed'=>'date_closed',
            'sales_stage'=>'sales_stage',
            'name'=>'name',
            'commit_stage'=>'commit_stage',
            'probability'=>'probability',
        );

        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');

        if ($settings['show_worksheet_best'])
        {
            $fields_array['best_case'] = 'best_case';
        }

        if ($settings['show_worksheet_likely'])
        {
            $fields_array['likely_case'] = 'likely_case';
        }

        if ($settings['show_worksheet_worst'])
        {
            $fields_array['worst_case'] = 'worst_case';
        }

        $seed = BeanFactory::getBean('ForecastWorksheets');

        return $this->getContent($data, $seed, $fields_array);
    }


    /**
     * getFilename
     *
     * @return string name of the filename to export contents into
     */
    public function getFilename()
    {
        return sprintf("%s_rep_forecast.csv", parent::getFilename());
    }

}
