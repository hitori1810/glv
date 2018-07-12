<?php
$module_name = 'C_DeliveryRevenue';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
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
      'date_input' => 
      array (
        'type' => 'date',
        'label' => 'LBL_DATE_INPUT',
        'width' => '10%',
        'default' => true,
        'name' => 'date_input',
      ),
      'passed' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_PASSED',
        'width' => '10%',
        'default' => true,
        'name' => 'passed',
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
    ),
    'advanced_search' => 
    array (
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
      'duration' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_DURATION',
        'width' => '10%',
        'default' => true,
        'name' => 'duration',
      ),
      'amount' => 
      array (
        'type' => 'decimal',
        'label' => 'LBL_AMOUNT',
        'width' => '10%',
        'default' => true,
        'name' => 'amount',
      ),
      'date_input' => 
      array (
        'type' => 'date',
        'label' => 'LBL_DATE_INPUT',
        'width' => '10%',
        'default' => true,
        'name' => 'date_input',
      ),
      'passed' => 
      array (
        'type' => 'bool',
        'label' => 'LBL_PASSED',
        'width' => '10%',
        'default' => true,
        'name' => 'passed',
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
