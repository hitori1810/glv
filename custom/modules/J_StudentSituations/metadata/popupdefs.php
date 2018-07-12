<?php
$popupMeta = array (
    'moduleMain' => 'J_StudentSituations',
    'varName' => 'J_StudentSituations',
    'orderBy' => 'j_studentsituations.name',
    'whereClauses' => array (
  'name' => 'j_studentsituations.name',
  'ju_class_name' => 'j_studentsituations.ju_class_name',
),
    'searchInputs' => array (
  1 => 'name',
  6 => 'ju_class_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'ju_class_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_JU_CLASS_NAME',
    'id' => 'JU_CLASS_ID',
    'width' => '10%',
    'name' => 'ju_class_name',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '14%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'STUDENT_PHONE' => 
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'label' => 'Phone',
    'width' => '10%',
    'default' => true,
    'name' => 'student_phone',
  ),
  'TYPE' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'type',
  ),
  'START_STUDY' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_STUDY',
    'width' => '10%',
    'default' => true,
    'name' => 'start_study',
  ),
  'END_STUDY' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_STUDY',
    'width' => '10%',
    'default' => true,
    'name' => 'end_study',
  ),
  'TOTAL_HOUR' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_TOTAL_HOUR',
    'width' => '10%',
    'default' => true,
    'name' => 'total_hour',
  ),
  'TOTAL_AMOUNT' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_TOTAL_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'name' => 'total_amount',
  ),
),
);
