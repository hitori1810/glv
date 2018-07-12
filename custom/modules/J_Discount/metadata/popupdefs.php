<?php
$popupMeta = array (
    'moduleMain' => 'J_Discount',
    'varName' => 'J_Discount',
    'orderBy' => 'j_discount.date_entered DESC',
    'whereClauses' => array (
  'name' => 'j_discount.name',
  'status' => 'j_discount.status',
  'category' => 'j_discount.category',
  'type' => 'j_discount.type',
  'date_entered' => 'j_discount.date_entered',
  'team_name' => 'j_discount.team_name',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'category',
  5 => 'type',
  6 => 'date_entered',
  7 => 'team_name',
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
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'category' =>
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_CATEGORY',
    'width' => '10%',
    'name' => 'category',
  ),
  'type' =>
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
    'discount_percent' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
  ),
  'discount_amount' => 
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_AMOUNT',
    'width' => '10%',
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
