<?php
// created: 2014-09-05 14:53:45
$subpanel_layout['where'] = ' (meetings.class_id = "" OR meetings.class_id IS NULL)';
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
  'week_date' =>
  array (
    'type' => 'varchar',
    'vname' => 'LBL_WEEK_DATE',
    'width' => '7%',
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
//    'width' => '10%',
//    'default' => true,
//  ),
//  'date_end' =>
//  array (
//    'type' => 'datetimecombo',
//    'studio' =>
//    array (
//      'wirelesseditview' => false,
//    ),
//    'vname' => 'LBL_DATE_END',
//    'width' => '10%',
//    'default' => true,
//  ),
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
    'vname' => 'LBL_TEAMS',
    'id' => 'TEAM_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Teams',
    'target_record_key' => 'team_id',
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