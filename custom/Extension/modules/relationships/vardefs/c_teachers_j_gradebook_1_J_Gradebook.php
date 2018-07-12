<?php
// created: 2016-07-27 11:01:41
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1"] = array (
  'name' => 'c_teachers_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'c_teachers_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'C_Teachers',
  'bean_name' => 'C_Teachers',
  'side' => 'right',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link-type' => 'one',
);
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1_name"] = array (
  'name' => 'c_teachers_j_gradebook_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
  'save' => true,
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link' => 'c_teachers_j_gradebook_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1c_teachers_ida"] = array (
  'name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE_ID',
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link' => 'c_teachers_j_gradebook_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
