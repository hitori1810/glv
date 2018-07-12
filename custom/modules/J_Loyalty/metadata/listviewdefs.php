<?php
$module_name = 'J_Loyalty';
$listViewDefs[$module_name] =
array (
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
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'point' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_POINT',
    'width' => '10%',
    'default' => true,
  ),
  'input_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_INPUT_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'exp_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_EXP_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'payment_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'student_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STUDENT_NAME',
    'id' => 'STUDENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'created_by_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' =>
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
  ),
  'description' =>
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
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
  'assigned_user_name' =>
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
