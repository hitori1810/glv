<?php
$module_name = 'J_Gradebook';
$viewdefs[$module_name] =
array (
    'QuickCreate' =>
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
            'javascript' => '{sugar_getscript file="custom/modules/J_Gradebook/js/editview.js"}',
            'useTabs' => false,
            'tabDefs' =>
            array (
                'DEFAULT' =>
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
                    0 => array(
                        'name' => 'name',
                        'customCode' => '<input type="text" class="input_readonly" name="name" id="name" maxlength="255"
                        value="{if $fields.name.value == ""} Auto-Generate {else} {$fields.name.value} {/if}" title="{$MOD.LBL_NAME}" size="30" readonly>',
                    ),
                    1 =>
                    array (
                        'name' => 'j_class_j_gradebook_1_name',
                        'label' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'type',
                        'label' => 'LBL_TYPE',
                        'customCode' => '{html_options name="type" id="type" options=$gradebook_type selected=$fields.type.value} {html_options style="display:none;" name="minitest" id="minitest" options=$gradebook_mini selected=$fields.minitest.value}',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
                        'customCode' => '
                        {if $fields.status.value == "Approved" }
                        <span>{$fields.status.value}</span>
                        <input type="hidden"  name="status" id="status" class="status" value = "{$fields.status.value}">
                        {else}
                        {html_options name="status" id="status" options=$fields.status.options selected=$fields.status.value}
                        {/if}
                        ',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'c_teachers_j_gradebook_1_name',
                        'label' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
                    ),
                    1 =>
                    array (
                        'name' => 'date_input',
                        'label' => 'LBL_DATE_INPUT',
                    ),
                ),
                3 =>
                array (
                    0 => 'description',
                ),
            ),
        ),
    ),
);
