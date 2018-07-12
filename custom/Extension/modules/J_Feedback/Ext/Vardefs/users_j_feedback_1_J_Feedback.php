<?php
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
