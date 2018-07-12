<?php
$module_name = 'J_Class';
$listViewDefs[$module_name] = 
array (
  'class_code' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CLASS_CODE',
    'width' => '10%',
    'default' => true,
    'link' => true,
  ),
  'name' => 
  array (
    'width' => '12%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'period' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_PERIOD',
    'width' => '5%',
  ),
  'class_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CLASS_TYPE',
    'width' => '10%',
  ),
  'koc_name' => 
  array (
    'type' => 'relate',
    'link' => false,
    'label' => 'LBL_KOC_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '7%',
  ),
  'start_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '7%',
    'default' => true,
  ),
  'end_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '7%',
    'default' => true,
  ),
  'number_of_student' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_NUMBER_OF_STUDENT',
    'width' => '5%',
    'default' => true,
    'sortable' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'aims_id' => 
  array (
    'type' => 'int',
    'label' => 'LBL_AIMS_ID',
    'width' => '10%',
    'default' => false,
  ),
  'kind_of_course' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_KIND_OF_COURSE',
    'width' => '10%',
  ),
  'hours' => 
  array (
    'type' => 'int',
    'default' => false,
    'label' => 'LBL_HOURS',
    'width' => '10%',
  ),
  'max_size' => 
  array (
    'type' => 'enum',
    'default' => false,
    'studio' => 'visible',
    'label' => 'LBL_MAX_SIZE',
    'width' => '10%',
  ),
  'j_class_j_class_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_L_TITLE',
    'id' => 'J_CLASS_J_CLASS_1J_CLASS_IDA',
    'width' => '20%',
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
);
