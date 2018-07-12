<?php
// created: 2016-07-27 11:01:40
$dictionary["c_teachers_j_gradebook_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_teachers_j_gradebook_1' => 
    array (
      'lhs_module' => 'C_Teachers',
      'lhs_table' => 'c_teachers',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Gradebook',
      'rhs_table' => 'j_gradebook',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_teachers_j_gradebook_1_c',
      'join_key_lhs' => 'c_teachers_j_gradebook_1c_teachers_ida',
      'join_key_rhs' => 'c_teachers_j_gradebook_1j_gradebook_idb',
    ),
  ),
  'table' => 'c_teachers_j_gradebook_1_c',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'varchar',
      'len' => 36,
    ),
    1 => 
    array (
      'name' => 'date_modified',
      'type' => 'datetime',
    ),
    2 => 
    array (
      'name' => 'deleted',
      'type' => 'bool',
      'len' => '1',
      'default' => '0',
      'required' => true,
    ),
    3 => 
    array (
      'name' => 'c_teachers_j_gradebook_1c_teachers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_teachers_j_gradebook_1j_gradebook_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_teachers_j_gradebook_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_teachers_j_gradebook_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_teachers_j_gradebook_1c_teachers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_teachers_j_gradebook_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'c_teachers_j_gradebook_1j_gradebook_idb',
      ),
    ),
  ),
);