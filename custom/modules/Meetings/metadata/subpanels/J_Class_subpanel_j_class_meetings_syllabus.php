<?php
// created: 2018-06-13 15:59:44
$subpanel_layout['list_fields'] = array (
  'lesson_number' => 
  array (
    'type' => 'int',
    'default' => true,
    'vname' => 'LBL_NO',
    'width' => '3%',
  ),
  'till_hour' => 
  array (
    'type' => 'till_hour',
    'vname' => 'LBL_TILL_HOUR',
    'width' => '5%',
    'default' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_SUBJECT',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'session_status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_SESSION_STATUS',
    'width' => '12%',
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
    'vname' => 'LBL_DATE_TIMES',
    'width' => '12%',
    'default' => true,
    'sort_by' => 'date_start',
  ),
  'duration_cal' => 
  array (
    'type' => 'duration_cal',
    'vname' => 'LBL_DURATION',
    'width' => '5%',
    'default' => true,
  ),
  'syllabus_default' => 
  array (
    'name' => 'syllabus_default',
    'vname' => 'LBL_SYLLABUS_DEFAULT',
    'width' => '15%',
    'default' => true,
  ),
  'syllabus_custom' => 
  array (
    'name' => 'syllabus_custom',
    'vname' => 'LBL_SYLLABUS_CUSTOM',
    'width' => '15%',
    'default' => true,
  ),
  'homework' => 
  array (
    'name' => 'homework',
    'vname' => 'LBL_HOMEWORK',
    'width' => '15%',
    'default' => true,
  ),
  'type_of_class' => 
  array (
    'type' => 'enum',
    'default' => false,
    'usage' => 'query_only',
  ),
  'recurring_source' => 
  array (
    'usage' => 'query_only',
  ),
  'meeting_type' => 
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
  ),
);