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


require_once('include/SugarForecasting/AbstractForecast.php');
class SugarForecasting_Committed extends SugarForecasting_AbstractForecast implements SugarForecasting_ForecastSaveInterface
{
    /**
     * Run all the tasks we need to process get the data back
     *
     * @return array|string
     */
    public function process()
    {
        $this->loadCommitted();

        return array_values($this->dataArray);
    }

    /**
     * Load the Committed Values for someones forecast
     *
     * @return void
     */
    protected function loadCommitted()
    {
        $db = DBManagerFactory::getInstance();

        $args = $this->getArgs();

        $where = "forecasts.user_id = '{$args['user_id']}' AND forecasts.forecast_type='{$args['forecast_type']}' AND forecasts.timeperiod_id = '{$args['timeperiod_id']}'";

        $order_by = 'forecasts.date_modified DESC';
        if (isset($args['order_by'])) {
            $order_by = clean_string($args['order_by']);
        }

        $bean = BeanFactory::getBean('Forecasts');
        $query = $bean->create_new_list_query($order_by, $where, array(), array(), $args['include_deleted']);
        $results = $db->query($query);

        $forecasts = array();
        while (($row = $db->fetchByAssoc($results))) {
            $row['date_entered'] = $this->convertDateTimeToISO($db->fromConvert($row['date_entered'],'datetime'));
            $row['date_modified'] = $this->convertDateTimeToISO($db->fromConvert($row['date_modified'],'datetime'));
            $forecasts[] = $row;
        }

        $this->dataArray = $forecasts;
    }

