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
 * WorksheetSeedData.php
 *
 * This is a class used for creating worksheet seed data.
 * It first retrieves all non fiscal year timeperiods in the database.  Then for each timeperiod
 * it finds the opportunities assigned to users in the system.  For each opportunity it creates
 * a worksheet entry for the user as well as a rollup worksheet calculation for the user's manager.
 *
 * Usage: WorksheetSeedData::populateSeedData()
 *
 */

class WorksheetSeedData {

/**
 * populateSeedData
 *
 * This is a static function to create the seed data
 * @param Array $timeperiods Array of $timeperiod instances to build forecast data for
 * @return Array of created worksheet ids
 *
 */
public static function populateSeedData($timeperiods = null)
{

require_once('modules/Forecasts/Common.php');
require_once('modules/TimePeriods/TimePeriod.php');
require_once('modules/Users/User.php');

$comm = new Common();
$comm->get_all_users();
$db = DBManagerFactory::getInstance();

$created_ids = array();

if ( empty($timeperiods) )
{
    //Get the previous, current and next timeperiods
    $timeperiods = array();
    $result = $GLOBALS['db']->query("SELECT id FROM timeperiods WHERE is_fiscal_year = 0");
    while(($row = $GLOBALS['db']->fetchByAssoc($result)) != null)
    {
        $timeperiods[$row['id']] = $row['id'];
    }
}

foreach ($timeperiods as $timeperiod_id=>$timeperiod)
{
    foreach($comm->all_users as $user_id => $reports_to)
    {
        $opps = self::getRelatedOpportunities($user_id, $timeperiod_id);
        $comm->current_user = $user_id;
		$isManager = User::isManager($user_id);
		
        if(!empty($opps))
        {
            
            $comm->my_managers = array();
            $comm->get_my_managers();

            $best = 0;
            $likely = 0;
            $worst = 0;

            foreach($opps as $opp_id)
            {
                /* @var $opp Opportunity */
                $opp = BeanFactory::getBean('Opportunities', $opp_id);

                $best += $opp->best_case;
                $likely += $opp->amount;
                $worst += $opp->worst_case;

                //This is a sales rep's worksheet entry
                $products = $opp->getProducts();
                foreach($products as $prod)
                {
                    $worksheet = new Worksheet();
                    $worksheet->user_id = $user_id;
                    $worksheet->timeperiod_id = $timeperiod_id;
                    $worksheet->forecast_type = 'Direct';
                    $worksheet->related_id = $prod->id;
                    $worksheet->related_forecast_type = 'Product';   //Set this to 'Product' to indicate a product line
                    $worksheet->best_case = $prod->best_case + 500;
                    $worksheet->likely_case = $prod->likely_case + 500;
                    $worksheet->worst_case = $prod->worst_case + 500;
                    $worksheet->commit_stage = $prod->commit_stage;
               		$worksheet->op_probability = $prod->probability;
                    $worksheet->save();
                    $created_ids[] = $worksheet->id;
                }
            }

            //this is the direct worksheet for the manager
            if($isManager)
            {
	            $worksheet = new Worksheet();
	            $worksheet->user_id = $user_id;
	            $worksheet->timeperiod_id = $timeperiod_id;
	            $worksheet->forecast_type = 'Rollup';
	            $worksheet->related_id = $user_id;
	            $worksheet->related_forecast_type = 'Direct';
	            $worksheet->best_case = $best;
	            $worksheet->likely_case = $likely;
	            $worksheet->worst_case = $worst;
	            $worksheet->save();
	            $created_ids[] = $worksheet->id;
            }

            //This is the rollup worksheet for the manager
            $increment = 500;
			
            foreach($comm->my_managers as $manager_id)
            {
                $worksheet = new Worksheet();
                $worksheet->user_id = $manager_id;
                $worksheet->timeperiod_id = $timeperiod_id;
                $worksheet->forecast_type = 'Rollup';
                $worksheet->related_id = $user_id;
                $worksheet->related_forecast_type = ($isManager)? 'Rollup':'Direct';
                $worksheet->best_case = $best + $increment;
                $worksheet->likely_case = $likely + $increment;
                $worksheet->worst_case = $worst + $increment;
                $worksheet->save();
                $created_ids[] = $worksheet->id;
                $increment += 100;
            }
        }
    }

}

return $created_ids;
}

/**
 * getRelatedOpportunities
 *
 * Returns the opportunities assigned to the user for the given timeperiod
 *
 * @param $user_id
 * @param $timeperiod_id
 *
 * @return array Array of opportunities assigned to the user for the given timeperiod
 */
public static function getRelatedOpportunities($user_id, $timeperiod_id)
{

    $db = DBManagerFactory::getInstance();
    $opps = array();

    $query = sprintf("SELECT o.id FROM opportunities o
LEFT JOIN timeperiods tp ON tp.start_date_timestamp <= o.date_closed_timestamp and tp.end_date_timestamp >= o.date_closed_timestamp
WHERE tp.id = '%s' AND o.assigned_user_id = '%s' AND o.deleted = 0 AND o.sales_stage != '%s'", $timeperiod_id, $user_id, Opportunity::STAGE_CLOSED_LOST);

    $result = $db->query($query, false, "Error fetching related opps for user");
    while (($row = $db->fetchByAssoc($result)) != null)
    {
        $opps[] = $row['id'];
    }
    return $opps;
}

}
