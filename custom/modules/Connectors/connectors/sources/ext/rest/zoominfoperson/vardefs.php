<?php
//FILE SUGARCRM flav=pro
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

$dictionary['ext_rest_zoominfoperson'] = array(
  'comment' => 'vardefs for ZoomInfo Person connector',
  'fields' => array (
    'id' => array (
	    'name' => 'id',
	    'vname' => 'LBL_ID',
	    'hidden' => true,
	),  
	'firstname' => array (
	    'name' => 'firstname',
	    'vname' => 'LBL_FIRST_NAME',
	    'input' => 'firstName',
	    'search' => true,
    ),  
    'lastname'=> array(
	    'name' => 'lastname',
	    'vname' => 'LBL_LAST_NAME',	    
	    'input' => 'lastName',
	    'search' => true,
    ),
    'email' => array(
	    'name' => 'email',
	    'vname' => 'LBL_EMAIL',	    
	    'input' => 'EmailAddress',
	    'search' => true,    
    ),
    'imageurl' => array(
	    'name' => 'imageurl',
	    'vname' => 'LBL_IMAGE_URL',
    ),
    'companyname' => array(
	    'name' => 'companyname',
	    'vname' => 'LBL_COMPANY_NAME',
	    'input' => 'companyName',
        'search' => true, 
    ),
    'zoompersonurl' => array(
		'name' => 'zoompersonurl',
    	'vname' => 'LBL_ZOOMPERSON_URL',    
    ),
    'directphone' => array(
		'name' => 'directphone',
    	'vname' => 'LBL_DIRECT_PHONE',    
    ),
    'companyphone' => array(
		'name' => 'companyphone',
    	'vname' => 'LBL_COMPANY_PHONE',    
    ),            
    'fax' => array(
		'name' => 'fax',
    	'vname' => 'LBL_FAX',    
    ), 
    'jobtitle' => array(
    	'name'=>'jobtitle',
    	'vname' => 'LBL_CURRENT_JOB_TITLE',
    ),
    'current_job_start_date' => array(
    	'name'=>'current_job_start_date',
    	'vname' => 'LBL_CURRENT_JOB_START_DATE',
    ),
    'companyname' => array(
    	'name'=>'companyname',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_NAME',
        'search'=>true,
    ),
    'street' => array(
    	'name'=>'street',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_STREET',
    ),  
    'city' => array(
    	'name'=>'city',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_CITY',
    ),  
    'state' => array(
    	'name'=>'state',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_STATE',
    ),  
    'zip' => array(
    	'name'=>'current_job_company_zip',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_ZIP',
    ),  
    'countrycode' => array(
    	'name'=>'countrycode',
    	'vname' => 'LBL_CURRENT_JOB_COMPANY_COUNTRY_CODE',
    ),  
    'industry' => array(
    	'name'=>'industry',
    	'vname' => 'LBL_CURRENT_INDUSTRY',
    ), 
	'biography' => array(
		'name'=>'biography',
    	'vname' => 'LBL_BIOGRAPHY',    
    ),    
    'school' => array(
    	'name' => 'school',
    	'vname' => 'LBL_EDUCATION_SCHOOL',
        'search' => true,
    ),
    'affiliation_title' => array(
        'name' => 'affiliation_title',
        'vname' => 'LBL_AFFILIATION_TITLE',
        'input' => 'JobTitle',
    ),
    'affiliation_company_name' => array(
        'name' => 'affiliation_company_name',
        'vname' => 'LBL_AFFILIATION_COMPANY_NAME',        
    ),
    'affiliation_company_phone' => array(
        'name' => 'affiliation_company_phone',
        'vname' => 'LBL_AFFILIATION_COMPANY_PHONE',        
    ),    
    'affiliation_company_website' => array(
        'name' => 'affiliation_company_website',
        'vname' => 'LBL_AFFILIATION_COMPANY_WEBSITE',      
    )                      
   )
);
?>
