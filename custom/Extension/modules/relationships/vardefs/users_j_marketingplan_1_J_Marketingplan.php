<?php
// created: 2015-07-31 11:48:19
$dictionary["J_Marketingplan"]["fields"]["users_j_marketingplan_1"] = array (
  'name' => 'users_j_marketingplan_1',
  'type' => 'link',
  'relationship' => 'users_j_marketingplan_1',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'side' => 'right',
  'vname' => 'LBL_USERS_J_MARKETINGPLAN_1_FROM_J_MARKETINGPLAN_TITLE',
  'id_name' => 'users_j_marketingplan_1users_ida',
  'link-type' => 'one',
);
$dictionary["J_Marketingplan"]["fields"]["users_j_marketingplan_1_name"] = array (
  'name' => 'users_j_marketingplan_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_MARKETINGPLAN_1_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'users_j_marketingplan_1users_ida',
  'link' => 'users_j_marketingplan_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["J_Marketingplan"]["fields"]["users_j_marketingplan_1users_ida"] = array (
  'name' => 'users_j_marketingplan_1users_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_J_MARKETINGPLAN_1_FROM_J_MARKETINGPLAN_TITLE_ID',
  'id_name' => 'users_j_marketingplan_1users_ida',
  'link' => 'users_j_marketingplan_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
