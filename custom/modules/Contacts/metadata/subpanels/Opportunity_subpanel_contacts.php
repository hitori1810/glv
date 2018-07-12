<?php
    // created: 2014-04-24 16:13:18
    $subpanel_layout['list_fields'] = array (
        'name' => 
        array (
            'name' => 'name',
            'vname' => 'LBL_LIST_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'module' => 'Contacts',
            'width' => '23%',
            'default' => true,
        ),
        'phone_mobile' => 
        array (
            'type' => 'phone',
            'vname' => 'LBL_MOBILE_PHONE',
            'width' => '10%',
            'default' => true,
        ),
        'phone_work' => 
        array (
            'name' => 'phone_work',
            'vname' => 'LBL_LIST_PHONE',
            'width' => '15%',
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
        'opportunity_role_fields' => 
        array (
            'usage' => 'query_only',
        ),
        'opportunity_role_id' => 
        array (
            'usage' => 'query_only',
        ),
        'type' => 
        array (
            'usage' => 'query_only',
        ),
    );