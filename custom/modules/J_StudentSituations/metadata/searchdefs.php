<?php
$module_name = 'J_StudentSituations';
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
      'type' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
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
      'start_study' => 
      array (
        'type' => 'date',
        'label' => 'LBL_START_STUDY',
        'width' => '10%',
        'default' => true,
        'name' => 'start_study',
      ),
      'end_study' => 
      array (
        'type' => 'date',
        'label' => 'LBL_END_STUDY',
        'width' => '10%',
        'default' => true,
        'name' => 'end_study',
      ),
      'ju_class_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_JU_CLASS_NAME',
        'id' => 'JU_CLASS_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'ju_class_name',
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
      'date_entered' => 
      array (
        'type' => 'datetime',
        'studio' => 
        array (
          'portaleditview' => false,
        ),
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_entered',
      ),
      'total_hour' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_TOTAL_HOUR',
        'width' => '10%',
        'default' => true,
        'name' => 'total_hour',
      ),
      'total_amount' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_TOTAL_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'total_amount',
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
