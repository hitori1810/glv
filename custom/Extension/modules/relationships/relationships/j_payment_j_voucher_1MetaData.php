<?php
// created: 2017-01-16 17:18:07
$dictionary["j_payment_j_voucher_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_payment_j_voucher_1' => 
    array (
      'lhs_module' => 'J_Payment',
      'lhs_table' => 'j_payment',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Voucher',
      'rhs_table' => 'j_voucher',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_payment_j_voucher_1_c',
      'join_key_lhs' => 'j_payment_j_voucher_1j_payment_ida',
      'join_key_rhs' => 'j_payment_j_voucher_1j_voucher_idb',
    ),
  ),
  'table' => 'j_payment_j_voucher_1_c',
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
      'name' => 'j_payment_j_voucher_1j_payment_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_payment_j_voucher_1j_voucher_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_payment_j_voucher_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_payment_j_voucher_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'j_payment_j_voucher_1j_payment_ida',
        1 => 'j_payment_j_voucher_1j_voucher_idb',
      ),
    ),
  ),
);