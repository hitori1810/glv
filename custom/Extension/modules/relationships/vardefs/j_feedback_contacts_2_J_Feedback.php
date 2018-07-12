<?php
// created: 2015-07-03 11:26:24
$dictionary["J_Feedback"]["fields"]["j_feedback_contacts_2"] = array (
  'name' => 'j_feedback_contacts_2',
  'type' => 'link',
  'relationship' => 'j_feedback_contacts_2',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_CONTACTS_TITLE',
  'id_name' => 'j_feedback_contacts_2contacts_idb',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_contacts_2_name"] = array (
  'name' => 'j_feedback_contacts_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_contacts_2contacts_idb',
  'link' => 'j_feedback_contacts_2',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Feedback"]["fields"]["j_feedback_contacts_2contacts_idb"] = array (
  'name' => 'j_feedback_contacts_2contacts_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_CONTACTS_TITLE_ID',
  'id_name' => 'j_feedback_contacts_2contacts_idb',
  'link' => 'j_feedback_contacts_2',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
