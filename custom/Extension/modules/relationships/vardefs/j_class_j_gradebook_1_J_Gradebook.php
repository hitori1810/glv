<?php
// created: 2016-05-12 11:08:29
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1"] = array (
  'name' => 'j_class_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'j_class_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1_name"] = array (
  'name' => 'j_class_j_gradebook_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
  'save' => true,
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link' => 'j_class_j_gradebook_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1j_class_ida"] = array (
  'name' => 'j_class_j_gradebook_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE_ID',
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link' => 'j_class_j_gradebook_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
