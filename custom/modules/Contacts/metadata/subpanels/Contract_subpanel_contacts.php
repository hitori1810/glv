<?php
// created: 2014-08-20 09:24:51
$subpanel_layout['list_fields'] = array (
    'checkbox' =>
    array (
        'type' => 'varchar',
        'studio' => true,
        'field_value' => 'STUDENT_ID',
        'width' => '3%',
        'default' => true,
        'widget_class' => 'SubPanelCheckbox',
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
    'email1' =>
    array (
        'name' => 'email1',
        'vname' => 'LBL_LIST_EMAIL',
        'widget_class' => 'SubPanelEmailLink',
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
    'account_name' =>
    array (
        'type' => 'relate',
        'link' => true,
        'vname' => 'LBL_ACCOUNT_NAME',
        'id' => 'ACCOUNT_ID',
        'width' => '15%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Accounts',
        'target_record_key' => 'account_id',
    ),
    'edit_button' =>
    array (
        'width' => '5%',
        'vname' => 'LBL_EDIT_BUTTON',
        'default' => true,
        'widget_class' => 'SubPanelEditButton',
    ),
    'remove_button' =>
    array (
        'width' => '5%',
        'vname' => 'LBL_REMOVE',
        'default' => true,
        'widget_class' => 'SubPanelRemoveButton',
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
);