<?php
$module_name = 'J_Inventory';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'date_create' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_CREATE',
    'width' => '8%',
    'default' => true,
  ),
  'type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '8%',
  ),
  'from_inventory_list' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_FROM_INVENTORY_LIST',
    'width' => '11%',
  ),
  'to_inventory_list' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TO_INVENTORY_LIST',
    'width' => '11%',
  ),
  /*'total_quantity' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_TOTAL_QUANTITY',
    'width' => '10%',
    'default' => true,
  ),
  'total_amount' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_TOTAL_AMOUNT',
    'width' => '10%',
    'default' => true,
  ),  */
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '8%',
  ),
  'HTML_DETAIL' => 
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'label' => 'LBL_DETAIL',
    'width' => '15%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),   
);
