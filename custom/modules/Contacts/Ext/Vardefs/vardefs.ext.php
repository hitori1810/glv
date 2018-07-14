<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-08-05 14:40:26
$dictionary["Contact"]["fields"]["contacts_contacts_1"] = array (
  'name' => 'contacts_contacts_1',
  'type' => 'link',
  'relationship' => 'contacts_contacts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_CONTACTS_CONTACTS_1_FROM_CONTACTS_L_TITLE',
  'id_name' => 'contacts_contacts_1contacts_ida',
);
$dictionary["Contact"]["fields"]["contacts_contacts_1"] = array (
  'name' => 'contacts_contacts_1',
  'type' => 'link',
  'relationship' => 'contacts_contacts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_CONTACTS_CONTACTS_1_FROM_CONTACTS_R_TITLE',
  'id_name' => 'contacts_contacts_1contacts_ida',
);


// created: 2014-07-15 14:40:52
$dictionary["Contact"]["fields"]["c_classes_contacts_1"] = array (
  'name' => 'c_classes_contacts_1',
  'type' => 'link',
  'relationship' => 'c_classes_contacts_1',
  'source' => 'non-db',
  'module' => 'C_Classes',
  'bean_name' => 'C_Classes',
  'vname' => 'LBL_C_CLASSES_CONTACTS_1_FROM_C_CLASSES_TITLE',
  'id_name' => 'c_classes_contacts_1c_classes_ida',
);


