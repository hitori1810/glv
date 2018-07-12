<?php
$listViewDefs['Contracts'] =
array (
  'name' =>
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_CONTRACT_NAME',
    'link' => true,
    'default' => true,
  ),
  'account_name' =>
  array (
    'width' => '20%',
    'label' => 'LBL_LIST_ACCOUNT_NAME',
    'module' => 'Accounts',
    'id' => 'ACCOUNT_ID',
    'link' => true,
    'default' => true,
    'ACLTag' => 'ACCOUNT',
    'related_fields' =>
    array (
      0 => 'account_id',
    ),
  ),
  'status' =>
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'link' => false,
    'default' => true,
  ),
  'number_of_student' =>
  array (
    'type' => 'int',
    'label' => 'LBL_NUMBER_OF_STUDENT',
    'width' => '10%',
    'default' => true,
  ),
  'total_contract_value' =>
  array (
    'type' => 'currency',
    'label' => 'LBL_TOTAL_CONTRACT_VALUE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'kind_of_course' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'label' => 'LBL_KIND_OF_COURSE',
    'width' => '10%',
  ),
  'customer_signed_date' =>
  array (
    'type' => 'date',
    'label' => 'LBL_CUSTOMER_SIGNED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' =>
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_ASSIGNED_TO_USER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'team_name' =>
  array (
    'width' => '2%',
    'label' => 'LBL_LIST_TEAM',
    'default' => true,
    'related_fields' =>
    array (
      0 => 'team_id',
    ),
  ),
);
