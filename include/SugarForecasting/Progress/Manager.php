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


require_once("include/SugarForecasting/Manager.php");
class SugarForecasting_Progress_Manager extends SugarForecasting_Manager
{
    /**
     * @var Opportunity
     */
    protected $opportunity;
    
    /**
     * @var pipelineCount
     */
     protected $pipelineCount;
     
     /**
     * @var pipelineRevenue
     */
     protected $pipelineRevenue;
     
     /**
       * @var closedAmount
       */
      protected $closedAmount;  
     
     
    /**
     * Class Constructor
     * @param array $args       Service Arguments
     */
    public function __construct($args)
    {
        parent::__construct($args);

        $this->loadConfigArgs();
    }

    /**
     * Get Settings from the Config Table.
     */
    public function loadConfigArgs() {
        /* @var $admin Administration */
        $admin = BeanFactory::getBean('Administration');
        $settings = $admin->getConfigForModule('Forecasts');

        // decode and json decode the settings from the administration to set the sales stages for closed won and closed lost
        $this->setArg('sales_stage_won', $settings["sales_stage_won"]);
        $this->setArg('sales_stage_lost', $settings["sales_stage_lost"]);
    }

    /**
     * Process the code to return the values that we need
     *
     * @return array
     */
    public function process()
    {
        return $this->getManagerProgress();
    }

    /**
     * Get the Numbers for the Manager View
     *
     * @return array
     */
    public function getManagerProgress()
    {
        //create opportunity to use to build queries
        $this->opportunity = new Opportunity();

        //get the quota data for user
        /* @var $quota Quota */
        $quota = BeanFactory::getBean('Quotas');

        //grab user that is the target of this call to check if it is the top level manager
        $targetedUser = BeanFactory::getBean("Users", $this->getArg('user_id'));

        //top level manager has to receive special treatment, but all others can be routed through quota function.
        if ($targetedUser->reports_to_id != "") {
            $quotaData = $quota->getRollupQuota($this->getArg('timeperiod_id'), $this->getArg('user_id'), true);
        } else {
            $quotaData["amount"] = $this->getQuotaTotalFromData();
        }

        //Get pipeline total and count;
        $this->getPipelineRevenue();

        //get data
        $progressData = array(
            "closed_amount"     => $this->closedAmount,
            "opportunities"     => $this->pipelineCount,
            "pipeline_revenue"  => $this->pipelineRevenue,
            "quota_amount"      => isset($quotaData["amount"]) ? ($quotaData["amount"]) : 0
        );

        return $progressData;
    }

    /**
     * utilizes some of the functions from the base manager class to load data and sum the quota figures
     * @return float
     */
    public function getQuotaTotalFromData()
    {
        try {
            $this->loadUsers();
        } catch (SugarForecasting_Exception $sfe) {
            return "";
        }

        $this->loadUsersQuota();
        $this->loadWorksheetAdjustedValues();

        $quota = 0;

        foreach ($this->dataArray as $data) {
            $quota += SugarCurrency::convertAmountToBase($data['quota'], $data['currency_id']);
        }

        return $quota;
    }    

