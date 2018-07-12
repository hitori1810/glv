<?php
$popupMeta = array (
    'moduleMain' => 'Case',
    'varName' => 'CASE',
    'orderBy' => 'name',
    'whereClauses' => array (
  'name' => 'cases.name',
  'case_number' => 'cases.case_number',
  'assigned_user_id' => 'cases.assigned_user_id',
  'type' => 'cases.type',
  'priority' => 'cases.priority',
  'status' => 'cases.status',
),
    'searchInputs' => array (
  0 => 'case_number',
  1 => 'name',
  2 => 'assigned_user_id',
  3 => 'type',
  4 => 'priority',
  5 => 'status',
),
    'searchdefs' => array (
  'case_number' => 
  array (
    'name' => 'case_number',
    'width' => '10%',
  ),
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
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
  'type' => 
  array (
    'type' => 'enum',
    'sortable' => true,
    'label' => 'LBL_TYPE',
    'width' => '10%',
    'name' => 'type',
  ),
  'priority' => 
  array (
    'name' => 'priority',
    'width' => '10%',
  ),
  'status' => 
  array (
    'name' => 'status',
    'width' => '10%',
  ),
),
    'listviewdefs' => array (
  'CASE_NUMBER' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_NUMBER',
    'default' => true,
    'name' => 'case_number',
  ),
  'NAME' => 
  array (
    'width' => '15%',
    'label' => 'LBL_LIST_SUBJECT',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'PRIORITY' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_PRIORITY',
    'default' => true,
    'name' => 'priority',
  ),
  'STATUS' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_STATUS',
    'default' => true,
    'name' => 'status',
  ),
  'DESCRIPTION' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '35%',
    'default' => true,
    'name' => 'description',
  ),
  'WORK_LOG' => 
  array (
    'type' => 'text',
    'label' => 'LBL_WORK_LOG',
    'sortable' => false,
    'width' => '30%',
    'default' => true,
    'name' => 'work_log',
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
  ),
  'CREATED_BY_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CREATED',
    'id' => 'CREATED_BY',
    'width' => '10%',
    'default' => true,
    'name' => 'created_by_name',
  ),
),
);
