<?php
$module_name = 'J_Gradebook';
$listViewDefs[$module_name] = 
array (
  'j_class_j_gradebook_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
    'id' => 'J_CLASS_J_GRADEBOOK_1J_CLASS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'type' => 
  array (
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'date_input' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'c_teachers_j_gradebook_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
    'id' => 'C_TEACHERS_J_GRADEBOOK_1C_TEACHERS_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
  'created_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => false,
  ),
  'modified_by_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MODIFIED_NAME',
    'id' => 'MODIFIED_USER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
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
);
