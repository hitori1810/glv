<?php
$module_name = 'J_Inventorydetail';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'j_inventory_j_inventorydetail_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_INVENTORY_J_INVENTORYDETAIL_1_FROM_J_INVENTORY_TITLE',
    'id' => 'J_INVENTORY_J_INVENTORYDETAIL_1J_INVENTORY_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'producttemplates_j_inventorydetail_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PRODUCTTEMPLATES_J_INVENTORYDETAIL_1_FROM_PRODUCTTEMPLATES_TITLE',
    'id' => 'PRODUCTTEMPLATES_J_INVENTORYDETAIL_1PRODUCTTEMPLATES_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
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
    'default' => false,
  ),
);
