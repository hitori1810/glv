<?php
$popupMeta = array (
    'moduleMain' => 'Contract',
    'varName' => 'CONTRACT',
    'orderBy' => 'contracts.name',
    'whereClauses' => array (
  'name' => 'contracts.name',
  'status' => 'contracts.status',
  'contract_id' => 'contracts.contract_id',
  'account_name' => 'contracts.account_name',
  'team_name' => 'contracts.team_name',
  'assigned_user_id' => 'contracts.assigned_user_id',
),
    'searchInputs' => array (
  1 => 'account_name',
  2 => 'name',
  4 => 'status',
  5 => 'contract_id',
  6 => 'team_name',
  7 => 'assigned_user_id',
),
    'searchdefs' => array (
  'contract_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTRACT_ID',
    'width' => '10%',
    'name' => 'contract_id',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'account_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_ACCOUNT_NAME',
    'id' => 'ACCOUNT_ID',
    'width' => '10%',
    'name' => 'account_name',
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
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
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'type' => 'enum',
    'label' => 'LBL_ASSIGNED_TO',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'CONTRACT_ID' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_CONTRACT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_CONTRACT_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'STATUS' => 
  array (
    'width' => '10%',
    'label' => 'LBL_STATUS',
    'link' => false,
    'default' => true,
    'name' => 'status',
  ),
  'CUSTOMER_SIGNED_DATE' => 
  array (
    'type' => 'date',
    'label' => 'LBL_CUSTOMER_SIGNED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'TOTAL_CONTRACT_VALUE' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_TOTAL_CONTRACT_VALUE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'TOTAL_STUDENT' => 
  array (
    'type' => 'int',
    'label' => 'LBL_TOTAL_STUDENT',
    'width' => '10%',
    'default' => true,
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
  'ASSIGNED_USER_NAME' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_ASSIGNED_USER',
    'link' => false,
    'default' => true,
    'name' => 'assigned_user_name',
  ),
),
);
