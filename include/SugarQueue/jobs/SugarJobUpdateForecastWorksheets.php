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


require_once('modules/SchedulersJobs/SchedulersJob.php');

/**
 * SugarJobUpdateForecastWorksheets
 *
 * Class to run a job which will create the ForecastWorksheet entries for the timeperiod and user
 *
 */
class SugarJobUpdateForecastWorksheets implements RunnableSchedulerJob {

    protected $job;

    /**
     * @param SchedulersJob $job
     */
    public function setJob(SchedulersJob $job)
    {
        $this->job = $job;
    }



    /**
     * @param string $data The job data set for this particular Scheduled Job instance
     * @return boolean true if the run succeeded; false otherwise
     */
    public function run($data)
    {
        $db = DBManagerFactory::getInstance();
        $args = json_decode(html_entity_decode($data), true);
        $this->job->runnable_ran = true;

        if(empty($args['timeperiod_id']) || empty($args['user_id'])) {
            $GLOBALS['log']->fatal("Unable to run job due to missing arguments");
            return false;
        }

        $tp = BeanFactory::getBean('TimePeriods', $args['timeperiod_id']);

        if(empty($tp->id)) {
            $GLOBALS['log']->fatal("Unable to load TimePeriod for id: " . $args['timeperiod_id']);
            return false;
        }

        // Get all the $current_users opportunities
        $sql = "SELECT id FROM opportunities WHERE assigned_user_id = '" . $args['user_id'] ."' and deleted = 0
            and (date_closed_timestamp >= " . $tp->start_date_timestamp . " and date_closed_timestamp <=  " . $tp->end_date_timestamp . ")";

        $result = $db->query($sql);

        while($row = $db->fetchByAssoc($result)) {
            /* @var $opportunity Opportunity */
            $opportunity = BeanFactory::getBean('Opportunities', $row['id']);

            /* @var $opp_wkst ForecastWorksheet */
            $opp_wkst = BeanFactory::getBean('ForecastWorksheets');
            $opp_wkst->saveRelatedOpportunity($opportunity, true);
        }

        $this->job->succeedJob();
        return true;
    }

}