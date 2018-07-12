<?php
// created: 2015-09-15 10:34:08
$subpanel_layout['list_fields'] = array (
  'meetings_j_ptresult_1_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_SCHEDULE',
    'id' => 'MEETINGS_J_PTRESULT_1MEETINGS_IDA',
    'width' => '20%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Meetings',
    'target_record_key' => 'meetings_j_ptresult_1meetings_ida',
  ),
  'date_start' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'date_end' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'duration_cal' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_DURATION',
    'width' => '10%',
    'default' => true,
  ),
  'attended' =>
  array (
    'type' => 'bool',
    'default' => true,
    'vname' => 'LBL_ATTENDED',
    'width' => '10%',
  ),
  'full_teacher_name' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_TEACHER',
    'width' => '10%',
    'default' => true,
  ),
  'room_name' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_ROOM',
    'width' => '10%',
    'default' => true,
  ),
  'custom_button_demo' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_CUSTOM_BUTTON',
    'width' => '10%',
    'default' => true,
  ),
);