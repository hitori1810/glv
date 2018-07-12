<?php
$popupMeta = array (
    'moduleMain' => 'C_Rooms',
    'varName' => 'C_Rooms',
    'orderBy' => 'c_rooms.name',
    'whereClauses' => array (
  'name' => 'c_rooms.name',
  'room_id' => 'c_rooms.room_id',
  'status' => 'c_rooms.status',
  'location' => 'c_rooms.location',
  'room_type' => 'c_rooms.room_type',
  'capacity' => 'c_rooms.capacity',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'room_id',
  5 => 'location',
  6 => 'room_type',
  7 => 'capacity',
),
    'searchdefs' => array (
  'room_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ROOM_ID',
    'width' => '10%',
    'name' => 'room_id',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'location' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_LOCATION',
    'sortable' => false,
    'width' => '10%',
    'name' => 'location',
  ),
  'room_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_ROOM_TYPE',
    'width' => '10%',
    'name' => 'room_type',
  ),
  'capacity' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CAPACITY',
    'width' => '10%',
    'name' => 'capacity',
  ),
),
);
