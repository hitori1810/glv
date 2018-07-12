<?php
// created: 2015-07-03 11:32:29
$dictionary["J_Feedback"]["fields"]["j_feedback_c_rooms_1"] = array (
  'name' => 'j_feedback_c_rooms_1',
  'type' => 'link',
  'relationship' => 'j_feedback_c_rooms_1',
  'source' => 'non-db',
  'module' => 'C_Rooms',
  'bean_name' => 'C_Rooms',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_C_ROOMS_TITLE',
  'id_name' => 'j_feedback_c_rooms_1c_rooms_idb',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_rooms_1_name"] = array (
  'name' => 'j_feedback_c_rooms_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_C_ROOMS_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_c_rooms_1c_rooms_idb',
  'link' => 'j_feedback_c_rooms_1',
  'table' => 'c_rooms',
  'module' => 'C_Rooms',
  'rname' => 'name',
);
$dictionary["J_Feedback"]["fields"]["j_feedback_c_rooms_1c_rooms_idb"] = array (
  'name' => 'j_feedback_c_rooms_1c_rooms_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_C_ROOMS_TITLE_ID',
  'id_name' => 'j_feedback_c_rooms_1c_rooms_idb',
  'link' => 'j_feedback_c_rooms_1',
  'table' => 'c_rooms',
  'module' => 'C_Rooms',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
