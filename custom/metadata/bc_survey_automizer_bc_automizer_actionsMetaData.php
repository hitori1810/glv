<?php
// created: 2016-07-07 16:43:11
$dictionary["bc_survey_automizer_bc_automizer_actions"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'bc_survey_automizer_bc_automizer_actions' => 
    array (
      'lhs_module' => 'bc_survey_automizer',
      'lhs_table' => 'bc_survey_automizer',
      'lhs_key' => 'id',
      'rhs_module' => 'bc_automizer_actions',
      'rhs_table' => 'bc_automizer_actions',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'bc_survey_automizer_bc_automizer_actions_c',
      'join_key_lhs' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
      'join_key_rhs' => 'bc_survey_automizer_bc_automizer_actionsbc_automizer_actions_idb',
    ),
  ),
  'table' => 'bc_survey_automizer_bc_automizer_actions_c',
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
      'name' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'bc_survey_automizer_bc_automizer_actionsbc_automizer_actions_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'bc_survey_automizer_bc_automizer_actionsspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'bc_survey_automizer_bc_automizer_actions_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'bc_survey_automizer_bc_automizer_actions_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'bc_survey_automizer_bc_automizer_actionsbc_automizer_actions_idb',
      ),
    ),
  ),
);