<?php
$popupMeta = array (
    'moduleMain' => 'J_Coursefee',
    'varName' => 'J_Coursefee',
    'orderBy' => 'j_coursefee.name',
    'whereClauses' => array (
  'name' => 'j_coursefee.name',
  'type_of_course_fee' => 'j_coursefee.type_of_course_fee',
  'team_name' => 'j_coursefee.team_name',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'type_of_course_fee',
  5 => 'team_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'type_of_course_fee' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE_OF_COURSE_FEE',
    'width' => '10%',
    'name' => 'type_of_course_fee',
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
);
