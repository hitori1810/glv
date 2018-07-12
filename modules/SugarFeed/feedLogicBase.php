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


class FeedLogicBase {
	var $module = '';
	
	function pushFeed($bean, $event, $arguments){
		
	}

	function installHook($file,$className){
		check_logic_hook_file($this->module, "before_save", array(1, $this->module . " push feed",  $file, $className, "pushFeed"));
	}

    function removeHook($file,$className){
		remove_logic_hook($this->module, "before_save", array(1, $this->module . " push feed",  $file, $className, "pushFeed"));        
    }
}
