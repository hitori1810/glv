<?php
$popupMeta = array (
    'moduleMain' => 'J_Class',
    'varName' => 'J_Class',
    'orderBy' => 'j_class.date_entered DESC',
    'whereClauses' => array (
  'name' => 'j_class.name',
  'class_code' => 'j_class.class_code',
  'koc_name' => 'j_class.koc_name',
  'hours' => 'j_class.hours',
  'status' => 'j_class.status',
  'team_name' => 'j_class.team_name',
  'isupgrade' => 'j_class.isupgrade',
  'class_type' => 'j_class.class_type',
  'start_date' => 'j_class.start_date',
  'end_date' => 'j_class.end_date',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'class_code',
  9 => 'koc_name',
  10 => 'hours',
  11 => 'status',
  12 => 'team_name',
  13 => 'isupgrade',
  14 => 'class_type',
  15 => 'start_date',
  16 => 'end_date',
),
    'searchdefs' => array (
  'class_code' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_CLASS_CODE',
    'width' => '10%',
    'name' => 'class_code',
  ),
  'name' =>
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'start_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'name' => 'start_date',
  ),
  'end_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'name' => 'end_date',
  ),
  'koc_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_KOC_NAME',
    'id' => 'KOC_ID',
    'width' => '10%',
    'name' => 'koc_name',
  ),
  'hours' =>
  array (
    'type' => 'int',
    'label' => 'LBL_HOURS',
    'width' => '10%',
    'name' => 'hours',
  ),
  'status' =>
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'class_type' =>
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CLASS_TYPE',
    'width' => '10%',
    'name' => 'class_type',
  ),
  'isupgrade' =>
  array (
    'type' => 'bool',
    'label' => 'LBL_ISUPGRADE',
    'width' => '10%',
    'name' => 'isupgrade',
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
    'name' => 'team_name',
  ),
),
    'listviewdefs' => array (
  'CLASS_CODE' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_CLASS_CODE',
    'width' => '10%',
    'default' => true,
    'name' => 'class_code',
  ),
  'NAME' =>
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'KOC_NAME' =>
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_KOC_NAME',
    'id' => 'KOC_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'koc_name',
  ),
  'STATUS' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'CLASS_TYPE' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_CLASS_TYPE',
    'width' => '10%',
    'default' => true,
    'name' => 'class_type',
  ),
  'START_DATE' =>
  array (
    'type' => 'date',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
    'name' => 'start_date',
  ),
  'END_DATE' =>
  array (
    'type' => 'date',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
    'name' => 'end_date',
  ),
  'ASSIGNED_USER_NAME' =>
  array (
    'width' => '9%',
    'label' => 'LBL_ASSIGNED_TO_NAME',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
    'name' => 'assigned_user_name',
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