// created: 2015-08-06 14:27:38
$dictionary["Contact"]["fields"]["c_contacts_contacts_1"] = array (
  'name' => 'c_contacts_contacts_1',
  'type' => 'link',
  'relationship' => 'c_contacts_contacts_1',
  'source' => 'non-db',
  'module' => 'C_Contacts',
  'bean_name' => 'C_Contacts',
  'side' => 'right',
  'vname' => 'LBL_C_CONTACTS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'c_contacts_contacts_1c_contacts_ida',
  'link-type' => 'one',
);
$dictionary["Contact"]["fields"]["c_contacts_contacts_1_name"] = array (
  'name' => 'c_contacts_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'c_contacts_contacts_1c_contacts_ida',
  'link' => 'c_contacts_contacts_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["c_contacts_contacts_1c_contacts_ida"] = array (
  'name' => 'c_contacts_contacts_1c_contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_CONTACTS_1_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'c_contacts_contacts_1c_contacts_ida',
  'link' => 'c_contacts_contacts_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-09-23 15:23:34
$dictionary["Contact"]["fields"]["leads_contacts_1"] = array (
  'name' => 'leads_contacts_1',
  'type' => 'link',
  'relationship' => 'leads_contacts_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_CONTACTS_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_contacts_1leads_ida',
);


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

$dictionary['Contact']['fields']['last_name']['required']=true;
                                                             

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
$dictionary['Contact']['fields']['saint']=array (
    'name' => 'saint',
    'vname' => 'LBL_SAINT',
    'type' => 'enum',
    'len' => '100',
    'comment' => '',    
    'merge_filter' => 'disabled',
    'unified_search' => true,
    'function' => 'getSaintListOptions',
);                                     
$dictionary['Contact']['fields']['guardian_saint_1']=array (
    'name' => 'guardian_saint_1',
    'vname' => 'LBL_GUARDIAN_SAINT_1',
    'type' => 'enum',
    'len' => '100',
    'comment' => '',    
    'merge_filter' => 'disabled',
    'unified_search' => true,
    'function' => 'getSaintListOptions',
);                                  
$dictionary['Contact']['fields']['guardian_rela_1']=array (
    'name' => 'guardian_rela_1',
    'vname' => 'LBL_GUARDIAN_RELA_1',
    'type' => 'enum',
    'len' => '100',
    'comment' => '',    
    'merge_filter' => 'disabled',
    'unified_search' => true,
    'default' => 'father',
    'options' => 'contact_guardian_rela_options',
);                                  
$dictionary['Contact']['fields']['guardian_rela_2']=array (
    'name' => 'guardian_rela_2',
    'vname' => 'LBL_GUARDIAN_RELA_2',
    'type' => 'enum',
    'len' => '100',
    'comment' => '',    
    'merge_filter' => 'disabled',
    'unified_search' => true,
    'default' => 'mother',
    'options' => 'contact_guardian_rela_options',
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
$dictionary['Contact']['fields']['guardian_saint_2']=array (
    'name' => 'guardian_saint_2',
    'vname' => 'LBL_GUARDIAN_SAINT_2',
    'type' => 'enum',
    'len' => '100',
    'comment' => '',    
    'merge_filter' => 'disabled',
    'unified_search' => true,
    'function' => 'getSaintListOptions',
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
$dictionary['Contact']['fields']['family_no']=array (
    'name' => 'family_no',
    'vname' => 'LBL_FAMILY_NO',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
); 
$dictionary['Contact']['fields']['address_no']=array (
    'name' => 'address_no',
    'vname' => 'LBL_ADDRESS_NO',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
); 
$dictionary['Contact']['fields']['address_quarter']=array (
    'name' => 'address_quarter',
    'vname' => 'LBL_ADDRESS_QUARTER',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
); 
$dictionary['Contact']['fields']['address_ward']=array (
    'name' => 'address_ward',
    'vname' => 'LBL_ADDRESS_WARD',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['baptism_date'] = array(
    'name' => 'baptism_date',
    'vname' => 'LBL_BAPTISM_DATE',
    'massupdate' => false,
    'type' => 'date',    
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
);
$dictionary['Contact']['fields']['baptism_place']=array (
    'name' => 'baptism_place',
    'vname' => 'LBL_BAPTISM_PLACE',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['baptism_godparent']=array (
    'name' => 'baptism_godparent',
    'vname' => 'LBL_BAPTISM_GODPARENT',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['eucharist_date'] = array(
    'name' => 'eucharist_date',
    'vname' => 'LBL_EUCHARIST_DATE',
    'massupdate' => false,
    'type' => 'date',    
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
); 
$dictionary['Contact']['fields']['eucharist_place']=array (
    'name' => 'eucharist_place',
    'vname' => 'LBL_EUCHARIST_PLACE',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['eucharist_godparent']=array (
    'name' => 'eucharist_godparent',
    'vname' => 'LBL_EUCHARIST_GODPARENT',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
); 
$dictionary['Contact']['fields']['confirmation_date'] = array(
    'name' => 'confirmation_date',
    'vname' => 'LBL_CONFIRMATION_DATE',
    'massupdate' => false,
    'type' => 'date',    
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
);         
$dictionary['Contact']['fields']['confirmation_place']=array (
    'name' => 'confirmation_place',
    'vname' => 'LBL_CONFIRMATION_PLACE',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['confirmation_godparent']=array (
    'name' => 'confirmation_godparent',
    'vname' => 'LBL_CONFIRMATION_GODPARENT',
    'type' => 'varchar',
    'len' => '255',
    'comment' => '',
    'merge_filter' => 'disabled',
    'unified_search' => true,
);  
$dictionary['Contact']['fields']['graduation_date'] = array(
    'name' => 'graduation_date',
    'vname' => 'LBL_GRADUATION_DATE',
    'massupdate' => false,
    'type' => 'date',    
    'key' => 'dob',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
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
$dictionary['Contact']['fields']['team_name']['required']=false;
$dictionary['Contact']['fields']['lead_source']['required']=false;
$dictionary['Contact']['fields']['student_type']['required']=false;
$dictionary['Contact']['fields']['first_name']['required']=false;
$dictionary['Contact']['fields']['last_name']['required']=false;
$dictionary['Contact']['fields']['last_name']['importable']=true;
$dictionary['Contact']['fields']['first_name']['importable']='required';
$dictionary['Contact']['fields']['phone_mobile']['required']=false;
$dictionary['Contact']['fields']['email1']['required']=false;     

$dictionary['Contact']['fields']['phone_work']['unified_search']=false;
$dictionary['Contact']['fields']['assistant_phone']['unified_search']=false;

// created: 2016-05-23 03:55:52
$dictionary['Contact']['fields']['contact_c_sms'] = array (
								  'name' => 'contact_c_sms',
									'type' => 'link',
									'relationship' => 'contact_c_sms',
									'source'=>'non-db',
									'vname'=>'LBL_C_SMS',
							);


?>