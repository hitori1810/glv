<?php
// created: 2015-07-03 09:27:36
$dictionary["j_school_j_school_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_school_j_school_1' => 
    array (
      'lhs_module' => 'J_School',
      'lhs_table' => 'j_school',
      'lhs_key' => 'id',
      'rhs_module' => 'J_School',
      'rhs_table' => 'j_school',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_school_j_school_1_c',
      'join_key_lhs' => 'j_school_j_school_1j_school_ida',
      'join_key_rhs' => 'j_school_j_school_1j_school_idb',
    ),
  ),
  'table' => 'j_school_j_school_1_c',
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
      'name' => 'j_school_j_school_1j_school_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_school_j_school_1j_school_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_school_j_school_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_school_j_school_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_school_j_school_1j_school_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_school_j_school_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_school_j_school_1j_school_idb',
      ),
    ),
  ),
);