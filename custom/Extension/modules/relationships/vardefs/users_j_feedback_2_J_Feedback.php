<?php
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
