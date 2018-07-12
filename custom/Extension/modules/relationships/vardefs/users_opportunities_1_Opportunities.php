<?php
// created: 2014-05-18 12:10:41
$dictionary["Opportunity"]["fields"]["users_opportunities_1"] = array (
  'name' => 'users_opportunities_1',
  'type' => 'link',
  'relationship' => 'users_opportunities_1',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'side' => 'right',
  'vname' => 'LBL_USERS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'users_opportunities_1users_ida',
  'link-type' => 'one',
);
$dictionary["Opportunity"]["fields"]["users_opportunities_1_name"] = array (
  'name' => 'users_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_OPPORTUNITIES_1_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'users_opportunities_1users_ida',
  'link' => 'users_opportunities_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["users_opportunities_1users_ida"] = array (
  'name' => 'users_opportunities_1users_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_USERS_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'users_opportunities_1users_ida',
  'link' => 'users_opportunities_1',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
