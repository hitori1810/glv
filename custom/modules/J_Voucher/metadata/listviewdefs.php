<?php
$module_name = 'J_Voucher';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'student_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STUDENT_NAME',
    'id' => 'STUDENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'discount_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_DISCOUNT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'discount_percent' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
  ),
  'used_time' => 
  array (
    'type' => 'int',
    'default' => true,
    'label' => 'LBL_USED_TIME',
    'width' => '5%',
  ),
  'use_time' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_USE_TIME',
    'width' => '5%',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
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
  'foc_type' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_FOC_TYPE',
    'width' => '10%',
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
