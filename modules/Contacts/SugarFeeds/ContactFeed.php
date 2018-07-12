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


class ContactFeed extends FeedLogicBase{
    var $module = 'Contacts';
    function pushFeed($bean, $event, $arguments){
    	global $locale;
        $text = '';
        if(empty($bean->fetched_row)){
            $text = sprintf(
                '{SugarFeed.CREATED_CONTACT} [%s:%s:%s]',
                $bean->module_dir,
                $bean->id,
                $locale->formatName($bean)
            );
        }
		
        if(!empty($text)){ 
			SugarFeed::pushFeed2($text, $bean);
        }
		
    }
}
?>
