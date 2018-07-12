<?php
$searchdefs['Contacts'] = 
array (
    'layout' => 
    array (
        'basic_search' => 
        array (
            'contact_id' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_CONTACT_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'contact_id',
            ),
            'full_student_name' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_FULL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'full_student_name',
            ),
            'phone' => 
            array (
                'name' => 'phone',
                'label' => 'LBL_ANY_PHONE',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),  
        ),
        'advanced_search' => 
        array (
            'full_student_name' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_FULL_NAME',
                'width' => '10%',
                'default' => true,
                'name' => 'full_student_name',
            ),
            'contact_id' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_CONTACT_ID',
                'width' => '10%',
                'default' => true,
                'name' => 'contact_id',
            ), 
            'phone' => 
            array (
                'name' => 'phone',
                'label' => 'LBL_ANY_PHONE',
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
            'contact_status' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_CONTACT_STATUS',
                'width' => '10%',
                'name' => 'contact_status',
            ),
            'email' => 
            array (
                'name' => 'email',
                'label' => 'LBL_ANY_EMAIL',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),  
            'prefer_level' => 
            array (
                'type' => 'enum',
                'label' => 'LBL_PREFER_LEVEL',
                'width' => '10%',
                'default' => true,
                'name' => 'prefer_level',
            ),
            'type' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_TYPE',
                'width' => '10%',
                'name' => 'type',
            ), 
            'portal_active' => 
            array (
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_PORTAL_ACTIVE',
                'width' => '10%',
                'name' => 'portal_active',
            ),    
            'j_school_contacts_1_name' => 
            array (
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_J_SCHOOL_CONTACTS_1_FROM_J_SCHOOL_TITLE',
                'id' => 'J_SCHOOL_CONTACTS_1J_SCHOOL_IDA',
                'width' => '10%',
                'default' => true,
                'name' => 'j_school_contacts_1_name',
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
            'favorites_only' => 
            array (
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
                'width' => '10%',
                'default' => true,
            ),
            'current_user_only' => 
            array (
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
                'default' => true,
                'width' => '10%',
                'name' => 'current_user_only',
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
