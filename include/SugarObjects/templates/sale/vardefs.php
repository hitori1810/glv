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

/*********************************************************************************

 * Description:  Defines the English language pack for the base application.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/
 
$vardefs = array(
	'fields'=>array(
		'name'=>
		 array(
			'name'=>'name',
			'type'=>'name',
		    'link' => true, // bug 39288 
			'dbType'=>'varchar',
			'vname'=>'LBL_NAME',
			'comment'=>'Name of the Sale',
			'unified_search'=>true,
			'full_text_search'=>array('boost' => 3),
			'audited'=>true,
			'merge_filter'=>'selected',
			'required' => true,
            'importable' => 'required',
		),
		strtolower($object_name).'_type' =>
		 array(
			'name'=>strtolower($object_name).'_type',
			'vname'=>'LBL_TYPE',
			'type'=>'enum',
			'options'=>strtolower($object_name).'_type_dom',
			'len' => 100,
			'comment'=>'The Sale is of this type',
		),
		'description'=>
		 array(
		 	'name'=>'description',
		 	'vname'=>'LBL_DESCRIPTION',
		 	'type'=>'text',
		 	'comment'=>'Description of the sale',
            'rows' => 6,
            'cols' => 80,
		),
		 'lead_source' =>
		  array (
		    'name' => 'lead_source',
		    'vname' => 'LBL_LEAD_SOURCE',
		    'type' => 'enum',
		    'options' => 'lead_source_dom',
		    'len' => '50',
		    'comment' => 'Source of the sale',
		  ),
		    'amount' =>
		  array (
		    'name' => 'amount',
		    'vname' => 'LBL_AMOUNT',
		    'type' => 'currency',
		    'dbType' => 'double',
		    'comment' => 'Unconverted amount of the sale',
		    'duplicate_merge'=>'disabled',
		    'required' => true,
		  ),
		  'amount_usdollar' =>
		  array (
		    'name' => 'amount_usdollar',
		    'vname' => 'LBL_AMOUNT_USDOLLAR',
		    'type' => 'currency',
		    'group'=>'amount',
		    'dbType' => 'double',
		    'disable_num_format' => true,
		    'audited'=>true,
		    'comment' => 'Formatted amount of the sale'
		  ),
          'currency_id' =>
          array (
            'name' => 'currency_id',
            'type' => 'id',
            'group'=>'currency_id',
            'vname' => 'LBL_CURRENCY',
            'function'=>array('name'=>'getCurrencyDropDown', 'returns'=>'html'),
            'reportable'=>false,
            'default'=>'-99',
            'comment' => 'Currency used for display purposes'
          ),
          'currency_name'=>
               array(
                'name'=>'currency_name',
                'rname'=>'name',
                'id_name'=>'currency_id',
                'vname'=>'LBL_CURRENCY_NAME',
                'type'=>'relate',
                'isnull'=>'true',
                'table' => 'currencies',
                'module'=>'Currencies',
                'source' => 'non-db',
                'function'=>array('name'=>'getCurrencyNameDropDown', 'returns'=>'html'),
                'studio' => 'false',
            ),
           'currency_symbol'=>
               array(
                'name'=>'currency_symbol',
                'rname'=>'symbol',
                'id_name'=>'currency_id',
                'vname'=>'LBL_CURRENCY_SYMBOL',
                'type'=>'relate',
                'isnull'=>'true',
                'table' => 'currencies',
                'module'=>'Currencies',
                'source' => 'non-db',
                'function'=>array('name'=>'getCurrencySymbolDropDown', 'returns'=>'html'),
            ),
		   'date_closed' =>
		  array (
		    'name' => 'date_closed',
		    'vname' => 'LBL_DATE_CLOSED',
		    'type' => 'date',
		    'audited'=>true,
		    'required' => true,
		    'comment' => 'Expected or actual date the sale will close',
		    'enable_range_search' => true,
		    'options' => 'date_range_search_dom',
		  ),
		  'next_step' =>
		  array (
		    'name' => 'next_step',
		    'vname' => 'LBL_NEXT_STEP',
		    'type' => 'varchar',
		    'len' => '100',
		    'comment' => 'The next step in the sales process',
		    'merge_filter' => 'enabled',
		  ),
		  'sales_stage' =>
		  array (
		    'name' => 'sales_stage',
		    'vname' => 'LBL_SALES_STAGE',
		    'type' => 'enum',
		    'options' => 'sales_stage_dom',
		    'len' => 100,
		    'audited'=>true,
		    'comment' => 'Indication of progression towards closure',
			'required'=>true,
            'importable' => 'required',
		    'merge_filter' => 'enabled',
		  ),
		  'probability' =>
		  array (
		    'name' => 'probability',
		    'vname' => 'LBL_PROBABILITY',
		    'type' => 'int',
		    'dbType' => 'double',
		    'audited'=>true,
		    'comment' => 'The probability of closure',
		    'validation' => array('type' => 'range', 'min' => 0, 'max' => 100),
		    'merge_filter' => 'enabled',
		  )
	)
);
?>
