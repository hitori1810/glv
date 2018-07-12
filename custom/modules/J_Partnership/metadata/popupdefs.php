<?php
$popupMeta = array (
    'moduleMain' => 'J_Partnership',
    'varName' => 'J_Partnership',
    'orderBy' => 'j_partnership.date_entered DESC',
    'whereClauses' => array (
  'name' => 'j_partnership.name',
),
    'searchInputs' => array (
  0 => 'j_partnership_number',
  1 => 'name',
  2 => 'priority',
  3 => 'status',
),
    'listviewdefs' => array (
  'NAME' =>
  array (
    'width' => '20%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
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
  'DISCOUNT_PERCENT' =>
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_PERCENT',
    'width' => '10%',
    'default' => true,
    'name' => 'discount_percent',
  ),
  'DISCOUNT_AMOUNT' =>
  array (
    'type' => 'decimal',
    'label' => 'LBL_DISCOUNT_AMOUNT',
    'width' => '10%',
    'default' => true,
    'name' => 'discount_amount',
  ),
  'HOURS' =>
  array (
    'type' => 'enum',
    'label' => 'LBL_HOURS',
    'width' => '10%',
    'default' => true,
    'name' => 'hours',
  ),
  'DESCRIPTION' =>
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
    'name' => 'description',
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
