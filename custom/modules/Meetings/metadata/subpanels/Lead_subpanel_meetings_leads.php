<?php
// created: 2015-08-27 17:24:10
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '20%',
    'default' => true,
  ),
  'date_start' => 
  array (
    'name' => 'date_start',
    'vname' => 'LBL_LIST_DATE',
    'width' => '15%',
    'default' => true,
  ),
  'date_end' => 
  array (
    'name' => 'date_end',
    'vname' => 'LBL_DATE_END',
    'width' => '15%',
    'default' => true,
  ),
  'duration_cal' => 
  array (
    'type' => 'duration_cal',
    'vname' => 'LBL_DURATION',
    'width' => '10%',
    'default' => true,
  ),
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
  'custom_button' => 
  array (
    'studio' => true,
    'width' => '15%',
    'sortable' => false,
    'default' => true,
  ),
  'recurring_source' => 
  array (
    'usage' => 'query_only',
  ),
);