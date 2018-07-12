<?php
$popupMeta = array (
    'moduleMain' => 'Prospect',
    'varName' => 'PROSPECT',
    'orderBy' => 'prospects.last_name, prospects.first_name',
    'whereClauses' => array (
        'name' => 'prospects.name',
        'dob_date' => 'prospects.dob_date',
        'created_by' => 'prospects.created_by',
        'assigned_user_id' => 'prospects.assigned_user_id',
        'status' => 'prospects.status',                          
        'date_entered' => 'prospects.date_entered',
    ),
    'searchInputs' => array (
        2 => 'name',
        5 => 'dob_date',
        10 => 'assigned_user_id',
        11 => 'created_by',
        12 => 'status',             
        18 => 'date_entered',
    ),
    'searchdefs' => array (
        'name' => 
        array (
            'type' => 'name',
            'link' => true,
            'label' => 'LBL_NAME',
            'width' => '10%',
            'name' => 'name',
        ),
        'dob_date' => 
        array (
            'type' => 'date',
            'label' => 'LBL_BIRTHDATE',
            'width' => '10%',
            'name' => 'dob_date',
        ),
        'status' => 
        array (
            'type' => 'enum',
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
        ),    
        'date_entered' => 
        array (
            'type' => 'datetime',
            'studio' => 
            array (
                'portaleditview' => false,
            ),
            'label' => 'LBL_DATE_ENTERED',
            'width' => '10%',
            'name' => 'date_entered',
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
        'created_by' => 
        array (
            'name' => 'created_by',
            'type' => 'enum',
            'label' => 'LBL_CREATED_USER',
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
        'FULL_NAME' => 
        array (
            'width' => '15%',
            'label' => 'LBL_LIST_NAME',
            'link' => true,
            'related_fields' => 
            array (
                0 => 'first_name',
                1 => 'last_name',
            ),
            'orderBy' => 'last_name',
            'default' => true,
            'name' => 'full_name',
        ),   
        'DOB_DATE' => 
        array (
            'type' => 'date',
            'label' => 'LBL_BIRTHDATE',
            'width' => '10%',
            'default' => true,
            'name' => 'dob_date',
        ),
        'STATUS' => 
        array (
            'type' => 'enum',
            'default' => true,
            'label' => 'LBL_STATUS',
            'width' => '10%',
            'name' => 'status',
        ),   
        'ASSIGNED_USER_NAME' => 
        array (
            'link' => true,
            'type' => 'relate',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'id' => 'ASSIGNED_USER_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'assigned_user_name',
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
            'name' => 'team_name',
        ),
    ),
);
