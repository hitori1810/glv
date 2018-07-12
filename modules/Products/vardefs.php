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

$dictionary['Product'] = array('table' => 'products','audited'=>true,
		'comment' => 'The user (not Admin)) view of a Product definition; an instance of a product'
                               ,'fields' => array (
  'product_template_id' =>
  array (
    'name' => 'product_template_id',
    'type' => 'id',
    'vname' => 'LBL_PRODUCT_TEMPLATE_ID',
    'required'=>false,
    'reportable'=>false,
    'comment' => 'Product (in Admin Products) from which this product is derived (in user Products)'
  ),
  'account_id' =>
  array (
    'name' => 'account_id',
    'type' => 'id',
    'vname' => 'LBL_ACCOUNT_ID',
    'required'=>false,
    'reportable'=>false,
	'audited'=>true,
	'comment' => 'Account this product is associated with'
  ),
  'contact_id' =>
  array (
    'name' => 'contact_id',
    'type' => 'id',
    'vname' => 'LBL_CONTACT_ID',
    'required'=>false,
    'reportable'=>false,
    'audited'=>true,
    'comment' => 'Contact this product is associated with'
  ),
 'contact_name'=>
 	array(
		'name'=>'contact_name',
		'rname'=>'last_name',
		'id_name'=>'contact_id',
		'vname'=>'LBL_CONTACT_NAME',
		'type'=>'relate',
		'link'=>'contact_link',
		'table' =>'contacts', // for bug 20184, replace 'join_name'=>'contacts',
		'isnull'=>'true',
		'module'=>'Contacts',
		'source'=>'non-db',
        'db_concat_fields'=> array(0=>'last_name', 1=>'first_name'),
		),
  'type_id' =>
  array (
    'name' => 'type_id',
    'vname' => 'LBL_TYPE',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
    'function'=>array('name'=>'getProductTypes', 'returns'=>'html', 'include'=>'modules/ProductTemplates/ProductTemplate.php'),
    'comment' => 'Product type (ex: hardware, software)'
  ),
  'quote_id' =>
  array (
    'name' => 'quote_id',
    'type' => 'id',
    'vname' => 'LBL_QUOTE_ID',
    'required'=>false,
    'reportable'=>false,
    'comment' => 'If product created via Quote, this is quote ID'
  ),
  'currency_symbol' =>
	array (
		'name' => 'currency_symbol',
		'type' => 'varchar',
		'vname' =>'LBL_CURRENCY_SYMBOL_NAME',
		'source'=>'non-db',
		'importable' => 'false',
  ),
  'manufacturer_id' =>
  array (
    'name' => 'manufacturer_id',
    'vname' => 'LBL_MANUFACTURER',
    'type' => 'id',
    'required'=>false,
    'reportable'=>false,
    'function'=>array('name'=>'getManufacturers', 'returns'=>'html', 'include'=>'modules/ProductTemplates/ProductTemplate.php'),
    'comment' => 'Manufacturer of product'
  ),
  'category_id' =>
  array (
    'name' => 'category_id',
    'vname' => 'LBL_CATEGORY',
    'type' => 'id',
    'group'=>'category_name',
    'required'=>false,
    'reportable'=>true,
    'function'=>array('name'=>'getCategories', 'returns'=>'html', 'include'=>'modules/ProductTemplates/ProductTemplate.php'),
    'comment' => 'Product category'
  ),
  'category_name' =>
        array (
            'name' => 'category_name',
            'rname' => 'name',
            'id_name' => 'category_id',
            'vname' => 'LBL_CATEGORY_NAME',
            'join_name'=>'categories',
            'type' => 'relate',
            'link' => 'product_categories_link',
            'table' => 'product_categories',
            'isnull' => 'true',
            'module' => 'ProductCategories',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
        	'studio' => array('editview'=>false, 'detailview'=>false, 'quickcreate'=>false),
        ),
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_NAME',
    'dbType' => 'varchar',
    'type' => 'name',
    'len' => '50',
    'comment' => 'Name of the product',
    'reportable' => true,
    'importable' => 'required',
  ),
  'mft_part_num' =>
  array (
    'name' => 'mft_part_num',
    'vname' => 'LBL_MFT_PART_NUM',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Manufacturer part number'
  ),
  'vendor_part_num' =>
  array (
    'name' => 'vendor_part_num',
    'vname' => 'LBL_VENDOR_PART_NUM',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Vendor part number'
  ),
  'date_purchased' =>
  array (
    'name' => 'date_purchased',
    'vname' => 'LBL_DATE_PURCHASED',
    'type' => 'date',
    'comment' => 'Date product purchased'
  ),
  'cost_price' =>
  array (
    'name' => 'cost_price',
    'vname' => 'LBL_COST_PRICE',
    'type' => 'currency',
    'len' => '26,6',
    'audited'=>true,
    'comment' => 'Product cost ("Cost" in Quote)'
  ),
  'discount_price' =>
  array (
    'name' => 'discount_price',
    'vname' => 'LBL_DISCOUNT_PRICE',
    'type' => 'currency',
    'len' => '26,6',
    'audited'=>true,
    'comment' => 'Discounted price ("Unit Price" in Quote)'
  ),
  'discount_amount' =>
  array (
    'name' => 'discount_amount',
    'vname' => 'LBL_DISCOUNT_RATE',
    'type' => 'decimal',
    'options' => 'discount_amount_class_dom',
    'len' => '26,6',
    'precision' => 6,
    'comment' => 'Discounted amount'
  ),
  'discount_amount_usdollar' =>
  array (
    'name' => 'discount_amount_usdollar',
    'vname' => 'LBL_DISCOUNT_RATE_USDOLLAR',
    'type' => 'decimal',
    'len' => '26,6',
  	'studio' => array('editview' => false),
  ),
  'discount_select' =>
  array (
    'name' => 'discount_select',
    'vname' => 'LBL_SELECT_DISCOUNT',
    'type' => 'bool',
    'reportable'=>false,
  ),
    'deal_calc' =>
  array (
    'name' => 'deal_calc',
    'vname' => 'LBL_DISCOUNT_TOTAL',
    'type' => 'currency',
    'len' => '26,6',
    'group'=>'deal_calc',
    'comment' => 'deal_calc',
    'customCode' => '{$fields.currency_symbol.value}{$fields.deal_calc.value}&nbsp;',
  ),
    'deal_calc_usdollar' =>
  array (
    'name' => 'deal_calc_usdollar',
    'vname' => 'LBL_DISCOUNT_TOTAL_USDOLLAR',
    'type' => 'currency',
    'len' => '26,6',
    'group'=>'deal_calc',
    'comment' => 'deal_calc_usdollar',
  	'studio' => array('editview' => false),
  ),
  'list_price' =>
  array (
    'name' => 'list_price',
    'vname' => 'LBL_LIST_PRICE',
    'type' => 'currency',
    'len' => '26,6',
    'audited'=>true,
    'comment' => 'List price of product ("List" in Quote)'
  ),
  'cost_usdollar' =>
  array (
    'name' => 'cost_usdollar',
    'vname' => 'LBL_COST_USDOLLAR',
    'dbType' => 'decimal',
    'group'=>'cost_price',
    'type' => 'currency',
    'len' => '26,6',
    'comment' => 'Cost expressed in USD',
    'studio' => array('editview' => false),
  ),
  'discount_usdollar' =>
  array (
    'name' => 'discount_usdollar',
    'vname' => 'LBL_DISCOUNT_USDOLLAR',
    'dbType' => 'decimal',
    'group'=>'discount_price',
    'type' => 'currency',
    'len' => '26,6',
    'comment' => 'Discount price expressed in USD',
  	'studio' => array('editview' => false),
  ),
  'list_usdollar' =>
  array (
    'name' => 'list_usdollar',
    'vname' => 'LBL_LIST_USDOLLAR',
    'dbType' => 'decimal',
    'type' => 'currency',
    'group'=>'list_price',
    'len' => '26,6',
    'comment' => 'List price expressed in USD',
  	'studio' => array('editview' => false),
  ),
  'currency_id' =>
  array (
    'name' => 'currency_id',
    'type' => 'currency_id',
    'dbType' => 'id',
    'vname'=>'LBL_CURRENCY_ID',
	'function'=>array('name'=>'getCurrencyDropDown', 'returns'=>'html'),
    'required'=>false,
    'reportable'=>false,
    'default'=>'-99',
    'comment' => 'Currency of the product'
  ),
  'base_rate' =>
  array (
    'name' => 'base_rate',
    'vname' => 'LBL_BASE_RATE',
    'type' => 'double',
    'studio' => false
  ),
  'status' =>
  array (
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'options' => 'product_status_dom',
    'len' => 100,
    'audited'=>true,
    'comment' => 'Product status (ex: Quoted, Ordered, Shipped)'
  ),
	'tax_class' =>
	array (
		'name' => 'tax_class',
		'vname' => 'LBL_TAX_CLASS',
		'type' => 'enum',
		'options' => 'tax_class_dom',
		'len' => 100,
		'comment' => 'Tax classification (ex: Taxable, Non-taxable)'
	),
/*
  'tax_class' =>
  array (
    'name' => 'tax_class',
    'vname' => 'LBL_TAX_CLASS',
    'type' => 'varchar',
    'len' => 100,
    'comment' => 'Tax classification (ex: Taxable, Non-taxable)'
  ),
*/
  'website' =>
  array (
    'name' => 'website',
    'vname' => 'LBL_URL',
    'type' => 'varchar',
    'len' => '255',
    'comment' => 'Product URL'
  ),
  'weight' =>
  array (
    'name' => 'weight',
    'vname' => 'LBL_WEIGHT',
    'type' => 'decimal',
    'len' => '12,2',
    'precision' => 2,
    'comment' => 'Weight of the product'
  ),
  'quantity' =>
  array (
    'name' => 'quantity',
    'vname' => 'LBL_QUANTITY',
    'type' => 'int',
    'len'=>5,
    'comment' => 'Quantity in use'
  ),
  'support_name' =>
  array (
    'name' => 'support_name',
    'vname' => 'LBL_SUPPORT_NAME',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Name of product for support purposes'
  ),
  'support_description' =>
  array (
    'name' => 'support_description',
    'vname' => 'LBL_SUPPORT_DESCRIPTION',
    'type' => 'varchar',
    'len' => '255',
    'comment' => 'Description of product for support purposes'
  ),
  'support_contact' =>
  array (
    'name' => 'support_contact',
    'vname' => 'LBL_SUPPORT_CONTACT',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Contact for support purposes'
  ),
  'support_term' =>
  array (
    'name' => 'support_term',
    'vname' => 'LBL_SUPPORT_TERM',
    'type' => 'varchar',
    'len' => 100,
    'function'=>array('name'=>'getSupportTerms', 'returns'=>'html', 'include'=>'modules/ProductTemplates/ProductTemplate.php'),
    'comment' => 'Term (length) of support contract'
  ),
  'date_support_expires' =>
  array (
    'name' => 'date_support_expires',
    'vname' => 'LBL_DATE_SUPPORT_EXPIRES',
    'type' => 'date',
    'comment' => 'Support expiration date'
  ),
  'date_support_starts' =>
  array (
    'name' => 'date_support_starts',
    'vname' => 'LBL_DATE_SUPPORT_STARTS',
    'type' => 'date',
    'comment' => 'Support start date'
  ),
  'pricing_formula' =>
  array (
    'name' => 'pricing_formula',
    'vname' => 'LBL_PRICING_FORMULA',
    'type' => 'varchar',
    'len' => 100,
    'comment' => 'Pricing formula (ex: Fixed, Markup over Cost)'
  ),
  'pricing_factor' =>
  array (
    'name' => 'pricing_factor',
    'vname' => 'LBL_PRICING_FACTOR',
    'type' => 'int',
    'group'=>'pricing_formula',
    'len' => '4',
    'comment' => 'Variable pricing factor depending on pricing_formula'
  ),
  'serial_number' =>
  array (
    'name' => 'serial_number',
    'vname' => 'LBL_SERIAL_NUMBER',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Serial number of product in use'
  ),
  'asset_number' =>
  array (
    'name' => 'asset_number',
    'vname' => 'LBL_ASSET_NUMBER',
    'type' => 'varchar',
    'len' => '50',
    'comment' => 'Asset tag number of product in use'
  ),
  'book_value' =>
  array (
    'name' => 'book_value',
    'vname' => 'LBL_BOOK_VALUE',
    'type' => 'currency',
    'len' => '26,6',
    'comment' => 'Book value of product in use'
  ),
  'book_value_usdollar' =>
  array (
    'name' => 'book_value_usdollar',
    'vname' => 'LBL_BOOK_VALUE_USDOLLAR',
    'dbType' => 'decimal',
    'group'=>'book_value',
    'type' => 'currency',
    'len' => '26,6',
    'comment' => 'Book value expressed in USD',
    'studio' => array('editview' => false),
  ),
  'book_value_date' =>
  array (
    'name' => 'book_value_date',
    'vname' => 'LBL_BOOK_VALUE_DATE',
    'type' => 'date',
    'comment' => 'Date of book value for product in use'
  ),
  'quotes' =>
  array (
      'name' => 'quotes',
      'type' => 'link',
      'relationship' => 'quote_products',
      'vname' => 'LBL_QUOTE',
      'source'=>'non-db',
    ),
    'best_case' =>
    array (
        'name' => 'best_case',
        'vname' => 'LBL_BEST_CASE',
        'dbType' => 'decimal',
        'type' => 'currency',
        'len' => '26,6',
    ),
    'likely_case' =>
    array (
        'name' => 'likely_case',
        'vname' => 'LBL_LIKELY_CASE',
        'dbType' => 'decimal',
        'type' => 'currency',
        'len' => '26,6',
    ),
    'worst_case' =>
    array (
        'name' => 'worst_case',
        'vname' => 'LBL_WORST_CASE',
        'dbType' => 'decimal',
        'type' => 'currency',
        'len' => '26,6',
    ),
    'date_closed' =>
  array (
    'name' => 'date_closed',
    'vname' => 'LBL_DATE_CLOSED',
    'type' => 'date',
    'audited'=>true,
    'comment' => 'Expected or actual date the product (for opportunity) will close',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
  ),
   'date_closed_timestamp' =>
  array (
    'name' => 'date_closed_timestamp',
    'vname' => 'LBL_DATE_CLOSED_TIMESTAMP',

    'type' => 'int',
    'studio' => false
  ),
  'commit_stage' =>
  array (
    'name' => 'commit_stage',
    'vname' => 'LBL_COMMIT_STAGE',
    'type' => 'enum',
    'options' => 'commit_stage_dom',
    'len' => '20',
    'comment' => 'Forecast commit category: Include, Likely, Omit etc.',
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
  ),
  'related_products' =>
  array (
  	'name' => 'related_products',
    'type' => 'link',
    'relationship' => 'product_product',
    'source'=>'non-db',
		'vname'=>'LBL_RELATED_PRODUCTS',
  ),
  'notes' =>
  array (
  	'name' => 'notes',
    'type' => 'link',
    'relationship' => 'product_notes',
    'source'=>'non-db',
		'vname'=>'LBL_NOTES',
  ),
  'documents'=>
  array (
      'name' => 'documents',
      'type' => 'link',
      'relationship' => 'documents_products',
      'source' => 'non-db',
      'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
  ),
  'contracts' => array (
	'name' => 'contracts',
	'type' => 'link',
	'vname' => 'LBL_CONTRACTS',
	'relationship' => 'contracts_products',
	'link_type' => 'one',
	'source' => 'non-db',
  ),
  // Added for Meta-Data framework
  'currency_name' =>
  array (
    'name' => 'currency_name',
    'type' => 'varchar',
    'vname' => 'LBL_CURRENCY',
    'source'=>'non-db',
    'comment' => 'Currency String of the loaded currency_id',
    'importable' => 'false',
  ),
  'quote_name' =>
  array (
    'name' => 'quote_name',
    'rname' => 'name',
    'id_name' => 'quote_id',
    'join_name' => 'quotes',
    'type' => 'relate',
    'link' => 'quotes',
    'table' => 'quotes',
    'isnull' => 'true',
    'module' => 'Quotes',
    'dbType' => 'varchar',
    'len' => '255',
    'vname' => 'LBL_QUOTE_NAME',
    'source'=>'non-db',
    'comment' => 'Quote Name'
  ),
  'manufacturer_name' =>
  array (
    'name' => 'manufacturer_name',
    'type' => 'varchar',
    'vname' => 'LBL_MANUFACTURER',
    'source'=>'non-db',
    'comment' => 'Manufacturer Name'
  ),
'assigned_user_id' =>
    array (
        'name' => 'assigned_user_id',
        'vname' => 'LBL_ASSIGNED_USER_ID',
        'type' => 'id',
    ),
'opportunity_id' =>
array (
  'name' => 'opportunity_id',
  'type' => 'id',
  'vname' => 'LBL_OPPORTUNITY_ID',
  'required'=>false,
  'reportable' => false,
  'comment' => 'The opportunity id for the line item entry'
),
'opportunities' =>
  array(
    'name' => 'opportunities',
    'type' => 'link',
    'relationship' => 'opportunities_products',
    'source'=>'non-db',
    'link_type'=>'one',
    'module'=>'Opportunities',
    'bean_name'=>'Opportunity',
    'vname'=>'LBL_OPPORTUNITIES',
  ),
  'type_name' =>
  array (
      'name' => 'type_name',
      'rname' => 'name',
      'id_name' => 'type_id',
      'vname' => 'LBL_TYPE',
      'join_name' => 'types',
      'type' => 'relate',
      'link' => 'product_types_link',
      'table' => 'product_types',
      'isnull' => 'true',
      'module' => 'ProductTypes',
      'importable' => 'false',
      'dbType' => 'varchar',
      'len' => '255',
      'source' => 'non-db',
  ),

  'account_link' =>
  array (
    'name' => 'account_link',
    'type' => 'link',
    'relationship' => 'products_accounts',
      'link_file' => 'modules/Products/AccountLink.php',
      'link_class' => 'AccountLink',
    'vname' => 'LBL_ACCOUNT',
    'link_type' => 'one',
    'module'=>'Accounts',
    'bean_name'=>'Account',
    'source'=>'non-db',
  ),
    'product_categories_link' =>
  array (
    'name' => 'product_categories_link',
    'type' => 'link',
    'relationship' => 'product_categories',
    'vname' => 'LBL_PRODUCT_CATEGORIES',
    'link_type' => 'one',
    'module'=>'ProductCategories',
    'bean_name'=>'ProductCategory',
    'source'=>'non-db',
  ),
 'product_types_link' =>
  array (
    'name' => 'product_types_link',
    'type' => 'link',
    'relationship' => 'product_types',
    'vname' => 'LBL_PRODUCT_TYPES',
    'link_type' => 'one',
    'module'=>'ProductTypes',
    'bean_name'=>'ProductType',
    'source'=>'non-db',
  ),
  'contact_link' =>
  array (
    'name' => 'contact_link',
    'type' => 'link',
    'relationship' => 'contact_products',
    'vname' => 'LBL_CONTACT',
    'link_type' => 'one',
    'module' => 'Contacts',
    'bean_name' => 'Contact',
    'source' => 'non-db',
    'link_file' => 'modules/Products/AccountLink.php',
    'link_class' => 'AccountLink',
    'duplicate_merge' => 'disabled',
  ), //bug 20184, add contact_link field
  'account_name' =>
  array (
	'name' => 'account_name',
	'rname' => 'name',
	'id_name' => 'account_id',
	'vname' => 'LBL_ACCOUNT_NAME',
	'join_name'=>'accounts',
	'type' => 'relate',
	'link' => 'account_link',
	'table' => 'accounts',
	'isnull' => 'true',
	'module' => 'Accounts',
	'dbType' => 'varchar',
	'len' => '255',
	'source' => 'non-db',
	'unified_search' => true,
	'full_text_search' => array('boost' => 1),
  ),
  'projects' =>
	array (
	    'name' => 'projects',
	    'type' => 'link',
	    'relationship' => 'projects_products',
	    'source'=>'non-db',
	    'vname'=>'LBL_PROJECTS',
	),
    'product_bundles' =>
      array (
        'name' => 'product_bundles',
        'type' => 'link',
        'relationship' => 'product_bundle_product',
        'module'=>'ProductBundles',
        'bean_name'=>'ProductBundle',
        'source'=>'non-db',
        'rel_fields'=>array('product_index'=>array('type'=>'integer')),
        'relationship_fields' =>
            array(
                'product_index' => 'product_index'
            ),
        'vname'=>'LBL_PRODUCTS',
      ),

)
 , 'indices' => array (
       array('name' =>'idx_products', 'type'=>'index', 'fields'=>array('name','deleted')),
       array('name' =>'idx_user_dateclosed_timestamp', 'type'=>'index', 'fields' => array('id', 'assigned_user_id', 'date_closed_timestamp'))
       )

