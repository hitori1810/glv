<?php
$popupMeta = array (
    'moduleMain' => 'J_Gradebook',
    'varName' => 'J_Gradebook',
    'orderBy' => 'j_gradebook.date_entered DESC',
    'whereClauses' => array (
  'name' => 'j_gradebook.name',
  'status' => 'j_gradebook.status',
  'j_class_j_gradebook_1_name' => 'j_gradebook.j_class_j_gradebook_1_name',
  'type' => 'j_gradebook.type',
  'date_input' => 'j_gradebook.date_input',
  'date_entered' => 'j_gradebook.date_entered',
  'c_teachers_j_gradebook_1_name' => 'j_gradebook.c_teachers_j_gradebook_1_name',
  'team_name' => 'j_gradebook.team_name',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'j_class_j_gradebook_1_name',
  5 => 'type',
  6 => 'date_input',
  7 => 'date_entered',
  8 => 'c_teachers_j_gradebook_1_name',
  9 => 'team_name',
),
    'searchdefs' => array (
  'name' =>
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'status' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'j_class_j_gradebook_1_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
    'width' => '10%',
    'id' => 'J_CLASS_J_GRADEBOOK_1J_CLASS_IDA',
    'name' => 'j_class_j_gradebook_1_name',
  ),
  'type' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
  'date_input' =>
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_INPUT',
    'width' => '10%',
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
    'name' => 'date_entered',
  ),
  'c_teachers_j_gradebook_1_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
    'id' => 'C_TEACHERS_J_GRADEBOOK_1C_TEACHERS_IDA',
    'width' => '10%',
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
    'id' => 'TEAM_ID',
    'name' => 'team_name',
  ),
),
    'listviewdefs' => array (
  'J_CLASS_J_GRADEBOOK_1_NAME' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
    'id' => 'J_CLASS_J_GRADEBOOK_1J_CLASS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'j_class_j_gradebook_1_name',
  ),
  'NAME' =>
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'C_TEACHERS_J_GRADEBOOK_1_NAME' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
    'id' => 'C_TEACHERS_J_GRADEBOOK_1C_TEACHERS_IDA',
    'width' => '10%',
    'default' => true,
    'name' => 'c_teachers_j_gradebook_1_name',
  ),
  'DATE_INPUT' =>
  array (
    'type' => 'date',
    'label' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
    'name' => 'date_input',
  ),
  'TYPE' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_TYPE',
    'width' => '7%',
    'default' => true,
    'name' => 'type',
  ),
  'STATUS' =>
  array (
    'type' => 'enum',
    'default' => true,
    'label' => 'LBL_STATUS',
    'width' => '7%',
    'name' => 'status',
  ),
  'DATE_ENTERED' =>
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
  'TEAM_NAME' =>
  array (
    'width' => '9%',
    'label' => 'LBL_TEAM',
    'default' => true,
    'name' => 'team_name',
  ),
),
);
