<?php
$module_name = 'C_Saint';
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
                        'name' => 'saint_day',       
                    ),
                ),
                1 => 
                array (
                    0 => 'description',
                ),
            ),
        ),
    ),
);
