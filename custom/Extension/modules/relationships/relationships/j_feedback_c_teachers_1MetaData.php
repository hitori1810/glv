<?php
// created: 2015-07-03 11:34:36
$dictionary["j_feedback_c_teachers_1"] = array (
  'true_relationship_type' => 'one-to-one',
  'from_studio' => true,
  'relationships' => 
  array (
    'j_feedback_c_teachers_1' => 
    array (
      'lhs_module' => 'J_Feedback',
      'lhs_table' => 'j_feedback',
      'lhs_key' => 'id',
      'rhs_module' => 'C_Teachers',
      'rhs_table' => 'c_teachers',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'j_feedback_c_teachers_1_c',
      'join_key_lhs' => 'j_feedback_c_teachers_1j_feedback_ida',
      'join_key_rhs' => 'j_feedback_c_teachers_1c_teachers_idb',
    ),
  ),
  'table' => 'j_feedback_c_teachers_1_c',
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
      'name' => 'j_feedback_c_teachers_1j_feedback_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'j_feedback_c_teachers_1c_teachers_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'j_feedback_c_teachers_1spk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'j_feedback_c_teachers_1_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_feedback_c_teachers_1j_feedback_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'j_feedback_c_teachers_1_idb2',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'j_feedback_c_teachers_1c_teachers_idb',
      ),
    ),
  ),
);