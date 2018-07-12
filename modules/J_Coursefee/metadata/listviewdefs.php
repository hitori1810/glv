<?php
$module_name = 'J_Coursefee';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'unit_price_1' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_UNIT_PRICE_1',
    'width' => '10%',
    'default' => true,
  ),
  'hour_price_1' => 
  array (
    'type' => 'int',
    'label' => 'LBL_HOUR_PRICE_1',
    'width' => '10%',
    'default' => true,
  ),
  'unit_price_2' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_UNIT_PRICE_2',
    'width' => '10%',
    'default' => true,
  ),
  'hour_price_2' => 
  array (
    'type' => 'int',
    'label' => 'LBL_HOUR_PRICE_2',
    'width' => '10%',
    'default' => true,
  ),
  'unit_price_3' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_UNIT_PRICE_3',
    'width' => '10%',
    'default' => true,
  ),
  'hour_price_3' => 
  array (
    'type' => 'int',
    'label' => 'LBL_HOUR_PRICE_3',
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
  'date_modified' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_MODIFIED',
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
