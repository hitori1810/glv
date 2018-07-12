<?php
// created: 2014-06-27 15:20:55
$dictionary["c_programs_c_classes_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_programs_c_classes_1' => 
    array (
      'lhs_module' => 'C_Programs',
      'lhs_table' => 'c_programs',
      'lhs_key' => 'id',
      'rhs_module' => 'C_Classes',
      'rhs_table' => 'c_classes',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_programs_c_classes_1_c',
      'join_key_lhs' => 'c_programs_c_classes_1c_programs_ida',
      'join_key_rhs' => 'c_programs_c_classes_1c_classes_idb',
    ),
  ),
  'table' => 'c_programs_c_classes_1_c',
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
      'name' => 'c_programs_c_classes_1c_programs_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_programs_c_classes_1c_classes_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_programs_c_classes_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_programs_c_classes_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_programs_c_classes_1c_programs_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_programs_c_classes_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'c_programs_c_classes_1c_classes_idb',
      ),
    ),
  ),
);