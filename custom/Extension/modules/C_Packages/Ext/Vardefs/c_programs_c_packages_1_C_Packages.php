<?php
// created: 2014-04-12 00:59:44
$dictionary["C_Packages"]["fields"]["c_programs_c_packages_1"] = array (
  'name' => 'c_programs_c_packages_1',
  'type' => 'link',
  'relationship' => 'c_programs_c_packages_1',
  'source' => 'non-db',
  'module' => 'C_Programs',
  'bean_name' => 'C_Programs',
  'side' => 'right',
  'vname' => 'LBL_C_PROGRAMS_C_PACKAGES_1_FROM_C_PACKAGES_TITLE',
  'id_name' => 'c_programs_c_packages_1c_programs_ida',
  'link-type' => 'one',
);
$dictionary["C_Packages"]["fields"]["c_programs_c_packages_1_name"] = array (
  'name' => 'c_programs_c_packages_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PROGRAMS_C_PACKAGES_1_FROM_C_PROGRAMS_TITLE',
  'save' => true,
  'id_name' => 'c_programs_c_packages_1c_programs_ida',
  'link' => 'c_programs_c_packages_1',
  'table' => 'c_programs',
  'module' => 'C_Programs',
  'rname' => 'name',
);
$dictionary["C_Packages"]["fields"]["c_programs_c_packages_1c_programs_ida"] = array (
  'name' => 'c_programs_c_packages_1c_programs_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PROGRAMS_C_PACKAGES_1_FROM_C_PACKAGES_TITLE_ID',
  'id_name' => 'c_programs_c_packages_1c_programs_ida',
  'link' => 'c_programs_c_packages_1',
  'table' => 'c_programs',
  'module' => 'C_Programs',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
