<?php
// created: 2015-07-03 11:26:24
$dictionary["Contact"]["fields"]["j_feedback_contacts_2"] = array (
  'name' => 'j_feedback_contacts_2',
  'type' => 'link',
  'relationship' => 'j_feedback_contacts_2',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'j_feedback_contacts_2j_feedback_ida',
);
$dictionary["Contact"]["fields"]["j_feedback_contacts_2_name"] = array (
  'name' => 'j_feedback_contacts_2_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_J_FEEDBACK_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_contacts_2j_feedback_ida',
  'link' => 'j_feedback_contacts_2',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'name',
);
$dictionary["Contact"]["fields"]["j_feedback_contacts_2j_feedback_ida"] = array (
  'name' => 'j_feedback_contacts_2j_feedback_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_CONTACTS_2_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'j_feedback_contacts_2j_feedback_ida',
  'link' => 'j_feedback_contacts_2',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
