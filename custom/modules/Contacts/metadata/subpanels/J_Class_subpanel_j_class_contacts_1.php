<?php
// created: 2015-09-13 07:27:13
$subpanel_layout['list_fields'] = array (
  'custom_checkbox_class' =>
  array (
    'type' => 'varchar',
    'studio' => true,
    'field_value' => 'STUDENT_ID',
    'width' => '3%',
    'default' => true,
    'widget_class' => 'SubPanelCheckbox',
  ),
  'picture' =>
  array (
    'width' => '8%',
    'vname' => 'LBL_AVATAR',
    'default' => true,
  ),
  'contact_id' =>
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CONTACT_ID',
    'width' => '10%',
    'default' => true,
  ),
  'full_student_name' =>
  array (
    'type' => 'relate',
    'vname' => 'LBL_FULL_NAME',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
  ),
  'nick_name' =>
  array (
    'type' => 'varchar',
    'vname' => 'LBL_NICK_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'contact_status' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_CONTACT_STATUS',
    'width' => '10%',
  ),
  'gender' =>
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_GENDER',
    'width' => '10%',
  ),
  'birthdate' =>
  array (
    'type' => 'date',
    'vname' => 'LBL_BIRTHDATE',
    'width' => '10%',
    'default' => true,
  ),
  'phone_mobile' =>
  array (
    'type' => 'function',
    'vname' => 'LBL_MOBILE_PHONE',
    'width' => '10%',
    'default' => true,
  ),
  'email1' =>
  array (
    'name' => 'email1',
    'vname' => 'LBL_LIST_EMAIL',
    'widget_class' => 'SubPanelEmailLink',
    'width' => '10%',
    'sortable' => false,
    'default' => true,
  ),
  'c_contacts_contacts_1_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_C_CONTACTS_CONTACTS_1_NAME',
    'id' => 'C_CONTACTS_CONTACTS_1C_CONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Contacts',
    'target_record_key' => 'c_contacts_contacts_1c_contacts_ida',
  ),
  'subpanel_button' =>
  array (
    'module' => 'Contacts',
    'width' => '3%',
    'default' => true,
    'sortable' => false,
  ),
  'first_name' =>
  array (
    'name' => 'first_name',
    'usage' => 'query_only',
  ),
  'type' =>
  array (
    'name' => 'type',
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
);