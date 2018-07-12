<?php
// created: 2015-07-03 11:18:50
$dictionary["J_Feedback"]["fields"]["j_feedback_c_classes_1"] = array (
  'name' => 'j_feedback_c_classes_1',
  'type' => 'link',
  'relationship' => 'j_feedback_c_classes_1',
  'source' => 'non-db',
  'module' => 'C_Classes',
  'bean_name' => 'C_Classes',
  'vname' => 'LBL_J_FEEDBACK_C_CLASSES_1_FROM_C_CLASSES_TITLE',
  'id_name' => 'j_feedback_c_classes_1c_classes_idb',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_classes_1_name"] = array (
  'name' => 'j_feedback_c_classes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_CLASSES_1_FROM_C_CLASSES_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_c_classes_1c_classes_idb',
  'link' => 'j_feedback_c_classes_1',
  'table' => 'c_classes',
  'module' => 'C_Classes',
  'rname' => 'name',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_classes_1c_classes_idb"] = array (
  'name' => 'j_feedback_c_classes_1c_classes_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_CLASSES_1_FROM_C_CLASSES_TITLE_ID',
  'id_name' => 'j_feedback_c_classes_1c_classes_idb',
  'link' => 'j_feedback_c_classes_1',
  'table' => 'c_classes',
  'module' => 'C_Classes',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
