<?php
$module_name = 'J_Marketingplan';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'document_name',
      1 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
      ),
    ),
    'advanced_search' => 
    array (
      'document_name' => 
      array (
        'name' => 'document_name',
        'default' => true,
        'width' => '10%',
      ),
      'start_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
      ),
      'end_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'end_date',
      ),
      'pr_number' => 
      array (
        'type' => 'float',
        'label' => 'LBL_PR_NUMBER',
        'width' => '10%',
        'default' => true,
        'name' => 'pr_number',
      ),
      'budget' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_BUDGET',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'budget',
      ),
      'actual_cost' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_ACTUAL_COST',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'actual_cost',
      ),
      'cost_enquiry' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_COST_ENQUIRY',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'cost_enquiry',
      ),
      'estimated_database' => 
      array (
        'type' => 'float',
        'label' => 'LBL_ESTIMATED_DATABASE',
        'width' => '10%',
        'default' => true,
        'name' => 'estimated_database',
      ),
      'estimated_enquiries' => 
      array (
        'type' => 'float',
        'label' => 'LBL_ESTIMATED_ENQUIRIES',
        'width' => '10%',
        'default' => true,
        'name' => 'estimated_enquiries',
      ),
      'expected_cost' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_EXPECTED_COST',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'expected_cost',
      ),
      'expected_new_sale' => 
      array (
        'type' => 'float',
        'label' => 'LBL_EXPECTED_NEW_SALE',
        'width' => '10%',
        'default' => true,
        'name' => 'expected_new_sale',
      ),
      'expected_revenue' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_EXPECTED_REVENUE',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'expected_revenue',
      ),
      'category' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CATEGORY',
        'width' => '10%',
        'name' => 'category',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status',
      ),
      'assigned_user_name' => 
      array (
        'link' => true,
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'assigned_user_name',
      ),
      'users_j_marketingplan_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_USERS_J_MARKETINGPLAN_1_FROM_USERS_TITLE',
        'id' => 'USERS_J_MARKETINGPLAN_1USERS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'users_j_marketingplan_1_name',
      ),
      'users_j_marketingplan_2_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_USERS_J_MARKETINGPLAN_2_FROM_USERS_TITLE',
        'id' => 'USERS_J_MARKETINGPLAN_2USERS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'users_j_marketingplan_2_name',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
