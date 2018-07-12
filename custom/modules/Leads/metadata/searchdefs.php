<?php
$searchdefs['Leads'] = 
array (
    'layout' => 
    array (
        'basic_search' => 
        array (
            'full_lead_name' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_FULL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'full_lead_name',
            ),
            'phone' => 
            array (
                'name' => 'phone',
                'label' => 'LBL_ANY_PHONE',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'status' => 
            array (
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
        ),
        'advanced_search' => 
        array (
            'full_lead_name' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_FULL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'full_lead_name',
            ),
            'phone' => 
            array (
                'name' => 'phone',
                'label' => 'LBL_ANY_PHONE',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'email' => 
            array (
                'name' => 'email',
                'label' => 'LBL_ANY_EMAIL',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'lead_source' => 
            array (
                'name' => 'lead_source',
                'default' => true,
                'width' => '10%',
            ),        
            'status' => 
            array (
                'name' => 'status',
                'default' => true,
                'width' => '10%',
            ),
            'nick_name' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_NICK_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'nick_name',
            ), 
            'prefer_level' => 
            array (
                'type' => 'enum',
                'label' => 'LBL_PREFER_LEVEL',
                'width' => '10%',
                'default' => true,
                'name' => 'prefer_level',
            ), 
            'last_pt_result' => 
            array (
                'type' => 'enum',
                'label' => 'LBL_LAST_PT_RESULT',
                'width' => '10%',
                'default' => true,
                'name' => 'last_pt_result',
            ),
            'potential' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_POTENTIAL',
                'width' => '10%',
                'name' => 'potential',
            ),   
            'j_school_leads_1_name' => 
            array (
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_J_SCHOOL_LEADS_1_FROM_J_SCHOOL_TITLE',
                'id' => 'J_SCHOOL_LEADS_1J_SCHOOL_IDA',
                'width' => '10%',
                'default' => true,
                'name' => 'j_school_leads_1_name',
            ),       
            'birthdate' => 
            array (
                'type' => 'date',
                'label' => 'LBL_BIRTHDATE',
                'width' => '10%',
                'default' => true,
                'name' => 'birthdate',
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
                'default' => true,
                'width' => '10%',
            ),   
            'campaign_name' => 
            array (
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_CAMPAIGN',
                'id' => 'CAMPAIGN_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'campaign_name',
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
                'default' => true,
                'name' => 'date_entered',
            ),
            'created_by' => 
            array (
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'team_name' => 
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
            'date_modified' => 
            array (
                'type' => 'datetime',
                'studio' => 
                array (
                    'portaleditview' => false,
                ),
                'label' => 'LBL_DATE_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'modified_user_id' => 
            array (
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),
            'current_user_only' => 
            array (
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'width' => '10%',
                'default' => true,
                'name' => 'current_user_only',
            ),
            'favorites_only' => 
            array (
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'width' => '10%',
                'default' => true,
            ),
        ),
    ),
    'templateMeta' => 
    array (
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => 
        array (
            'label' => '10',
            'field' => '30',
        ),
    ),
);
