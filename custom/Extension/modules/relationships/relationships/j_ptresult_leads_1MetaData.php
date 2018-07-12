<?php
// created: 2015-08-25 11:34:29
$dictionary["j_ptresult_leads_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_ptresult_leads_1' => 
    array (
      'lhs_module' => 'J_PTResult',
      'lhs_table' => 'j_ptresult',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_ptresult_leads_1_c',
      'join_key_lhs' => 'j_ptresult_leads_1j_ptresult_ida',
      'join_key_rhs' => 'j_ptresult_leads_1leads_idb',
    ),
  ),
  'table' => 'j_ptresult_leads_1_c',
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
      'name' => 'j_ptresult_leads_1j_ptresult_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_ptresult_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_ptresult_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_ptresult_leads_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'j_ptresult_leads_1j_ptresult_ida',
        1 => 'j_ptresult_leads_1leads_idb',
      ),
    ),
  ),
);