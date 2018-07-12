<?php
// created: 2015-08-26 15:20:15
$subpanel_layout['list_fields'] = array (
    'meetings_j_ptresult_1_name' =>
    array (
        'type' => 'relate',
        'link' => true,
        'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_MEETINGS_TITLE',
        'id' => 'MEETINGS_J_PTRESULT_1MEETINGS_IDA',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Meetings',
        'target_record_key' => 'meetings_j_ptresult_1meetings_ida',
    ),
    'time_start' =>
    array (
        'type' => 'datetimecombo',
        'vname' => 'LBL_TIME_START',
        'width' => '10%',
        'default' => true,
    ),
    'time_end' =>
    array (
        'type' => 'datetimecombo',
        'vname' => 'LBL_TIME_END',
        'width' => '10%',
        'default' => true,
    ),
    'listening' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_LISTENING',
        'width' => '7%',
        'default' => true,
    ),
    'speaking' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_SPEAKING',
        'width' => '7%',
        'default' => true,
    ),
    'reading' =>
    array (
        'type' => 'reading',
        'vname' => 'LBL_READING',
        'width' => '7%',
        'default' => true,
    ),
    'writing' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_WRITING',
        'width' => '7%',
        'default' => true,
    ),
    'score' =>
    array (
        'type' => 'score',
        'vname' => 'LBL_SCORE',
        'width' => '10%',
        'default' => true,
    ),
    'result' =>
    array (
        'type' => 'result',
        'vname' => 'LBL_RESULT',
        'width' => '10%',
        'default' => true,
    ),
    'ec_note' =>
    array (
        'type' => 'text',
        'vname' => 'LBL_EC_NOTE',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
    ),
    'teacher_comment' =>
    array (
        'type' => 'text',
        'vname' => 'LBL_TEACHER_COMMENT',
        'sortable' => false,
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
    'custom_button' =>
    array (
        'studio' => true,
        'width' => '15%',
        'sortable' => false,
        'default' => true,
    ),
);