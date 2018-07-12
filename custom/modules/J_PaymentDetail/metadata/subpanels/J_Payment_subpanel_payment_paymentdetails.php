<?php
// created: 2018-06-04 15:53:01
$subpanel_layout['list_fields'] = array (
  'payment_no' => 
  array (
    'vname' => 'LBL_PAYMENT_NO',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '5%',
    'default' => true,
    'link' => true,
    'target_module' => NULL,
    'target_record_key' => NULL,
  ),
  'payment_method' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_PAYMENT_METHOD',
    'width' => '6%',
    'link' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => NULL,
    'target_record_key' => NULL,
  ),
  'before_discount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_BEFORE_DISCOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'discount_amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_DISCOUNT_AMOUNT',
    'currency_format' => true,
    'width' => '7%',
    'default' => true,
  ),
  'sponsor_amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_SPONSOR_AMOUNT',
    'currency_format' => true,
    'width' => '7%',
    'default' => true,
  ),
  'payment_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'vname' => 'LBL_PAYMENT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
  ),
  'payment_date' => 
  array (
    'type' => 'date',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_PAYMENT_DATE',
    'width' => '10%',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '7%',
  ),
  'assigned_user_name' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'width' => '9%',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'custom_button' => 
  array (
    'type' => 'varchar',
    'width' => '20%',
    'default' => true,
  ),
  'team_id' => 
  array (
    'usage' => 'query_only',
  ),
  'card_type' => 
  array (
    'usage' => 'query_only',
  ),
  'bank_type' => 
  array (
    'usage' => 'query_only',
  ),
  'payment_id' => 
  array (
    'usage' => 'query_only',
  ),
  'is_release' => 
  array (
    'usage' => 'query_only',
  ),
  'invoice_number' => 
  array (
    'usage' => 'query_only',
  ),
  'student_id' => 
  array (
    'usage' => 'query_only',
  ),
);