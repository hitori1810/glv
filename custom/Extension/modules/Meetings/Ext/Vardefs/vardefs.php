<?php
// created: 2014-03-28 15:45:52
$dictionary['Meeting']['fields']['meeting_type']=array (
    'name' => 'meeting_type',
    'vname' => 'LBL_MEETING_TYPE',
    'type' => 'enum',
    'len' => 100,
    'comment' => 'Meeting type (ex: Meeting, Testing  )',
    'options' => 'type_meeting_list',
    'default'    => 'Testing',
    'massupdate' => false,
);
$dictionary["Meeting"]["fields"]["option_date1"] = array (
    'name' => 'option_date1',
    'vname' => 'LBL_OPTION_DATE_1',
    'type' => 'datetimecombo',
    'dbType' => 'datetime',
    'massupdate'=>false,
    'full_text_search' => array('boost' => 3),
    'options' => 'date_range_search_dom',
    'studio' => array('wirelesseditview'=>false),
);
$dictionary["Meeting"]["fields"]["option_date2"] = array (
    'name' => 'option_date2',
    'vname' => 'LBL_OPTION_DATE_2',
    'type' => 'datetimecombo',
    'dbType' => 'datetime',
    'massupdate'=>false,
    'full_text_search' => array('boost' => 3),
    'options' => 'date_range_search_dom',
    'studio' => array('wirelesseditview'=>false),
);
$dictionary["Meeting"]["fields"]["option_date3"] = array (
    'name' => 'option_date3',
    'vname' => 'LBL_OPTION_DATE_3',
    'type' => 'datetimecombo',
    'dbType' => 'datetime',
    'massupdate'=>false,
    'full_text_search' => array('boost' => 3),
    'options' => 'date_range_search_dom',
    'studio' => array('wirelesseditview'=>false),
);

$dictionary["Meeting"]['fields']['meeting_module'] = array (
    'name' => 'meeting_module',
    'vname' => 'LBL_MEETING_MODULE',
    'type' => 'varchar',
    'len' => '30',
    'duplicate_merge'=> 'disabled',
);

//Custom Relationship. Class - Meeting  By Lap Nguyen

$dictionary['Meeting']['fields']['class_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'class_name',
    'vname'     => 'LBL_CLASS_NAME',
    'type'      => 'relate',
    'rname'     => 'name',
    'id_name'   => 'class_id',
    'join_name' => 'c_classes',
    'link'      => 'c_classes',
    'table'     => 'c_classes',
    'isnull'    => 'true',
    'module'    => 'C_Classes',
);

