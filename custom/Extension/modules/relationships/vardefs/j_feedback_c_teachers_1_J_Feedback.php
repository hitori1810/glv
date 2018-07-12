<?php
// created: 2015-07-03 11:34:36
$dictionary["J_Feedback"]["fields"]["j_feedback_c_teachers_1"] = array (
  'name' => 'j_feedback_c_teachers_1',
  'type' => 'link',
  'relationship' => 'j_feedback_c_teachers_1',
  'source' => 'non-db',
  'module' => 'C_Teachers',
  'bean_name' => 'C_Teachers',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_C_TEACHERS_TITLE',
  'id_name' => 'j_feedback_c_teachers_1c_teachers_idb',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_teachers_1_name"] = array (
  'name' => 'j_feedback_c_teachers_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_C_TEACHERS_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_c_teachers_1c_teachers_idb',
  'link' => 'j_feedback_c_teachers_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_teachers_1c_teachers_idb"] = array (
  'name' => 'j_feedback_c_teachers_1c_teachers_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_TEACHERS_1_FROM_C_TEACHERS_TITLE_ID',
  'id_name' => 'j_feedback_c_teachers_1c_teachers_idb',
  'link' => 'j_feedback_c_teachers_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
