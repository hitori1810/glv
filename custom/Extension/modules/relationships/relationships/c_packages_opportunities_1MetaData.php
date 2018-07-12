<?php
// created: 2014-04-12 01:02:18
$dictionary["c_packages_opportunities_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_packages_opportunities_1' => 
    array (
      'lhs_module' => 'C_Packages',
      'lhs_table' => 'c_packages',
      'lhs_key' => 'id',
      'rhs_module' => 'Opportunities',
      'rhs_table' => 'opportunities',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_packages_opportunities_1_c',
      'join_key_lhs' => 'c_packages_opportunities_1c_packages_ida',
      'join_key_rhs' => 'c_packages_opportunities_1opportunities_idb',
    ),
  ),
  'table' => 'c_packages_opportunities_1_c',
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
      'name' => 'c_packages_opportunities_1c_packages_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_packages_opportunities_1opportunities_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_packages_opportunities_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_packages_opportunities_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_packages_opportunities_1c_packages_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_packages_opportunities_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'c_packages_opportunities_1opportunities_idb',
      ),
    ),
  ),
);