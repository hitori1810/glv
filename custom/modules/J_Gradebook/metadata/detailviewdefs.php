<?php
$module_name = 'J_Gradebook';
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
                    2 => 'DELETE',
                    4 =>
                    array (
                        'customCode' => '{if !$LOCKUPDATE}
                        <input type="button" class="button primary" id="inputMark" name="inputMark" value="{$MOD.LNK_INPUT_MARK}" onclick="location.href = \'index.php?&module=J_Gradebook&action=InputMark&record={$fields.id.value}\'"/>
                        {/if}
                        ',
                    ),
                    5 =>
                    array (
                        'customCode' => '
                        {if $fields.grade_config != ""}
                        <input type="button" class="button" id="viewConfig" name="viewConfig" value="{$MOD.LNK_VIEWCONFIG}" onclick="viewConfig(\'{$fields.id.value}\');"/>
                        {/if}',
                    ),
                    6 =>
                    array (
                        'customCode' => '<input type="button" class="button" id="exportResultExcel" name="exportResultExcel" value="{$MOD.LNK_EXPORT_EXCEL}"/>',
                    ),
                ),
                'hidden' => array(
                    '<input type="hidden" name="lock_update" id="lock_update" value="{$LOCKUPDATE}">'
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
                'LBL_MARKDETAIL' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'javascript' => '
            {sugar_getscript file="modules/Reports/javascript/Blob.js"}
            {sugar_getscript file="modules/Reports/javascript/FileSaver.min.js"}
            {sugar_getscript file="modules/Reports/javascript/battatech_excelexport/battatech.excelexport.js"}
            {sugar_getscript file="custom/modules/J_Gradebook/js/detailviews.js"}
            <link rel="stylesheet" href="{sugar_getjspath file=custom/modules/J_Gradebook/css/detailview.css}"/>
            ',
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
                        'name' => 'j_class_j_gradebook_1_name',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'type',
                        'label' => 'LBL_TYPE',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'label' => 'LBL_STATUS',
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
                    1 => 'date_confirm'
                ),
                4 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                5 =>
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
            'lbl_markdetail' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'grade_config',
                        'hideLabel' => true,
                        'customCode' => '{$MARKDETAIL}',
                    ),
                ),
            ),
        ),
    ),
);
