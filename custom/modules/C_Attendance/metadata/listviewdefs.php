<?php
$module_name = 'C_Attendance';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
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
  'leaving_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_LEAVING_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'attended' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ATTENDED',
    'width' => '10%',
  ),
  'meeting_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_MEETING_NAME',
    'id' => 'MEETING_ID',
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
