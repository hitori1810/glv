<?php
$searchdefs['Meetings'] = 
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
      'meeting_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'width' => '10%',
        'label' => 'LBL_MEETING_TYPE',
        'name' => 'meeting_type',
      ),
      'date_start' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'date_start',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'meeting_type' => 
      array (
        'name' => 'meeting_type',
        'default' => true,
        'width' => '10%',
      ),
      'date_start' => 
      array (
        'type' => 'datetimecombo',
        'label' => 'LBL_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'date_start',
      ),
      'teacher_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_TEACHER_NAME',
        'id' => 'TEACHER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'teacher_name',
      ),
      'duration_cal' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_DURATION',
        'width' => '10%',
        'default' => true,
        'name' => 'duration_cal',
      ),
      'lesson_number' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_LESSON_NUMBER',
        'width' => '10%',
        'name' => 'lesson_number',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'room_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_ROOM_NAME',
        'id' => 'ROOM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'room_name',
      ),
      'team_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'studio' => 
        array (
          'portallistview' => false,
          'portaldetailview' => false,
          'portaleditview' => false,
        ),
        'label' => 'LBL_TEAMS',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
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
