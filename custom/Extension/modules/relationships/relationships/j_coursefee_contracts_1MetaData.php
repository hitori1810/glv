<?php
// created: 2017-08-10 11:53:30
$dictionary["j_coursefee_contracts_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_coursefee_contracts_1' => 
    array (
      'lhs_module' => 'J_Coursefee',
      'lhs_table' => 'j_coursefee',
      'lhs_key' => 'id',
      'rhs_module' => 'Contracts',
      'rhs_table' => 'contracts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_coursefee_contracts_1_c',
      'join_key_lhs' => 'j_coursefee_contracts_1j_coursefee_ida',
      'join_key_rhs' => 'j_coursefee_contracts_1contracts_idb',
    ),
  ),
  'table' => 'j_coursefee_contracts_1_c',
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
      'name' => 'j_coursefee_contracts_1j_coursefee_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_coursefee_contracts_1contracts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_coursefee_contracts_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_coursefee_contracts_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_coursefee_contracts_1j_coursefee_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_coursefee_contracts_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'j_coursefee_contracts_1contracts_idb',
      ),
    ),
  ),
);