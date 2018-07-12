<?php
$module_name = 'C_Memberships';
$listViewDefs[$module_name] =
array (
  'student_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_STUDENT_NAME',
    'id' => 'STUDENT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' =>
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'type' =>
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_TYPE',
    'width' => '10%',
  ),
  'upgrade_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_UPGRADE_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'description' =>
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '12%',
    'default' => true,
  ),
  'team_name' =>
  array (
    'width' => '9%',
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
  'assigned_user_name' =>
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