    /**
     * retrieves the amount of opportunities with count less the closed won/lost stages
     *
     */
    public function getPipelineRevenue()
    {

        $db = DBManagerFactory::getInstance();
        $amountSum = 0;
        $query = "";

        $user_id = $this->getArg('user_id');
        $timeperiod_id = $this->getArg('timeperiod_id');
        $excluded_sales_stages_won = $this->getArg('sales_stage_won');
        $excluded_sales_stages_lost = $this->getArg('sales_stage_lost');
        $repIds = User::getReporteeReps($user_id);
        $mgrIds = User::getReporteeManagers($user_id);
        $arrayLen = 0;

        //Note: this will all change in sugar7 to the filter API
        //set up outer part of the query
        $query = "select sum(amount) as amount, sum(recordcount) as recordcount, sum(closed) as closed from(";
        
        //build up two subquery strings so we can unify the sales stage loops
        //all manager opps 
        $queryMgrOpps = "SELECT " .
                            "sum(o.amount/o.base_rate) AS amount, count(*) as recordcount, 0 as closed " .
                        "FROM opportunities o " .
                        "INNER JOIN users u  " .
                            "ON o.assigned_user_id = u.id " .
                        "INNER JOIN timeperiods t " .
                            "ON t.id = {$db->quoted($timeperiod_id)} " . 
                        "WHERE " .
                            "o.assigned_user_id = {$db->quoted($user_id)} " .                            
                            "AND o.deleted = 0 " .
                            "AND t.start_date_timestamp <= o.date_closed_timestamp " . 
                            "AND t.end_date_timestamp >= o.date_closed_timestamp ";
        
        $queryClosedMgrOpps = "SELECT " .
                                  "0 as amount, 0 as recordcount, sum(o.amount/o.base_rate) AS closed " .
                              "FROM opportunities o " .
                              "INNER JOIN users u  " .
                                  "ON o.assigned_user_id = u.id " .
                              "INNER JOIN timeperiods t " .
                                  "ON t.id = {$db->quoted($timeperiod_id)} " . 
                              "WHERE " .
                                  "o.assigned_user_id = {$db->quoted($user_id)} " .                            
                                  "AND o.deleted = 0 " .
                                  "AND t.start_date_timestamp <= o.date_closed_timestamp " . 
                                  "AND t.end_date_timestamp >= o.date_closed_timestamp ";
                                         
        
        //only committed direct reportee (manager) opps
        $queryRepOpps = "";
        $arrayLen = count($mgrIds);
        for($index = 0; $index < $arrayLen; $index++) {
            $subQuery = "(select (pipeline_amount / base_rate) as amount, " .
                                  "pipeline_opp_count as recordcount, " .
                                  "(closed_amount / base_rate) as closed from forecasts " .
                         "where timeperiod_id = {$db->quoted($timeperiod_id)} " .
                            "and user_id = {$db->quoted($mgrIds[$index])} " .
                            "and forecast_type = 'Rollup' " .
                         "order by date_entered desc ";
            $queryRepOpps .= $db->limitQuery($subQuery, 0, 1, false, "", false);
            $queryRepOpps .= ") ";
            if ($index+1 != $arrayLen) {
                $queryRepOpps .= "union all ";
            }
        }
        
        $arrayLen = count($repIds);
        
        //if we've started adding queries, we need a union to pick up the rest if we have more to add
        if ($queryRepOpps != "" && $arrayLen > 0) {
            $queryRepOpps .= " union all ";
        }
        //only committed direct reportee (manager) opps
        for($index = 0; $index < $arrayLen; $index++) {
            $subQuery = "(select (pipeline_amount / base_rate) as amount, " .
                                  "pipeline_opp_count as recordcount, " .
                                  "(closed_amount / base_rate) as closed from forecasts " .
                         "where timeperiod_id = {$db->quoted($timeperiod_id)} " .
                            "and user_id = {$db->quoted($repIds[$index])} " .
                            "and forecast_type = 'Direct' " .
                         "order by date_entered desc ";
            $queryRepOpps .= $db->limitQuery($subQuery, 0, 1, false, "", false);
            $queryRepOpps .= ") ";
            if ($index+1 != $arrayLen) {
                $queryRepOpps .= "union all ";
            }
        }
        
        //per requirements, exclude the sales stages won from amount, but find them for the closed total
        if (count($excluded_sales_stages_won)) {
            foreach ($excluded_sales_stages_won as $exclusion) {
                $queryMgrOpps .= "AND o.sales_stage != {$db->quoted($exclusion)} ";                
            }
            $queryClosedMgrOpps .= "AND o.sales_stage IN ('" . implode("', '", $excluded_sales_stages_won) . "') ";
        }

        //per the requirements, exclude the sales stages for closed lost
        if (count($excluded_sales_stages_lost)) {
            foreach ($excluded_sales_stages_lost as $exclusion) {
                $queryMgrOpps .= "AND o.sales_stage != {$db->quoted($exclusion)} ";
            }
        }
        
        //Union the two together if we have two separate queries
        $query .= $queryMgrOpps . " union all " . $queryClosedMgrOpps;
        if ($queryRepOpps != "") {
            $query .= " union all " . $queryRepOpps;
         }
        //finally, finish up the outer query
        $query .= ") sums";
        
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        $this->pipelineRevenue = is_numeric($row["amount"]) ? $row["amount"] : 0;
        $this->pipelineCount = is_numeric($row["recordcount"]) ? $row["recordcount"] : 0;
        $this->closedAmount = is_numeric($row["closed"]) ? $row["closed"] : 0;
    }
}