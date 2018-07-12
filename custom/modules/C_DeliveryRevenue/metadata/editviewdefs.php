<?php
$module_name = 'C_DeliveryRevenue';
$viewdefs[$module_name] = 
array (
    'EditView' => 
    array (
        'templateMeta' => 
        array (
            'maxColumns' => '2',
            'widths' => 
            array (
                0 => 
                array (
                    'label' => '10',
                    'field' => '30',
                ),
                1 => 
                array (
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => false,
            'tabDefs' => 
            array (
                'DEFAULT' => 
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' => 
        array (
            'default' => 
            array (
                0 => 
                array (
                    0 => 'name',
                    1 => 
                    array (
                        'name' => 'passed',
                        'label' => 'LBL_PASSED',
                    ),
                ),
                1 => 
                array (
                    0 => 'ju_payment_id',
                    1 => 
                    array (
                        'name' => 'student_name',
                        'label' => 'LBL_STUDENT_NAME',
                    ),
                ),
                2 => 
                array (
                    0 => 'sss_id',
                    1 => 
                    array (
                        'name' => 'amount',
                        'label' => 'LBL_AMOUNT',
                    ),
                ),
                3 => 
                array (
                    0 => 'session_id',
                    1 => 
                    array (
                        'name' => 'duration',
                        'label' => 'LBL_DURATION',
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    1 => 
                    array (
                        'name' => 'revenue_type',
                        'studio' => 'visible',
                        'label' => 'LBL_REVENUE_TYPE',
                    ),
                ),
                5 => 
                array (
                    0 => 
                    array (
                        'name' => 'date_input',
                        'label' => 'LBL_DATE_INPUT',
                    ),

                ),
                6 => 
                array (
                    0 => 'assigned_user_name',
                    1 => 
                    array (
                        'name' => 'team_name',
                        'displayParams' => 
                        array (
                            'display' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
