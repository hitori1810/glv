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
 * OpportunitiesSeedData.php
 *
 * This is a class used for creating OpportunitiesSeedData.  We moved this code out from install/populateSeedData.php so
 * that we may better control and test creating default Opportunities.
 *
 */

class OpportunitiesSeedData {

    static private $_ranges;
/**
 * populateSeedData
 *
 * This is a static function to create Opportunities.
 *
 * @static
 * @param $records Integer value indicating the number of Opportunities to create
 * @param $app_list_strings Array of application language strings
 * @param $accounts Array of Account instances to randomly build data against
 * @param $timeperiods Array of Timeperiods to create timeperiod seed data off of
 * @param $products Array of Product instances to randomly build data against
 * @param $users Array of User instances to randomly build data against
 * @return array Array of Opportunities created
 */
public static function populateSeedData($records, $app_list_strings, $accounts
    ,$products, $users
)
{
    if(empty($accounts) || empty($app_list_strings) || (!is_int($records) || $records < 1)
       || empty($products) || empty($users)

    )
    {
        return array();
    }

    $opp_ids = array();
    $timedate = TimeDate::getInstance();
    
    while($records-- > 0)
    {
        $key = array_rand($accounts);
        $account = $accounts[$key];

        //Create new opportunities
        /* @var $opp Opportunity */
        $opp = BeanFactory::getBean('Opportunities');
        $opp->team_id = $account->team_id;
        $opp->team_set_id = $account->team_set_id;

        $opp->assigned_user_id = $account->assigned_user_id;
        $opp->assigned_user_name = $account->assigned_user_name;
        $opp->currency_id = '-99';
        $opp->base_rate = 1;
        $opp->name = substr($account->name." - 1000 units", 0, 50);
        $opp->lead_source = array_rand($app_list_strings['lead_source_dom']);
        $opp->sales_stage = array_rand($app_list_strings['sales_stage_dom']);

        // If the deal is already done, make the date closed occur in the past.
        $opp->date_closed = ($opp->sales_stage == Opportunity::STAGE_CLOSED_WON || $opp->sales_stage == Opportunity::STAGE_CLOSED_WON)
            ? self::createPastDate()
            : self::createDate();
        $opp->date_closed_timestamp = $timedate->fromDbDate($opp->date_closed)->getTimestamp();
        $opp->opportunity_type = array_rand($app_list_strings['opportunity_type_dom']);
        $amount = array("10000", "25000", "50000", "75000");
        $key = array_rand($amount);
        $opp->amount = $amount[$key];
        $probability = array("10", "40", "70", "90");
        $key = array_rand($probability);
        $opp->probability = $probability[$key];

        //Setup forecast seed data
        $opp->best_case = $opp->amount;
        $opp->worst_case = $opp->amount;
        $opp->commit_stage = $opp->probability >= 70 ? 'include' : 'exclude';

        $product = BeanFactory::getBean('Products');

        $opp->id = create_guid();
        $opp->new_with_id = true;

        /* @var $product Product */
        $product->name = $opp->name;
        $product->best_case = $opp->best_case;
        $product->likely_case = $opp->amount;
        $product->worst_case = $opp->worst_case;
        $product->cost_price = $opp->amount;
        $product->quantity = 1;
        $product->currency_id = $opp->currency_id;
        $product->base_rate = $opp->base_rate;
        $product->probability = $opp->probability;
        $product->date_closed = $opp->date_closed;
        $product->date_closed_timestamp = $opp->date_closed_timestamp;
        $product->assigned_user_id = $opp->assigned_user_id;
        $product->opportunity_id = $opp->id;
        $product->commit_stage = $opp->commit_stage;
        $product->save();

        $opp->save();

        // save a draft worksheet for the new forecasts stuff
        /* @var $worksheet ForecastWorksheet */
        $worksheet = BeanFactory::getBean('ForecastWorksheets');
        $worksheet->saveRelatedOpportunity($opp, true);

        // save a draft worksheet for the new forecasts stuff
        /* @var $product_worksheet ForecastWorksheet */
        $product_worksheet = BeanFactory::getBean('ForecastWorksheets');
        $product_worksheet->saveRelatedProduct($product, true);


        // Create a linking table entry to assign an account to the opportunity.
        $opp->set_relationship('accounts_opportunities', array('opportunity_id'=>$opp->id ,'account_id'=> $account->id), false);
        $opp_ids[] = $opp->id;
    }

    return $opp_ids;
}

    /**
     * @static creates range of probability for the months
     * @param int $total_months - total count of months
     * @return mixed
     */
    private static function getRanges($total_months = 12)
    {
        if ( self::$_ranges === null )
        {
            self::$_ranges = array();
            for ($i = $total_months; $i >= 0; $i--)
            {
                // define priority for month,
                self::$_ranges[$total_months-$i] = ( $total_months-$i > 6 )
                    ? self::$_ranges[$total_months-$i] = pow(6, 2) + $i
                    :  self::$_ranges[$total_months-$i] = pow($i, 2) + 1;
                // increase probability for current quarters
                self::$_ranges[$total_months-$i] = $total_months-$i == 0 ? self::$_ranges[$total_months-$i]*2.5 : self::$_ranges[$total_months-$i];
                self::$_ranges[$total_months-$i] = $total_months-$i == 1 ? self::$_ranges[$total_months-$i]*2 : self::$_ranges[$total_months-$i];
                self::$_ranges[$total_months-$i] = $total_months-$i == 2 ? self::$_ranges[$total_months-$i]*1.5 : self::$_ranges[$total_months-$i];
            }
        }
        return self::$_ranges;
    }

    /**
     * @static return month delta as random value using range of probability, 0 - current month, 1 next/previos month...
     * @param int $total_months - total count of months
     * @return int
     */
    public static function getMonthDeltaFromRange($total_months = 12)
    {
        $ranges = self::getRanges($total_months);
        asort($ranges,SORT_NUMERIC );
        $x = mt_rand (1, array_sum($ranges) );
        foreach ($ranges as $key => $y)
        {
            $x -= $y;
            if ( $x <= 0 )
            {
                break;
            }
        }
        return $key;
    }

    /**
     * @static generates date
     * @param null $monthDelta - offset from current date in months to create date, 0 - current month, 1 - next month
     * @return string
     */
    public static function createDate($monthDelta = null)
    {
        global $timedate;
        $monthDelta = $monthDelta === null ? self::getMonthDeltaFromRange() : $monthDelta;

        $now = $timedate->getNow(true);
        $now->modify("+$monthDelta month");
        // random day from now to end of month
        $now->setTime(0,0,0);
        $day = mt_rand($now->day, $now->days_in_month);
        return $timedate->asDbDate($now->get_day_begin($day));
    }

    /**
     * @static generate past date
     * @param null $monthDelta - offset from current date in months to create past date, 0 - current month, 1 - previous month
     * @return string
     */
    public static function createPastDate($monthDelta = null)
    {
        global $timedate;
        $monthDelta = $monthDelta === null ? self::getMonthDeltaFromRange() : $monthDelta;

        $now = $timedate->getNow(true);
        $now->modify("-$monthDelta month");

        if ( $monthDelta == 0 && $now->day == 1 ) {
            $now->modify("-1 day");
            $day = $now->day;
        }
        else
        {
            // random day from start of month to now
            $day =  mt_rand(1, $now->day);
        }
        $now->setTime(0,0,0); // always default it to midnight
        return $timedate->asDbDate($now->get_day_begin($day));
    }
}
