<?php
// created: 2016-08-23 09:20:07
$dictionary["Call"]["fields"]["j_class_calls_1"] = array (
  'name' => 'j_class_calls_1',
  'type' => 'link',
  'relationship' => 'j_class_calls_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_CALLS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_calls_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["Call"]["fields"]["j_class_calls_1_name"] = array (
  'name' => 'j_class_calls_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_CALLS_1_FROM_J_CLASS_TITLE',
  'save' => true,
  'id_name' => 'j_class_calls_1j_class_ida',
  'link' => 'j_class_calls_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["Call"]["fields"]["j_class_calls_1j_class_ida"] = array (
  'name' => 'j_class_calls_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_CALLS_1_FROM_CALLS_TITLE_ID',
  'id_name' => 'j_class_calls_1j_class_ida',
  'link' => 'j_class_calls_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
