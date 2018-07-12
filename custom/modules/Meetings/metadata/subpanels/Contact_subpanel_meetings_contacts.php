<?php
// created: 2016-02-23 04:57:04
$subpanel_layout['where'] = ' (meetings.meeting_type = "Session") AND (meetings.class_id = "" OR meetings.class_id IS NULL)';
$subpanel_layout['list_fields'] = array (
    'lesson_number' =>
    array (
        'type' => 'int',
        'default' => true,
        'vname' => 'LBL_LESSON_NUMBER',
        'width' => '10%',
    ),
    'name' =>
    array (
        'name' => 'name',
        'vname' => 'LBL_LIST_SUBJECT',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '15%',
        'default' => true,
    ),
    'session_status' =>
    array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_SESSION_STATUS',
        'width' => '15%',
    ),
  'time_start_end' =>
  array (
    'name' => 'time_start_end',
    'vname' => 'Dates/Times',
    'width' => '15%',
    'default' => true,
    'sort_by' => 'date_start',
  ),
    //    'date_start' =>
    //    array (
    //        'name' => 'date_start',
    //        'vname' => 'LBL_LIST_DATE',
    //        'width' => '15%',
    //        'default' => true,
    //    ),
    //    'date_end' =>
    //    array (
    //        'name' => 'date_end',
    //        'vname' => 'LBL_DATE_END',
    //        'width' => '15%',
    //        'default' => true,
    //    ),
    'duration_cal' =>
    array (
        'type' => 'decimal',
        'vname' => 'LBL_DURATION',
        'width' => '7%',
        'default' => true,
    ),
//    'situation_type' =>
//    array (
//        'name' => 'situation_type',
//        'vname' => 'LBL_TYPE',
//        'width' => '15%',
//        'default' => true,
//    ),
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
    'teaching_type' =>
    array (
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_TEACHING_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'change_teacher_reason' =>
    array (
        'type' => 'text',
        'vname' => 'LBL_CHANGE_TEACHER_REASON',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
    ),
//    'room_name' =>
//    array (
//        'type' => 'relate',
//        'link' => true,
//        'vname' => 'LBL_ROOM_NAME',
//        'id' => 'ROOM_ID',
//        'width' => '10%',
//        'default' => true,
//        'widget_class' => 'SubPanelDetailViewLink',
//        'target_module' => 'C_Rooms',
//        'target_record_key' => 'room_id',
//    ),
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