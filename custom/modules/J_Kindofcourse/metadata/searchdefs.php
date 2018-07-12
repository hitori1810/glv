<?php
$module_name = 'J_Kindofcourse';
$searchdefs[$module_name] = 
array (
    'layout' => 
    array (
        'basic_search' => 
        array (    
        ),
        'advanced_search' => 
        array (
            'name' => 
            array (
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'kind_of_course' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_KIND_OF_COURSE',
                'width' => '10%',
                'name' => 'kind_of_course',
            ), 
            'status' =>
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
                'width' => '10%',
                'name' => 'status',
            ), 
            'assigned_user_id' => 
            array (
                'name' => 'assigned_user_id',
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
                'default' => true,
                'width' => '10%',
            ),   
            'created_by' => 
            array (
                'type' => 'assigned_user_name',
                'label' => 'LBL_CREATED',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
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
                'width' => '10%',
                'default' => true,
                'id' => 'TEAM_ID',
                'name' => 'team_name',
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
