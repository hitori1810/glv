<?php
$popupMeta = array (
    'moduleMain' => 'Account',
    'varName' => 'ACCOUNT',
    'orderBy' => 'name',
    'whereClauses' => array (
  'name' => 'accounts.name',
  'full_name' => 'accounts.full_name',
  'phone_office' => 'accounts.phone_office',
  'billing_address_street' => 'accounts.billing_address_street',
  'tax_code' => 'accounts.tax_code',
),
    'searchInputs' => array (
  0 => 'name',
  1 => 'full_name',
  2 => 'phone_office',
  3 => 'billing_address_street',
  4 => 'tax_code',
),
    'create' => array (
  'formBase' => 'AccountFormBase.php',
  'formBaseClass' => 'AccountFormBase',
  'getFormBodyParams' =>
  array (
    0 => '',
    1 => '',
    2 => 'AccountSave',
  ),
  'createButton' => 'LNK_NEW_ACCOUNT',
),
    'searchdefs' => array (
  'name' =>
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'full_name' =>
  array (
    'name' => 'full_name',
    'width' => '10%',
  ),
  'billing_address_street' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'width' => '10%',
    'name' => 'billing_address_street',
  ),
  'phone_office' =>
  array (
    'type' => 'phone',
    'label' => 'LBL_PHONE_OFFICE',
    'width' => '10%',
    'name' => 'phone_office',
  ),
  'tax_code' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_TAX_CODE',
    'width' => '10%',
    'name' => 'tax_code',
  ),
),
    'listviewdefs' => array (
  'NAME' =>
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'FULL_NAME' =>
  array (
    'width' => '20%',
    'label' => 'LBL_FULL_NAME',
    'default' => true,
    'name' => 'full_name',
  ),
  'PHONE_OFFICE' =>
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_PHONE',
    'default' => true,
    'name' => 'phone_office',
  ),
  'BILLING_ADDRESS_STREET' =>
  array (
    'width' => '20%',
    'label' => 'LBL_BILLING_ADDRESS_STREET',
    'default' => true,
    'name' => 'billing_address_street',
  ),
  'TAX_CODE' =>
  array (
    'type' => 'varchar',
    'label' => 'LBL_TAX_CODE',
    'width' => '10%',
    'default' => true,
    'name' => 'tax_code',
  ),
  'TEAM_NAME' =>
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
  ),
),
);
