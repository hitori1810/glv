<?php
$viewdefs['ProspectLists'] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'closeFormBeforeCustomButtons' => true,
                'buttons' =>
                array (
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
                    3 =>
                    array (
                        'customCode' => '<input title="{$APP.LBL_EXPORT}"  class="button" type="button" name="opp_to_quote_button" id="export_button" value="{$APP.LBL_EXPORT}">',
                    ),
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
                    'file' => 'custom/modules/ProspectLists/js/detail.js',
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
                        'name' => 'entry_count',
                        'label' => 'LBL_ENTRIES',
                    ),
                ),
                1 =>
                array (
                    0 => 'list_type',
                    1 => 'domain_name',
                ),
                2 =>
                array (
                    0 => 'description',
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array (
                0 =>
                array (
                    0 => 'assigned_user_name',
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
