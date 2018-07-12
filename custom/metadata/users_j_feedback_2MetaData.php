<?php
// created: 2017-01-05 13:58:05
$dictionary["users_j_feedback_2"] = array (
  'true_relationship_type' => 'one-to-many',
  'from_studio' => true,
  'relationships' => 
  array (
    'users_j_feedback_2' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'J_Feedback',
      'rhs_table' => 'j_feedback',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'users_j_feedback_2_c',
      'join_key_lhs' => 'users_j_feedback_2users_ida',
      'join_key_rhs' => 'users_j_feedback_2j_feedback_idb',
    ),
  ),
  'table' => 'users_j_feedback_2_c',
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
      'name' => 'users_j_feedback_2users_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'users_j_feedback_2j_feedback_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'users_j_feedback_2spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'users_j_feedback_2_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'users_j_feedback_2users_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'users_j_feedback_2_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'users_j_feedback_2j_feedback_idb',
      ),
    ),
  ),
);