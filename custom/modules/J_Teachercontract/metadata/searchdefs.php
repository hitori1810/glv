<?php
$module_name = 'J_Teachercontract';
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
      'c_teachers_j_teachercontract_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_C_TEACHERS_J_TEACHERCONTRACT_1_FROM_C_TEACHERS_TITLE',
        'id' => 'C_TEACHERS_J_TEACHERCONTRACT_1C_TEACHERS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'c_teachers_j_teachercontract_1_name',
      ),
      'contract_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_CONTRACT_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'contract_date',
      ),
      'contract_until' => 
      array (
        'type' => 'date',
        'label' => 'LBL_CONTRACT_UNTIL',
        'width' => '10%',
        'default' => true,
        'name' => 'contract_until',
      ),
      'contract_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CONTRACT_TYPE',
        'width' => '10%',
        'name' => 'contract_type',
      ),
      'status' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status',
      ),
      'day_off' => 
      array (
        'type' => 'multienum',
        'studio' => 'visible',
        'label' => 'LBL_DAY_OFF',
        'width' => '10%',
        'default' => true,
        'name' => 'day_off',
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
