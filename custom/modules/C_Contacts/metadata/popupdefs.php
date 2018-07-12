<?php
$popupMeta = array (
    'moduleMain' => 'C_Contacts',
    'varName' => 'C_Contacts',
    'orderBy' => 'c_contacts.name',
    'whereClauses' => array (
  'name' => 'c_contacts.name',
  'email' => 'c_contacts.email',
  'mobile_phone' => 'c_contacts.mobile_phone',
  'address' => 'c_contacts.address',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'email',
  5 => 'mobile_phone',
  6 => 'address',
),
    'create' => array (
  'formBase' => 'FormBase.php',
  'formBaseClass' => 'FormBase',
  'createButton' => 'Create',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'mobile_phone' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'name' => 'mobile_phone',
  ),
  'email' => 
  array (
    'type' => 'email',
    'studio' => 
    array (
      'visible' => false,
      'searchview' => true,
    ),
    'label' => 'LBL_ANY_EMAIL',
    'width' => '10%',
    'name' => 'email',
  ),
  'address' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_ADDRESS',
    'sortable' => false,
    'width' => '10%',
    'name' => 'address',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_NAME',
    'default' => true,
    'link' => true,
    'name' => 'name',
  ),
  'MOBILE_PHONE' => 
  array (
    'type' => 'phone',
    'label' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
    'name' => 'mobile_phone',
  ),
  'EMAIL1' => 
  array (
    'type' => 'varchar',
    'studio' => 
    array (
      'editview' => true,
      'editField' => true,
      'searchview' => false,
      'popupsearch' => false,
    ),
    'label' => 'LBL_EMAIL_ADDRESS',
    'width' => '10%',
    'default' => true,
    'name' => 'email1',
  ),
  'ADDRESS' => 
  array (
    'type' => 'text',
    'studio' => 'visible',
    'label' => 'LBL_ADDRESS',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
    'name' => 'address',
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
