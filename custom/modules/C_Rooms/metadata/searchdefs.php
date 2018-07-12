<?php
$module_name = 'C_Rooms';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'capacity' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CAPACITY',
        'width' => '10%',
        'name' => 'capacity',
      ),
    ),
    'advanced_search' => 
    array (
      'room_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ROOM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'room_id',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'room_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_ROOM_TYPE',
        'width' => '10%',
        'name' => 'room_type',
      ),
      'capacity' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CAPACITY',
        'width' => '10%',
        'name' => 'capacity',
      ),
      'location' => 
      array (
        'type' => 'text',
        'studio' => 'visible',
        'label' => 'LBL_LOCATION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'location',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
