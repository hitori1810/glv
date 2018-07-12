<?php
// created: 2014-04-12 00:22:44
$dictionary["c_invoices_c_invoicelines_1"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_invoices_c_invoicelines_1' => 
    array (
      'lhs_module' => 'C_Invoices',
      'lhs_table' => 'c_invoices',
      'lhs_key' => 'id',
      'rhs_module' => 'C_Invoicelines',
      'rhs_table' => 'c_invoicelines',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_invoices_c_invoicelines_1_c',
      'join_key_lhs' => 'c_invoices_c_invoicelines_1c_invoices_ida',
      'join_key_rhs' => 'c_invoices_c_invoicelines_1c_invoicelines_idb',
    ),
  ),
  'table' => 'c_invoices_c_invoicelines_1_c',
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
      'name' => 'c_invoices_c_invoicelines_1c_invoices_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_invoices_c_invoicelines_1c_invoicelines_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_invoices_c_invoicelines_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_invoices_c_invoicelines_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_invoices_c_invoicelines_1c_invoices_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_invoices_c_invoicelines_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'c_invoices_c_invoicelines_1c_invoicelines_idb',
      ),
    ),
  ),
);