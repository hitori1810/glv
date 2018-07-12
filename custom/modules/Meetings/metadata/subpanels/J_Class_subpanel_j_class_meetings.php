<?php
// created: 2018-06-13 15:58:32
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
  'teacher_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'width' => '12%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Teachers',
    'target_record_key' => 'teacher_id',
  ),
  'teaching_type' => 
  array (
    'name' => 'teaching_type',
    'vname' => 'LBL_TEACHING_TYPE',
    'width' => '10%',
    'default' => true,
  ),
  'change_teacher_reason' => 
  array (
    'name' => 'change_teacher_reason',
    'vname' => 'LBL_CHANGE_TEACHER_REASON',
    'width' => '10%',
    'default' => true,
  ),
  'subpanel_button' => 
  array (
    'type' => 'varchar',
    'studio' => 'visible',
    'vname' => 'LBL_SUBPANEL_BUTTON',
    'width' => '10%',
    'default' => true,
    'align' => 'center',
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