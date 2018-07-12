<?php
$popupMeta = array (
    'moduleMain' => 'C_Teachers',
    'varName' => 'C_Teachers',
    'orderBy' => 'c_teachers.date_entered DESC',
    'whereClauses' => array (
  'teacher_id' => 'c_teachers.teacher_id',
  'full_teacher_name' => 'c_teachers.full_teacher_name',
  'phone_mobile' => 'c_teachers.phone_mobile',
  'email' => 'c_teachers.email',
  'date_modified' => 'c_teachers.date_modified',
  'date_entered' => 'c_teachers.date_entered',
  'teacher_type' => 'c_teachers.teacher_type',
  'team_name' => 'c_teachers.team_name',
),
    'searchInputs' => array (
  2 => 'teacher_id',
  3 => 'full_teacher_name',
  4 => 'phone_mobile',
  5 => 'email',
  8 => 'date_modified',
  9 => 'date_entered',
  10 => 'teacher_type',
  11 => 'team_name',
),
    'searchdefs' => array (
  'teacher_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_TEACHER_ID',
    'width' => '10%',
    'name' => 'teacher_id',
  ),
  'full_teacher_name' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_FULL_NAME',
    'width' => '10%',
    'name' => 'full_teacher_name',
  ),
  'phone_mobile' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'name' => 'phone_mobile',
  ),
  'email' => 
  array (
    'name' => 'email',
    'width' => '10%',
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
    'name' => 'date_entered',
  ),
  'teacher_type' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TEACHER_TYPE',
    'width' => '10%',
    'name' => 'teacher_type',
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
  'TEACHER_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_TEACHER_ID',
    'width' => '10%',
    'default' => true,
    'name' => 'teacher_id',
  ),
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'link' => true,
    'orderBy' => 'last_name',
    'default' => true,
    'related_fields' => 
    array (
      0 => 'first_name',
      1 => 'last_name',
      2 => 'salutation',
    ),
    'name' => 'name',
  ),
  'EMAIL1' => 
  array (
    'width' => '15%',
    'label' => 'LBL_EMAIL_ADDRESS',
    'sortable' => false,
    'link' => true,
    'customCode' => '{$EMAIL1_LINK}{$EMAIL1}</a>',
    'default' => true,
    'name' => 'email1',
  ),
  'PHONE_MOBILE' => 
  array (
    'width' => '10%',
    'label' => 'LBL_MOBILE_PHONE',
    'default' => true,
    'name' => 'phone_mobile',
  ),
  'TITLE' => 
  array (
    'width' => '15%',
    'label' => 'LBL_TITLE',
    'default' => true,
    'name' => 'title',
  ),
  'DATE_ENTERED' => 
  array (
    'width' => '12%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => true,
    'name' => 'date_entered',
  ),
  'TEAM_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_TEAM',
    'default' => true,
    'name' => 'team_name',
  ),
),
);
