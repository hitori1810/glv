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
 * @param Opportunity $focus        The Current Opportunity we are working with
 */
function perform_save($focus){
    global $app_list_strings, $timedate, $current_language;
    $app_list_strings = return_app_list_strings_language($current_language);

    /* @var $admin Administration */
    $admin = BeanFactory::getBean('Administration');
    $settings = $admin->getConfigForModule('Forecasts');
    //Determine the default commit_stage based on the probability
    if ($settings['is_setup'] && empty($focus->commit_stage) && $focus->probability !== '')
    {
        //Retrieve Forecasts_category_ranges and json decode as an associative array
        $forecast_ranges = isset($settings['forecast_ranges']) ? $settings['forecast_ranges'] : '';
        $category_ranges = isset($settings[$forecast_ranges.'_ranges']) ? $settings[$forecast_ranges.'_ranges'] : array();
        foreach($category_ranges as $key=>$entry)
        {
            if($focus->probability >= $entry['min'] && $focus->probability <= $entry['max'])
            {
               $focus->commit_stage = $key;
               break;
            }
        }
    }

    if ($timedate->check_matching_format($focus->date_closed, TimeDate::DB_DATE_FORMAT)) {
        $date_close_db = $focus->date_closed;
    } else {
        $date_close_db = $timedate->to_db_date($focus->date_closed);
    }

    if(!empty($date_close_db)) {
        $date_close_datetime = $timedate->fromDbDate($date_close_db);
        $focus->date_closed_timestamp = $date_close_datetime->getTimestamp();
    }

    // if any of the case fields are NULL or an empty string set it to the amount from the main opportunity
    if(is_null($focus->best_case) || strval($focus->best_case) === "") {
        $focus->best_case = $focus->amount;
    }

    if(is_null($focus->worst_case) || strval($focus->worst_case) === "") {
        $focus->worst_case = $focus->amount;
    }

    // if sales stage was set to Closed Won set best and worst cases to amount
    $wonStages = $settings['sales_stage_won'];
    if (!empty($focus->sales_stage) && in_array($focus->sales_stage, $wonStages))
    {
        $focus->best_case = $focus->amount;
        $focus->worst_case = $focus->amount;
    }

    // Bug49495: amount may be a calculated field
    $focus->updateCalculatedFields();

    if($settings['is_setup'])
    {
        //We create a related product entry for any new opportunity so that we may forecast on products
        // create an empty product module
        /* @var $product Product */
        $product = BeanFactory::getBean('Products');
        if (empty($focus->id)) {
            $focus->id = create_guid();
            $focus->new_with_id = true;
        } else {
            //We still need to update the associated product with changes
            $product->retrieve_by_string_fields(array('opportunity_id'=>$focus->id));
        }

        //If $product is set then we need to copy values into it from the opportunity
        if(isset($product)) {
            $product->name = $focus->name;
            $product->best_case = $focus->best_case;
            $product->likely_case = $focus->amount;
            $product->worst_case = $focus->worst_case;
            $product->cost_price = $focus->amount;
            $product->quantity = 1;
            $product->currency_id = $focus->currency_id;
            $product->base_rate = $focus->base_rate;
            $product->probability = $focus->probability;
            $product->date_closed = $focus->date_closed;
            $product->date_closed_timestamp = $focus->date_closed_timestamp;
            $product->assigned_user_id = $focus->assigned_user_id;
            $product->opportunity_id = $focus->id;
            $product->commit_stage = $focus->commit_stage;
            $product->deleted = $focus->deleted;
            $product->save();
        }


        // save the a draft of each opportunity
        /* @var $worksheet ForecastWorksheet */
        $worksheet = BeanFactory::getBean('ForecastWorksheets');
        $worksheet->saveRelatedOpportunity($focus);
    }
}
