<?php
$module_name = 'J_StudentSituations';
$listViewDefs[$module_name] = 
array (
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
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '7%',
    'default' => true,
  ),
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'id' => 'PAYMENT_ID',
    'width' => '7%',
    'default' => true,
  ),
  'start_study' => 
  array (
    'type' => 'date',
    'label' => 'LBL_START_STUDY',
    'width' => '7%',
    'default' => true,
  ),
  'end_study' => 
  array (
    'type' => 'date',
    'label' => 'LBL_END_STUDY',
    'width' => '7%',
    'default' => true,
  ),
  'total_hour' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_TOTAL_HOUR',
    'width' => '7%',
    'default' => true,
  ),
  'total_amount' => 
  array (
    'type' => 'currency',
    'default' => true,
    'label' => 'LBL_TOTAL_AMOUNT',
    'currency_format' => true,
    'width' => '7%',
  ),
  'assigned_user_name' => 
  array (
    'width' => '7%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '7%',
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
    'width' => '8%',
    'default' => true,
  ),
  'student_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE_STUDENT',
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
