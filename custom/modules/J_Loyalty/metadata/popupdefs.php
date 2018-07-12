<?php
$popupMeta = array (
    'moduleMain' => 'J_Loyalty',
    'varName' => 'J_Loyalty',
    'orderBy' => 'j_loyalty.name',
    'whereClauses' => array (
  'name' => 'j_loyalty.name',
  'payment_name' => 'j_loyalty.payment_name',
  'student_name' => 'j_loyalty.student_name',
  'point' => 'j_loyalty.point',
  'type' => 'j_loyalty.type',
  'input_date' => 'j_loyalty.input_date',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'payment_name',
  5 => 'student_name',
  6 => 'point',
  7 => 'type',
  8 => 'input_date',
),
    'searchdefs' => array (
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '10%',
    'name' => 'payment_name',
  ),
  'student_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STUDENT_NAME',
    'id' => 'STUDENT_ID',
    'width' => '10%',
    'name' => 'student_name',
  ),
  'point' => 
  array (
    'type' => 'int',
    'label' => 'LBL_POINT',
    'width' => '10%',
    'name' => 'point',
  ),
  'type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'input_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_INPUT_DATE',
    'width' => '10%',
    'name' => 'input_date',
  ),
),
);
