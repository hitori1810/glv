<?php
$module_name = 'C_Teachers';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      0 => 
      array (
        'name' => 'search_name',
        'label' => 'LBL_NAME',
        'type' => 'name',
      ),
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
      'teacher_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_TEACHER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'teacher_id',
      ),
      'first_name' => 
      array (
        'name' => 'first_name',
        'default' => true,
        'width' => '10%',
      ),
      'last_name' => 
      array (
        'name' => 'last_name',
        'default' => true,
        'width' => '10%',
      ),
      'address_city' => 
      array (
        'name' => 'address_city',
        'default' => true,
        'width' => '10%',
      ),
      'phone_mobile' => 
      array (
        'type' => 'phone',
        'label' => 'LBL_MOBILE_PHONE',
        'width' => '10%',
        'default' => true,
        'name' => 'phone_mobile',
      ),
      'email' => 
      array (
        'name' => 'email',
        'default' => true,
        'width' => '10%',
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
      'teach_hours' => 
      array (
        'type' => 'int',
        'label' => 'LBL_TEACH_HOURS',
        'width' => '10%',
        'default' => true,
        'name' => 'teach_hours',
      ),
      'date_modified' => 
      array (
        'type' => 'datetime',
        'studio' => 
        array (
          'portaleditview' => false,
        ),
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => true,
        'name' => 'date_modified',
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
      'nationality' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_NATIONALITY',
        'width' => '10%',
        'default' => true,
        'name' => 'nationality',
      ),
      'teacher_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TEACHER_TYPE',
        'width' => '10%',
        'name' => 'teacher_type',
      ),
      'full_teacher_name' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_FULL_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'full_teacher_name',
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
