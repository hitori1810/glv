<?php
// created: 2018-07-12 17:26:19
$dictionary["C_Class"]["fields"]["c_grade_c_class_1"] = array (
  'name' => 'c_grade_c_class_1',
  'type' => 'link',
  'relationship' => 'c_grade_c_class_1',
  'source' => 'non-db',
  'module' => 'C_Grade',
  'bean_name' => 'C_Grade',
  'side' => 'right',
  'vname' => 'LBL_C_GRADE_C_CLASS_1_FROM_C_GRADE_TITLE',
  'id_name' => 'c_grade_c_class_1c_grade_ida',
  'link-type' => 'one',
);
$dictionary["C_Class"]["fields"]["c_grade_c_class_1_name"] = array (
  'name' => 'c_grade_c_class_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_GRADE_C_CLASS_1_FROM_C_GRADE_TITLE',
  'save' => true,
  'id_name' => 'c_grade_c_class_1c_grade_ida',
  'link' => 'c_grade_c_class_1',
  'table' => 'c_grade',
  'module' => 'C_Grade',
  'rname' => 'name',
);
$dictionary["C_Class"]["fields"]["c_grade_c_class_1c_grade_ida"] = array (
  'name' => 'c_grade_c_class_1c_grade_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_GRADE_C_CLASS_1_FROM_C_CLASS_TITLE_ID',
  'id_name' => 'c_grade_c_class_1c_grade_ida',
  'link' => 'c_grade_c_class_1',
  'table' => 'c_grade',
  'module' => 'C_Grade',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
