<?php
$module_name = 'J_School';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'level' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
    'width' => '10%',
    'default' => true,
  ),
  'address_address_street' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STREET',
    'width' => '10%',
    'default' => true,
  ),
  'address_address_state' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STATE',
    'width' => '10%',
    'default' => true,
  ),
  'address_address_city' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_CITY',
    'width' => '10%',
    'default' => true,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
  ),
);
