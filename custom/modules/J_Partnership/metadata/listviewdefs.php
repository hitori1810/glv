<?php
$module_name = 'J_Partnership';
$listViewDefs[$module_name] =
array (
  'name' =>
  array (
    'width' => '20%',
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
    'width' => '10%',
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
  'loyalty_type' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_LOYALTY_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'hours' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_HOURS',
    'width' => '10%',
    'default' => true,
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
  'description' =>
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'team_name' =>
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
  'assigned_user_name' =>
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
