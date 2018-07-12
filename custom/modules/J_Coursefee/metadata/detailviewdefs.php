<?php
$module_name = 'J_Coursefee';
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
                    0 => 'EDIT',
                    1 => 'DELETE',
                    2 => 'DUPLICATE',
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
                    0 => 'name',
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
                        'customCode' => '<label>{$UNIT_PRICE_PER_HOUR} {$MOD.LBL_PER_HOUR}</label>'
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
                ),
                1 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                2 =>
                array (
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
