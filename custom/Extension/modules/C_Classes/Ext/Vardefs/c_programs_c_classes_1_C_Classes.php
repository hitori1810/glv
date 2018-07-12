<?php
// created: 2014-06-27 15:20:55
$dictionary["C_Classes"]["fields"]["c_programs_c_classes_1"] = array (
  'name' => 'c_programs_c_classes_1',
  'type' => 'link',
  'relationship' => 'c_programs_c_classes_1',
  'source' => 'non-db',
  'module' => 'C_Programs',
  'bean_name' => 'C_Programs',
  'side' => 'right',
  'vname' => 'LBL_C_PROGRAMS_C_CLASSES_1_FROM_C_CLASSES_TITLE',
  'id_name' => 'c_programs_c_classes_1c_programs_ida',
  'link-type' => 'one',
);
$dictionary["C_Classes"]["fields"]["c_programs_c_classes_1_name"] = array (
  'name' => 'c_programs_c_classes_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PROGRAMS_C_CLASSES_1_FROM_C_PROGRAMS_TITLE',
  'save' => true,
  'id_name' => 'c_programs_c_classes_1c_programs_ida',
  'link' => 'c_programs_c_classes_1',
  'table' => 'c_programs',
  'module' => 'C_Programs',
  'rname' => 'name',
);
$dictionary["C_Classes"]["fields"]["c_programs_c_classes_1c_programs_ida"] = array (
  'name' => 'c_programs_c_classes_1c_programs_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PROGRAMS_C_CLASSES_1_FROM_C_CLASSES_TITLE_ID',
  'id_name' => 'c_programs_c_classes_1c_programs_ida',
  'link' => 'c_programs_c_classes_1',
  'table' => 'c_programs',
  'module' => 'C_Programs',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
