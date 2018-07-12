<?php
$module_name = 'J_Voucher';
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
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status',
      ),
      'foc_type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_FOC_TYPE',
        'width' => '10%',
        'name' => 'foc_type',
      ),
      'student_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_STUDENT_NAME',
        'width' => '10%',
        'default' => true,
        'id' => 'STUDENT_ID',
        'name' => 'student_name',
      ),
      'discount_amount' => 
      array (
        'type' => 'currency',
        'default' => true,
        'label' => 'LBL_DISCOUNT_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'name' => 'discount_amount',
      ),
      'discount_percent' => 
      array (
        'type' => 'decimal',
        'default' => true,
        'label' => 'LBL_DISCOUNT_PERCENT',
        'width' => '10%',
        'name' => 'discount_percent',
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
