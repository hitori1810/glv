<?php
$popupMeta = array (
    'moduleMain' => 'C_Memberships',
    'varName' => 'C_Memberships',
    'orderBy' => 'c_memberships.name',
    'whereClauses' => array (
  'name' => 'c_memberships.name',
  'name_on_card' => 'c_memberships.name_on_card',
  'is_using' => 'c_memberships.is_using',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'name_on_card',
  5 => 'is_using',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'name_on_card' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_NAME_ON_CARD',
    'width' => '10%',
    'name' => 'name_on_card',
  ),
  'is_using' => 
  array (
    'type' => 'bool',
    'label' => 'LBL_IS_USING',
    'width' => '10%',
    'name' => 'is_using',
  ),
),
);
