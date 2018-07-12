<?php
$module_name = 'J_Discount';
$listViewDefs[$module_name] = 
array (
  'order_no' => 
  array (
    'type' => 'int',
    'label' => 'LBL_ORDER',
    'width' => '2%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '5%',
  ),
  'discount_percent' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
    'default' => true,
  ),
  'discount_amount' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_AMOUNT',
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
  'type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '7%',
  ),
  'policy' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_POLICY',
    'sortable' => false,
    'width' => '20%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => false,
  ),
  'apply_for' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_APPLY_FOR',
    'width' => '8%',
  ),
  'assigned_user_name' => 
  array (
    'width' => '12%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
