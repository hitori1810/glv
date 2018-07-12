<?php
$module_name = 'J_Sponsor';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'voucher_code' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_VOUCHER_CODE',
    'id' => 'VOUCHER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'discount_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_DISCOUNT_NAME',
    'id' => 'DISCOUNT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'foc_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_FOC_TYPE',
    'width' => '10%',
  ),
  'total_down' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DISCOUNT_SPONSOR_DOWN',
    'currency_format' => true,
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
    'default' => true,
  ),
);
