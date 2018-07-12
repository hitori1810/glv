<?php
// created: 2014-10-01 21:30:36
$dictionary["c_memberships_leads_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_memberships_leads_1' => 
    array (
      'lhs_module' => 'C_Memberships',
      'lhs_table' => 'c_memberships',
      'lhs_key' => 'id',
      'rhs_module' => 'Leads',
      'rhs_table' => 'leads',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_memberships_leads_1_c',
      'join_key_lhs' => 'c_memberships_leads_1c_memberships_ida',
      'join_key_rhs' => 'c_memberships_leads_1leads_idb',
    ),
  ),
  'table' => 'c_memberships_leads_1_c',
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
      'name' => 'c_memberships_leads_1c_memberships_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_memberships_leads_1leads_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_memberships_leads_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_memberships_leads_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_memberships_leads_1c_memberships_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'c_memberships_leads_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'c_memberships_leads_1leads_idb',
      ),
    ),
  ),
);