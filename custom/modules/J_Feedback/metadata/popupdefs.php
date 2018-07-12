<?php
$popupMeta = array (
    'moduleMain' => 'J_Feedback',
    'varName' => 'J_Feedback',
    'orderBy' => 'j_feedback.name',
    'whereClauses' => array (
  'name' => 'j_feedback.name',
  'receiver' => 'j_feedback.receiver',
  'received_date' => 'j_feedback.received_date',
  'resolved_date' => 'j_feedback.resolved_date',
  'contacts_j_feedback_1_name' => 'j_feedback.contacts_j_feedback_1_name',
  'j_class_j_feedback_1_name' => 'j_feedback.j_class_j_feedback_1_name',
),
    'searchInputs' => array (
  1 => 'name',
  17 => 'receiver',
  18 => 'received_date',
  19 => 'resolved_date',
  20 => 'contacts_j_feedback_1_name',
  21 => 'j_class_j_feedback_1_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'receiver' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RECEIVER',
    'id' => 'RECEIVER_ID',
    'width' => '10%',
    'name' => 'receiver',
  ),
  'received_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_RECEIVED_DATE ',
    'width' => '10%',
    'name' => 'received_date',
  ),
  'resolved_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_RESOLVED_DATE',
    'width' => '10%',
    'name' => 'resolved_date',
  ),
  'contacts_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
    'id' => 'CONTACTS_J_FEEDBACK_1CONTACTS_IDA',
    'width' => '10%',
    'name' => 'contacts_j_feedback_1_name',
  ),
  'j_class_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
    'id' => 'J_CLASS_J_FEEDBACK_1J_CLASS_IDA',
    'width' => '10%',
    'name' => 'j_class_j_feedback_1_name',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '32%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'STATUS' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
    'name' => 'status',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
    'name' => 'description',
  ),
  'RECEIVER' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_RECEIVER',
    'id' => 'RECEIVER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'receiver',
  ),
  'FEEDBACK' => 
  array (
    'type' => 'text',
    'label' => 'LBL_FEEDBACK',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
    'name' => 'feedback',
  ),
  'RECEIVED_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_RECEIVED_DATE ',
    'width' => '10%',
    'default' => true,
    'name' => 'received_date',
  ),
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
    'name' => 'assigned_user_name',
  ),
  'TEAM_NAME' => 
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
    'name' => 'team_name',
  ),
),
);
