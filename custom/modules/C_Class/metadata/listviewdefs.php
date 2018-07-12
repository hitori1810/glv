<?php
$module_name = 'C_Class';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'c_grade_c_class_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_GRADE_C_CLASS_1_FROM_C_GRADE_TITLE',
    'id' => 'C_GRADE_C_CLASS_1C_GRADE_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
);
