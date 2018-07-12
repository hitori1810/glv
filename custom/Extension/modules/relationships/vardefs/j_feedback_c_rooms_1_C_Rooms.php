<?php
// created: 2015-07-03 11:32:29
$dictionary["C_Rooms"]["fields"]["j_feedback_c_rooms_1"] = array (
  'name' => 'j_feedback_c_rooms_1',
  'type' => 'link',
  'relationship' => 'j_feedback_c_rooms_1',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'j_feedback_c_rooms_1j_feedback_ida',
);
$dictionary["C_Rooms"]["fields"]["j_feedback_c_rooms_1_name"] = array (
  'name' => 'j_feedback_c_rooms_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_J_FEEDBACK_TITLE',
  'save' => true,
  'id_name' => 'j_feedback_c_rooms_1j_feedback_ida',
  'link' => 'j_feedback_c_rooms_1',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'name',
);
$dictionary["C_Rooms"]["fields"]["j_feedback_c_rooms_1j_feedback_ida"] = array (
  'name' => 'j_feedback_c_rooms_1j_feedback_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_FEEDBACK_C_ROOMS_1_FROM_J_FEEDBACK_TITLE_ID',
  'id_name' => 'j_feedback_c_rooms_1j_feedback_ida',
  'link' => 'j_feedback_c_rooms_1',
  'table' => 'j_feedback',
  'module' => 'J_Feedback',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
