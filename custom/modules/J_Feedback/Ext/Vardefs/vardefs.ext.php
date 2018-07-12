<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-07-16 08:57:21
$dictionary["J_Feedback"]["fields"]["contacts_j_feedback_1"] = array (
  'name' => 'contacts_j_feedback_1',
  'type' => 'link',
  'relationship' => 'contacts_j_feedback_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'contacts_j_feedback_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["J_Feedback"]["fields"]["contacts_j_feedback_1_name"] = array (
  'name' => 'contacts_j_feedback_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_j_feedback_1contacts_ida',
  'link' => 'contacts_j_feedback_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Feedback"]["fields"]["contacts_j_feedback_1contacts_ida"] = array (
  'name' => 'contacts_j_feedback_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'contacts_j_feedback_1contacts_ida',
  'link' => 'contacts_j_feedback_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-07-16 09:00:12
$dictionary["J_Feedback"]["fields"]["j_class_j_feedback_1"] = array (
  'name' => 'j_class_j_feedback_1',
  'type' => 'link',
  'relationship' => 'j_class_j_feedback_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'j_class_j_feedback_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["J_Feedback"]["fields"]["j_class_j_feedback_1_name"] = array (
  'name' => 'j_class_j_feedback_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
  'save' => true,
  'id_name' => 'j_class_j_feedback_1j_class_ida',
  'link' => 'j_class_j_feedback_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["J_Feedback"]["fields"]["j_class_j_feedback_1j_class_ida"] = array (
  'name' => 'j_class_j_feedback_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'j_class_j_feedback_1j_class_ida',
  'link' => 'j_class_j_feedback_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


 // created: 2015-07-07 14:41:54
$dictionary['J_Feedback']['fields']['description']['required']=true;
$dictionary['J_Feedback']['fields']['description']['comments']='Full text of the note';
$dictionary['J_Feedback']['fields']['description']['merge_filter']='disabled';
$dictionary['J_Feedback']['fields']['description']['calculated']=false;
$dictionary['J_Feedback']['fields']['description']['rows']='4';
$dictionary['J_Feedback']['fields']['description']['cols']='40';

 

// created: 2017-01-05 12:05:27
$dictionary["J_Feedback"]["fields"]["users_j_feedback_1"] = array (
  'name' => 'users_j_feedback_1',
  'type' => 'link',
  'relationship' => 'users_j_feedback_1',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'side' => 'right',
  'vname' => 'LBL_USERS_J_FEEDBACK_1_FROM_USERS_TITLE',
  'id_name' => 'users_j_feedback_1users_ida',
  'link-type' => 'one',
);
$dictionary["J_Feedback"]["fields"]["users_j_feedback_1_name"] = array (
  'name' => 'users_j_feedback_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_FEEDBACK_1_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'users_j_feedback_1users_ida',
  'link' => 'users_j_feedback_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["J_Feedback"]["fields"]["users_j_feedback_1users_ida"] = array (
  'name' => 'users_j_feedback_1users_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'users_j_feedback_1users_ida',
  'link' => 'users_j_feedback_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2017-01-05 13:58:05
$dictionary["J_Feedback"]["fields"]["users_j_feedback_2"] = array (
  'name' => 'users_j_feedback_2',
  'type' => 'link',
  'relationship' => 'users_j_feedback_2',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'side' => 'right',
  'vname' => 'LBL_USERS_J_FEEDBACK_2_FROM_USERS_TITLE',
  'id_name' => 'users_j_feedback_2users_ida',
  'link-type' => 'one',
);
$dictionary["J_Feedback"]["fields"]["users_j_feedback_2_name"] = array (
  'name' => 'users_j_feedback_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_FEEDBACK_2_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'users_j_feedback_2users_ida',
  'link' => 'users_j_feedback_2',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["J_Feedback"]["fields"]["users_j_feedback_2users_ida"] = array (
  'name' => 'users_j_feedback_2users_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_FEEDBACK_2_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'users_j_feedback_2users_ida',
  'link' => 'users_j_feedback_2',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

?>