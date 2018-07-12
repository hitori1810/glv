<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2014-07-15 14:43:15
$dictionary["C_Teachers"]["fields"]["c_classes_c_teachers_1"] = array (
  'name' => 'c_classes_c_teachers_1',
  'type' => 'link',
  'relationship' => 'c_classes_c_teachers_1',
  'source' => 'non-db',
  'module' => 'C_Classes',
  'bean_name' => 'C_Classes',
  'vname' => 'LBL_C_CLASSES_C_TEACHERS_1_FROM_C_CLASSES_TITLE',
  'id_name' => 'c_classes_c_teachers_1c_classes_ida',
);


// created: 2016-07-27 11:01:41
$dictionary["C_Teachers"]["fields"]["c_teachers_j_gradebook_1"] = array (
  'name' => 'c_teachers_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'c_teachers_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'J_Gradebook',
  'bean_name' => 'J_Gradebook',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE',
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-07-24 11:57:42
$dictionary["C_Teachers"]["fields"]["c_teachers_j_teachercontract_1"] = array (
  'name' => 'c_teachers_j_teachercontract_1',
  'type' => 'link',
  'relationship' => 'c_teachers_j_teachercontract_1',
  'source' => 'non-db',
  'module' => 'J_Teachercontract',
  'bean_name' => 'J_Teachercontract',
  'vname' => 'LBL_C_TEACHERS_J_TEACHERCONTRACT_1_FROM_C_TEACHERS_TITLE',
  'id_name' => 'c_teachers_j_teachercontract_1c_teachers_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-08-11 08:50:10
$dictionary["C_Teachers"]["fields"]["j_class_c_teachers_1"] = array (
  'name' => 'j_class_c_teachers_1',
  'type' => 'link',
  'relationship' => 'j_class_c_teachers_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_J_CLASS_C_TEACHERS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_c_teachers_1j_class_ida',
);


$dictionary['C_Teachers']['fields']['phone_mobile']['type'] = 'function';
$dictionary['C_Teachers']['fields']['phone_mobile']['function'] = array('name'=>'sms_phone', 'returns'=>'html', 'include'=>'custom/fieldFormat/sms_phone_fields.php');




 // created: 2015-08-03 14:45:09
$dictionary['C_Teachers']['fields']['experience']['cols']='60';

 


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




// created: 2016-05-23 03:55:52
$dictionary['C_Teachers']['fields']['c_teachers_c_sms'] = array (
								  'name' => 'c_teachers_c_sms',
									'type' => 'link',
									'relationship' => 'c_teachers_c_sms',
									'source'=>'non-db',
									'vname'=>'LBL_C_SMS',
							);


?>