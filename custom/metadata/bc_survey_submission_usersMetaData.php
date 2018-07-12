<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["bc_survey_submission_users"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'bc_survey_submission_users' => 
    array (
      'lhs_module' => 'Users',
      'lhs_table' => 'users',
      'lhs_key' => 'id',
      'rhs_module' => 'bc_survey_submission',
      'rhs_table' => 'bc_survey_submission',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'bc_survey_submission_users_c',
      'join_key_lhs' => 'bc_survey_submission_usersusers_ida',
      'join_key_rhs' => 'bc_survey_submission_usersbc_survey_submission_idb',
    ),
  ),
  'table' => 'bc_survey_submission_users_c',
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
      'name' => 'bc_survey_submission_usersusers_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'bc_survey_submission_usersbc_survey_submission_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'bc_survey_submission_usersspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'bc_survey_submission_users_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'bc_survey_submission_usersusers_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'bc_survey_submission_users_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'bc_survey_submission_usersbc_survey_submission_idb',
      ),
    ),
  ),
);