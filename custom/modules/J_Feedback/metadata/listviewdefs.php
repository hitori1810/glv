<?php
$module_name = 'J_Feedback';
$listViewDefs[$module_name] = 
array (
  'name' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'receiver' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RECEIVER',
    'id' => 'RECEIVER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'priority' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_PRIORITY',
    'width' => '10%',
    'default' => true,
  ),
  'contacts_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
    'id' => 'CONTACTS_J_FEEDBACK_1CONTACTS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'j_class_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
    'id' => 'J_CLASS_J_FEEDBACK_1J_CLASS_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'received_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_RECEIVED_DATE ',
    'width' => '10%',
    'default' => true,
  ),
  'resolved_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_RESOLVED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
);
