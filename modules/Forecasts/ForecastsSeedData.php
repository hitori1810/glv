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


/**
 * Handles populating seed data for Forecasts module
 */
class ForecastsSeedData {

    /**
     * @static
     *
     * @param Array $timeperiods Array of $timeperiod instances to build forecast data for
     */
    public static function populateSeedData($timeperiods)
    {

        require_once('modules/Forecasts/ForecastDirectReports.php');
        require_once('modules/Forecasts/Common.php');
        require_once('modules/Forecasts/ForecastManagerWorksheet.php');

        global $timedate, $current_user, $app_list_strings;

        $user = new User();
        $comm = new Common();
        $commit_order=$comm->get_forecast_commit_order();

        foreach ($timeperiods as $timeperiod_id=>$timeperiod) {

            foreach($commit_order as $commit_type_array) {
                //create forecast schedule for this timeperiod record and user.
                //create forecast schedule using this record becuse there will be one
                //direct entry per user, and some user will have a Rollup entry too.
                if ($commit_type_array[1] == 'Direct') {

                    //commit a direct forecast for this user and timeperiod.
                    $forecastopp = new ForecastOpportunities();
                    $forecastopp->current_timeperiod_id = $timeperiod_id;
                    $forecastopp->current_user_id = $commit_type_array[0];
                    $opp_summary_array= $forecastopp->get_opportunity_summary(false);

                    if($opp_summary_array['OPPORTUNITYCOUNT'] == 0)
                    {
                        continue;
                    }

                    $multiplier = mt_rand(1,6);

                    $quota = new Quota();
                    $quota->timeperiod_id=$timeperiod_id;
                    $quota->user_id = $commit_type_array[0];
                    $quota->quota_type='Direct';
                    $quota->currency_id=-99;
                    $ratio = array('.8','1','1.2','1.4');
                    $key = array_rand($ratio);
                    $quota->amount = ($opp_summary_array['TOTAL_AMOUNT'] * $ratio[$key]) / 2;
                    $quota->amount_base_currency = $quota->amount;
                    $quota->committed=1;
                    $quota->set_created_by = false;
                    if ($commit_type_array[0] == 'seed_sarah_id' || $commit_type_array[0] == 'seed_will_id' || $commit_type_array[0] == 'seed_jim_id')
                        $quota->created_by = 'seed_jim_id';
                    else if ($commit_type_array[0] == 'seed_sally_id' || $commit_type_array[0] == 'seed_max_id')
                        $quota->created_by = 'seed_sarah_id';
                    else if ($commit_type_array[0] == 'seed_chris_id')
                        $quota->created_by = 'seed_will_id';
                    else
                        $quota->created_by = $current_user->id;

                    $quota->save();

                    if(!$user->isManager($commit_type_array[0])) {
                        $quotaRollup = new Quota();
                        $quotaRollup->timeperiod_id=$timeperiod_id;
                        $quotaRollup->user_id = $commit_type_array[0];
                        $quotaRollup->quota_type='Rollup';
                        $quota->currency_id=-99;
                        $quotaRollup->amount = ($opp_summary_array['TOTAL_AMOUNT'] * $ratio[$key]) / 2;
                        $quotaRollup->amount_base_currency = $quotaRollup->amount;
                        $quotaRollup->committed=1;
                        $quotaRollup->set_created_by = false;
                        if ($commit_type_array[0] == 'seed_sarah_id' || $commit_type_array[0] == 'seed_will_id' || $commit_type_array[0] == 'seed_jim_id')
                            $quotaRollup->created_by = 'seed_jim_id';
                        else if ($commit_type_array[0] == 'seed_sally_id' || $commit_type_array[0] == 'seed_max_id')
                            $quotaRollup->created_by = 'seed_sarah_id';
                        else if ($commit_type_array[0] == 'seed_chris_id')
                            $quotaRollup->created_by = 'seed_will_id';
                         else
                             $quotaRollup->created_by = $current_user->id;

                        $quotaRollup->save();
                    }
                    
                    //Create a previous forecast to simulate change
                    $forecast2 = new Forecast();
                    $forecast2->timeperiod_id=$timeperiod_id;
                    $forecast2->user_id =  $commit_type_array[0];
                    $forecast2->opp_count= $opp_summary_array['OPPORTUNITYCOUNT'];
                    $forecast2->opp_weigh_value=$opp_summary_array['WEIGHTEDVALUENUMBER'];
                    $forecast2->best_case=$opp_summary_array['WEIGHTEDVALUENUMBER'];
                    $forecast2->worst_case=$opp_summary_array['WEIGHTEDVALUENUMBER'];
                    $forecast2->likely_case=$opp_summary_array['WEIGHTEDVALUENUMBER'];
                    $forecast2->forecast_type='Direct';
                    $forecast2->date_entered = $timedate->asDb($timedate->getNow()->modify("-1 day"));
                    $forecast2->save();
                                   
                    $forecast = BeanFactory::getBean('Forecasts');
                    $forecast->timeperiod_id=$timeperiod_id;
                    $forecast->user_id =  $commit_type_array[0];
                    $forecast->opp_count= $opp_summary_array['OPPORTUNITYCOUNT'];
                    $forecast->opp_weigh_value=$opp_summary_array['WEIGHTEDVALUENUMBER'];
                    $forecast->best_case=$opp_summary_array['WEIGHTEDVALUENUMBER'] + (($multiplier+1) * 100);
                    $forecast->worst_case=$opp_summary_array['WEIGHTEDVALUENUMBER'] + ($multiplier * 100);
                    $forecast->likely_case=$opp_summary_array['WEIGHTEDVALUENUMBER'] + (($multiplier-1) * 100);
                    $forecast->forecast_type='Direct';
                    $forecast->calculatePipelineData($opp_summary_array['CLOSED_AMOUNT'], $opp_summary_array['CLOSED_OPP_COUNT']);             
                    $forecast->date_entered = $timedate->asDb($timedate->getNow());
                    $forecast->save();
                    
                    self::createManagerWorksheet($commit_type_array[0], $forecast->toArray());
                    self::createManagerWorksheet($commit_type_array[0], $forecast2->toArray());

                } else {
                    //create where clause....
                    $where  = " users.deleted=0 ";
                    $where .= " AND (users.id = '$commit_type_array[0]'";
                    $where .= " or users.reports_to_id = '$commit_type_array[0]')";
                    //Get the forecasts created by the direct reports.
                    $DirReportsFocus = new ForecastDirectReports();
                    $DirReportsFocus->current_user_id=$commit_type_array[0];
                    $DirReportsFocus->current_timeperiod_id=$timeperiod_id;
                    $DirReportsFocus->compute_rollup_totals('',$where,false);

                    $multiplier = mt_rand(1,6);

                    $quota = new Quota();
                    $quota->timeperiod_id=$timeperiod_id;
                    $quota->user_id = $commit_type_array[0];
                    $quota->quota_type='Rollup';
                    $quota->currency_id=-99;
                    $quota->amount=$quota->getGroupQuota($timeperiod_id, false, $commit_type_array[0]);
                    if (!isset($quota->amount)) $quota->amount = $multiplier * 1000;
                    $quota->amount_base_currency=$quota->getGroupQuota($timeperiod_id, false, $commit_type_array[0]);
                    if (!isset($quota->amount_base_currency)) $quota->amount_base_currency = $quota->amount;
                    $quota->committed=1;
                    $quota->save();

                    $forecast = new Forecast();
                    $forecast->timeperiod_id=$timeperiod_id;
                    $forecast->user_id =  $commit_type_array[0];
                    $forecast->opp_count= $DirReportsFocus->total_opp_count;
                    $forecast->opp_weigh_value=$DirReportsFocus->total_weigh_value_number;
                    $forecast->likely_case=$DirReportsFocus->total_weigh_value_number + (($multiplier+1) * 100);
                    $forecast->best_case=$DirReportsFocus->total_weigh_value_number + ($multiplier * 100);
                    $forecast->worst_case=$DirReportsFocus->total_weigh_value_number + (($multiplier-1) * 100);
                    $forecast->forecast_type='Rollup';
                    $forecast->pipeline_opp_count = $DirReportsFocus->pipeline_opp_count;
                    $forecast->pipeline_amount = $DirReportsFocus->pipeline_amount;
                    $forecast->date_entered = $timedate->to_display_date_time(date($timedate->get_db_date_time_format(), time()), true);
                    $forecast->save();

                    self::createManagerWorksheet($commit_type_array[0], $forecast->toArray());

                }
            }
        }

        $admin = BeanFactory::getBean('Administration');
        $admin->saveSetting('Forecasts', 'is_setup', 1, 'base');

        // TODO-sfa - remove this once the ability to map buckets when they get changed is implemented (SFA-215).
        // this locks the forecasts ranges configs if the apps is installed with demo data and already has commits
        $admin->saveSetting('Forecasts', 'has_commits', 1, 'base');
    }

    protected static function createManagerWorksheet($user_id, $data)
    {
        /* @var $user User */
        $user = BeanFactory::getBean('Users', $user_id);
        $worksheet = new ForecastManagerWorksheet();
        $worksheet->reporteeForecastRollUp($user, $data);
    }
}
