<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$module_name = 'bc_survey_template';
$viewdefs [$module_name] = array(
            'EditView' =>
            array(
                'templateMeta' =>
                array(
                    'maxColumns' => '2',
                    'widths' =>
                    array(
                        0 =>
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                        1 =>
                        array(
                            'label' => '10',
                            'field' => '30',
                        ),
                    ),
                    'useTabs' => false,
                    'tabDefs' =>
                    array(
                        'DEFAULT' =>
                        array(
                            'newTab' => false,
                            'panelDefault' => 'expanded',
                        ),
                        'LBL_EDITVIEW_PANEL1' =>
                        array(
                            'newTab' => false,
                            'panelDefault' => 'expanded',
                        ),
                    ),
                    'form' =>
                    array(
                        'enctype' => 'multipart/form-data',
                        'buttons' =>
                        array(
                            0 =>
                            array(
                                'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_HEADER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="required_fieldsvalidation()" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                            ),
                            1 => 'CANCEL',
                        ),
                        'buttons_footer' =>
                        array(
                            0 =>
                            array(
                                'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id="SAVE_FOOTER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.calls.fill_invitees();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if}formSubmitCheck();;" type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                            ),
                            1 => 'CANCEL',
                        ),
                    ),
                ),
                'panels' =>
                array(
                    'default' =>
                    array(
                        0 =>
                        array(
                            0 => 'name',
                            1 => '',
                        ),
                        1 =>
                        array(
                            0 => 'description',
                        ),
                    ),
                    'lbl_editview_panel1' =>
                    array(
                        0 =>
                        array(
                            0 =>
                            array(
                                'name' => 'survey_page',
                                'type' => 'AddSurveyPagefield',
                                'hideLabel' => true,
                            ),
                        ),
                    ),
                ),
            ),
);
?>
