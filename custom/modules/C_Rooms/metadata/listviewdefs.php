<?php
$module_name = 'C_Rooms';
$listViewDefs[$module_name] = 
array (
  'room_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ROOM_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'room_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_ROOM_TYPE',
    'width' => '10%',
  ),
  'capacity' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_CAPACITY',
    'width' => '10%',
  ),
  'location' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_LOCATION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
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
