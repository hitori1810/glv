<?php
// created: 2015-07-21 16:29:24
$dictionary["c_payments_c_refunds_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_payments_c_refunds_1' => 
    array (
      'lhs_module' => 'C_Payments',
      'lhs_table' => 'c_payments',
      'lhs_key' => 'id',
      'rhs_module' => 'C_Refunds',
      'rhs_table' => 'c_refunds',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_payments_c_refunds_1_c',
      'join_key_lhs' => 'c_payments_c_refunds_1c_payments_ida',
      'join_key_rhs' => 'c_payments_c_refunds_1c_refunds_idb',
    ),
  ),
  'table' => 'c_payments_c_refunds_1_c',
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
      'name' => 'c_payments_c_refunds_1c_payments_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_payments_c_refunds_1c_refunds_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_payments_c_refunds_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_payments_c_refunds_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_payments_c_refunds_1c_payments_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_payments_c_refunds_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_payments_c_refunds_1c_refunds_idb',
      ),
    ),
  ),
);