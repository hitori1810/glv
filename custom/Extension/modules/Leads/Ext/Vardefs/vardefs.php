<?php
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