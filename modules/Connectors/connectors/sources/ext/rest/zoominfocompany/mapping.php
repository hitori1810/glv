<?php
//FILE SUGARCRM flav=pro
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
  'beans' => 
  array (
    'Leads' => 
    array (
      'companyname' => 'account_name', 
      'street' => 'primary_address_street',   
      'city' => 'primary_address_city',
      'state' => 'primary_address_state',
      'zip' => 'primary_address_postalcode',
      'countrycode' => 'primarty_address_country',
      'companydescription' => 'description',
      'phone' => 'phone_work',
    ),
    'Accounts' => 
    array (
      'companyname' => 'name',
      'street' => 'billing_address_street',    
      'city' => 'billing_address_city',
      'state' => 'billing_address_state',
      'zip' => 'billing_address_postalcode',
      'countrycode' => 'billing_address_country',
      'industry' => 'industry',      
      'website' => 'website',      
      'companydescription' => 'description',
      'revenue' => 'annual_revenue',
      'phone' => 'phone_office',
      'employees' => 'employees',    
      'id' => 'id',
    ),    
    'Contacts' => 
    array (
      'companyname' => 'account_name',
      'street' => 'primary_address_street',    
      'city' => 'primary_address_city',
      'state' => 'primary_address_state',
      'zip' => 'primary_address_postalcode',
      'countrycode' => 'primary_address_country',
      'companydescription' => 'description',
    ),          
  ),
);
?>
