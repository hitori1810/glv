<?php
// created: 2016-08-05 15:50:22
$dictionary["Call"]["fields"]["calls_j_class_1"] = array (
  'name' => 'calls_j_class_1',
  'type' => 'link',
  'relationship' => 'calls_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'calls_j_class_1j_class_idb',
);
$dictionary["Call"]["fields"]["calls_j_class_1_name"] = array (
  'name' => 'calls_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_J_CLASS_TITLE',
  'save' => true,
  'id_name' => 'calls_j_class_1j_class_idb',
  'link' => 'calls_j_class_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["Call"]["fields"]["calls_j_class_1j_class_idb"] = array (
  'name' => 'calls_j_class_1j_class_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CALLS_J_CLASS_1_FROM_J_CLASS_TITLE_ID',
  'id_name' => 'calls_j_class_1j_class_idb',
  'link' => 'calls_j_class_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
