<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["Lead"]["fields"]["bc_survey_leads"] = array (
  'name' => 'bc_survey_leads',
  'type' => 'link',
  'relationship' => 'bc_survey_leads',
  'source' => 'non-db',
  'module' => 'bc_survey',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_LEADS_FROM_BC_SURVEY_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["Lead"]["fields"]["bc_survey_submission_leads"] = array (
  'name' => 'bc_survey_submission_leads',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_leads',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_BC_SURVEY_SUBMISSION_TITLE',
);


// created: 2015-10-20 14:44:48
$dictionary["Lead"]["fields"]["c_contacts_leads_1"] = array (
  'name' => 'c_contacts_leads_1',
  'type' => 'link',
  'relationship' => 'c_contacts_leads_1',
  'source' => 'non-db',
  'module' => 'C_Contacts',
  'bean_name' => 'C_Contacts',
  'side' => 'right',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link-type' => 'one',
);
$dictionary["Lead"]["fields"]["c_contacts_leads_1_name"] = array (
  'name' => 'c_contacts_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_C_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link' => 'c_contacts_leads_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["c_contacts_leads_1c_contacts_ida"] = array (
  'name' => 'c_contacts_leads_1c_contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link' => 'c_contacts_leads_1',
  'table' => 'c_contacts',
  'module' => 'C_Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2014-10-01 21:30:36
$dictionary["Lead"]["fields"]["c_memberships_leads_1"] = array (
  'name' => 'c_memberships_leads_1',
  'type' => 'link',
  'relationship' => 'c_memberships_leads_1',
  'source' => 'non-db',
  'module' => 'C_Memberships',
  'bean_name' => 'C_Memberships',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE',
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
);
$dictionary["Lead"]["fields"]["c_memberships_leads_1_name"] = array (
  'name' => 'c_memberships_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE',
  'save' => true,
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
  'link' => 'c_memberships_leads_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["c_memberships_leads_1c_memberships_ida"] = array (
  'name' => 'c_memberships_leads_1c_memberships_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_MEMBERSHIPS_LEADS_1_FROM_C_MEMBERSHIPS_TITLE_ID',
  'id_name' => 'c_memberships_leads_1c_memberships_ida',
  'link' => 'c_memberships_leads_1',
  'table' => 'c_memberships',
  'module' => 'C_Memberships',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-10-19 08:53:40
$dictionary["Lead"]["fields"]["j_class_leads_1"] = array (
  'name' => 'j_class_leads_1',
  'type' => 'link',
  'relationship' => 'j_class_leads_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_J_CLASS_LEADS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_leads_1j_class_ida',
);


// created: 2015-08-05 12:09:35
$dictionary["Lead"]["fields"]["j_school_leads_1"] = array (
  'name' => 'j_school_leads_1',
  'type' => 'link',
  'relationship' => 'j_school_leads_1',
  'source' => 'non-db',
  'module' => 'J_School',
  'bean_name' => 'J_School',
  'side' => 'right',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'j_school_leads_1j_school_ida',
  'link-type' => 'one',
);
$dictionary["Lead"]["fields"]["j_school_leads_1_name"] = array (
  'name' => 'j_school_leads_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_J_SCHOOL_TITLE',
  'save' => true,
  'id_name' => 'j_school_leads_1j_school_ida',
  'link' => 'j_school_leads_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'name',
);
$dictionary["Lead"]["fields"]["j_school_leads_1j_school_ida"] = array (
  'name' => 'j_school_leads_1j_school_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_SCHOOL_LEADS_1_FROM_LEADS_TITLE_ID',
  'id_name' => 'j_school_leads_1j_school_ida',
  'link' => 'j_school_leads_1',
  'table' => 'j_school',
  'module' => 'J_School',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$dictionary['Lead']['fields']['email1']['required'] = true;



// created: 2015-09-23 15:23:34
$dictionary["Lead"]["fields"]["leads_contacts_1"] = array (
  'name' => 'leads_contacts_1',
  'type' => 'link',
  'relationship' => 'leads_contacts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_LEADS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'leads_contacts_1contacts_idb',
);


// created: 2014-09-28 15:16:06
$dictionary["Lead"]["fields"]["leads_c_payments_1"] = array (
  'name' => 'leads_c_payments_1',
  'type' => 'link',
  'relationship' => 'leads_c_payments_1',
  'source' => 'non-db',
  'module' => 'C_Payments',
  'bean_name' => 'C_Payments',
  'vname' => 'LBL_LEADS_C_PAYMENTS_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_c_payments_1leads_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-09-07 09:49:06
$dictionary["Lead"]["fields"]["leads_j_ptresult_1"] = array (
  'name' => 'leads_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'leads_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'J_PTResult',
  'bean_name' => 'J_PTResult',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-09-29 17:15:18
$dictionary["Lead"]["fields"]["leads_leads_1"] = array (
  'name' => 'leads_leads_1',
  'type' => 'link',
  'relationship' => 'leads_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_LEADS_1_FROM_LEADS_L_TITLE',
  'id_name' => 'leads_leads_1leads_ida',
);
$dictionary["Lead"]["fields"]["leads_leads_1"] = array (
  'name' => 'leads_leads_1',
  'type' => 'link',
  'relationship' => 'leads_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_LEADS_LEADS_1_FROM_LEADS_R_TITLE',
  'id_name' => 'leads_leads_1leads_ida',
);


//$dictionary['Lead']['fields']['phone_mobile']['type'] = 'function';
//$dictionary['Lead']['fields']['phone_mobile']['function'] = array('name'=>'sms_phone', 'returns'=>'html', 'include'=>'custom/fieldFormat/sms_phone_fields.php');
//



 // created: 2015-11-18 04:58:35
$dictionary['Lead']['fields']['description']['comments']='Full text of the note';
$dictionary['Lead']['fields']['description']['merge_filter']='disabled';
$dictionary['Lead']['fields']['description']['calculated']=false;
$dictionary['Lead']['fields']['description']['rows']='4';
$dictionary['Lead']['fields']['description']['cols']='60';
$dictionary['Lead']['fields']['lead_source_description']['rows']='4';
$dictionary['Lead']['fields']['lead_source_description']['cols']='40';

 

 // created: 2015-08-26 08:36:11
$dictionary['Lead']['fields']['email1']['importable']='true';
$dictionary['Lead']['fields']['email1']['merge_filter']='disabled';
$dictionary['Lead']['fields']['email1']['calculated']=false;

 

 // created: 2015-08-26 08:35:00
$dictionary['Lead']['fields']['first_name']['comments']='First name of the contact';
$dictionary['Lead']['fields']['first_name']['importable']='true';
$dictionary['Lead']['fields']['first_name']['merge_filter']='disabled';
$dictionary['Lead']['fields']['first_name']['calculated']=false;

 

 // created: 2015-08-26 08:36:50
$dictionary['Lead']['fields']['lead_source']['comments']='Lead source (ex: Web, print)';
$dictionary['Lead']['fields']['lead_source']['importable']='required';
$dictionary['Lead']['fields']['lead_source']['merge_filter']='disabled';
$dictionary['Lead']['fields']['lead_source']['calculated']=false;
$dictionary['Lead']['fields']['lead_source']['dependency']=false;

 

 // created: 2015-08-26 08:35:34
$dictionary['Lead']['fields']['phone_mobile']['comments']='Mobile phone number of the contact';
$dictionary['Lead']['fields']['phone_mobile']['importable']='true';
$dictionary['Lead']['fields']['phone_mobile']['merge_filter']='disabled';
$dictionary['Lead']['fields']['phone_mobile']['calculated']=false;
$dictionary['Lead']['fields']['phone_mobile']['audited']=true;
$dictionary['Lead']['fields']['birthdate']['audited']=true;

 

 // created: 2015-08-05 16:23:46
$dictionary['Lead']['fields']['status']['required']=false;
$dictionary['Lead']['fields']['status']['comments']='Status of the lead';
$dictionary['Lead']['fields']['status']['merge_filter']='disabled';
$dictionary['Lead']['fields']['status']['calculated']=false;
$dictionary['Lead']['fields']['status']['dependency']=false;

 

// created: 2014-03-28 15:45:52
$dictionary['Lead']['fields']['gender']=array (
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
$dictionary['Lead']['fields']['nationality']=array (
    'name' => 'nationality',
    'vname' => 'LBL_NATIONALITY',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
);
$dictionary['Lead']['fields']['occupation']=array (
    'name' => 'occupation',
    'vname' => 'LBL_OCCUPATION',
    'type' => 'varchar',
    'len' => '255',
    'comment' => ''
);

$dictionary['Lead']['fields']['potential']=array (
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
    'options' => 'level_lead_list',
    'studio' => 'visible',
);

$dictionary['Lead']['fields']['guardian_name']=array (
    'name' => 'guardian_name',
    'vname' => 'LBL_GUARDIAN_NAME',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
);
$dictionary['Lead']['fields']['guardian_name_2']=array (
    'name' => 'guardian_name_2',
    'vname' => 'LBL_GUARDIAN_NAME_2',
    'type' => 'varchar',
    'len' => '100',
    'comment' => '',
    'merge_filter' => 'disabled',
);


$dictionary["Lead"]["fields"]["school_name"] = array (
    'name' => 'school_name',
    'vname' => 'LBL_SCHOOL_NAME',
    'type' => 'varchar',
    'options' => 'schools_list',
    'len' => '200',
);

$dictionary['Lead']['fields']['nick_name']=array (
    'name' => 'nick_name',
    'vname' => 'LBL_NICK_NAME',
    'type' => 'varchar',
    'len' => '100',
    'comment' => ''
);
$dictionary['Lead']['fields']['other_mobile']=array (
    'name' => 'other_mobile',
    'vname' => 'LBL_OTHER_MOBILE',
    'type' => 'phone',
    'dbType' => 'varchar',
    'len' => '50',
);

$dictionary['Lead']['fields']['last_name']['required']=false;
$dictionary['Lead']['fields']['last_name']['importable']=true;
$dictionary['Lead']['fields']['first_name']['importable']=required;
$dictionary['Lead']['fields']['phone_mobile']['required']=true;

$dictionary['Lead']['fields']['prefer_level'] = array (
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
$dictionary['Lead']['fields']['company_name'] = array (
    'name' => 'company_name',
    'vname' => 'LBL_COMPANY_NAME',
    'type' => 'varchar',
    'len' => '255',
    'unified_search' => false,
);
$dictionary['Lead']['fields']['custom_button'] = array (
    'name' => 'custom_button',
    'vname' => 'LBL_CUSTOM_BUTTON',
    'type' => 'varchar',
    'studio' => 'visible',
    'source' => 'non-db',
);

//add team type
$dictionary['Lead']['fields']['team_type'] = array(
    'name' => 'team_type',
    'vname' => 'LBL_TEAM_TYPE',
    'type' => 'enum',
    'importable' => 'true',
    'reportable' => true,
    'len' => 100,
    'size' => '20',
    'options' => 'type_team_list',
    'studio' => 'visible',
    'massupdate' => 0,
);
// END: add team type

//Custom Relationship. Student - SMS  By Lap Nguyen
$dictionary['Lead']['fields']['leads_sms'] = array (
    'name' => 'leads_sms',
    'type' => 'link',
    'relationship' => 'lead_smses',
    'module' => 'C_SMS',
    'bean_name' => 'C_SMS',
    'source' => 'non-db',
    'vname' => 'LBL_LEAD_SMS',
);
$dictionary['Lead']['relationships']['lead_smses'] = array (
    'lhs_module'        => 'Leads',
    'lhs_table'            => 'leads',
    'lhs_key'            => 'id',
    'rhs_module'        => 'C_SMS',
    'rhs_table'            => 'c_sms',
    'rhs_key'            => 'parent_id',
    'relationship_type'    => 'one-to-many',
);

$dictionary['Lead']['fields']['contact_rela'] = array (
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
$dictionary["Lead"]["fields"]["relationship"] = array (
    'name'      => 'relationship',
    'vname'     => 'LBL_RELATIONSHIP',
    'type'      => 'text',
    'source' => 'non-db',
    'studio'    => 'visible',
);
$dictionary["Lead"]["fields"]["describe_relationship"] = array (
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

//Custom Relationship JUNIOR. Student - StudentSituation  By Nhi Vo
$dictionary['Lead']['fields']['ju_studentsituations'] = array (
    'name' => 'ju_studentsituations',
    'type' => 'link',
    'relationship' => 'lead_studentsituations',
    'module' => 'J_StudentSituations',
    'bean_name' => 'J_StudentSituations',
    'source' => 'non-db',
    'vname' => 'LBL_LEAD_SITUATION',
);
$dictionary['Lead']['relationships']['lead_studentsituations'] = array (
    'lhs_module'        => 'Leads',
    'lhs_table'            => 'leads',
    'lhs_key'            => 'id',
    'rhs_module'        => 'J_StudentSituations',
    'rhs_table'            => 'j_studentsituations',
    'rhs_key'            => 'lead_id',
    'relationship_type'    => 'one-to-many',
);

//Add Relationship Lead - Payment (Thu tiền Placement Test)
$dictionary['Lead']['relationships']['lead_payments'] = array(
    'lhs_module' => 'Leads',
    'lhs_table' => 'leads',
    'lhs_key' => 'id',
    'rhs_module' => 'J_Payment',
    'rhs_table' => 'j_payment',
    'rhs_key' => 'lead_id',
    'relationship_type' => 'one-to-many'
);
$dictionary['Lead']['fields']['payment_link'] = array(
    'name' => 'payment_link',
    'type' => 'link',
    'relationship' => 'lead_payments',
    'module' => 'J_Payment',
    'bean_name' => 'J_Payment',
    'source' => 'non-db',
    'vname' => 'LBL_PAYMENT_NAME',
);

//Add by tung - Field for Import

$dictionary["Lead"]["fields"]["old_lead_source"] = array(
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
$dictionary["Lead"]["fields"]["grade"] = array(
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


$dictionary["Lead"]["fields"]["prospect_list_leads"] = array (   
    'name' => 'prospect_list_leads',   
    'type' => 'link',   
    'relationship' => 'prospect_list_leads',   
    'source' => 'non-db',   
    'vname' => 'LBL_PROSPECT_LIST', 
); 
$dictionary["Lead"]["fields"]["last_pt_result"] = array (
    'name' => 'last_pt_result',
    'vname' => 'LBL_LAST_PT_RESULT',
    'type' => 'enum',
    'len' => '255',
    'importable' => 'true',
    'options' => 'full_kind_of_course_list',
    'default' => 'none'
);       

$dictionary['Lead']['fields']['email1']['required']=false;        
$dictionary['Lead']['fields']['birthdate']['required']=false;      

$dictionary['Lead']['fields']['other_mobile']['unified_search']=true;
$dictionary['Lead']['fields']['phone_home']['unified_search']=false;
$dictionary['Lead']['fields']['phone_work']['unified_search']=false;
$dictionary['Lead']['fields']['assistant_phone']['unified_search']=false;
$dictionary['Lead']['fields']['account_description']['unified_search']=false;

$dictionary['Lead']['fields']['status']['default']='New';

// created: 2016-05-23 03:55:52
$dictionary['Lead']['fields']['lead_c_sms'] = array (
								  'name' => 'lead_c_sms',
									'type' => 'link',
									'relationship' => 'lead_c_sms',
									'source'=>'non-db',
									'vname'=>'LBL_C_SMS',
							);


?>