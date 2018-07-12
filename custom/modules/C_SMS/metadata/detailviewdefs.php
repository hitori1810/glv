<?php
$module_name = 'C_SMS';
$viewdefs[$module_name] = 
array (
    'DetailView' => 
    array (
        'templateMeta' => 
        array (
            'form' => 
            array (
                'buttons' => 
                array (
                ),
            ),
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
                        'name' => 'parent_name',
                        'customLabel' => '{sugar_translate label=\'LBL_MODULE_NAME\' module=$fields.parent_type.value}',
                    ),
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'phone_number',
                        'label' => 'LBL_PHONE_NUMBER',
                    ),
                    1 => 
                    array (
                        'name' => 'delivery_status',
                        'studio' => 'visible',
                        'label' => 'LBL_DELIVERY_STATUS',
                    ),
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'supplier',
                    ),
                    1 => 'message_count',
                ),
                3 => 
                array (
                    0 => 'description',
                ),
                3 => 
                array (
                    0 => 'assigned_user_name',
                    1 => 
                    array (
                        'name' => 'date_send',
                        'label' => 'LBL_DATE_SEND',
                    ),
                ),
                4 => 
                array (
                    0 => 'team_name',
                    1 => 'date_entered',
                ),
            ),
        ),
    ),
);
