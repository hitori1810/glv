<?php
$viewdefs['ProductTemplates'] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/ProductTemplates/js/detailview.js"}',
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
                    0 => 'code',
                    1 =>
                    array (
                        'name' => 'status2',
                        'label' => 'LBL_STATUS_2',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'category_name',
                        'type' => 'varchar',
                        'label' => 'LBL_CATEGORY',
                    ),
                    1 => 'date_available',
                ),
                2 =>
                array (
                    0 => 'name',
                    1 =>
                    array (
                        'name' => 'type_id',
                        'type' => 'varchar',
                        'label' => 'LBL_TYPE',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'list_price',
                        'label' => '{$MOD.LBL_LIST_PRICE|strip_semicolon}',
                    ),
                    1 =>
                    array (
                        'name' => 'unit',
                        'studio' => 'visible',
                        'label' => 'LBL_UNIT',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'nl2br' => true,
                        ),
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
