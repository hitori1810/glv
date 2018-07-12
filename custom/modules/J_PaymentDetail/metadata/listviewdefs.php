<?php
$module_name = 'J_PaymentDetail';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'serial_no' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SERIAL_NO',
    'width' => '10%',
    'default' => true,
  ),
  'invoice_number' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_INVOICE_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'payment_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_PAYMENT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'payment_method' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PAYMENT_METHOD',
    'width' => '10%',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'payment_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_PAYMENT_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'payment_method_fee' => 
  array (
    'type' => 'currency',
    'default' => false,
    'label' => 'LBL_METHOD_FEE',
    'currency_format' => true,
    'width' => '10%',
  ),
  'card_type' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_CARD_TYPE',
    'width' => '10%',
  ),
);
