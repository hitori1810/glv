<?php
$module_name = 'J_Coursefee';
$viewdefs[$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/J_Coursefee/js/editview.js"}',
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
                'LBL_EDITVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL3' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' =>
        array (
            'lbl_editview_panel1' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'code',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'apply_date',
                        'label' => 'LBL_APPLY_DATE',
                    ),
                ),
                2 =>
                array ( 
                    0 =>
                    array (
                        'name' => 'type_of_course_fee',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE_OF_COURSE_FEE',
                        'customCode' => '{html_options name="type_of_course_fee" id="type_of_course_fee" options=$fields.type_of_course_fee.options selected=$fields.type_of_course_fee.value}<input type="hidden" name="type" id="type" value="{$fields.type.value}">'
                    ),
                    1 =>
                    array (
                        'name' => 'inactive_date',
                        'label' => 'LBL_INACTIVE_DATE',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'unit_price',
                        'studio' => 'visible',
                        'label' => 'LBL_UNIT_PRICE',
                    ),
                    1 => 
                    array (                    
                        'name' => 'apply_for',
                    ),
                ), 
                4 =>
                array (
                    0 =>
                    array (                   
                        'label' => 'LBL_UNIT_PRICE_PER_HOUR',
                        'customCode' => '<label id="unit_price_per_hour"></label><label>{$MOD.LBL_PER_HOUR}</label>'
                    ),
                    1 => 
                    array (                    
                        'hideLabel' => true,
                    ),
                ),       
            ),
            'lbl_editview_panel3' =>
            array (
                0 =>
                array (
                    0 => 'description',
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
