<?php
$module_name = 'J_Loyalty';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 'name',
      1 => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
      ),
      2 => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
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
      'point' => 
      array (
        'type' => 'int',
        'label' => 'LBL_POINT',
        'width' => '10%',
        'default' => true,
        'name' => 'point',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'name' => 'type',
      ),
      'payment_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_PAYMENT_NAME',
        'id' => 'PAYMENT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'payment_name',
      ),
      'student_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STUDENT_NAME',
        'id' => 'STUDENT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'student_name',
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
      'exp_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_EXP_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'exp_date',
      ),
      'input_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_INPUT_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'input_date',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
      ),
      'team_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'studio' => 
        array (
          'portallistview' => false,
          'portaldetailview' => false,
          'portaleditview' => false,
        ),
        'label' => 'LBL_TEAMS',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
      ),
      'current_user_only' => 
      array (
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
        'name' => 'current_user_only',
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