, 'relationships' => array (
	'product_notes' => array('lhs_module'=> 'Products', 'lhs_table'=> 'products', 'lhs_key' => 'id',
							  'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
							  'relationship_type'=>'one-to-many','relationship_role_column'=>'parent_type',
							  'relationship_role_column_value'=>'Products')
   ,'products_accounts' =>
                       array('lhs_module'=> 'Accounts', 'lhs_table'=> 'accounts', 'lhs_key' => 'id',
                       'rhs_module'=> 'Products', 'rhs_table'=> 'products', 'rhs_key' => 'account_id',
                       'relationship_type'=>'one-to-many')
   ,'product_categories' =>
                       array('lhs_module'=> 'ProductCategories', 'lhs_table'=> 'product_categories', 'lhs_key' => 'id',
                       'rhs_module'=> 'Products', 'rhs_table'=> 'products', 'rhs_key' => 'category_id',
                       'relationship_type'=>'one-to-many')
   ,'product_types' =>
                       array('lhs_module'=> 'ProductTypes', 'lhs_table'=> 'product_types', 'lhs_key' => 'id',
                       'rhs_module'=> 'Products', 'rhs_table'=> 'products', 'rhs_key' => 'type_id',
                       'relationship_type'=>'one-to-many')
   ,'products_modified_user' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Products', 'rhs_table'=> 'products', 'rhs_key' => 'modified_user_id',
   'relationship_type'=>'one-to-many')

   ,'products_created_by' =>
   array('lhs_module'=> 'Users', 'lhs_table'=> 'users', 'lhs_key' => 'id',
   'rhs_module'=> 'Products', 'rhs_table'=> 'products', 'rhs_key' => 'created_by',
   'relationship_type'=>'one-to-many')

	,'products_worksheet' =>
   array('lhs_module'=> 'Products', 'lhs_table'=> 'products', 'lhs_key' => 'id',
   'rhs_module'=> 'Worksheet', 'rhs_table'=> 'worksheet', 'rhs_key' => 'related_id',
   'relationship_type'=>'one-to-many'),
	)
);

VardefManager::createVardef('Products','Product', array('default',
'team_security',
));
?>
