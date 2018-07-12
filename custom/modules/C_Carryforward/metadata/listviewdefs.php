<?php
$module_name = 'C_Carryforward';
$listViewDefs[$module_name] = 
array (
  'type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'date_input' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
  ),
  'payment_id' => 
  array (
    'type' => 'id',
    'studio' => 'visible',
    'label' => 'LBL_PAYMENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'this_stock' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_THIS_STOCK',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
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
