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
// created: 2014-10-08 08:28:58
$dictionary["bc_survey_pages_bc_survey"] = array (
  'true_relationship_type' => 'one-to-many',
  'relationships' => 
  array (
    'bc_survey_pages_bc_survey' => 
    array (
      'lhs_module' => 'bc_survey',
      'lhs_table' => 'bc_survey',
      'lhs_key' => 'id',
      'rhs_module' => 'bc_survey_pages',
      'rhs_table' => 'bc_survey_pages',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'bc_survey_pages_bc_survey_c',
      'join_key_lhs' => 'bc_survey_pages_bc_surveybc_survey_ida',
      'join_key_rhs' => 'bc_survey_pages_bc_surveybc_survey_pages_idb',
    ),
  ),
  'table' => 'bc_survey_pages_bc_survey_c',
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
      'name' => 'bc_survey_pages_bc_surveybc_survey_ida',
      'type' => 'varchar',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'bc_survey_pages_bc_surveybc_survey_pages_idb',
      'type' => 'varchar',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'bc_survey_pages_bc_surveyspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'bc_survey_pages_bc_survey_ida1',
      'type' => 'index',
      'fields' => 
      array (
        0 => 'bc_survey_pages_bc_surveybc_survey_ida',
      ),
    ),
    2 => 
    array (
      'name' => 'bc_survey_pages_bc_survey_alt',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'bc_survey_pages_bc_surveybc_survey_pages_idb',
      ),
    ),
  ),
);