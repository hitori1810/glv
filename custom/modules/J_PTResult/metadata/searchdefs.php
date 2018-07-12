<?php
$module_name = 'J_PTResult';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'speaking' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SPEAKING',
        'width' => '10%',
        'default' => true,
        'name' => 'speaking',
      ),
      'writing' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_WRITING',
        'width' => '10%',
        'default' => true,
        'name' => 'writing',
      ),
      'listening' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_LISTENING',
        'width' => '10%',
        'default' => true,
        'name' => 'listening',
      ),
      'result' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RESULT',
        'width' => '10%',
        'default' => true,
        'name' => 'result',
      ),
    ),
    'advanced_search' => 
    array (
      'meetings_j_ptresult_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_MEETINGS_TITLE',
        'id' => 'MEETINGS_J_PTRESULT_1MEETINGS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'meetings_j_ptresult_1_name',
      ),
      'name' => 
      array (
        'type' => 'name',
        'link' => true,
        'label' => 'LBL_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'name',
      ),
      'speaking' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SPEAKING',
        'width' => '10%',
        'default' => true,
        'name' => 'speaking',
      ),
      'writing' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_WRITING',
        'width' => '10%',
        'default' => true,
        'name' => 'writing',
      ),
      'listening' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_LISTENING',
        'width' => '10%',
        'default' => true,
        'name' => 'listening',
      ),
      'result' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_RESULT',
        'width' => '10%',
        'default' => true,
        'name' => 'result',
      ),
      'attended' => 
      array (
        'type' => 'bool',
        'default' => true,
        'label' => 'LBL_ATTENDED',
        'width' => '10%',
        'name' => 'attended',
      ),
      'parent' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_PARENT',
        'width' => '10%',
        'default' => true,
        'name' => 'parent',
      ),
      'contacts_j_ptresult_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_CONTACTS_TITLE',
        'id' => 'CONTACTS_J_PTRESULT_1CONTACTS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'contacts_j_ptresult_1_name',
      ),
      'leads_j_ptresult_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
        'id' => 'LEADS_J_PTRESULT_1LEADS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'leads_j_ptresult_1_name',
      ),
      'assigned_user_id' => 
      array (
        'name' => 'assigned_user_id',
        'label' => 'LBL_ASSIGNED_TO',
        'type' => 'enum',
        'function' => 
        array (
          'name' => 'get_user_array',
          'params' => 
          array (
            0 => false,
          ),
        ),
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
