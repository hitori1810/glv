<?php
$module_name = 'C_DeliveryRevenue';
$viewdefs[$module_name] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'hidden' =>
                array (
                    1 => '<input type="hidden" name="descriptions" id="descriptions" value="">',
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
            'includes' =>
            array (
                0 =>
                array (
                    'file' => 'custom/modules/C_DeliveryRevenue/js/detailview.js',
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
                        'customCode' => '{if $fields.type.value == "Junior" && $fields.passed.value == 0}
                        Drop Revenue
                        {/if}',
                    ),
                    1 =>
                    array (
                        'name' => 'date_input',
                        'label' => 'LBL_DATE_INPUT',
                    ),
                ),
                5 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
                6 =>
                array (
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '
                        {$fields.date_entered.value} {$APP.LBL_BY}
                        {$fields.created_by_name.value}
                        ',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
            ),
        ),
    ),
);
