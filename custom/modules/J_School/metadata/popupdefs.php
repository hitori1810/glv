<?php
$popupMeta = array (
    'moduleMain' => 'J_School',
    'varName' => 'J_School',
    'orderBy' => 'j_school.name',
    'whereClauses' => array (
  'level' => 'j_school.level',
  'address_address_street' => 'j_school.address_address_street',
  'address_address_state' => 'j_school.address_address_state',
  'address_address_city' => 'j_school.address_address_city',
  'name' => 'j_school.name',
),
    'searchInputs' => array (
  5 => 'level',
  6 => 'address_address_street',
  10 => 'address_address_state',
  11 => 'address_address_city',
  12 => 'name',
),
    'create' => array (
  'formBase' => 'FormBase.php',
  'formBaseClass' => 'FormBase',
  'createButton' => 'Create',
),
    'searchdefs' => array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'level' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
    'width' => '10%',
    'name' => 'level',
  ),
  'address_address_street' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STREET',
    'width' => '10%',
    'name' => 'address_address_street',
  ),
  'address_address_state' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STATE',
    'width' => '10%',
    'name' => 'address_address_state',
  ),
  'address_address_city' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_CITY',
    'width' => '10%',
    'name' => 'address_address_city',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'LEVEL' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_LEVEL',
    'width' => '10%',
    'default' => true,
    'name' => 'level',
  ),
  'ADDRESS_ADDRESS_STREET' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STREET',
    'width' => '10%',
    'default' => true,
    'name' => 'address_address_street',
  ),
  'ADDRESS_ADDRESS_STATE' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_STATE',
    'width' => '10%',
    'default' => true,
    'name' => 'address_address_state',
  ),
  'ADDRESS_ADDRESS_CITY' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ADDRESS_CITY',
    'width' => '10%',
    'default' => true,
    'name' => 'address_address_city',
  ),
),
);
