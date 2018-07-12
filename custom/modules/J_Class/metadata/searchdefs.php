<?php
$module_name = 'J_Class';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'class_code' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CLASS_CODE',
        'width' => '10%',
        'default' => true,
        'name' => 'class_code',
      ),
      'name' => 
      array (
        'type' => 'name',
        'link' => true,
        'default' => true,
        'width' => '10%',
        'label' => 'LBL_NAME',
        'name' => 'name',
      ),
      'class_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CLASS_TYPE',
        'width' => '10%',
        'name' => 'class_type',
      ),
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
      ),
    ),
    'advanced_search' => 
    array (
      'class_code' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CLASS_CODE',
        'width' => '10%',
        'default' => true,
        'name' => 'class_code',
      ),
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'class_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_CLASS_TYPE',
        'width' => '10%',
        'name' => 'class_type',
      ),
      'start_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
      ),
      'end_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'end_date',
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
      'kind_of_course' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_KIND_OF_COURSE',
        'width' => '10%',
        'name' => 'kind_of_course',
      ),
      'hours' => 
      array (
        'type' => 'int',
        'default' => true,
        'label' => 'LBL_HOURS',
        'width' => '10%',
        'name' => 'hours',
      ),
      'max_size' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MAX_SIZE',
        'width' => '10%',
        'name' => 'max_size',
      ),
      'isupgrade' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_ISUPGRADE',
        'width' => '10%',
        'default' => true,
        'name' => 'isupgrade',
      ),
      'level' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_LEVEL',
        'width' => '10%',
        'name' => 'level',
      ),
      'modules' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_MODULE',
        'width' => '10%',
        'name' => 'modules',
      ),
      'period' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_PERIOD',
        'width' => '10%',
        'name' => 'period',
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
        'width' => '10%',
        'default' => true,
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
      'description' => 
      array (
        'type' => 'text',
        'label' => 'LBL_DESCRIPTION',
        'sortable' => false,
        'width' => '10%',
        'default' => true,
        'name' => 'description',
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
