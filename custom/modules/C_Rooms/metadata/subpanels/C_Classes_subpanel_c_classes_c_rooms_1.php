<?php
// created: 2014-08-14 14:11:03
$subpanel_layout['list_fields'] = array (
  'room_id' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_ROOM_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '20%',
    'default' => true,
  ),
  'room_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_ROOM_TYPE',
    'width' => '10%',
  ),
  'capacity' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_CAPACITY',
    'width' => '10%',
  ),
  'location' => 
  array (
    'type' => 'text',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_LOCATION',
    'width' => '10%',
  ),
  'team_name' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_TEAM',
    'width' => '10%',
  ),
);