<?php
$module_name = 'J_Partnership';
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
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    //          3 => 'FIND_DUPLICATES',
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
                'LBL_EDITVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
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
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'discount_amount',
                        'label' => 'LBL_DISCOUNT_AMOUNT',
                    ),
                    1 =>
                    array (
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'discount_percent',
                        'label' => 'LBL_DISCOUNT_PERCENT',
                    ),
                    1 =>
                    array (
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                3 =>
                array (
                    0 => 'description',
                    1 => 'loyalty_type',
                ),
                4 =>
                array (
                    0 => '',
                    1 => 'hours',
                ),
            ),
            'lbl_editview_panel1' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO',
                    ),
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                    ),
                ),
                1 =>
                array (
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                    ),
                ),
            ),
        ),
    ),
);
