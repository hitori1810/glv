<?php
// created: 2017-08-24 23:03:00
$subpanel_layout['list_fields'] = array (
  'contract_number' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CONTRACT_NUMBER',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'module' => 'Contacts',
    'width' => '15%',
    'default' => true,
  ),
  'status' => 
  array (
    'name' => 'status',
    'vname' => 'LBL_LIST_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'total_contract_value' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_TOTAL_CONTRACT_VALUE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'sortable' => false,
  ),
  'customer_signed_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_CUSTOMER_SIGNED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'number_of_student' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_NUMBER_OF_STUDENT',
    'width' => '10%',
    'default' => true,
  ),
  'duration_session' => 
  array (
    'type' => 'decimal',
    'vname' => 'LBL_DURATION_SESSION',
    'width' => '10%',
    'default' => true,
  ),
  'duration_hour' => 
  array (
    'type' => 'decimal',
    'vname' => 'LBL_DURATION_HOUR',
    'width' => '10%',
    'default' => true,
  ),
  'team_name' => 
  array (
    'width' => '10%',
    'vname' => 'LBL_TEAM',
    'widget_class' => 'SubPanelDetailViewLink',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'usage' => 'query_only',
  ),
);