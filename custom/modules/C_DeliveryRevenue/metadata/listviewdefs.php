<?php
$module_name = 'C_DeliveryRevenue';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'date_input' => 
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
  ),
  'student_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_SESSION_NAME',
    'id' => 'SESSION_ID',
    'width' => '10%',
    'default' => true,
  ),
  'amount' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_AMOUNT',
    'width' => '10%',
    'default' => true,
  ),
  'duration' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DURATION',
    'width' => '10%',
    'default' => true,
  ),
  'passed' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_PASSED',
    'width' => '10%',
    'default' => true,
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
);
