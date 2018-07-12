<?php
// created: 2015-08-26 14:09:21
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_LIST_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'module' => 'Leads',
    'width' => '15%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'gender' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_GENDER',
    'width' => '10%',
  ),
  'phone_mobile' => 
  array (
    'type' => 'phone',
    'vname' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'email1' => 
  array (
    'vname' => 'LBL_LIST_EMAIL',
    'width' => '15%',
    'widget_class' => 'SubPanelEmailLink',
    'default' => true,
  ),
  'potential' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_POTENTIAL',
    'width' => '10%',
  ),
  'lead_source' => 
  array (
    'type' => 'enum',
    'vname' => 'LBL_LEAD_SOURCE',
    'width' => '10%',
    'default' => true,
  ),
  'working_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_WORKING_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'link' => true,
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'custom_button' => 
  array (
    'studio' => true,
    'width' => '15%',
    'sortable' => false,
    'default' => true,
  ),
  'm_accept_status_fields' => 
  array (
    'usage' => 'query_only',
  ),
  'accept_status_id' => 
  array (
    'usage' => 'query_only',
  ),
  'first_name' => 
  array (
    'usage' => 'query_only',
  ),
  'last_name' => 
  array (
    'usage' => 'query_only',
  ),
  'salutation' => 
  array (
    'name' => 'salutation',
    'usage' => 'query_only',
  ),
);