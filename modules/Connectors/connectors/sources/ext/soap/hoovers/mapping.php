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

$mapping = array (
    'beans' => array (
      'Leads' => array (
            'id' => 'id',
		  	'recname' => 'account_name',
            'addrstreet1' => 'primary_address_street', 
            'addrstreet2' => 'primary_address_street_2',
		    'addrstateprov' => 'primary_address_state',
		    'addrcountry' => 'primary_address_country',
		    'addrcity' => 'primary_address_city',
		    'addrzip' => 'primary_address_postalcode',
		    'hqphone' => 'phone_work',	    
      ),
      'Accounts' => array (
            'id' => 'id',
		  	'recname' => 'name',
            'addrstreet1' => 'billing_address_street', 
            'addrstreet2' => 'billing_address_street_2',      
		    'addrcity' => 'billing_address_city',
		    'addrstateprov' => 'billing_address_state',
		    'addrcountry' => 'billing_address_country',
		    'addrcity' => 'billing_address_city',
		    'addrzip' => 'billing_address_postalcode',
            'finsales' => 'annual_revenue',
            'employees' => 'employees',
            'hqphone' => 'phone_office',
      		'description' => 'description',
      ),
      'Contacts' => array(
            'id' => 'id',
            'recname' => 'company_name',
            'addrstreet1' => 'primary_address_street', 
            'addrstreet2' => 'primary_address_street_2',      
		    'addrcity' => 'primary_address_city',
		    'addrstateprov' => 'primary_address_state',
		    'addrcountry' => 'primary_address_country',
		    'addrcity' => 'primary_address_city',
		    'addrzip' => 'primary_address_postalcode',
            'hqphone' => 'phone_work',
      ),
    ),
);
?>
