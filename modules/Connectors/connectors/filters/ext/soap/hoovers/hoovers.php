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


require_once('include/connectors/filters/default/filter.php');

class ext_soap_hoovers_filter extends default_filter {
	
public function getList($args, $module) {

	$list = $this->_component->getSource()->getList($args, $module);

	//If count was 0 and city, zip, state and/or country value was used, we try to improve searching		
	if(empty($list) && !empty($args['bal']['location']['city'])) {
	   $GLOBALS['log']->info("ext_soap_hoovers_filter, unset ['bal']['location']['city'] search term");
	   unset($args['bal']['location']['city']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	}		
	
	if(empty($list) && !empty($args['bal']['location']['postalCode'])) {
	   $GLOBALS['log']->info("ext_soap_hoovers_filter, unset ['bal']['location']['postalCode'] search term");
	   unset($args['bal']['location']['postalCode']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	}		
	
	if(empty($list) && !empty($args['bal']['location']['globalState'])) {
	   $GLOBALS['log']->info("ext_soap_hoovers_filter, unset ['bal']['location']['globalState'] search term");
	   unset($args['bal']['location']['globalState']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	}	
	
	if(empty($list) && !empty($args['bal']['location']['countryId'])) {
	   $GLOBALS['log']->info("ext_soap_hoovers_filter, unset ['bal']['location']['countryId'] search term");
	   unset($args['bal']['location']['countryId']);	
	   $list = $this->_component->getSource()->getList($args, $module);
	}

	//Sometimes Hoovers makes the mistake of returning the first entry that may not be what we want
	if(count($list) == 1 && !empty($args['bal']['specialtyCriteria']['companyName'])) {
	   if(preg_match('/^(.*?)([\,|\s]+.*?)$/', $args['bal']['specialtyCriteria']['companyName'], $matches)) {
	   	 $GLOBALS['log']->info("ext_soap_hoovers_filter, change companyName search term");
	   	 $args['bal']['specialtyCriteria']['companyName'] = $matches[1];
	     $list = $this->_component->getSource()->getList($args, $module);
	     if(!empty($list)) {
	        return $list;	
	     }
	   }
	}
	
	return $list;

}
	
}

?>