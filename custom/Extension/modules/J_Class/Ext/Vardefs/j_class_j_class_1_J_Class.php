<?php
// created: 2015-07-16 16:15:52
$dictionary["J_Class"]["fields"]["j_class_j_class_1"] = array (
  'name' => 'j_class_j_class_1',
  'type' => 'link',
  'relationship' => 'j_class_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_L_TITLE',
  'id_name' => 'j_class_j_class_1j_class_idb',
  'link-type' => 'many',
  'side' => 'left',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1_right"] = array (
  'name' => 'j_class_j_class_1_right',
  'type' => 'link',
  'relationship' => 'j_class_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_R_TITLE',
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1_name"] = array (
  'name' => 'j_class_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_L_TITLE',
  'save' => true,
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link' => 'j_class_j_class_1_right',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["J_Class"]["fields"]["j_class_j_class_1j_class_ida"] = array (
  'name' => 'j_class_j_class_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_CLASS_1_FROM_J_CLASS_R_TITLE_ID',
  'id_name' => 'j_class_j_class_1j_class_ida',
  'link' => 'j_class_j_class_1_right',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
