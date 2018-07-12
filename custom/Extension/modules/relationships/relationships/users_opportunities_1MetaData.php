<?php
// created: 2014-05-18 12:10:41
$dictionary["users_opportunities_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'users_opportunities_1' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'Opportunities',
      'rhs_table' => 'opportunities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'users_opportunities_1_c',
      'join_key_lhs' => 'users_opportunities_1users_ida',
      'join_key_rhs' => 'users_opportunities_1opportunities_idb',
    ),
  ),
  'table' => 'users_opportunities_1_c',
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
      'name' => 'users_opportunities_1users_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'users_opportunities_1opportunities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'users_opportunities_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'users_opportunities_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'users_opportunities_1users_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'users_opportunities_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'users_opportunities_1opportunities_idb',
      ),
    ),
  ),
);