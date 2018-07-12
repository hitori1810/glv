<?php
// created: 2015-07-03 11:32:29
$dictionary["j_feedback_c_rooms_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_feedback_c_rooms_1' => 
    array (
      'lhs_module' => 'J_Feedback',
      'lhs_table' => 'j_feedback',
      'lhs_key' => 'id',
      'rhs_module' => 'C_Rooms',
      'rhs_table' => 'c_rooms',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_feedback_c_rooms_1_c',
      'join_key_lhs' => 'j_feedback_c_rooms_1j_feedback_ida',
      'join_key_rhs' => 'j_feedback_c_rooms_1c_rooms_idb',
    ),
  ),
  'table' => 'j_feedback_c_rooms_1_c',
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
      'name' => 'j_feedback_c_rooms_1j_feedback_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_feedback_c_rooms_1c_rooms_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_feedback_c_rooms_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_feedback_c_rooms_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_feedback_c_rooms_1j_feedback_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_feedback_c_rooms_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_feedback_c_rooms_1c_rooms_idb',
      ),
    ),
  ),
);