<?php
$module_name = 'J_School';
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
//                    3 => 'FIND_DUPLICATES',
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
                'LBL_DETAILVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_DETAILVIEW_PANEL2' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' =>
        array (
            'lbl_detailview_panel1' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 => ''
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'level',
                        'studio' => 'visible',
                        'label' => 'LBL_LEVEL',
                    ),
                    1 => ''
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'address_address_street',
                        'label' => 'LBL_PRIMARY_ADDRESS',
                        'type' => 'address',
                        'displayParams' =>
                        array (
                            'key' => 'address',
                        ),
                    ),      
                    1 =>
                    array (
                        'name' => 'alt_address_street',
                        'label' => 'LBL_ALTERNATE_ADDRESS',
                        'type' => 'address',
                        'displayParams' =>
                        array (
                            'key' => 'alt',
                        ),
                    ),
                ),
            ),
            'LBL_DETAILVIEW_PANEL2' => array (
                0 => array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                    ),
                    1 => array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array (
                    0 => '',
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
