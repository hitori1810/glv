<?php
$module_name = 'J_StudentSituations';
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
                    0 =>
                    array (
                        'customCode' => '{if $is_admin}{$bt_undo}{/if}',
                    ),
                //    1 => 'DELETE',
                    1 =>
                    array (
                        'customCode' => '{if $is_admin}
                        <input title="Delete" accesskey="d" class="button" onclick="var _form = document.getElementById(\'formDetailView\'); _form.return_module.value=\'J_StudentSituations\'; _form.return_action.value=\'ListView\'; _form.action.value=\'Delete\'; if(confirm(\'Are you sure you want to delete this record?\')) SUGAR.ajaxUI.submitForm(_form);" type="submit" name="Delete" value="Delete" id="delete_button">
                        {/if}',
                    ),
                    2 =>
                    array (
                        'customCode' => '{if $is_admin}
                        <a title="{$MOD.LBL_EDIT}" class="quickEdit" href="index.php?module=J_StudentSituations&return_module=J_StudentSituations&return_action=DetailView&record={$fields.id.value}&return_id={$fields.id.value}"  data-record="{$fields.id.value}" data-list = "true" data-module="J_StudentSituations"  class="quickEdit" value="Edit">
                        <input type="submit" class="button primary" value="Edit" >
                        </a>
                        {/if}',
                    ),
                    3 =>
                    array (
                        'customCode' => '{$EXPORT_FROM_BUTTON}',
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
            'javascript' => '
            {sugar_getscript file="custom/modules/J_StudentSituations/js/detailview.js"}
            ',
            'useTabs' => false,
            'tabDefs' =>
            array (
                'LBL_MOVING_OUT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_DEFAUT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_OTHER' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' =>
        array (
            'LBL_DEFAUT' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'student_name',
                        'label' => 'LBL_STUDENT_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'type',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'ju_class_name',
                    ),
                    1 => array (
                        'name' => 'relate_situation_name',
                        'customCode' => '{$relate_a}',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'start_study',
                    ),
                    1 =>
                    array (
                        'name' => 'end_study',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'total_hour',
                    ),
                    1 =>
                    array (
                        'name' => 'total_amount',
                    ),
                ),
                4 =>
                array (
                    0 => 'description',
                    1 => 'payment_name',
                ),
            ),
            'LBL_OTHER' => array (
                0 => array (
                    0 => 'assigned_user_name',
                    1 => array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                1 => array (
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
