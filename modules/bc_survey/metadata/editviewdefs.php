<?php

/**
* editview of survey module
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Original Author Biztech Co.
*/

$module_name = 'bc_survey';
$viewdefs [$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'includes' =>
            array (
                0 =>
                array (
                    'file' => 'custom/include/javascripts/CKEditor/ckeditor.js',
                ),
            ),
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
            'form' =>
            array (
                'enctype' => 'multipart/form-data',
                'hidden' =>
                array (
                    1 => '<link rel="stylesheet" href="{sugar_getjspath file="custom/include/javascripts/CKEditor/editor.css"}"/>',
                ),
/*                'buttons' =>
                array (
                    0 =>
                    array (
                        'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_HEADER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="required_fieldsvalidation()" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                    ),
                    1 => 'CANCEL',
                ),
                'buttons_footer' =>
                array (
                    0 =>
                    array (
                        'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_FOOTER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.calls.fill_invitees();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                    ),
                    1 => 'CANCEL',
                ), */
            ),
            'useTabs' => false,
            'tabDefs' =>
            array (
                'DEFAULT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL2' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'collapsed',
                ),
                'LBL_EDITVIEW_PANEL3' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'collapsed',
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
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'logo',
                        'label' => 'LBL_LOGO',
                        'type' => 'ImageLogo',
                        'inline_edit' => false,
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                        'inline_edit' => false,
                    ),
                    1 => '',
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                        'inline_edit' => false,
                    ),
                    1 => '',
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'inline_edit' => false,
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'redirect_url',
                        'label' => 'LBL_REDIRECT_URL',
                        'type' => 'url',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'is_progress',
                        'label' => 'LBL_IS_PROGRESS',
                    ),
                ),
                7 =>
                array (
                    0 =>
                    array (
                        'name' => 'allowed_resubmit_count',
                        'label' => 'LBL_ALLOWED_RESUBMIT_COUNT',
                    ),
                    1 =>
                    array (
                        'name' => 'enable_data_piping',
                        'studio' => 'visible',
                        'label' => 'LBL_ENABLE_DATA',
                    ),
                ),
                8 =>
                array (
                    0 =>
                    array (
                        'name' => 'allow_redundant_answers',
                        'studio' => 'visible',
                        'label' => 'LBL_ALLOW_REDUNDANT_ANSWERS',
                    ),
                ),
                9 =>
                array (
                    0 =>
                    array (
                        'name' => 'sync_module',
                        'label' => 'LBL_SYNC_MODULE',
                    ),
                    1 =>
                    array (
                        'name' => 'sync_type',
                        'label' => 'LBL_SYNC_TYPE',
                    ),
                ),
            ),
            'lbl_editview_panel2' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'welcome_page',
                        'label' => 'LBL_WELCOM_PAGE',
                        'hideLabel' => true,
                        'customCode' => '<textarea class="ckeditor" id="welcome_page" name="welcome_page" rows="7" cols="90" >{$fields.welcome_page.value}</textarea>',
                    ),
                ),
            ),
            'lbl_editview_panel3' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'thanks_page',
                        'label' => 'LBL_THANKS_PAGE',
                        'hideLabel' => true,
                        'customCode' => '<textarea class="ckeditor" id="thanks_page" name="thanks_page" rows="7" cols="90" >{$fields.thanks_page.value}</textarea>',
                    ),
                ),
            ),
            'lbl_editview_panel1' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'survey_page',
                        'type' => 'AddSurveyPagefield',
                        'label' => 'LBL_SURVEYPAGES',
                        'hideLabel' => true,
                        'inline_edit' => false,
                    ),
                ),
            ),
        ),
    ),
);
?>
