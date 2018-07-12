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

require_once('modules/SugarFeed/feedLogicBase.php');


class OppFeed extends FeedLogicBase {
    var $module = "Opportunities";
    function pushFeed($bean, $event, $arguments){
        $text = '';
        if(empty($bean->fetched_row)){
            $currency = new Currency();
            $currency->retrieve($bean->currency_id);
            $text = '{SugarFeed.CREATED_OPPORTUNITY} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name . '] {SugarFeed.WITH} [Accounts:' . $bean->account_id . ':' . $bean->account_name . '] {SugarFeed.FOR} ' . $currency->symbol. format_number($bean->amount);
        }else{
            if(!empty($bean->fetched_row['sales_stage']) && $bean->fetched_row['sales_stage'] != $bean->sales_stage && $bean->sales_stage == Opportunity::STAGE_CLOSED_WON){
                $currency = new Currency();
                $currency->retrieve($bean->currency_id);
                $text = '{SugarFeed.WON_OPPORTUNITY} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name . '] {SugarFeed.WITH} [Accounts:' . $bean->account_id . ':' . $bean->account_name . '] {SugarFeed.FOR} '. $currency->symbol . format_number($bean->amount);
            }
        }
		
        if(!empty($text)){ 
			SugarFeed::pushFeed2($text, $bean);
        }
		
    }
}
?>
