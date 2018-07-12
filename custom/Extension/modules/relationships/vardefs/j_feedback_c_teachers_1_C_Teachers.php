<?php
// created: 2015-07-03 11:34:36
$dictionary["C_Teachers"]["fields"]["j_feedback_c_teachers_1"] = array (
  'name' => 'j_feedback_c_teachers_1',
  'type' => 'link',
  'relationship' => 'j_feedback_c_teachers_1',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'j_feedback_c_teachers_1j_feedback_ida',
);
$dictionary["C_Teachers"]["fields"]["j_feedback_c_teachers_1_name"] = array (
  'name' => 'j_feedback_c_teachers_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_J_FEEDBACK_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_c_teachers_1j_feedback_ida',
  'link' => 'j_feedback_c_teachers_1',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'name',
);
$dictionary["C_Teachers"]["fields"]["j_feedback_c_teachers_1j_feedback_ida"] = array (
  'name' => 'j_feedback_c_teachers_1j_feedback_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'j_feedback_c_teachers_1j_feedback_ida',
  'link' => 'j_feedback_c_teachers_1',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
