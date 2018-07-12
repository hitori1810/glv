<?php
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
