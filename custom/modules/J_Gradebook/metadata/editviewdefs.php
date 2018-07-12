<?php
$module_name = 'J_Gradebook';
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
            'form' =>
            array (
                'hidden' =>
                array (
                    0 => '<input type="hidden" name="kind_of_course" id = "kind_of_course" class ="kind_of_course" value="{$kind_of_course}">',
                ),
            ),
        ),
        'panels' =>
        array (
            'default' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                        'customCode' => '<input type="text" class="input_readonly" name="name" id="name" maxlength="255" value="{if $fields.name.value == ""} Auto-Generate {else} {$fields.name.value} {/if}" title="{$MOD.LBL_NAME}" size="30" readonly>',
                    ),
                    1 =>
                    array (
                        'name' => 'j_class_j_gradebook_1_name',
                        'displayParams' =>
                        array (
                            'field_to_name_array' =>
                            array (
                                'kind_of_course' => 'kind_of_course',
                                'id' => 'j_class_j_gradebook_1j_class_ida',
                                'name' => 'j_class_j_gradebook_1_name',
                            ),
                            'class' => 'sqsNoAutofill',
                        ),
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