    /**
     * Save any committed values
     *
     * @return array|mixed
     */
    public function save()
    {
        global $current_user;

        $args = $this->getArgs();
        $db = DBManagerFactory::getInstance();
        
        $args['opp_count'] = !isset($args['opp_count']) ? 0 : $args['opp_count'];
        $args['closed_amount'] = !isset($args['closed_amount']) ? 0 : $args['closed_amount'];
        $args['closed_count'] = !isset($args['closed_count']) ? 0 : $args['closed_count'];
        $args['lost_amount'] = !isset($args['lost_amount']) ? 0 : $args['lost_amount'];
        $args['pipeline_opp_count'] = !isset($args['pipeline_opp_count']) ? 0 : $args['pipeline_opp_count'];
        $args['pipeline_amount'] = !isset($args['pipeline_amount']) ? 0 : $args['pipeline_amount'];

        /* @var $forecast Forecast */
        $forecast = BeanFactory::getBean('Forecasts');
        $forecast->user_id = $current_user->id;
        $forecast->timeperiod_id = $args['timeperiod_id'];
        $forecast->best_case = $args['best_case'];
        $forecast->likely_case = $args['likely_case'];
        $forecast->worst_case = $args['worst_case'];
        $forecast->forecast_type = $args['forecast_type'];
        $forecast->opp_count = $args['opp_count'];
        $forecast->currency_id = $args['currency_id'];
        $forecast->base_rate = $args['base_rate'];
        
        //If we are committing a rep forecast, calculate things.  Otherwise, for a manager, just use what is passed in.
        if ($args['commit_type'] == 'sales_rep') {
            $forecast->calculatePipelineData(($args['closed_amount']), ($args['closed_count']));
        } else {
            $forecast->pipeline_opp_count = $args['pipeline_opp_count'];
            $forecast->pipeline_amount = $args['pipeline_amount'];
            $forecast->closed_amount = $args['closed_amount'];
        } 
       
        if ($args['amount'] != 0 && $args['opp_count'] != 0) {
            $forecast->opp_weigh_value = $args['amount'] / $args['opp_count'];
        }

        $forecast->save();

        // roll up the committed forecast to that person manager view
        /* @var $mgr_worksheet ForecastManagerWorksheet */
        $mgr_worksheet = BeanFactory::getBean('ForecastManagerWorksheets');
        $mgr_worksheet->reporteeForecastRollUp($current_user, $args);

        //If there are any new worksheet entries that need created, do that here.
        foreach($args["worksheetData"]["new"] as $sheet)
        {
            //Update the Worksheet bean
            $worksheet  = BeanFactory::getBean("Worksheet");
            $worksheet->timeperiod_id = $args["timeperiod_id"];
            $worksheet->user_id = $current_user->id;
            $worksheet->best_case = $sheet["best_case"];
            $worksheet->likely_case = $sheet["likely_case"];
            $worksheet->worst_case = $sheet["worst_case"];
            $worksheet->op_probability = $sheet["probability"];
            $worksheet->commit_stage = $sheet["commit_stage"];
            $worksheet->forecast_type = "Direct";
            $worksheet->related_forecast_type = "Product";
            $worksheet->related_id = $sheet["product_id"];
            $worksheet->currency_id = $args["currency_id"];
            $worksheet->base_rate = $args["base_rate"];
            $worksheet->version = 1;
            $worksheet->save();
        }
        
        //Now we need to update any existing sheets using an ANSI standard update join
        //that should work across all DBs
        $worksheetIds = array();
        foreach($args["worksheetData"]["current"] as $sheet)
        {
            $worksheetIds[] = $sheet["worksheet_id"];
        }
        
        if (count($worksheetIds) > 0)
        {
            $sql = "update worksheet " .
                       "set best_case =     (" .
                                               "select p.best_case " .
                                               "from products p " .
                                               "where p.id = related_id" .
                                           "), " .
                           "likely_case = (" .
                                               "select p.likely_case " .
                                               "from products p " .
                                               "where p.id = related_id" .
                                           "), " .
                           "worst_case = (" .
                                               "select p.worst_case " .
                                               "from products p " .
                                               "where p.id = related_id" .
                                           "), " .
                           "op_probability = (" .
                                                   "select p.probability " .
                                                   "from products p " .
                                                   "where p.id = related_id" .
                                               "), " .
                           "commit_stage = (" .
                                               "select p.commit_stage " .
                                               "from products p " .
                                               "where p.id = related_id" .
                                             "), " .
                           "version = 1, " .
                           "date_modified = '" . $GLOBALS["timedate"]->nowDb() . "', " .
                           "modified_user_id = '" . $current_user->id . "' " .
                   "where exists (" .
                                   "select * " .
                                   "from products p " .
                                   "where p.id = related_id" .
                                 ") " .
                "and id in ('" . implode("', '", $worksheetIds) . "')";
                                    
            $db->query($sql, true);                      
        }

        // ForecastWorksheets Table Commit Version
        /* @var $tp TimePeriod */
        //$tp = BeanFactory::getBean('TimePeriods', $args['timeperiod_id']);

        global $current_user;

        $data = array(
            'user_id' => $current_user->id,
            'timeperiod_id' => $args['timeperiod_id']
        );

        $timedate = TimeDate::getInstance();
        $job = BeanFactory::getBean('SchedulersJobs');
        $job->execute_time = $timedate->nowDb();
        $job->name = "Update ForecastWorksheets";
        $job->status = SchedulersJob::JOB_STATUS_QUEUED;
        $job->target = "class::SugarJobUpdateForecastWorksheets";
        $job->data = json_encode($data);
        $job->retry_count = 0;
        $job->assigned_user_id = $current_user->id;
        $job->save();

        $mgr_worksheet->commitManagerForecast($current_user, $args['timeperiod_id']);

        //TODO-sfa remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');
        if (!isset($settings['has_commits']) || !$settings['has_commits']) {
            $admin->saveSetting('Forecasts', 'has_commits', true, 'base');
        }

        $forecast->date_entered = $this->convertDateTimeToISO($db->fromConvert($forecast->date_entered, 'datetime'));
        $forecast->date_modified = $this->convertDateTimeToISO($db->fromConvert($forecast->date_modified, 'datetime'));

        return $forecast->toArray(true);
    }
}