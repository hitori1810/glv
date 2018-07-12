<?php
// created: 2014-08-14 11:11:31
$subpanel_layout['list_fields'] = array (
  'checkbox' => 
  array (
    'type' => 'varchar',
    'studio' => true,
    'vname' => 'LBL_CHECKBOX',
    'width' => '3%',
    'default' => true,
    'sortable' => false,
    'widget_class' => 'SubPanelCheckAll',
  ),
  'contact_id' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CONTACT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'sort_by' => 'last_name',
    'sort_order' => 'asc',
    'widget_class' => 'SubPanelDetailViewLink',
    'module' => 'Contacts',
    'width' => '23%',
    'default' => true,
  ),
  'account_name' => 
  array (
    'name' => 'account_name',
    'module' => 'Accounts',
    'target_record_key' => 'account_id',
    'target_module' => 'Accounts',
    'widget_class' => 'SubPanelDetailViewLink',
    'vname' => 'LBL_LIST_ACCOUNT_NAME',
    'width' => '20%',
    'sortable' => false,
    'default' => true,
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
    'name' => 'email1',
    'vname' => 'LBL_LIST_EMAIL',
    'widget_class' => 'SubPanelEmailLink',
    'width' => '30%',
    'sortable' => false,
    'default' => true,
  ),
  'subpanel_button' => 
  array (
    'module' => 'Contacts',
    'width' => '5%',
    'default' => true,
    'sortable' => false,
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'usage' => 'query_only',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'usage' => 'query_only',
  ),
  'salutation' => 
  array (
    'name' => 'salutation',
    'usage' => 'query_only',
  ),
  'account_id' => 
  array (
    'usage' => 'query_only',
  ),
  'type' => 
  array (
    'name' => 'type',
    'usage' => 'query_only',
  ),
);