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
require_once('include/SugarForecasting/Manager.php');
class SugarForecasting_Chart_Manager extends SugarForecasting_Chart_AbstractChart
{
    /**
     * Constructor
     *
     * @param array $args
     */
    public function __construct($args)
    {
        $this->isManager = true;

        parent::__construct($args);

        if (!is_array($this->dataset)) {
            $this->dataset = array($this->dataset);
        }
    }

    /**
     * Process the data into the current JIT Chart Format
     * @return array
     */
    public function process()
    {
        $this->getManagerData();
        return $this->formatDataForChart();
    }

    /**
     * Run the Manager Code and set the data in this object
     */
    public function getManagerData()
    {
        $mgr_obj = new SugarForecasting_Manager($this->getArgs());
        $this->dataArray = $mgr_obj->process();
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

        // get the quota from the data
        $quota = $this->getRollupQuota();

        // sort the data so it's in the correct order
        usort($this->dataArray, array($this, 'sortChartColumns'));

        // loop variables
        $values = array();

        $dataset_sums = array();

        // load up the data into the chart
        foreach ($this->dataArray as $data) {
            $val = $this->defaultValueArray;

            $val['chart_id'] = md5($data['id']);
            $val['label'] = html_entity_decode($data['label'], ENT_QUOTES);
            $val['goalmarkervaluelabel'][] = SugarCurrency::formatAmountUserLocale($quota, $currency_id);
            $val['goalmarkervalue'][] = number_format($quota, 2, '.', '');
            $val['links'] = array();
            //$val['gvalue'] = number_format($data[$this->dataset . '_adjusted'], 2, '.', '');
            //$val['gvaluelabel'] = number_format($data[$this->dataset . '_adjusted'], 2, '.', '');


            foreach ($this->dataset as $dataset) {
                if (!isset($dataset_sums[$dataset])) {
                    $dataset_sums[$dataset] = 0;
                    $dataset_sums[$dataset . '_adjusted'] = 0;
                }

                // converts the amounts to base
                $data_case = SugarCurrency::convertAmountToBase($data[$dataset . '_case'], $data['currency_id']);
                $data_adjusted = SugarCurrency::convertAmountToBase($data[$dataset . '_adjusted'], $data['currency_id']);

                $dataset_sums[$dataset] += $data_case;
                $dataset_sums[$dataset . '_adjusted'] += $data_adjusted;

                // set the empty  links
                $val['links'][] = "";
                $val['links'][] = "";

                $val['values'][] = number_format($data_case, 2, '.', '');
                $val['values'][] = number_format($data_adjusted, 2, '.', '');
                $val['valuelabels'][] = SugarCurrency::formatAmountUserLocale($data_case, $currency_id);
                $val['valuelabels'][] = SugarCurrency::formatAmountUserLocale($data_adjusted, $currency_id);
                $val['goalmarkervalue'][] = number_format($dataset_sums[$dataset], 2, '.', '');
                $val['goalmarkervalue'][] = number_format($dataset_sums[$dataset . '_adjusted'], 2, '.', '');
                $val['goalmarkervaluelabel'][] = SugarCurrency::formatAmountUserLocale($dataset_sums[$dataset], $currency_id);
                $val['goalmarkervaluelabel'][] = SugarCurrency::formatAmountUserLocale($dataset_sums[$dataset . '_adjusted'], $currency_id);
            }
            $values[] = $val;
        }

        // fix the properties
        $properties = $this->defaultPropertiesArray;
        // remove the pareto lines
        $properties['goal_marker_label'][0] = $forecast_strings['LBL_QUOTA'];
        unset($properties['goal_marker_label'][1]);
        $properties['value_name'] = $forecast_strings['LBL_CHART_AMOUNT'];
        $properties['label_name'] = $forecast_strings['LBL_CHART_TYPE'];
        // add a second pareto line
        $properties['goal_marker_type'][] = "pareto";
        // set the pareto line colors
        $properties['goal_marker_color'][1] = $this->defaultColorsArray[0];
        $properties['goal_marker_color'][2] = $this->defaultColorsArray[1];

        // figure out the labels
        $labels = array();
        foreach ($this->dataset as $dataset) {
            switch ($dataset) {
                case "best":
                    $labels[] = $forecast_strings['LBL_BEST_CASE'];
                    $labels[] = $forecast_strings['LBL_BEST_CASE_VALUE'];
                    break;
                case "worst":
                    $labels[] = $forecast_strings['LBL_WORST_CASE'];
                    $labels[] = $forecast_strings['LBL_WORST_CASE_VALUE'];
                    break;
                case 'likely':
                default:
                    $labels[] = $forecast_strings['LBL_LIKELY_CASE'];
                    $labels[] = $forecast_strings['LBL_LIKELY_CASE_VALUE'];
                    break;
            }
        }

        // set the pareto labels
        $properties['goal_marker_label'] = array_merge($properties['goal_marker_label'], $labels);

        // create the chart array
        $chart = array(
            'properties' => array(
                '0' => $properties
            ),
            'color' => $this->defaultColorsArray,
            'label' => $labels,
            'values' => $values,
        );

        return $chart;
    }

    /**
     * Get the quota from the sum of all the rows in the dataset
     *
     * @return float
     */
    protected function getQuotaTotalFromData()
    {
        $quota = 0;

        foreach ($this->dataArray as $data) {
            $quota += SugarCurrency::convertAmountToBase($data['quota'], $data['currency_id']);
        }

        return $quota;
    }

    /**
     * Get the roll up quota for a manager from the quota table.  If one doesn't exist it
     * will call @see getQuotaTotalFromData to return the quota total from the worksheet dataset
     *
     * @return float
     */
    protected function getRollupQuota()
    {
        //get the quota data for user
        /* @var $quota Quota */
        $quota = BeanFactory::getBean('Quotas');

        //grab user that is the target of this call to check if it is the top level manager
        $targetedUser = BeanFactory::getBean("Users", $this->getArg('user_id'));

        if(!empty($targetedUser->reports_to_id)) {
            $quotaData = $quota->getRollupQuota($this->getArg('timeperiod_id'), $this->getArg('user_id'), true);
            return SugarCurrency::convertAmountToBase($quotaData["amount"], $quotaData['currency_id']);
        }
        // get the quota from the loaded data for a manager that has no manager
        return $this->getQuotaTotalFromData();


    }

    /**
     * Method for sorting the dataArray before we return it so that the tallest bar is always first and the
     * lowest bar is always last.
     *
     * @param array $a          The left side of the compare
     * @param array $b          The right side of the compare
     * @return int
     */
    protected function sortChartColumns($a, $b)
    {
        $sumA = 0;
        $sumB = 0;

        foreach($this->dataset as $dataset) {
            $sumA += SugarCurrency::convertAmountToBase($a[$dataset . '_adjusted'], $a['currency_id']);
            $sumB += SugarCurrency::convertAmountToBase($b[$dataset . '_adjusted'], $b['currency_id']);
        }

        if (intval($sumA) > intval($sumB)) {
            return -1;
        } else if (intval($sumA) < intval($sumB)) {
            return 1;
        } else {
            return 0;
        }
    }


}
