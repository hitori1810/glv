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
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '5%',
  ),
  'type_of_course_fee' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE_OF_COURSE_FEE',
    'width' => '10%',
    'default' => true,
  ),
  'apply_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_APPLY_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'unit_price' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_UNIT_PRICE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'inactive_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_INACTIVE_DATE',
    'width' => '10%',
    'default' => false,
  ),
);
