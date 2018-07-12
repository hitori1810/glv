<?php
$module_name = 'J_Invoice';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
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
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'invoice_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_INVOICE_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'before_discount' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_BEFORE_DISCOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'total_discount_amount' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DISCOUNT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'invoice_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_INVOICE_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'content_vat_invoice' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTENT_VAT_INVOICE',
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
