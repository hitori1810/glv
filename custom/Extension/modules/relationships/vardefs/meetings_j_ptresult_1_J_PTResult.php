<?php
// created: 2015-08-05 15:56:02
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1"] = array (
  'name' => 'meetings_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'meetings_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Meetings',
  'bean_name' => 'Meeting',
  'side' => 'right',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1_name"] = array (
  'name' => 'meetings_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_MEETINGS_TITLE',
  'save' => true,
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link' => 'meetings_j_ptresult_1',
  'table' => 'meetings',
  'module' => 'Meetings',
  'rname' => 'name',
);
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1meetings_ida"] = array (
  'name' => 'meetings_j_ptresult_1meetings_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link' => 'meetings_j_ptresult_1',
  'table' => 'meetings',
  'module' => 'Meetings',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
