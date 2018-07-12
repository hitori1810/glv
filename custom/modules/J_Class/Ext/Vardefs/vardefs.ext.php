<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2016-11-25 10:58:12
$dictionary["J_Class"]["fields"]["contracts_j_class_1"] = array (
  'name' => 'contracts_j_class_1',
  'type' => 'link',
  'relationship' => 'contracts_j_class_1',
  'source' => 'non-db',
  'module' => 'Contracts',
  'bean_name' => 'Contract',
  'vname' => 'LBL_CONTRACTS_J_CLASS_1_FROM_CONTRACTS_TITLE',
  'id_name' => 'contracts_j_class_1contracts_ida',
);


// created: 2015-08-14 09:21:53
$dictionary["J_Class"]["fields"]["j_class_contacts_1"] = array (
  'name' => 'j_class_contacts_1',
  'type' => 'link',
  'relationship' => 'j_class_contacts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_J_CLASS_CONTACTS_1_FROM_CONTACTS_TITLE',
  'id_name' => 'j_class_contacts_1contacts_idb',
);


// created: 2015-08-11 08:50:10
$dictionary["J_Class"]["fields"]["j_class_c_teachers_1"] = array (
  'name' => 'j_class_c_teachers_1',
  'type' => 'link',
  'relationship' => 'j_class_c_teachers_1',
  'source' => 'non-db',
  'module' => 'C_Teachers',
  'bean_name' => 'C_Teachers',
  'vname' => 'LBL_J_CLASS_C_TEACHERS_1_FROM_C_TEACHERS_TITLE',
  'id_name' => 'j_class_c_teachers_1c_teachers_idb',
);

//Trung Nguyen 2015.12.12
unset($dictionary["J_Class"]["fields"]["j_class_c_teachers_1"]);


// created: 2015-07-16 16:15:52
$dictionary["J_Class"]["fields"]["j_class_j_class_1"] = array (
  'name' => 'j_class_j_class_1',
  'type' => 'link',
  'relationship' => 'j_class_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_L_TITLE',
  'id_name' => 'j_class_j_class_1j_class_idb',
  'link-type' => 'many',
  'side' => 'left',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1_right"] = array (
  'name' => 'j_class_j_class_1_right',
  'type' => 'link',
  'relationship' => 'j_class_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_R_TITLE',
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1_name"] = array (
  'name' => 'j_class_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_L_TITLE',
  'save' => true,
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link' => 'j_class_j_class_1_right',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1j_class_ida"] = array (
  'name' => 'j_class_j_class_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_R_TITLE_ID',
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link' => 'j_class_j_class_1_right',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-07-16 09:00:12
$dictionary["J_Class"]["fields"]["j_class_j_feedback_1"] = array (
  'name' => 'j_class_j_feedback_1',
  'type' => 'link',
  'relationship' => 'j_class_j_feedback_1',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_j_feedback_1j_class_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2016-05-12 11:08:29
$dictionary["J_Class"]["fields"]["j_class_j_gradebook_1"] = array (
  'name' => 'j_class_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'j_class_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'J_Gradebook',
  'bean_name' => 'J_Gradebook',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE',
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2017-10-12 01:41:43
$dictionary["J_Class"]["fields"]["j_class_j_teachercontract_1"] = array (
  'name' => 'j_class_j_teachercontract_1',
  'type' => 'link',
  'relationship' => 'j_class_j_teachercontract_1',
  'source' => 'non-db',
  'module' => 'J_Teachercontract',
  'bean_name' => 'J_Teachercontract',
  'vname' => 'LBL_J_CLASS_J_TEACHERCONTRACT_1_FROM_J_TEACHERCONTRACT_TITLE',
  'id_name' => 'j_class_j_teachercontract_1j_class_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-10-19 08:53:40
$dictionary["J_Class"]["fields"]["j_class_leads_1"] = array (
  'name' => 'j_class_leads_1',
  'type' => 'link',
  'relationship' => 'j_class_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_J_CLASS_LEADS_1_FROM_LEADS_TITLE',
  'id_name' => 'j_class_leads_1leads_idb',
);


// created: 2015-08-24 09:03:46
$dictionary["J_Class"]["fields"]["j_coursefee_j_class_1"] = array (
  'name' => 'j_coursefee_j_class_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Coursefee',
  'bean_name' => 'J_Coursefee',
  'side' => 'right',
  'vname' => 'LBL_J_COURSEFEE_J_CLASS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_coursefee_j_class_1j_coursefee_ida',
  'link-type' => 'one',
);
$dictionary["J_Class"]["fields"]["j_coursefee_j_class_1_name"] = array (
  'name' => 'j_coursefee_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_CLASS_1_FROM_J_COURSEFEE_TITLE',
  'save' => true,
  'id_name' => 'j_coursefee_j_class_1j_coursefee_ida',
  'link' => 'j_coursefee_j_class_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'name',
);
$dictionary["J_Class"]["fields"]["j_coursefee_j_class_1j_coursefee_ida"] = array (
  'name' => 'j_coursefee_j_class_1j_coursefee_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_CLASS_1_FROM_J_CLASS_TITLE_ID',
  'id_name' => 'j_coursefee_j_class_1j_coursefee_ida',
  'link' => 'j_coursefee_j_class_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


 // created: 2015-07-22 20:14:54
$dictionary['J_Class']['fields']['description']['comments']='Full text of the note';
$dictionary['J_Class']['fields']['description']['merge_filter']='disabled';
$dictionary['J_Class']['fields']['description']['calculated']=false;
$dictionary['J_Class']['fields']['description']['rows']='4';
$dictionary['J_Class']['fields']['description']['cols']='30';

 

 // created: 2016-08-10 09:22:05
$dictionary['J_Class']['fields']['name']['unified_search']=false;
$dictionary['J_Class']['fields']['name']['calculated']=false;
$dictionary['J_Class']['fields']['name']['required']=false;

 

 // created: 2015-08-05 15:52:32
$dictionary['J_Class']['fields']['start_date']['display_default']='now';

 

    $dictionary['J_Class']['unified_search'] = true;

    $dictionary['J_Class']['unified_search_default_enabled'] = true;

    $dictionary['J_Class']['fields']['name']['unified_search'] = true; 

    $dictionary["J_Class"]["fields"]["main_teacher"] = array (
        'name' => 'main_teacher',
        'vname' => 'LBL_MAIN_TEACHER',
        'type' => 'text',
        'source' => 'non-db',
    );
    $dictionary["J_Class"]["fields"]["covered_teacher"] = array (
        'name' => 'covered_teacher',
        'vname' => 'LBL_COVERED_TEACHER',
        'type' => 'text',
        'source' => 'non-db',
    );
?>