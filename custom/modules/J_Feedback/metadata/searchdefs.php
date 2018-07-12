<?php
$module_name = 'J_Feedback';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'receiver' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_RECEIVER',
        'width' => '10%',
        'default' => true,
        'id' => 'RECEIVER_ID',
        'name' => 'receiver',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'default' => true,
        'name' => 'status',
      ),
      'priority' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_PRIORITY',
        'width' => '10%',
        'default' => true,
        'name' => 'priority',
      ),
      'contacts_j_feedback_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
        'id' => 'CONTACTS_J_FEEDBACK_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'contacts_j_feedback_1_name',
      ),
      'j_class_j_feedback_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
        'id' => 'J_CLASS_J_FEEDBACK_1J_CLASS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'j_class_j_feedback_1_name',
      ),
      'receiver' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_RECEIVER',
        'id' => 'RECEIVER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'receiver',
      ),
      'received_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_RECEIVED_DATE ',
        'width' => '10%',
        'default' => true,
        'name' => 'received_date',
      ),
      'resolved_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_RESOLVED_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'resolved_date',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
