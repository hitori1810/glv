<?php
$module_name = 'J_Gradebook';
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
      'j_class_j_gradebook_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
        'id' => 'J_CLASS_J_GRADEBOOK_1J_CLASS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'j_class_j_gradebook_1_name',
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
        'label' => 'LBL_TEAM',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
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
        'label' => 'LBL_STATUS',
        'width' => '10%',
        'name' => 'status',
      ),
      'type' => 
      array (
        'type' => 'enum',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'default' => true,
        'name' => 'type',
      ),
      'date_input' => 
      array (
        'type' => 'date',
        'label' => 'LBL_DATE_INPUT',
        'width' => '10%',
        'default' => true,
        'name' => 'date_input',
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
      'j_class_j_gradebook_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
        'width' => '10%',
        'default' => true,
        'id' => 'J_CLASS_J_GRADEBOOK_1J_CLASS_IDA',
        'name' => 'j_class_j_gradebook_1_name',
      ),
      'c_teachers_j_gradebook_1_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
        'id' => 'C_TEACHERS_J_GRADEBOOK_1C_TEACHERS_IDA',
        'width' => '10%',
        'default' => true,
        'name' => 'c_teachers_j_gradebook_1_name',
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
        'width' => '10%',
        'default' => true,
        'id' => 'TEAM_ID',
        'name' => 'team_name',
      ),
      'created_by' => 
      array (
        'type' => 'assigned_user_name',
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => true,
        'name' => 'created_by',
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
