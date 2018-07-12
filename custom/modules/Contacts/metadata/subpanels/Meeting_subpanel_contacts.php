<?php
// created: 2014-08-22 14:27:06
$subpanel_layout['top_buttons'] = array(
    array (
        'widget_class' => 'SubPanelTopSelectStudentButton',
    ),
);

$subpanel_layout['list_fields'] = array (
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
        'widget_class' => 'SubPanelDetailViewLink',
        'module' => 'Contacts',
        'width' => '23%',
        'default' => true,
    ),
    'situation_type' =>
    array (
        'name' => 'situation_type',
        'vname' => 'LBL_TYPE',
        'width' => '10%',
        'sortable' => false,
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
    'phone_mobile' => 
    array (
        'name' => 'phone_mobile',
        'vname' => 'LBL_PHONE_MOBILE',
        'width' => '15%',
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
        'width' => '15%',
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
        'usage' => 'query_only',
    ),
    'contact_attendance' => 
    array (
        'usage' => 'query_only',
    ),
    'attendance_id' => 
    array (
        'usage' => 'query_only',
    ),
);