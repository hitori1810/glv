<?php
$listViewDefs['Meetings'] = 
array (
  'name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_SUBJECT',
    'link' => true,
    'default' => true,
  ),
  'meeting_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_MEETING_TYPE',
    'width' => '8%',
  ),
  'week_date' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_WEEK_DATE',
    'width' => '5%',
    'default' => true,
  ),
  'date_start' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_DATE',
    'link' => false,
    'default' => true,
    'related_fields' => 
    array (
      0 => 'time_start',
    ),
  ),
  'duration_cal' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DURATION',
    'width' => '5%',
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
  'number_of_student' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_NUMBER_OF_STUDENT',
    'width' => '5%',
    'default' => true,
  ),
  'attended' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ATTENDEDD',
    'width' => '5%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'date_end' => 
  array (
    'type' => 'datetimecombo',
    'studio' => 
    array (
      'wirelesseditview' => false,
    ),
    'label' => 'LBL_DATE_END',
    'width' => '10%',
    'default' => false,
  ),
  'teacher_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'width' => '10%',
    'default' => false,
  ),
  'team_name' => 
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
  ),
  'status' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_STATUS',
    'link' => false,
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
);
