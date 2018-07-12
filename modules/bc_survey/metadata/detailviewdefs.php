<?php

/**
* detailview of survey module
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
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'buttons' =>
                array (
                    0 => 'EDIT',
                    1 => 'DELETE',
                    //                    2 =>
                    //          array (
                    //            'customCode' => '<input id="contrats_debranche_prima" title="Duplicate" class="button" type="button" name="contrats_debranche_prima" value="Duplicate" onclick="window.open(\'index.php?module=bc_survey&action=EditView&template_id={$fields.id.value}&return_module=bc_survey_template&prefill_type=bc_survey&return_action=index\',\'_blank\')">',
                    //                    ),
                    2 =>
                    array (
                        'customCode' => '<input title="Analyse Report" id="analyse_survey"   type="button" name="analyse_survey" value="Analyse Report" onclick="window.open(\'index.php?module=bc_survey&action=viewreport&survey_id={$fields.id.value}&type=status\',\'_blank\')" />',
                    ),
                    3=>
                    array (
                        'customCode' => '<input title="Preview" id="preview_survey"   type="button" name="preview_survey" value="Preview" onclick="window.open(\'preview_survey.php?survey_id={$fields.id.value}\',\'_blank\')" />',
                    ),
                    4 =>
                    array (
                        'customCode' => '<input title="Translate" id="translate_survey"   type="button" name="translate_survey" value="Translate" onclick=onclick="window.open(\'index.php?module=bc_survey&action=translate_survey&survey_id={$fields.id.value}\',\'_self\')"/>',
                    ),
                ),
            ),
            'maxColumns' => '2',
            'includes' =>
            array (
                'file' => 'custom/include/js/survey_js/drag-drop.js',
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
                    0 =>
                    array (
                        'name' => 'name',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'logo',
                        'label' => 'LBL_LOGO',
                        'customCode' => '{if $fields.logo.value != ""}<img src="custom/include/surveylogo_images/{$fields.logo.value}" height="120" width="100"/>{else}No Image{/if}',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                    1 =>
                    array (
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'theme',
                        'customCode' => '<img src="custom/include/survey-img/{$fields.theme.value}-hover.png" height="70px"/>&nbsp;&nbsp;{if $fields.theme.value eq theme1}Innovative{elseif $fields.theme.value eq theme2}Ultimate{elseif $fields.theme.value eq theme3}Incredible{elseif $fields.theme.value eq theme4}Agile{elseif $fields.theme.value eq theme5}Contemporary{elseif $fields.theme.value eq theme6}Creative{elseif $fields.theme.value eq theme7}Proffesional{elseif $fields.theme.value eq theme8}Elegant{elseif $fields.theme.value eq theme9}Automated{elseif $fields.theme.value eq theme10}Exclusive{/if}',
                        'label' => 'LBL_THEME',
                    ),
                    1 =>
                    array (
                        'name' => 'allowed_resubmit_count',
                        'label' => 'LBL_ALLOWED_RESUBMIT_COUNT',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'welcome_page',
                        'label' => 'LBL_WELCOM_PAGE',
                        'customCode' => '<div style="max-height: 250px; overflow-y: auto;">{$WELCOME}</div>',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'thanks_page',
                        'label' => 'LBL_THANKS_PAGE',
                        'customCode' => '<div style="max-height: 250px; overflow-y: auto;">{$THANKS}</div>',
                    ),
                ),
                7 =>
                array (
                    0 =>
                    array (
                        'name' => 'allow_redundant_answers',
                        'studio' => 'visible',
                        'label' => 'LBL_ALLOW_REDUNDANT_ANSWERS',
                    ),
                    1 => '',
                ),
                8 =>
                array (
                    0 =>
                    array(
                        'name' => 'enable_data_piping',
                        'studio' => 'visible',
                        'label' => 'LBL_ENABLE_DATA',
                    ),
                ),
                9 =>
                array(
                    0 =>
                    array(
                        'name' => 'sync_module',
                        'label' => 'LBL_SYNC_MODULE',
                    ),
                    1 =>
                    array(
                        'name' => 'sync_type',
                        'label' => 'LBL_SYNC_TYPE',
                    ),
                ),
                10 =>
                array(
                    0 =>
                    array(
                        'name' => 'survey_page',
                        'type' => 'AddSurveyPagefield',
                        'label' => 'LBL_SURVEYPAGES',
                    ),
                ),
            ),
        ),
    ),
);
?>
