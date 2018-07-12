<?php
    $popupMeta = array (
        'moduleMain' => 'J_Class',
        'varName' => 'J_Class',
        'orderBy' => 'j_class.name',
        'whereStatement' => 'j_class.class_type = "Waiting Class" ',
        'whereClauses' => array (
            'name' => 'j_class.name',
            'class_code' => 'j_class.class_code',
            'kind_of_course' => 'j_class.kind_of_course',
            'assigned_user_name' => 'j_class.assigned_user_name',
            'start_date' => 'j_class.start_date',
            'end_date' => 'j_class.end_date',
        ),
        'searchInputs' => array (
            1 => 'name',
            4 => 'class_code',
            5 => 'kind_of_course',
            6 => 'assigned_user_name',
            7 => 'start_date',
            8 => 'end_date',
        ),
        'searchdefs' => array (
            'class_code' => 
            array (
                'type' => 'varchar',
                'label' => 'LBL_CLASS_CODE',
                'width' => '10%',
                'name' => 'class_code',
            ),
            'name' => 
            array (
                'name' => 'name',
                'width' => '10%',
            ),
            'kind_of_course' => 
            array (
                'type' => 'enum',
                'studio' => 'visible',
                'label' => 'LBL_KIND_OF_COURSE',
                'width' => '10%',
                'name' => 'kind_of_course',
            ),
            'start_date' => 
            array (
                'type' => 'date',
                'label' => 'LBL_START_DATE',
                'width' => '10%',
                'name' => 'start_date',
            ),
            'end_date' => 
            array (
                'type' => 'date',
                'label' => 'LBL_END_DATE',
                'width' => '10%',
                'name' => 'end_date',
            ),
            'assigned_user_name' => 
            array (
                'link' => true,
                'type' => 'relate',
                'label' => 'LBL_ASSIGNED_TO_NAME',
                'id' => 'ASSIGNED_USER_ID',
                'width' => '10%',
                'name' => 'assigned_user_name',
            ),
        ),
        'listviewdefs' => array (
            'NAME' => 
            array (
                'width' => '15%',
                'label' => 'LBL_NAME',
                'default' => true,
                'link' => true,
                'name' => 'name',
            ),
            'KIND_OF_COURSE' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_KIND_OF_COURSE',
                'width' => '10%',
                'name' => 'kind_of_course',
            ),
            'STATUS' => 
            array (
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
                'width' => '10%',
                'name' => 'status',
            ),
            'START_DATE' => 
            array (
                'type' => 'date',
                'label' => 'LBL_START_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'start_date',
            ),
            'END_DATE' => 
            array (
                'type' => 'date',
                'label' => 'LBL_END_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'end_date',
            ),
            'ASSIGNED_USER_NAME' => 
            array (
                'width' => '9%',
                'label' => 'LBL_ASSIGNED_TO_NAME',
                'module' => 'Employees',
                'id' => 'ASSIGNED_USER_ID',
                'default' => true,
                'name' => 'assigned_user_name',
            ),
            'TEAM_NAME' => 
            array (
                'width' => '9%',
                'label' => 'LBL_TEAM',
                'default' => true,
                'name' => 'team_name',
            ),
        ),
    );
