<?php
/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/


$dictionary["documents_cases"] = array (
  'true_relationship_type' => 'many-to-many',
  'relationships' => 
  array (
    'documents_cases' => 
    array (
      'lhs_module' => 'Documents',
      'lhs_table' => 'documents',
      'lhs_key' => 'id',
      'rhs_module' => 'Cases',
      'rhs_table' => 'cases',
      'rhs_key' => 'id',
      'relationship_type' => 'many-to-many',
      'join_table' => 'documents_cases',
      'join_key_lhs' => 'document_id',
      'join_key_rhs' => 'case_id',
    ),
  ),
  'table' => 'documents_cases',
  'fields' => 
  array (
    0 => 
    array (
      'name' => 'id',
      'type' => 'id',
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
      'name' => 'document_id',
      'type' => 'id',
      'len' => 36,
    ),
    4 => 
    array (
      'name' => 'case_id',
      'type' => 'id',
      'len' => 36,
    ),
  ),
  'indices' => 
  array (
    0 => 
    array (
      'name' => 'documents_casesspk',
      'type' => 'primary',
      'fields' => 
      array (
        0 => 'id',
      ),
    ),
    1 => 
    array (
      'name' => 'documents_cases_case_id',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'case_id',
        1 => 'document_id',
      ),
    ),
    2 => 
    array (
      'name' => 'documents_cases_document_id',
      'type' => 'alternate_key',
      'fields' => 
      array (
        0 => 'document_id',
        1 => 'case_id',
      ),
    ),
  ),
);

