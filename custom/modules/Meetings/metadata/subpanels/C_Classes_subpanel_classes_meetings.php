<?php
// created: 2014-08-20 10:20:52
$subpanel_layout['list_fields'] = array (
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '20%',
    'default' => true,
  ),
  'session_status' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_SESSION_STATUS',
    'width' => '10%',
  ),
  'time_start_end' =>
  array (
    'name' => 'time_start_end',
    'vname' => 'Dates/Times',
    'width' => '12%',
    'default' => true,
    'sort_by' => 'date_start',
  ),
  'duration_cal' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_DURATION',
    'width' => '5%',
    'default' => true,
  ),
  'delivery_hour' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_DELIVERY_HOUR',
    'width' => '5%',
    'default' => true,
  ),
  'teaching_hour' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_TEACHING_HOUR',
    'width' => '5%',
    'default' => true,
  ),
  'teacher_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'width' => '15%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Teachers',
    'target_record_key' => 'teacher_id',
  ),
  'room_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_ROOM_NAME',
    'id' => 'ROOM_ID',
    'width' => '15%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Rooms',
    'target_record_key' => 'room_id',
  ),
  'subpanel_button' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_SUBPANEL_BUTTON',
    'width' => '20%',
    'default' => true,
  ),
  'recurring_source' =>
  array (
    'usage' => 'query_only',
  ),
      'date_start' =>
  array (
    'usage' => 'query_only',
  ),
  'date_end' =>
  array (
    'usage' => 'query_only',
  )
);