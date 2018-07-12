<?php
$module_name = 'C_Carryforward';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'name' => 'type',
      ),
      'payment_id' => 
      array (
        'type' => 'id',
        'studio' => 'visible',
        'label' => 'LBL_PAYMENT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_id',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'student_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STUDENT_NAME',
        'id' => 'STUDENT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'student_name',
      ),
      'month' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MONTH',
        'width' => '10%',
        'name' => 'month',
      ),
      'year' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_YEAR',
        'width' => '10%',
        'name' => 'year',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
