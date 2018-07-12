<?php
$module_name = 'J_Payment';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'contacts_j_payment_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACTS_J_PAYMENT_1_FROM_CONTACTS_TITLE',
    'id' => 'ID',
    'module' => 'J_Payment',
    'width' => '10%',
    'default' => true,
  ),
  'payment_amount' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_GRAND_TOTAL',
    'width' => '7%',
    'default' => true,
  ),
  'remain_hours' => 
  array (
    'type' => 'decimal',
    'default' => true,
    'label' => 'LBL_REMAIN_HOURS',
    'width' => '7%',
  ),
  'remain_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_REMAIN_AMOUNT',
    'currency_format' => true,
    'width' => '7%',
  ),
  'payment_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_PAYMENT_DATE',
    'width' => '7%',
    'default' => true,
  ),
  'payment_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PAYMENT_TYPE',
    'width' => '10%',
  ),
  'sale_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_SALE_TYPE',
    'width' => '8%',
  ),
  'assigned_user_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '7%',
    'label' => 'LBL_TEAM',
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
  'end_study' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_STUDY',
    'width' => '10%',
    'default' => false,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'tuition_fee' => 
  array (
    'type' => 'currency',
    'default' => false,
    'usage' => 'query_only',
    'force_exist' => true,
    'label' => 'LBL_TUITION_FEE',
    'currency_format' => true,
    'width' => '10%',
  ),
);
