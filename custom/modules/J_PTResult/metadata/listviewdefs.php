<?php
$module_name = 'J_PTResult';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'contacts_j_ptresult_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_CONTACTS_TITLE',
    'id' => 'CONTACTS_J_PTRESULT_1CONTACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'leads_j_ptresult_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
    'id' => 'LEADS_J_PTRESULT_1LEADS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'speaking' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SPEAKING',
    'width' => '10%',
    'default' => true,
  ),
  'writing' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_WRITING',
    'width' => '10%',
    'default' => true,
  ),
  'listening' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_LISTENING',
    'width' => '10%',
    'default' => true,
  ),
  'result' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_RESULT',
    'width' => '10%',
    'default' => true,
  ),
  'ec_note' => 
  array (
    'type' => 'text',
    'label' => 'LBL_EC_NOTE',
    'sortable' => false,
    'width' => '15%',
    'default' => true,
  ),
  'teacher_comment' => 
  array (
    'type' => 'text',
    'label' => 'LBL_TEACHER_COMMENT',
    'sortable' => false,
    'width' => '15%',
    'default' => true,
  ),
  'attended' => 
  array (
    'type' => 'bool',
    'default' => true,
    'label' => 'LBL_ATTENDED',
    'width' => '10%',
  ),
  'team_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => false,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => false,
  ),
);