$dictionary['Meeting']['fields']['class_id'] = array(
    'name'              => 'class_id',
    'rname'             => 'id',
    'vname'             => 'LBL_CLASS_ID',
    'type'              => 'id',
    'table'             => 'c_classes',
    'isnull'            => 'true',
    'module'            => 'C_Classes',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['c_classes'] = array(
    'name'          => 'c_classes',
    'type'          => 'link',
    'relationship'  => 'classes_meetings',
    'module'        => 'C_Classes',
    'bean_name'     => 'C_Classes',
    'source'        => 'non-db',
    'vname'         => 'LBL_CLASS',
);

//Custom Relationship JUNIOR. Class - Meeting  By Lap Nguyen
$dictionary['Meeting']['fields']['ju_class_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'ju_class_name',
    'vname'     => 'LBL_JU_CLASS_NAME',
    'type'      => 'relate',
    'rname'     => 'name',
    'id_name'   => 'ju_class_id',
    'join_name' => 'j_class',
    'link'      => 'j_classes',
    'table'     => 'j_class',
    'isnull'    => 'true',
    'module'    => 'J_Class',
);

$dictionary['Meeting']['fields']['ju_class_id'] = array(
    'name'              => 'ju_class_id',
    'rname'             => 'id',
    'vname'             => 'LBL_JU_CLASS_ID',
    'type'              => 'id',
    'table'             => 'j_class',
    'isnull'            => 'true',
    'module'            => 'J_Class',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['j_classes'] = array(
    'name'          => 'j_classes',
    'type'          => 'link',
    'relationship'  => 'j_class_meetings',
    'module'        => 'J_Class',
    'bean_name'     => 'J_Class',
    'source'        => 'non-db',
    'vname'         => 'LBL_JU_CLASS_NAME',
);

//Custom Relationship JUNIOR. Admin Hour - Meeting  By Lap Nguyen
$dictionary['Meeting']['fields']['timesheet_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'timesheet_name',
    'vname'     => 'LBL_TIMESHEET_NAME',
    'type'      => 'relate',
    'rname'     => 'name',
    'id_name'   => 'timesheet_id',
    'join_name' => 'c_timesheet',
    'link'      => 'c_timesheet_link',
    'table'     => 'c_timesheet',
    'isnull'    => 'true',
    'module'    => 'C_Timesheet',
);

$dictionary['Meeting']['fields']['timesheet_id'] = array(
    'name'              => 'timesheet_id',
    'rname'             => 'id',
    'vname'             => 'LBL_TIMESHEET_ID',
    'type'              => 'id',
    'table'             => 'c_timesheet',
    'isnull'            => 'true',
    'module'            => 'C_Timesheet',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['c_timesheet_link'] = array(
    'name'          => 'c_timesheet_link',
    'type'          => 'link',
    'relationship'  => 'c_timesheet_meeting',
    'module'        => 'C_Timesheet',
    'bean_name'     => 'C_Timesheet',
    'source'        => 'non-db',
    'vname'         => 'LBL_TIMESHEET_NAME',
);
$dictionary['Meeting']['fields']['teacher_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'teacher_name',
    'vname'     => 'LBL_TEACHER_NAME',
    'type'      => 'relate',
    'rname'     => 'full_teacher_name',
    'id_name'   => 'teacher_id',
    'join_name' => 'c_teachers',
    'link'      => 'c_teachers',
    'table'     => 'c_teachers',
    'isnull'    => 'true',
    'module'    => 'C_Teachers',
);

$dictionary['Meeting']['fields']['teacher_id'] = array(
    'name'              => 'teacher_id',
    'rname'             => 'id',
    'vname'             => 'LBL_TEACHER_ID',
    'type'              => 'id',
    'table'             => 'c_teachers',
    'isnull'            => 'true',
    'module'            => 'C_Teachers',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['c_teachers'] = array(
    'name'          => 'c_teachers',
    'type'          => 'link',
    'relationship'  => 'teachers_meetings',
    'module'        => 'C_Teachers',
    'bean_name'     => 'C_Teachers',
    'source'        => 'non-db',
    'vname'         => 'LBL_TEACHER_NAME',
);

//Custom Relationship Cover Teacher - 19/08/2014 - by MTN

$dictionary['Meeting']['fields']['teacher_cover_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'teacher_cover_name',
    'vname'     => 'LBL_TEACHER_COVER_NAME',
    'type'      => 'relate',
    'rname'     => 'name',
    'id_name'   => 'teacher_cover_id',
    'join_name' => 'c_teachers',
    'link'      => 'c_teachers',
    'table'     => 'c_teachers',
    'isnull'    => 'true',
    'module'    => 'C_Teachers',
);

$dictionary['Meeting']['fields']['teacher_cover_id'] = array(
    'name'              => 'teacher_cover_id',
    'rname'             => 'id',
    'vname'             => 'LBL_TEACHER_COVER_ID',
    'type'              => 'id',
    'table'             => 'c_teachers',
    'isnull'            => 'true',
    'module'            => 'C_Teachers',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['c_teachers_cover'] = array(
    'name'          => 'c_teachers_cover',
    'type'          => 'link',
    'relationship'  => 'teachers_cover_meetings',
    'module'        => 'C_Teachers',
    'bean_name'     => 'C_Teachers',
    'source'        => 'non-db',
    'vname'         => 'LBL_TEACHER_COVER_NAME',
);

//Custom Relationship Room - 22/07/2014
$dictionary['Meeting']['fields']['room_name'] = array(
    'required'  => false,
    'source'    => 'non-db',
    'name'      => 'room_name',
    'vname'     => 'LBL_ROOM_NAME',
    'type'      => 'relate',
    'rname'     => 'name',
    'id_name'   => 'room_id',
    'join_name' => 'c_rooms',
    'link'      => 'c_rooms',
    'table'     => 'c_rooms',
    'isnull'    => 'true',
    'module'    => 'C_Rooms',
);

$dictionary['Meeting']['fields']['room_id'] = array(
    'name'              => 'room_id',
    'rname'             => 'id',
    'vname'             => 'LBL_ROOM_ID',
    'type'              => 'id',
    'table'             => 'c_rooms',
    'isnull'            => 'true',
    'module'            => 'C_Rooms',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);

$dictionary['Meeting']['fields']['c_rooms'] = array(
    'name'          => 'c_rooms',
    'type'          => 'link',
    'relationship'  => 'rooms_meetings',
    'module'        => 'C_Rooms',
    'bean_name'     => 'C_Rooms',
    'source'        => 'non-db',
    'vname'         => 'LBL_ROOM_NAME',
);
//END: Custom Relationship

//Add new field - 22/07/2014

$dictionary['Meeting']['fields']['session_status'] = array(
    'required' => false,
    'name' => 'session_status',
    'vname' => 'LBL_SESSION_STATUS',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => 100,
    'size' => '20',
    'options' => 'session_status_list',
    'studio' => 'visible',
    'dependency' => false,
);

//add new field to custom button in subpanel
$dictionary['Meeting']['fields']['subpanel_button'] = array(
    'name' => 'subpanel_button',
    'vname' => 'LBL_SUBPANEL_BUTTON',
    'type' => 'varchar',
    'len' => '1',
    'studio' => 'visible',
    'source' => 'non-db',
);

// Relationship Session ( 1 - n ) Attendance - Lap Nguyen
$dictionary['Meeting']['relationships']['meeting_attendances'] = array(
    'lhs_module'        => 'Meetings',
    'lhs_table'            => 'meetings',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_Attendance',
    'rhs_table'            => 'c_attendance',
    'rhs_key'            => 'meeting_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary['Meeting']['fields']['meeting_attendances'] = array(
    'name' => 'meeting_attendances',
    'type' => 'link',
    'relationship' => 'meeting_attendances',
    'module' => 'C_Attendance',
    'bean_name' => 'C_Attendance',
    'source' => 'non-db',
    'vname' => 'LBL_ATTENDANCE',
);
// Relationship Session ( 1 - n ) Attendance - Lap Nguyen

$dictionary['Meeting']['fields']['session_type'] = array(
    'required' => false,
    'name' => 'session_type',
    'vname' => 'LBL_SESSION_TYPE',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => '',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => 100,
    'size' => '20',
    'options' => 'session_type_list',
    'studio' => 'visible',
    'dependency' => false,
    'source' => 'non-db',
);


// Relationship Session ( 1 - n ) Delivery Revenue
$dictionary['Meeting']['relationships']['session_revenue'] = array(
    'lhs_module'        => 'Meetings',
    'lhs_table'            => 'meetings',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_DeliveryRevenue',
    'rhs_table'            => 'c_deliveryrevenue',
    'rhs_key'            => 'session_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary['Meeting']['fields']['session_revenue'] = array(
    'name' => 'session_revenue',
    'type' => 'link',
    'relationship' => 'session_revenue',
    'module' => 'C_DeliveryRevenue',
    'bean_name' => 'C_DeliveryRevenue',
    'source' => 'non-db',
    'vname' => 'LBL_DELIVERY_REVENUE',
);
//END: Relationship Session ( 1 - n ) Delivery Revenue

//add Field - 16/01/2015
$dictionary['Meeting']['fields']['week_date'] = array(
    'required' => false,
    'name' => 'week_date',
    'vname' => 'LBL_WEEK_DATE',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '30',
    'size' => '20',
);
$dictionary['Meeting']['fields']['time_start_end'] = array(
    'required' => false,
    'name' => 'time_start_end',
    'vname' => 'LBL_TIME',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '50',
    'size' => '20',
    'source'    => 'non-db',
);
$dictionary['Meeting']['fields']['num_of_student_last_week'] = array(
    'required' => true,
    'name' => 'num_of_student_last_week',
    'vname' => 'LBL_NUM_OF_LAST_WEEK',
    'type' => 'int',
    'dbType' => 'varchar',
    'len' => 15,
    'enable_range_search' => false,
    'source'    => 'non-db',
);
$dictionary['Meeting']['fields']['num_of_student_this_week'] = array(
    'required' => true,
    'name' => 'num_of_student_this_week',
    'vname' => 'LBL_NUM_OF_THIS_WEEK',
    'type' => 'int',
    'dbType' => 'varchar',
    'len' => 15,
    'enable_range_search' => false,
    'source'    => 'non-db',
);
$dictionary['Meeting']['fields']['week_per_total'] = array(
    'required' => false,
    'name' => 'week_per_total',
    'vname' => 'LBL_WEEK_PER_TOTAL',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '30',
    'size' => '20',
    'source'    => 'non-db',
);
//END: Add Field - 16/01/2015



//////////////////////////////--------------Apolo Junior-----------------///////////////////////////////////

//add field for PT Result Junio By Quyen Cao
$dictionary['Meeting']['fields']['first_time'] =
array (
    'required' => false,
    'name' => 'first_time',
    'vname' => 'LBL_FIRST_TIME',
    'type' => 'datetimecombo',
    'massupdate' => '1',
    'help' => '',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'audited' => true,
    'reportable' => true,
    'size' => '20',
    'enable_range_search' => false,
    'dbType' => 'datetime',
);

$dictionary['Meeting']['fields']['first_duration'] =
array (
    'name' => 'first_duration',
    'vname' => 'LBL_FIRST_DURATION',
    'required' => false,
    'type' => 'decimal',
    'len' => '13',
    'precision' => '2',
    'enable_range_search' => true,
);

$dictionary['Meeting']['fields']['time_range'] =
array (
    'name' => 'time_range',
    'vname' => 'LBL_TIME_RANGE',
    'required' => false,
    'type' => 'int',
    'len' => '13',
    'enable_range_search' => true,
);
$dictionary['Meeting']['fields']['custom_button'] = array (
    'name' => 'custom_button',
    'vname' => 'LBL_CUSTOM_BUTTON',
    'type' => 'varchar',
    'studio' => 'visible',
    'source' => 'non-db',
);

// Add by Tung Bui in 08/01/2016 - fields for change teacher action

$dictionary['Meeting']['fields']['teaching_type'] = array(
    'required' => false,
    'name' => 'teaching_type',
    'vname' => 'LBL_TEACHING_TYPE',
    'type' => 'enum',
    'no_default' => false,
    'importable' => 'false',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => 100,
    'size' => '20',
    'options' => 'teaching_type_options',
    'studio' => 'visible',
    'dependency' => false,
);
$dictionary['Meeting']['fields']['change_teacher_reason'] = array (
    'name' => 'change_teacher_reason',
    'vname' => 'LBL_CHANGE_TEACHER_REASON',
    'type' => 'text',
);

//END - Add by Tung Bui in 08/01/2016 - fields for change teacher action

$dictionary['Meeting']['fields']['ju_contract_id'] = array(
    'name'              => 'ju_contract_id',
    'rname'             => 'id',
    'vname'             => 'LBL_CONTRACT_ID',
    'type'              => 'id',
    'table'             => 'j_teachercontract',
    'isnull'            => 'true',
    'module'            => 'J_Teachercontract',
    'dbType'            => 'id',
    'reportable'        => false,
    'massupdate'        => false,
    'duplicate_merge'   => 'disabled',
);


//////////////////////////////--------------End Apolo Junior-----------------///////////////////////////////
//add field by Trung Nguyen at 2015.11.26
$dictionary['Meeting']['fields']['cancel_date'] = array (
    'name' => 'cancel_date',
    'vname' => 'LBL_DATE_CANCEL',
    'type' => 'datetime',
    'comment' => 'Date record cancel',
    'enable_range_search' => true,
    'studio' =>
    array (
        'portaleditview' => false,
    ),
    'options' => 'date_range_search_dom',
);
$dictionary['Meeting']['fields']['cancel_by'] = array (
    'name' => 'cancel_by',
    'vname' => 'LBL_CANCEL_BY',
    'type' => 'enum',
    'options' => 'session_cancel_reason_options',
    'len' => 30,
);
$dictionary['Meeting']['fields']['cancel_reason'] = array (
    'name' => 'cancel_reason',
    'vname' => 'LBL_CANCEL_REASON',
    'type' => 'text',
);
//2015.12.01
$dictionary['Meeting']['fields']['makeup_session_name'] = array (
    'required' => false,
    'source' => 'non-db',
    'name' => 'makeup_session_name',
    'vname' => 'LBL_MAKEUP_SESSION',
    'type' => 'relate',
    'id_name' => 'makeup_session_id',
    'ext2' => 'Meetings',
    'module' => 'Meetings',
    'rname' => 'name',
    'quicksearch' => 'enabled',
    'studio' => 'visible',
);
$dictionary['Meeting']['fields']['makeup_session_id'] = array (
    'required' => false,
    'name' => 'makeup_session_id',
    'vname' => 'LBL_MAKEUP_SESSION_MEETING_ID',
    'type' => 'id',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'merge_filter' => 'disabled',
    'len' => '36',
);
//end
 // created: 2016-09-18 12:18:19
$dictionary['Meeting']['fields']['description']['comments']='Full text of the note';
$dictionary['Meeting']['fields']['description']['merge_filter']='disabled';
$dictionary['Meeting']['fields']['description']['calculated']=false;
$dictionary['Meeting']['fields']['description']['rows']='3';
$dictionary['Meeting']['fields']['description']['cols']='20';
?>