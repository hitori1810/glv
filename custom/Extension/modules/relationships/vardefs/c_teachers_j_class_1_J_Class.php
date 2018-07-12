<?php
// created: 2015-07-23 16:22:27
$dictionary["J_Class"]["fields"]["c_teachers_j_class_1"] = array (
  'name' => 'c_teachers_j_class_1',
  'type' => 'link',
  'relationship' => 'c_teachers_j_class_1',
  'source' => 'non-db',
  'module' => 'C_Teachers',
  'bean_name' => 'C_Teachers',
  'side' => 'right',
  'vname' => 'LBL_C_TEACHERS_J_CLASS_1_FROM_J_CLASS_TITLE',
  'id_name' => 'c_teachers_j_class_1c_teachers_ida',
  'link-type' => 'one',
);
$dictionary["J_Class"]["fields"]["c_teachers_j_class_1_name"] = array (
  'name' => 'c_teachers_j_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_CLASS_1_FROM_C_TEACHERS_TITLE',
  'save' => true,
  'id_name' => 'c_teachers_j_class_1c_teachers_ida',
  'link' => 'c_teachers_j_class_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Class"]["fields"]["c_teachers_j_class_1c_teachers_ida"] = array (
  'name' => 'c_teachers_j_class_1c_teachers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_CLASS_1_FROM_J_CLASS_TITLE_ID',
  'id_name' => 'c_teachers_j_class_1c_teachers_ida',
  'link' => 'c_teachers_j_class_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
