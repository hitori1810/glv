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

$dictionary['ext_soap_hoovers'] = array(

  'comment' => 'vardefs for hoovers connector',
  'fields' => array (
    'id' =>
	  array (
	    'name' => 'id',
	    'input' => 'uniqueId',
	    'vname' => 'LBL_ID',
	    'type' => 'id',
	    'hidden' => true,
	    'comment' => 'Unique identifier for records'
	),
    'recname'=> array(
	    'name' => 'recname',
		'input' => 'bal.specialtyCriteria.companyName',
		'output' => 'recname',
	    'vname' => 'LBL_NAME',
	    'type' => 'varchar',
	    'search' => true,
	    'comment' => 'The name of the company',
    ),
   'duns' => array (
	    'name' => 'duns',
    	'input' => 'bal.specialtyCriteria.duns',
		'output' => 'duns',
	    'vname' => 'LBL_DUNS',
	    'type' => 'varchar',
    	'hidden' => true,
	    'search' => true,
	    'comment' => 'The duns id used by Hoovers',
    ),
   'parent_duns' => array (
	    'name' => 'parent_duns',
		'output' => 'parent_duns',
	    'vname' => 'LBL_PARENT_DUNS',
	    'type' => 'varchar',
	    'comment' => 'The parent duns id used by Hoovers',
	    'search' => true,
    	'required' => true,
    	'hidden' => true,
    ),
   'addrcity' => array (
	    'name' => 'addrcity',
   		'input' => 'bal.location.city',
        'output' => 'addrcity',
	    'vname' => 'LBL_CITY',
	    'type' => 'varchar',
	    'search' => true,
	    'comment' => 'The city address for the company', 
   ),
   'addrstreet1' => array(
        'name' => 'addrstreet1',
        'vname' => 'LBL_ADDRESS_STREET1',
        'type' => 'varchar',
        'comment' => 'street address',
   ),
   'addrstreet2' => array(
        'name' => 'addrstreet2',
        'vname' => 'LBL_ADDRESS_STREET2',
        'type' => 'varchar',
        'comment' => 'street address (continued)',   
   ),
   'addrstateprov' => array(
        'name' => 'addrstateprov',
   		'input' => 'bal.location.globalState',
        'vname' => 'LBL_STATE',
        'type' => 'varchar',
        'search' => true,
        'options' => 'addrstateprov_dom',
        'comment' => 'The state address for the company',
   ),
   'addrcountry' => array(
        'name' => 'addrcountry',
        'input' => 'bal.location.countryId',
        'vname' => 'LBL_COUNTRY',
        'type' => 'varchar',
        'search' => true,
        'comment' => 'The country address for the company',
   ),
   'addrzip' => array(
        'name' => 'addrzip',
   		'input' => 'bal.location.postalCode',
        'vname' => 'LBL_ZIP',
        'type' => 'varchar',
        'search' => true,
        'comment' => 'The postal code address for the company',
   ),
   'finsales' => array(
        'name' => 'finsales',
        'vname' => 'LBL_FINSALES',
        'type' => 'decimal',
        'comment' => 'Annual sales (in millions)',
   ),
   /*
   'locationtype' => array(
        'name' => 'locationtype',
        'vname' => 'LBL_LOCATION_TYPE',
        'type' => 'varchar',
        'output' => 'locationtype',
        'comment' => 'Location type such as headquarters or branch',   
   ),
   'companytype' => array(
        'name' => 'companytype',
        'vname' => 'LBL_COMPANY_TYPE',
        'type' => 'varchar',
        'output' => 'companytype',
        'comment' => 'Company type (public, private, etc.)',
   ),
   */
   'hqphone' => array(
        'name' => 'hqphone',
        'vname' => 'LBL_HQPHONE',
        'type' => 'varchar',
        'comment' => 'Headquarters phone number',    
   ),
   'employees' => array(
        'name' => 'employees',
        'vname' => 'LBL_TOTAL_EMPLOYEES',
        'type' => 'decimal',
        'comment' => 'Total number of employees',
   ),
   'description' => array(
        'name' => 'description',
        'vname' => 'LBL_DESCRIPTION',
        'type' => 'varchar',
        'comment' => 'Company Description',      
   ),
   'synopsis' => array(
        'name' => 'synopsis',
        'vname' => 'LBL_SYNOPSIS',
        'type' => 'varchar',
        'comment' => 'Company Synopsis',      
   )
   )
);
?>
