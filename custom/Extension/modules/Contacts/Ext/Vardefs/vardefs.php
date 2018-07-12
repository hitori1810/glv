<?php
// created: 2014-03-28 15:45:52
$dictionary['Contact']['fields']['picture'] = array(
    'name' => 'picture',
    'vname' => 'LBL_PICTURE_FILE',
    'type' => 'image',
    'dbtype' => 'varchar',
    'comment' => 'Picture file',
    'len' => 255,
    'width' => '120',
    'height' => '',
    'border' => '',
);
$dictionary['Contact']['fields']['password_generated']=array (
    'name' => 'password_generated',
    'vname' => 'LBL_PASS',
    'type' => 'varchar',
    'len' => '50',
);
$dictionary["Contact"]["fields"]["closed_date"] = array (
    'name' => 'closed_date',
    'vname' => 'LBL_CLOSED_DATE',
    'type' => 'date',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
    'massupdate' => 0,
);
$dictionary["Contact"]["fields"]["contact_status"] = array (
    'name' => 'contact_status',
    'vname' => 'LBL_CONTACT_STATUS',
    'type' => 'enum',
    'comments' => '',
    'help' => '',
    'default' => 'Waiting for class',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 20,
    'size' => '20',
    'options' => 'contact_status_list',
    'studio' => 'visible',
    'massupdate' => 0,
);
$dictionary["Contact"]["fields"]["type"] = array (
    'name' => 'type',
    'vname' => 'LBL_TYPE',
    'massupdate' => 0,
    'type' => 'enum',
    'default' => 'Public',
    'len' => '20',
    'options' => 'student_type_list',
    'studio' => 'visible',
);

//Bo sung field non-db cho import HV vao contract
$dictionary["Contact"]["fields"]["checkbox"] = array (
    'name' => 'checkbox',
    'vname' => 'LBL_CHECKBOX',
    'type'        => 'varchar',
    'len'        => '1',
    'source'    => 'non-db',
    'reportable' => false,
    'studio'=>true,
);
$dictionary["Contact"]["fields"]["subpanel_button"] = array (
    'name' => 'subpanel_button',
    'vname' => 'LBL_SUBPANEL_BUTTON',
    'type'        => 'varchar',
    'len'        => '1',
    'source'    => 'non-db',
    'reportable' => false,
    'studio'=>true,
);
// Relationship Student ( 1 - n ) Attendance - Lap Nguyen
$dictionary['Contact']['relationships']['student_attendances'] = array(
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_Attendance',
    'rhs_table'            => 'c_attendance',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);
$dictionary['Contact']['fields']['student_attendances'] = array(
    'name' => 'student_attendances',
    'type' => 'link',
    'relationship' => 'student_attendances',
    'module' => 'C_Attendance',
    'bean_name' => 'C_Attendance',
    'source' => 'non-db',
    'vname' => 'LBL_ATTENDANCE',
);
// END: Relationship Student ( 1 - n ) Attendance - Lap Nguyen


