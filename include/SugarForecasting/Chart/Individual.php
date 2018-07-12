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


require_once('include/SugarForecasting/Chart/AbstractChart.php');
require_once('include/SugarForecasting/Individual.php');
class SugarForecasting_Chart_Individual extends SugarForecasting_Chart_AbstractChart
{
    /**
     * Default Group By
     *
     * @var string
     */
    protected $group_by = "forecast";

    /**
     * Default Category, 1 = include in forecast, 0 = include everything
     *
     *
     * @var string
     */
    protected $ranges = array();

    /**
     * The labels to be used in the legend and how to parse the data
     *
     * @var array
     */
    protected $group_by_labels = array();

    /**
     * The value array that we build out and pass back
     *
     * @var array
     */
    protected $values = array();


    /**
     * The timeperiod instance used in the data processing
     *
     * @var TimePeriodInterface instance
     */
    protected $timePeriod;

    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args)
    {
        if (isset($args['ranges'])) {
            if(is_array($args['ranges'])) {
                $this->ranges = $args['ranges'];
            } else {
                $this->ranges = array($args['ranges']);
            }
        } else {
            $this->ranges = array();
        }

        if (isset($args['group_by']) && !empty($args['group_by']))
        {
            $this->group_by = strtolower($args['group_by']);
        }
        parent::__construct($args);

        // the individual chart doesn't use the dataset as an arary yet
        if (is_array($this->dataset)) {
            $this->dataset = array_shift($this->dataset);
        }
    }

    /**
     * Process the data into the current JIT Chart Format
     * @return array
     */
    public function process()
    {
        $this->getIndividualData();
        $this->parseCategory();
        $this->parseGroupBy();
        $this->convertTimeperiodToChartValues();
        return $this->formatDataForChart();
    }

    /**
     * Run the Individual Code and set the data in this object
     */
    protected function getIndividualData()
    {
        $rep_obj = new SugarForecasting_Individual($this->getArgs());
        $this->dataArray = $rep_obj->process();
    }

    /**
     * Parse any data out that doesn't match the category filter
     * TODO: need to support buckets, currently it doesn't
     */
    protected function parseCategory()
    {
        if (empty($this->ranges))
        {
            // display in chart all products (opps) from the worksheet
            return;
        }

        foreach ($this->dataArray as $key => $val)
        {
            if (in_array($val['commit_stage'], $this->ranges))
            {
                continue;
            }
            unset($this->dataArray[$key]);
        }

        reset($this->dataArray);
    }

    /**
     * Parse out the data that we are grouping by to find the labels that we need for the chart data
     *
     * Currently this only supports the following fields, forecasts, sales_stage and probability
     *
     * TODO: add support for fields to be set via a config or admin setting
     */
    protected function parseGroupBy()
    {
        if ($this->group_by == "sales_stage") {
            foreach ($this->dataArray as $data) {
                $this->group_by_labels[] = $data['sales_stage'];
            }

            $this->group_by_labels = array_unique($this->group_by_labels);
        } else if ($this->group_by == "probability") {
            foreach ($this->dataArray as $data) {
                $this->group_by_labels[] = $data['probability'] . "%";
            }
            $this->group_by_labels = array_unique($this->group_by_labels);
            ksort($this->group_by_labels);
        } else {
            // default to forecast, just on the off chance it's not set
            $this->group_by = "commit_stage";

            foreach ($this->dataArray as $data)
            {
                $this->group_by_labels[] = ucfirst($data['commit_stage']);
            }
            $this->group_by_labels = array_unique($this->group_by_labels);
        }

        $this->group_by_labels = array_values($this->group_by_labels);
    }

    /**
     * Format the data from the Manager Worksheet into a usable format for the charting engine
     *
     * @return array
     */
    protected function formatDataForChart()
    {
        // since we are converting everything to base currency, we need to get the base currency id for the formatting
        $currency_id = -99;

        $forecast_strings = $this->getModuleLanguage('Forecasts');
        $opp_strings = $this->getModuleLanguage('Opportunities');

        // default the label name to empty to prevent a notice from fireing
        $label_name = "";

        // load up the data into the chart
        foreach ($this->dataArray as $data) {

            // figure out where we need to put this in the array
            $chart_value_key = $this->timePeriod->getChartLabelsKey($data['date_closed']);
            //date($this->timePeriod->chart_label_format, strtotime($data['date_closed']));

            // figure out where this needs to be put in the values array
            $value_key = 0;
            // TODO support more fields.
            // TODO Support bucket Mode.
            switch($this->group_by) {
                case 'sales_stage':
                    $label_name = $opp_strings['LBL_SALES_STAGE'];
                    $value_key = array_search($data['sales_stage'], $this->group_by_labels);
                    break;
                case 'probability':
                    $label_name = $opp_strings['LBL_PROBABILITY'];
                    $value_key = array_search($data['probability'] . '%', $this->group_by_labels);
                    break;
                case 'commit_stage':
                    // break left out should fall though to the default
                default:
                    $label_name = $opp_strings['LBL_FORECAST'];
                    // if this is not empty it means we are only showing committed
                    $value_key = array_search(ucfirst($data['commit_stage']), $this->group_by_labels);
                    break;
            }

            // if the data set is likely we need to use the amount field from the data
            $dataset_key = $this->dataset . '_case';
            if($this->dataset == "likely") {
                $dataset_key = "amount";
            }

            // Bug 56330: if the dataset_key doesn't exist default to 0
            $dataset_value = (isset($data[$dataset_key])) ? SugarCurrency::convertAmountToBase($data[$dataset_key], $data['currency_id']) : 0;

            // put the values in to their proper locations and add to any that are already there
            $this->values[$chart_value_key]['values'][$value_key] += number_format($dataset_value, 2, '.', '');
            $this->values[$chart_value_key]['gvalue'] += number_format($dataset_value, 2, '.', '');
        }

        // get the quota for the current user
        $quota = $this->getUserQuota();

        $goal_value_total = 0;
        // final adjust of the data. this sets the labels and the total values for the goal markers
        foreach($this->values as $key => $value) {
            $goal_value_total += $value['gvalue'];
            $this->values[$key]['goalmarkervalue'][0] = number_format($quota, 2, '.', '');
            $this->values[$key]['goalmarkervalue'][1] = number_format($goal_value_total, 2, '.', '');
            $this->values[$key]['goalmarkervaluelabel'][0] = SugarCurrency::formatAmountUserLocale($quota, $currency_id);
            $this->values[$key]['goalmarkervaluelabel'][1] = SugarCurrency::formatAmountUserLocale($goal_value_total, $currency_id);

            $this->values[$key]['gvaluelabel'] = SugarCurrency::formatAmountUserLocale($value['gvalue'], $currency_id);

            // set the labels to be correct
            foreach($value['values'] as $val_key => $val) {
                $this->values[$key]['valuelabels'][$val_key] = SugarCurrency::formatAmountUserLocale($val, $currency_id);
            }
        }

        // figure out the label
        switch ($this->dataset) {
            case "best":
                $label = $forecast_strings['LB_FS_BEST_CASE'];
                break;
            case "worst":
                $label = $forecast_strings['LB_FS_WORST_CASE'];
                break;
            case 'likely':
            default:
                $label = $forecast_strings['LB_FS_LIKELY_CASE'];
                break;
        }

        // set the properties for the return array
        $properties = $this->defaultPropertiesArray;
        $properties['goal_marker_label'][0] = $forecast_strings['LBL_QUOTA'];
        $properties['goal_marker_label'][1] = $label;
        $properties['value_name'] = $label;
        $properties['label_name'] = $label_name;

        // create the chart data as the display engine expects it
        $chart = array(
            'properties' => array(
                '0' => $properties
            ),
            'color' => $this->defaultColorsArray,
            'label' => array_values($this->group_by_labels),
            'values' => array_values($this->values),
        );

        if ($this->group_by != 'sales_stage' && $this->group_by != 'probability') {
            $assignedColorsArray = array(
                'Include' => '#468c2b',
                'Exclude' => '#8c2b2b',
                'Upside' => '#2b5d8c',
            );

            foreach($this->group_by_labels as $key => $value) {
                $chart['color'][$key] = $assignedColorsArray[$value];
            }
        }

        return $chart;
    }

    /**
     * Return the quota for the current user and time period
     *
     * @return mixed
     */
    protected function getUserQuota()
    {
        /* @var $quota_bean Quota */
        $quota_bean = BeanFactory::getBean('Quotas');
        $quota = $quota_bean->getRollupQuota($this->getArg('timeperiod_id'), $this->getArg('user_id'));

        return SugarCurrency::convertAmountToBase($quota['amount'], $quota['currency_id']);
    }


    /**
     * Find the months for a given timeperiod and turn them into values arrays that can be used by the charting
     * display engine
     */
    protected function convertTimeperiodToChartValues()
    {
        $admin = BeanFactory::getBean('Administration');
        $config = $admin->getConfigForModule('Forecasts', 'base');
        $type = $config['timeperiod_leaf_interval'];

        /* @var $timeperiod TimePeriod */
        $this->timePeriod = TimePeriod::getByType($type, $this->getArg('timeperiod_id'));

        $num_of_items = count($this->group_by_labels);
        $empty_array = $this->defaultValueArray;
        $empty_array['values'] = array_pad(array(), $num_of_items, 0);
        $empty_array['valuelabels'] = array_pad(array(), $num_of_items, "0");
        $empty_array['links'] = array_pad(array(), $num_of_items, "");
        $empty_array['goalmarkervalue'] = array(0, 0);
        $empty_array['goalmarkervaluelabel'] = array("0", "0");

        $this->values = $this->timePeriod->getChartLabels($empty_array);
    }

}
