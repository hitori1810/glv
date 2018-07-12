<?php
$module_name = 'J_Marketingplan';
$OBJECT_NAME = 'J_MARKETINGPLAN';
$listViewDefs[$module_name] = 
array (
  'document_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'budget' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_BUDGET',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'actual_cost' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_ACTUAL_COST',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'expected_cost' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_EXPECTED_COST',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'expected_revenue' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_EXPECTED_REVENUE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'category' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CATEGORY',
    'width' => '10%',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'modified_by_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MODIFIED_USER',
    'module' => 'Users',
    'id' => 'USERS_ID',
    'default' => false,
    'sortable' => false,
    'related_fields' => 
    array (
      0 => 'modified_user_id',
    ),
  ),
  'team_name' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
    'sortable' => false,
  ),
);