// Relationship Student ( 1 - n ) Delivery Revenue
$dictionary['Contact']['relationships']['student_revenue'] = array(
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_DeliveryRevenue',
    'rhs_table'            => 'c_deliveryrevenue',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary['Contact']['fields']['student_revenue'] = array(
    'name' => 'student_revenue',
    'type' => 'link',
    'relationship' => 'student_revenue',
    'module' => 'C_DeliveryRevenue',
    'bean_name' => 'C_DeliveryRevenue',
    'source' => 'non-db',
    'vname' => 'LBL_DELIVERY_REVENUE',
);
//END: Relationship Student ( 1 - n ) Delivery Revenue

// Relationship Student ( 1 - n ) Carry Forward
$dictionary['Contact']['relationships']['student_forward'] = array(
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_Carryforward',
    'rhs_table'            => 'c_carryforward',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary['Contact']['fields']['student_forward'] = array(
    'name' => 'student_forward',
    'type' => 'link',
    'relationship' => 'student_forward',
    'module' => 'C_Carryforward',
    'bean_name' => 'C_Carryforward',
    'source' => 'non-db',
    'vname' => 'LBL_CARRYFORWARD',
);
$dictionary['Contact']['fields']['lead_source_description'] = array(
    'name' => 'lead_source_description',
    'vname' => 'LBL_LEAD_SOURCE_DESCRIPTION',
    'type' => 'text',
    'group'=>'lead_source',
    'comment' => 'Description of the lead source',
    'rows' => '4',
    'cols' => '40',
);
//END: Relationship Student ( 1 - n ) Carry Forward

$dictionary['Contact']['fields']['last_name']['required']=true;

$dictionary["Contact"]["fields"]["relationship"] = array (
    'name'      => 'relationship',
    'vname'     => 'LBL_RELATIONSHIP',
    'type'      => 'text',
    'source' => 'non-db',
    'studio'    => 'visible',
);
$dictionary["Contact"]["fields"]["describe_relationship"] = array (
    'name'      => 'describe_relationship',
    'vname'     => 'LBL_DESCRIBE_RELATIONSHIP',
    'type'      => 'text',
    'help' => 'help',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'reportable' => true,
    'size' => '20',
    'studio' => 'visible',
    'rows' => '4',
    'cols' => '40',
);

$dictionary['Contact']['fields']['company_name'] = array (
    'name' => 'company_name',
    'vname' => 'LBL_COMPANY_NAME',
    'type' => 'varchar',
    'len' => '255',
    'unified_search' => false,
    'full_text_search' => array('boost' => 3),
);

$dictionary['Contact']['fields']['gender']=array (
    'name' => 'gender',
    'vname' => 'LBL_GENDER',
    'type' => 'enum',
    'massupdate' => 0,
    'default' => ' ',
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
    'len' => 20,
    'size' => '20',
    'options' => 'gender_lead_list',
    'studio' => 'visible',
    'dbType' => 'enum',
    'required'=>true,
);
$dictionary['Contact']['fields']['nationality']=array (
    'name' => 'nationality',
    'vname' => 'LBL_NATIONALITY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
);
$dictionary['Contact']['fields']['occupation']=array (
    'name' => 'occupation',
    'vname' => 'LBL_OCCUPATION',
    'type' => 'varchar',
    'len' => '255',
    'comment' => ''
);
$dictionary['Contact']['fields']['potential']=array (
    'name' => 'potential',
    'vname' => 'LBL_POTENTIAL',
    'type' => 'enum',
    'comments' => '',
    'help' => '',
    'default' => 'Interested',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 20,
    'size' => '20',
    'options' => 'level_Contact_list',
    'studio' => 'visible',
);
$dictionary['Contact']['fields']['guardian_name']=array (
    'name' => 'guardian_name',
    'vname' => 'LBL_GUARDIAN_NAME',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);
$dictionary['Contact']['fields']['other_mobile']=array (
    'name' => 'other_mobile',
    'vname' => 'LBL_OTHER_MOBILE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '100',
    'unified_search' => true,
);
$dictionary['Contact']['fields']['guardian_name_2']=array (
    'name' => 'guardian_name_2',
    'vname' => 'LBL_GUARDIAN_NAME_2',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);

$dictionary["Contact"]["fields"]["school_name"] = array (
    'name' => 'school_name',
    'vname' => 'LBL_SCHOOL_NAME',
    'type' => 'varchar',
    'options' => 'schools_list',
    'len' => '200',
);
$dictionary['Contact']['fields']['nick_name']=array (
    'name' => 'nick_name',
    'vname' => 'LBL_NICK_NAME',
    'type' => 'varchar',
    'len' => '100',
    'comment' => ''
);
$dictionary['Contact']['fields']['prefer_level'] = array (
    'name' => 'prefer_level',
    'vname' => 'LBL_PREFER_LEVEL',
    'type' => 'enum',
    'default' => '',
    'comments' => 'comment',
    'help' => 'help',
    'importable' => 'true',
    'duplicate_merge' => 'enabled',
    'duplicate_merge_dom_value' => '1',
    'audited' => true,
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'full_kind_of_course_list',
    'studio' => 'visible',
    'dependency' => false,
    'massupdate' => false,
);
$dictionary['Contact']['fields']['contact_rela'] = array (
    'name' => 'contact_rela',
    'vname' => 'LBL_CONTACT_RELA',
    'type' => 'enum',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'len' => 20,
    'size' => '20',
    'options' => 'rela_contacts_list',
    'studio' => 'visible',
    'massupdate' => 0,
);

//Custom Relationship JUNIOR. Student - StudentSituation  By Lap Nguyen
$dictionary['Contact']['fields']['ju_studentsituations'] = array (
    'name' => 'ju_studentsituations',
    'type' => 'link',
    'relationship' => 'contact_studentsituations',
    'module' => 'J_StudentSituations',
    'bean_name' => 'J_StudentSituations',
    'source' => 'non-db',
    'vname' => 'LBL_STUDENT_SITUATION',
);
$dictionary['Contact']['relationships']['contact_studentsituations'] = array (
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'J_StudentSituations',
    'rhs_table'            => 'j_studentsituations',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);


//Custom Relationship JUNIOR. Student - Voucher  By Lap Nguyen
$dictionary['Contact']['fields']['ju_vouchers'] = array (
    'name' => 'ju_vouchers',
    'type' => 'link',
    'relationship' => 'contact_vouchers',
    'module' => 'J_Voucher',
    'bean_name' => 'J_Voucher',
    'source' => 'non-db',
    'vname' => 'LBL_VOUCHER',
);
$dictionary['Contact']['relationships']['contact_vouchers'] = array (
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'J_Voucher',
    'rhs_table'            => 'j_voucher',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);

//Custom Relationship JUNIOR. Student - SMS  By Lap Nguyen
$dictionary['Contact']['fields']['contacts_sms'] = array (
    'name' => 'contacts_sms',
    'type' => 'link',
    'relationship' => 'contact_smses',
    'module' => 'C_SMS',
    'bean_name' => 'C_SMS',
    'source' => 'non-db',
    'vname' => 'LBL_STUDENT_SMS',
);
$dictionary['Contact']['relationships']['contact_smses'] = array (
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_SMS',
    'rhs_table'            => 'c_sms',
    'rhs_key'            => 'parent_id',
    'relationship_type'    => 'one-to-many',
);
//add team type
$dictionary['Contact']['fields']['team_type'] = array(
    'name' => 'team_type',
    'vname' => 'LBL_TEAM_TYPE_STUDENT',
    'type' => 'enum',
    'importable' => 'true',
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'type_team_list',
    'studio' => 'visible',
);
// END: add team type

///// Custom checkbox sms
$dictionary['Contact']['fields']['custom_checkbox_class'] =
array (
    'name' => 'custom_checkbox_class',
    'vname' => 'LBL_CHECKBOX',
    'type'        => 'varchar',
    'len'        => '1',
    'source'    => 'non-db',
    'reportable' => false,
    'studio'=>true,
);

$dictionary["Contact"]["fields"]["additional_week"] = array (
    'required' => false,
    'name' => 'additional_week',
    'vname' => 'LBL_ADDITIONAL_WEEK',
    'type' => 'int',
    'len' => 10,
    'size' => '5',
    'default' => '5',
);
$dictionary["Contact"]["fields"]["show_add_week"] = array (
    'name' => 'show_add_week',
    'vname' => 'LBL_SHOW_ADD_WEEK',
    'type' => 'bool',
    'default' => '0',
);

//Somes file for check attendance
$dictionary['Contact']['fields']['contact_attendance'] =
array (
    'name' => 'contact_attendance',
    'rname' => 'id',
    'relationship_fields'=>array(
        'meeting_id' => 'attendance_id',
        'attendance' => 'display_attendance'
    ),
    'vname' => 'LBL_CONTACT_ATTENDANCE',
    'type' => 'relate',
    'link' => 'meetings',
    'link_type' => 'relationship_info',
    'join_link_name' => 'meetings_contacts',
    'source' => 'non-db',
    'importable' => 'false',
    'duplicate_merge'=> 'disabled',
    'studio' => false,
);
$dictionary['Contact']['fields']['display_attendance'] =
array(
    'massupdate' => false,
    'name' => 'display_attendance',
    'type' => 'varchar',
    'studio' => 'false',
    'source' => 'non-db',
    'vname' => 'LBL_ATTENDANCE',
    'importable' => 'false',
);
$dictionary['Contact']['fields']['attendance_id'] =
array(
    'name' => 'attendance_id',
    'type' => 'varchar',
    'source' => 'non-db',
    'vname' => 'LBL_ATTENDANCE_ID',
    'studio' => array('listview' => false),
);
$dictionary['Contact']['fields']['situation_type'] =
array(
    'massupdate' => false,
    'name' => 'situation_type',
    'type' => 'varchar',
    'studio' => 'false',
    'source' => 'non-db',
    'vname' => 'LBL_SITUATION_TYPE',
    'importable' => 'false',
);
//------------ END - Add by Tung Bui -------------------//
$dictionary['Contact']['fields']['student_j_gradebookdetail'] = array(
    'name' => 'student_j_gradebookdetail',
    'type' => 'link',
    'relationship' => 'student_j_gradebookdetail',
    'module' => 'J_GradebookDetail',
    'bean_name' => 'J_GradebookDetail',
    'source' => 'non-db',
    'vname' => 'LBL_GRADEBOOK_DETAIL',
);
$dictionary['Contact']['relationships']['student_j_gradebookdetail'] = array(
    'lhs_module'        => 'Contacts',
    'lhs_table'            => 'contacts',
    'lhs_key'            => 'id',
    'rhs_module'        => 'J_GradebookDetail',
    'rhs_table'            => 'j_gradebookdetail',
    'rhs_key'            => 'student_id',
    'relationship_type'    => 'one-to-many',
);
$dictionary["Contact"]["fields"]["grade"] = array(
    'required' => false,
    'name' => 'grade',
    'vname' => 'LBL_GRADE',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '255',
    'size' => '20',
    'studio' => 'visible',
);


$dictionary["Contact"]["fields"]["old_lead_source"] = array(
    'required' => false,
    'name' => 'old_lead_source',
    'vname' => 'LBL_OLD_LEAD_SOURCE',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => false,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '255',
    'size' => '20',
    'studio' => 'hidden',
);
$dictionary["Contact"]["fields"]["grade"] = array(
    'required' => false,
    'name' => 'grade',
    'vname' => 'LBL_GRADE',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
    'importable' => true,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '255',
    'size' => '20',
    'studio' => 'visible',
);
$dictionary["Contact"]["fields"]["status_description"] = array(         
    'name' => 'status_description',
    'vname' => 'LBL_STATUS_DESCRIPTION',
    'type' => 'text',
    'group'=>'status',                                    
    'massupdate' => false,
    'no_default' => false,
    'importable' => false,
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => '255',
    'size' => '20',
    'studio' => 'visible',
);      
$dictionary["Contact"]["fields"]["last_pt_result"] = array (
    'name' => 'last_pt_result',
    'vname' => 'LBL_LAST_PT_RESULT',
    'type' => 'enum',
    'len' => '255',
    'importable' => 'true',
    'options' => 'full_kind_of_course_list',
    'default' => 'none'
);
$dictionary['Contact']['fields']['dob_day'] = array (
    'required' => false,
    'name' => 'dob_day',
    'vname' => 'LBL_DAY',
    'type' => 'enum',
    'len' => 10,
    'key' => 'dob',
    'options' => 'day_options',
    'studio'    => 'visible',
    'massupdate' => false,
);
$dictionary['Contact']['fields']['dob_month'] = array (
    'required' => false,
    'name' => 'dob_month',
    'vname' => 'LBL_MONTH',
    'type' => 'enum',
    'len' => 10,
    'key' => 'dob',
    'options' => 'month_options',
    'studio'    => 'visible',
    'massupdate' => false,
);
$dictionary['Contact']['fields']['dob_year'] = array (
    'name' => 'dob_year',
    'vname' => 'LBL_YEAR',
    'type' => 'int',
    'dbType' => 'varchar',
    'len' => 5,
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'numeric_range_search_dom',
    'studio'    => 'visible',
    'massupdate' => false,
);
$dictionary['Contact']['fields']['dob_date'] = array(
    'name' => 'dob_date',
    'vname' => 'LBL_BIRTHDATE',
    'massupdate' => false,
    'type' => 'date',    
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
);
$dictionary['Contact']['fields']['last_name']['required']=false;
$dictionary['Contact']['fields']['last_name']['importable']=true;
$dictionary['Contact']['fields']['first_name']['importable']='required';
$dictionary['Contact']['fields']['phone_mobile']['required']=true;
$dictionary['Contact']['fields']['email1']['required']=false;     

$dictionary['Contact']['fields']['phone_work']['unified_search']=false;
$dictionary['Contact']['fields']['assistant_phone']['unified_search']=false;