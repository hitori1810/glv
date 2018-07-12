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


class LeadFeed extends FeedLogicBase {
    var $module = 'Leads';
    function pushFeed($bean, $event, $arguments){
        global $locale;

        $text = '';
        if(empty($bean->fetched_row)){
            $full_name = $locale->formatName($bean);

            $text =  '{SugarFeed.CREATED_LEAD} [' . $bean->module_dir . ':' . $bean->id . ':' . $full_name . ']';
        }else{
            if(!empty($bean->fetched_row['status'] ) && $bean->fetched_row['status'] != $bean->status && $bean->status == 'Converted'){
                // Repeated here so we don't format the name on "uninteresting" events
                $full_name = $locale->formatName($bean);

                $text =  '{SugarFeed.CONVERTED_LEAD} [' . $bean->module_dir . ':' . $bean->id . ':' . $full_name . ']';
            }
        }
		
        if(!empty($text)){ 
        	SugarFeed::pushFeed2($text, $bean);
        }
		
    }
}
?>
