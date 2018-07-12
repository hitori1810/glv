<?php
$popupMeta = array (
    'moduleMain' => 'C_SMS',
    'varName' => 'C_SMS',
    'orderBy' => 'c_sms.status',
    'whereClauses' => array (
        'name' => 'c_sms.name',
        'assigned_user_id' => 'c_sms.assigned_user_id',
        'template_id' => 'c_sms.template_id',
        'date_in_content' => 'c_sms.date_in_content',
    ),
    'searchInputs' => array (
        1 => 'name',
        4 => 'assigned_user_id',
        5 => 'template_name',
        6 => 'date_in_content',
    ),
    'searchdefs' => array (
        'name' => 
        array (
            'name' => 'name',
            'width' => '10%',
        ),
        'assigned_user_name' => 
        array (
            'name' => 'assigned_user_name',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
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
        'template_name' => 
        array (
            'label' => 'LBL_TEMPLATE_NAME',
            'width' => '10%',
            'name' => 'template_name',
        ),
        'date_in_content' => 
        array (
            'type' => 'date',
            'label' => 'LBL_DATE_IN_CONTENT',
            'width' => '10%',
            'name' => 'date_in_content',
        ),
    ),
    'listviewdefs' => array (
        'NAME' => 
        array (
            'width' => '10%',
            'label' => 'LBL_NAME',
            'default' => true,
            'link' => true,
            'name' => 'name',
        ),
        'DATE_SEND' => 
        array (
            'type' => 'date',
            'label' => 'LBL_DATE_SEND',
            'width' => '10%',
            'default' => true,
            'name' => 'date_send',
            'query_only' => true,
        ),
        'DESCRIPTION' => 
        array (
            'type' => 'text',
            'label' => 'LBL_DESCRIPTION',
            'sortable' => false,
            'width' => '20%',
            'default' => true,
            'name' => 'description',
        ),
        'DELIVERY_STATUS' => 
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'label' => 'LBL_DELIVERY_STATUS',
            'width' => '10%',
            'name' => 'delivery_status',
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
            'name' => 'date_entered',
        ),
        'template_id' => 
        array (
            'usage' => 'query_only',
        ),
        'date_in_content' => 
        array (
            'usage' => 'query_only',
        ),
    ),
);
