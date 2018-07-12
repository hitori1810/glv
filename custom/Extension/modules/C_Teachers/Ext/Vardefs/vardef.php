<?php

    //Custom Relationship. Teacher - Meeting
    $dictionary['C_Teachers']['relationships']['teachers_meetings'] = array(
        'lhs_module'        => 'C_Teachers',
        'lhs_table'            => 'c_teachers',
        'lhs_key'            => 'id',
        'rhs_module'        => 'Meetings',
        'rhs_table'            => 'meetings',
        'rhs_key'            => 'teacher_id',
        'relationship_type'    => 'one-to-many',
    );
    $dictionary['C_Teachers']['fields']['meetings'] = array(
        'name' => 'meetings',
        'type' => 'link',
        'relationship' => 'teachers_meetings',
        'module' => 'Meetings',
        'bean_name' => 'Meetings',
        'source' => 'non-db',
        'vname' => 'LBL_MEETING',
    );
    //END: Custom Relationship

    //Custom Relationship. Teacher Cover - Meeting
    $dictionary['C_Teachers']['relationships']['teachers_cover_meetings'] = array(
        'lhs_module'        => 'C_Teachers',
        'lhs_table'            => 'c_teachers',
        'lhs_key'            => 'id',
        'rhs_module'        => 'Meetings',
        'rhs_table'            => 'meetings',
        'rhs_key'            => 'teacher_cover_id',
        'relationship_type'    => 'one-to-many',
    );

    $dictionary['C_Teachers']['fields']['teacher_cover_meetings'] = array(
        'name' => 'teacher_cover_meetings',
        'type' => 'link',
        'relationship' => 'teachers_cover_meetings',
        'module' => 'Meetings',
        'bean_name' => 'Meetings',
        'source' => 'non-db',
        'vname' => 'LBL_MEETING',
    );
    //END: Custom Relationship

    //Custom Relationship. Teacher - Timesheet
    $dictionary['C_Teachers']['relationships']['timesheet_teacher'] = array(
        'lhs_module'        => 'C_Teachers',
        'lhs_table'            => 'c_teachers',
        'lhs_key'            => 'id',
        'rhs_module'        => 'C_Timesheet',
        'rhs_table'            => 'c_timesheet',
        'rhs_key'            => 'teacher_id',
        'relationship_type'    => 'one-to-many',
    );

    $dictionary['C_Teachers']['fields']['timesheet_teacher'] = array(
        'name' => 'timesheet_teacher',
        'type' => 'link',
        'relationship' => 'timesheet_teacher',
        'module' => 'C_Timesheet',
        'bean_name' => 'C_Timesheet',
        'source' => 'non-db',
        'vname' => 'LBL_TIMESHEET',
    );
    //END: Custom Relationship

    //Custom Relationship. Teacher - Timekeeping
    $dictionary['C_Teachers']['relationships']['teacher_timekeeping'] = array(
        'lhs_module'        => 'C_Teachers',
        'lhs_table'            => 'c_teachers',
        'lhs_key'            => 'id',
        'rhs_module'        => 'c_Timekeeping',
        'rhs_table'            => 'c_timekeeping',
        'rhs_key'            => 'teacher_id',
        'relationship_type'    => 'one-to-many',
    );

    $dictionary['C_Teachers']['fields']['teacher_timekeeping'] = array(
        'name' => 'teacher_timekeeping',
        'type' => 'link',
        'relationship' => 'teacher_timekeeping',
        'module' => 'c_Timekeeping',
        'bean_name' => 'c_Timekeeping',
        'source' => 'non-db',
        'vname' => 'LBL_TIMEKEEPING',
    );
    //END: Custom Relationship
    $dictionary["C_Teachers"]["fields"]["checkbox"] = array (
        'name' => 'checkbox',
        'vname' => 'LBL_CHECKBOX',
        'type'        => 'varchar',
        'len'        => '1',
        'source'    => 'non-db',
        'reportable' => false,
        'studio'=>true,
    );

    //Teacher type
    $dictionary["C_Teachers"]["fields"]["teacher_type"] = array (
        'required' => false,
        'name' => 'teacher_type',
        'vname' => 'LBL_TEACHER_TYPE',
        'type' => 'enum',
        'massupdate' => '1',
        'default' => 'Analyst',
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => 100,
        'size' => '20',
        'options' => 'type_teacher_list',
        'studio' => 'visible',
        'dependency' => false,

    );
    //END teacher type
    //Reason inactive
    $dictionary["C_Teachers"]["fields"]["reason_inactive"] = array (
        'name' => 'reason_inactive',
        'vname' => 'LBL_REASON_INACTIVE',
        'type' => 'text',
        'massupdate' => 0,
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'size' => '20',
        'studio' => 'visible',
        'rows' => '2',
        'cols' => '32',
    );
    //END Reason inactive
    //Current university
    $dictionary["C_Teachers"]["fields"]["current_university"] = array (
        'name' => 'current_university',
        'vname' => 'LBL_CURRENT_UNIVERSITY',
        'type' => 'varchar',
        'massupdate' => 0,
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => '255',
        'size' => '20',

    );
    //END Current university
    //Bank no
    $dictionary["C_Teachers"]["fields"]["bank_no"] = array (
        'name' => 'bank_no',
        'vname' => 'LBL_BANK_NO',
        'type' => 'varchar',
        'massupdate' => 0,
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => '255',
        'size' => '20',
    );
    //END Bank no
    //CITAD code
    $dictionary["C_Teachers"]["fields"]["CITAD_code"] = array (
        'name' => 'CITAD_code',
        'vname' => 'LBL_CITAD_CODE',
        'type' => 'varchar',
        'massupdate' => 0,
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => '255',
        'size' => '20',
    );
    //END CITAD code
    //Avilable time
    $dictionary["C_Teachers"]["fields"]["avilable_time"] = array (
        'name' => 'avilable_time',
        'vname' => 'LBL_AVILABLE_TIME',
        'type' => 'text',
        'massupdate' => 0,
        'comments' => 'comment',
        'help' => 'help',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'size' => '20',
        'studio' => 'visible',
        'rows' => '2',
        'cols' => '32',

    );
    //END Avilable time

    $dictionary['C_Teachers']['fields']['hr_code'] = array(
        'name' => 'hr_code',
        'type' => 'varchar',
        'vname' => 'LBL_HR_CODE',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => '255',
        'size' => '20',
    );

    $dictionary['C_Teachers']['fields']['pit'] = array(
        'name' => 'pit',
        'type' => 'varchar',
        'vname' => 'LBL_PIT',
        'importable' => 'true',
        'duplicate_merge' => 'enabled',
        'duplicate_merge_dom_value' => '1',
        'audited' => true,
        'reportable' => true,
        'len' => '255',
        'size' => '20',
    );

?>
