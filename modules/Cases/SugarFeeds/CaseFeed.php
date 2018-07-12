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


class CaseFeed extends FeedLogicBase {
    var $module = 'Cases';
    function pushFeed($bean, $event, $arguments){
        $text = '';
        if(empty($bean->fetched_row)){
            $text =  '{SugarFeed.CREATED_CASE} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name.'] {SugarFeed.FOR} [Accounts:' . $bean->account_id . ':' . $bean->account_name . ']: '. $bean->description;
        }else{
            if(!empty($bean->fetched_row['status'] ) && $bean->fetched_row['status'] != $bean->status && $bean->status == 'Closed'){
                $text =  '{SugarFeed.CLOSED_CASE} [' . $bean->module_dir . ':' . $bean->id . ':' . $bean->name. '] {SugarFeed.FOR} [Accounts:' . $bean->account_id . ':' . $bean->account_name . ']';
            }
        }

        if(!empty($text)){
			SugarFeed::pushFeed2($text, $bean);
        }

    }
}
?>
