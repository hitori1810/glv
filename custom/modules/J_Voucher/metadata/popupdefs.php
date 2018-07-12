<?php
$popupMeta = array (
    'moduleMain' => 'J_Voucher',
    'varName' => 'J_Voucher',
    'orderBy' => 'j_voucher.name',
    'whereClauses' => array (
  'name' => 'j_voucher.name',
  'status' => 'j_voucher.status',
  'student_name' => 'j_voucher.student_name',
  'discount_amount' => 'j_voucher.discount_amount',
  'discount_percent' => 'j_voucher.discount_percent',
  'start_date' => 'j_voucher.start_date',
  'end_date' => 'j_voucher.end_date',
  'team_name' => 'j_voucher.team_name',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'student_name',
  5 => 'discount_amount',
  6 => 'discount_percent',
  7 => 'start_date',
  8 => 'end_date',
  9 => 'team_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'student_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STUDENT_NAME',
    'width' => '10%',
    'id' => 'STUDENT_ID',
    'name' => 'student_name',
  ),
  'discount_amount' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_DISCOUNT_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'name' => 'discount_amount',
  ),
  'discount_percent' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
    'name' => 'discount_percent',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'name' => 'start_date',
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'name' => 'end_date',
  ),
  'team_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'studio' => 
    array (
      'portallistview' => false,
      'portaldetailview' => false,
      'portaleditview' => false,
    ),
    'label' => 'LBL_TEAMS',
    'id' => 'TEAM_ID',
    'width' => '10%',
    'name' => 'team_name',
  ),
),
);
