<?php
// created: 2016-11-25 10:58:12
$dictionary["contracts_j_class_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'contracts_j_class_1' => 
    array (
      'lhs_module' => 'Contracts',
      'lhs_table' => 'contracts',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Class',
      'rhs_table' => 'j_class',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'contracts_j_class_1_c',
      'join_key_lhs' => 'contracts_j_class_1contracts_ida',
      'join_key_rhs' => 'contracts_j_class_1j_class_idb',
    ),
  ),
  'table' => 'contracts_j_class_1_c',
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
      'name' => 'contracts_j_class_1contracts_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'contracts_j_class_1j_class_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'contracts_j_class_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'contracts_j_class_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'contracts_j_class_1contracts_ida',
        1 => 'contracts_j_class_1j_class_idb',
      ),
    ),
  ),
);