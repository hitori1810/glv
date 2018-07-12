<?php
// created: 2015-08-29 09:04:59
$subpanel_layout['list_fields'] = array (
  'name' =>
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '15%',
    'default' => true,
  ),
  'time_start_end' =>
  array (
    'name' => 'time_start_end',
    'vname' => 'Dates/Times',
    'width' => '12%',
    'default' => true,
    'sort_by' => 'date_start',
  ),
//  'date_start' =>
//  array (
//    'name' => 'date_start',
//    'vname' => 'LBL_LIST_DATE',
//    'width' => '15%',
//    'default' => true,
//  ),
//  'date_end' =>
//  array (
//    'name' => 'date_end',
//    'vname' => 'LBL_DATE_END',
//    'width' => '15%',
//    'default' => true,
//  ),
  'teacher_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'width' => '10%',
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
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Rooms',
    'target_record_key' => 'room_id',
  ),
  'delivery_hour' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_DELIVERY_HOUR',
    'width' => '10%',
    'default' => true,
  ),
  'teaching_hour' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_TEACHING_HOUR',
    'width' => '10%',
    'default' => true,
  ),
  'custom_button' =>
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_CUSTOM_BUTTON',
    'width' => '10%',
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