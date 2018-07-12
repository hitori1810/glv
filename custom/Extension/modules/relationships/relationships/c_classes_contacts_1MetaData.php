<?php
// created: 2014-07-15 14:40:52
$dictionary["c_classes_contacts_1"] = array (
  'true_relationship_type' => 'many-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'c_classes_contacts_1' => 
    array (
      'lhs_module' => 'C_Classes',
      'lhs_table' => 'c_classes',
      'lhs_key' => 'id',
      'rhs_module' => 'Contacts',
      'rhs_table' => 'contacts',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'c_classes_contacts_1_c',
      'join_key_lhs' => 'c_classes_contacts_1c_classes_ida',
      'join_key_rhs' => 'c_classes_contacts_1contacts_idb',
    ),
  ),
  'table' => 'c_classes_contacts_1_c',
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
      'name' => 'c_classes_contacts_1c_classes_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'c_classes_contacts_1contacts_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'c_classes_contacts_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'c_classes_contacts_1_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'c_classes_contacts_1c_classes_ida',
        1 => 'c_classes_contacts_1contacts_idb',
      ),
    ),
  ),
);