<?php
$module_name = 'J_GradebookDetail';
$listViewDefs[$module_name] = 
array (
  'j_class_relate_field' => 
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'label' => 'LBL_J_CLASS_RELATE_FIELD',
    'id' => 'J_CLASS_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
  ),
  'gradebook_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_GRADEBOOK_NAME',
    'id' => 'GRADEBOOK_ID',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => false,
    'link' => true,
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
