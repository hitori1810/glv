<?php
// created: 2016-08-05 15:50:22
$dictionary["J_Class"]["fields"]["calls_j_class_1"] = array (
  'name' => 'calls_j_class_1',
  'type' => 'link',
  'relationship' => 'calls_j_class_1',
  'source' => 'non-db',
  'module' => 'Calls',
  'bean_name' => 'Call',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_CALLS_TITLE',
  'id_name' => 'calls_j_class_1calls_ida',
);
$dictionary["J_Class"]["fields"]["calls_j_class_1_name"] = array (
  'name' => 'calls_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_CALLS_TITLE',
  'save' => true,
  'id_name' => 'calls_j_class_1calls_ida',
  'link' => 'calls_j_class_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'name',
);
$dictionary["J_Class"]["fields"]["calls_j_class_1calls_ida"] = array (
  'name' => 'calls_j_class_1calls_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_CALLS_TITLE_ID',
  'id_name' => 'calls_j_class_1calls_ida',
  'link' => 'calls_j_class_1',
  'table' => 'calls',
  'module' => 'Calls',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
