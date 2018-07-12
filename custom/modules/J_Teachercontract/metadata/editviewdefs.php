<?php
$module_name = 'J_Teachercontract';
$viewdefs[$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/J_Teachercontract/js/editview.js"}',
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
                    0 =>
                    array (
                        'name' => 'name',
                        'customCode' => '<input type="text" class="input_readonly" name="named" id="name" maxlength="255" value="{$fields.name.value}" title="{$MOD.LBL_NAME}" size="30" readonly>',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                        'customCode' => '{html_options  style="width:23%;" name="status" id="status" options=$fields.status.options selected=$fields.status.value} ',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'c_teachers_j_teachercontract_1_name',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'contract_date',
                        'label' => 'LBL_CONTRACT_DATE',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'contract_type',
                        'studio' => 'visible',
                        'label' => 'LBL_CONTRACT_TYPE',
                        'customCode' => '{html_options  style="width:174px;" name="contract_type" id="contract_type" options=$fields.contract_type.options selected=$fields.contract_type.value} ',
                    ),
                    1 =>
                    array (
                        'name' => 'contract_until',
                        'label' => 'LBL_CONTRACT_UNTIL',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'working_hours_monthly',
                        'label' => 'LBL_WORKING_HOURS_MONTHLY',
                    ),
                    1 => ''
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'day_off',
                        'studio' => 'visible',
                        'label' => 'LBL_DAY_OFF',
                        'customCode' => '{$DAY_OFF}',
                    ),
                ),
                5 =>
                array (
                    0 => 'description',
                ),
            ),
            'lbl_editview_panel1' =>
            array (
                0 =>
                array (
                    0 => 'assigned_user_name',
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
