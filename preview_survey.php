<?php
/**
 * When admin create survey and they want to see the preview of survey that how this survey is look like
 * at client side
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
if (!defined('sugarEntry') || !sugarEntry)
    define('sugarEntry', true);
include_once('config.php');
require_once('include/entryPoint.php');
require_once('data/SugarBean.php');
require_once('include/utils.php');
require_once('include/database/DBManager.php');
require_once('include/database/DBManagerFactory.php');
ini_set('default_charset', 'UTF-8');
global $sugar_config;
$survey_id = $_REQUEST['survey_id'];
$survey = new bc_survey();
$survey->retrieve($survey_id);
$default_survey_language = $survey->default_survey_language;


// get survey supported language
if (empty($_REQUEST['selected_lang'])) {
    $selected_lang = $default_survey_language;
} else if(isset($_REQUEST['selected_lang']) && !empty($_REQUEST['selected_lang'])){
    $selected_lang = $_REQUEST['selected_lang'];
} else{
    $selected_lang = $sugar_config['default_language'];
}
$langValues = return_app_list_strings_language($selected_lang)['survey_language_dom'];
$supported_lang = unencodeMultienum($survey->supported_survey_language);
$available_lang = array();
foreach ($supported_lang as $key => $slang) {
    $oLang = BeanFactory::getBean('bc_survey_language');
    $oLang->retrieve_by_string_fields(array('bc_survey_id_c' => $survey_id, 'survey_language' => $slang, 'translated' => 1, 'language_status' => 'enable'));
    if (!empty($oLang->id)) {
        $available_lang[$slang] = $langValues[$slang];
    }
}

// list of lang wise survey detail
$list_lang_detail = return_app_list_strings_language($selected_lang)[$survey_id];
$is_progress_indicator = $survey->is_progress;
$survey->load_relationship('bc_survey_pages_bc_survey');
$survey_details = array();
$themeObject = SugarThemeRegistry::current();
$favicon = $themeObject->getImageURL('sugar_icon.ico', false);
$questions = array();
foreach ($survey->bc_survey_pages_bc_survey->getBeans() as $pages) {
    unset($questions);
    $survey_details[$pages->page_sequence]['page_title'] = (!empty($list_lang_detail[$pages->id])) ? $list_lang_detail[$pages->id] : $pages->name;
    $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
    $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
    $pages->load_relationship('bc_survey_pages_bc_survey_questions');
    foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
        $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
        $questions[$survey_questions->question_sequence]['que_title'] = (!empty($list_lang_detail[$survey_questions->id . '_quetitle'])) ? $list_lang_detail[$survey_questions->id . '_quetitle'] : $survey_questions->name;
        $questions[$survey_questions->question_sequence]['que_type'] = $survey_questions->question_type;
        $questions[$survey_questions->question_sequence]['is_required'] = $survey_questions->is_required;
        $questions[$survey_questions->question_sequence]['question_help_comment'] = (!empty($list_lang_detail[$survey_questions->id . '_helptitle'])) ? $list_lang_detail[$survey_questions->id . '_helptitle'] : $survey_questions->question_help_comment;
        //advance options
        $questions[$survey_questions->question_sequence]['advance_type'] = (isset($survey_questions->advance_type)) ? $survey_questions->advance_type : '';
        $questions[$survey_questions->question_sequence]['maxsize'] = (isset($survey_questions->maxsize)) ? $survey_questions->maxsize : '';
        $questions[$survey_questions->question_sequence]['min'] = (isset($survey_questions->min)) ? $survey_questions->min : '';
        $questions[$survey_questions->question_sequence]['max'] = (isset($survey_questions->max)) ? $survey_questions->max : '';
        $questions[$survey_questions->question_sequence]['precision'] = (isset($survey_questions->precision_value)) ? $survey_questions->precision_value : '';
        $questions[$survey_questions->question_sequence]['is_datetime'] = (isset($survey_questions->is_datetime) ) ? $survey_questions->is_datetime : '';
        $questions[$survey_questions->question_sequence]['is_sort'] = (isset($survey_questions->is_sort) ) ? $survey_questions->is_sort : '';
        $questions[$survey_questions->question_sequence]['enable_otherOption'] = (isset($survey_questions->enable_otherOption) ) ? $survey_questions->enable_otherOption : '';
        $questions[$survey_questions->question_sequence]['matrix_row'] = (isset($survey_questions->matrix_row)) ? base64_decode($survey_questions->matrix_row) : '';
        $questions[$survey_questions->question_sequence]['matrix_col'] = (isset($survey_questions->matrix_col)) ? base64_decode($survey_questions->matrix_col) : '';
        $questions[$survey_questions->question_sequence]['description'] = (isset($survey_questions->description)) ? $survey_questions->description : '';


        $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
        $questions[$survey_questions->question_sequence]['answers'] = array();
        foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
            if ($questions[$survey_questions->question_sequence]['is_required'] && !isset($survey_answers->name)) {
                continue;
            } else {
                $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence][$survey_answers->id] = (!empty($list_lang_detail[$survey_answers->id])) ? $list_lang_detail[$survey_answers->id] : $survey_answers->name;
            }
        }
        ksort($questions[$survey_questions->question_sequence]['answers']);
    }
    ksort($questions);
    $survey_details[$pages->page_sequence]['page_questions'] = $questions;
    ksort($survey_details);
}

/**
 * Display Question at preview
 *
 * @author     Original Author Biztech Co.
 * @param      String - $type, $que_id, $advancetype, $que_title,  $description
 * @param      array - $answers,$matrix_row, $matrix_col,
 * @param      integer - $is_required,$maxsize, $min, $max,$scale_slot,$is_sort,$is_datetime
 * @param      float - $precision
 */
function getMultiselectHTML($answers, $type, $que_id, $is_required, $maxsize, $min, $max, $precision, $scale_slot, $is_sort, $is_datetime, $advancetype, $que_title, $matrix_row, $matrix_col, $description,$list_lang_detail) {
    $html = "";
    switch ($type) {
        case 'MultiSelectList':
            $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option multiselect-list  two-col' id='{$que_id}_div'><input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />
                    <select class='form-control multiselect {$que_id}' multiple='' size='10' name='{$que_id}[]' onchange='addOtherField(this);'>";

            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options);
                $is_other = '';
                $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                if ($oAnswer->ans_type == 'other') {
                    $is_other = 'is_other_option';
                }
                foreach ($options as $ans_id => $answer) {
                    $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                }
            } else {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                    }
                        $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                }
            }
            }
            $html .= "</select></div>";
            return $html;
            break;
        case 'Checkbox':
             $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option checkbox-list' id='{$que_id}_div'>";
            $html .= "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />";
            if ($advancetype == 'Horizontal') {
                $html .= '<ul class="horizontal-options">';
            } else {
                $html .= '<ul>';
            }
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options);
                $is_other = '';
                $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                if ($oAnswer->ans_type == 'other') {
                    $is_other = 'is_other_option';
                }
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>"
                                . "<span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        $op++;
                    }
                } else {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                           <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        $op++;
                    }
                }
            } else {
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            $op++;
                        }
                    }
                } else {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            $op++;
                        }
                    }
                }
            }
            $html .= "</ul></div>";
            return $html;
            break;
        case 'RadioButton':
             $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option radio-list' id='{$que_id}_div'>";
            $html .= "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />";
            if ($advancetype == 'Horizontal') {
                $html .= '<ul class="horizontal-options">';
            } else {
                $html .= '<ul>';
            }
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options);
                $is_other = '';
                $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                if ($oAnswer->ans_type == 'other') {
                    $is_other = 'is_other_option';
                }
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        $html .= "<li class='md-radio' style='display:inline;'><label><input type='radio' id='{$que_id}_{$op}' value='{$ans_id}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                    }
                    $op++;
                } else {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        $html .= "<li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        $op++;
                    }
                }
            } else {
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            $html .= "<li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        }
                        $op++;
                    }
                } else {
                    $op = 1;
                    foreach ($answers as $ans) {

                        foreach ($ans as $ans_id => $answer) {
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        }
                        $op++;
                    }
                }
            }
            $html .= "</ul></div>";
            return $html;
            break;
        case 'DrodownList':
             $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' /><ul><li><div class='styled-select'>";
            $html .= "<select name='{$que_id}[]' class='form-control required {$que_id}' onchange='addOtherField(this);'><option selected='' value='0' class=''>Select</option>";

            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options);
                $is_other = '';
                $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                if ($oAnswer->ans_type == 'other') {
                    $is_other = 'is_other_option';
                }
                foreach ($options as $ans_id => $answer) {
                    $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                }
            } else {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                    }
                        $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                }
            }
            }
            $html .= "</select></div></li></ul></div>";
            return $html;
            break;
        case 'Textbox':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            $html .= "<input class='form-control {$que_id}' type='textbox' name='{$que_id}[]' class='{$que_id}'>";
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'CommentTextbox':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            if (!empty($min) || !empty($max)) {

                $html .= "<textarea style='height:auto;width:auto;' class='form-control {$que_id}' rows='{$min}' cols='{$max}' name='{$que_id}[]'></textarea>";
            } else {
                $html .= "<textarea class='form-control {$que_id}' rows='4' cols='20' name='{$que_id}[]'></textarea>";
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'Rating':
            $html = "<div class='option select-list' id='{$que_id}_div'>";
            $html .= "<ul onMouseOut='resetRating(\"{$que_id}\")'>";
            if (!empty($maxsize)) {
                $starCount = $maxsize;
            } else {
                $starCount = 5;
            }
            for ($i = 1; $i <= $starCount; $i++) {
                $selected = "";

                $html .= "<li class='rating {$selected}' style='display: inline;font-size: x-large' onmouseover='highlightStar(this,\"{$que_id}\");' onclick='addRating(this,\"{$que_id}\")'>&#9733;</li>";
            }
            $html .= "</ul>";
            $html .= "</div>";
            $html .= "<input type='hidden'  name='{$que_id}[]' class='{$que_id}' id='{$que_id}_hidden'>";
            return $html;
            break;
        case 'ContactInformation':
            if ($is_required == 1 && empty($advancetype)) {

                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>
                                <li><input placeholder='Name *' class='form-control {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>
                                <li><input placeholder='Email Address *'  class='form-control {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>
                                <li><input placeholder='Company' class='form-control {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>
                                <li><input placeholder='Phone Number *' class='form-control {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>
                                <li><input placeholder='Address' class='form-control {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>
                                <li><input placeholder='Address2'class='form-control {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>
                                <li><input placeholder='City/Town' class='form-control {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>
                                <li><input placeholder='State/Province' class='form-control {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>
                                <li><input placeholder='ZIP/Postal Code' class='form-control {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>
                                <li><input placeholder='Country' class='form-control {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>
                            </ul></div>";
            } else if ($is_required == 1 && !empty($advancetype)) {
                $requireFields = explode(' ', $advancetype);
                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>";
                if (in_array('Name', $requireFields)) {
                    $html .= "                <li><input placeholder='Name *' class='form-control {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Name ' class='form-control {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                }
                if (in_array('Email', $requireFields)) {
                    $html .= "                <li><input placeholder='Email Address *'  class='form-control {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Email Address '  class='form-control {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                }
                if (in_array('Company', $requireFields)) {
                    $html .= "                <li><input placeholder='Company *' class='form-control {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Company' class='form-control {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                }
                if (in_array('Phone', $requireFields)) {
                    $html .= "                <li><input placeholder='Phone Number *' class='form-control {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li> ";
                } else {
                    $html .= "                <li><input placeholder='Phone Number ' class='form-control {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li> ";
                }
                if (in_array('Address', $requireFields)) {
                    $html .= "                <li><input placeholder='Address *' class='form-control {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Address' class='form-control {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                }
                if (in_array('Address2', $requireFields)) {
                    $html .= "                <li><input placeholder='Address2 *'class='form-control {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Address2'class='form-control {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                }
                if (in_array('City', $requireFields)) {
                    $html .= "                <li><input placeholder='City/Town *' class='form-control {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                } else {
                    $html .= "                <li><input placeholder='City/Town' class='form-control {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                }
                if (in_array('State', $requireFields)) {
                    $html .= "                <li><input placeholder='State/Province *' class='form-control {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                } else {
                    $html .= "                <li><input placeholder='State/Province' class='form-control {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                }
                if (in_array('Zip', $requireFields)) {
                    $html .= "                <li><input placeholder='ZIP/Postal Code *' class='form-control {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                } else {
                    $html .= "                <li><input placeholder='ZIP/Postal Code' class='form-control {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                }
                if (in_array('Country', $requireFields)) {
                    $html .= "                <li><input placeholder='Country *' class='form-control {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                } else {
                    $html .= "                <li><input placeholder='Country' class='form-control {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                }
                $html .= "            </ul></div>";
            } else {
                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>
                                <li><input placeholder='Name' class='form-control {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>
                                <li><input placeholder='Email Address'  class='form-control {$que_id}_email'  type='text'  name='{$que_id}[{$que_id}][Email Address]'></li>
                                <li><input placeholder='Company' class='form-control {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>
                                <li><input placeholder='Phone Number' class='form-control {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>
                                <li><input placeholder='Address' class='form-control {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>
                                <li><input placeholder='Address2' class='form-control {$que_id}_address2'  type='text'name='{$que_id}[{$que_id}][Address2]'></li>
                                <li><input placeholder='City/Town' class='form-control {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>
                                <li><input placeholder='State/Province' class='form-control {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>
                                <li><input placeholder='ZIP/Postal Code' class='form-control {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>
                                <li><input placeholder='Country' class='form-control {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>
                                 </ul></div>";
            }
            return $html;
            break;
        case 'Date':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            if ($is_datetime == 1) {
                $html .= "<input class='form-control setdatetime {$que_id}_datetime' type='text' name='{$que_id}[]' class='{$que_id}'>";
            } else {
                $html .= "<input class='form-control setdate {$que_id}_datetime' type='text' name='{$que_id}[]' class='{$que_id}'>";
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'Image':
            $html = "<div class='option select-list' id='{$que_id}_div'><ul><li>";
            if ($que_title == "upload") {
                $html .= ""
                        . "<img src='custom/include/Image_question/{$advancetype}' class='  {$que_id}_datetime'  name='{$que_id}[]' >";
            } else {
                $html .= ""
                        . "<img src='{$advancetype}' class='  {$que_id}_datetime'  name='{$que_id}[]' >";
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'Video':
            $html = "<div class='option select-list' id='{$que_id}_div'><ul><li>";
            $html .= '<iframe width="420" height="315"
                                src="' . $que_title . '">
                      </iframe>';
            if (!empty($description)) {
                $html .="<p>" . $description . "</p>";
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'Scale':
            $lables = !empty($advancetype) ? split('-', $advancetype) : '';
            $left = !empty($list_lang_detail[$que_id . '_display_left']) ? $list_lang_detail[$que_id . '_display_left'] : (!empty($lables) ? $lables[0] : '');
            $middle = !empty($list_lang_detail[$que_id . '_display_center']) ? $list_lang_detail[$que_id . '_display_center'] : (!empty($lables) ? $lables[1] : '');
            $right = !empty($list_lang_detail[$que_id . '_display_right']) ? $list_lang_detail[$que_id . '_display_right'] : (!empty($lables) ? $lables[2] : '');
            //display scale input field
            $html = "<div id='{$que_id}_div'>";
            $html .='<div style="width:60%">
                        <span class="equal">' . $min . '</span>
                        <span class="equal" ></span>
                        <span class="equal" style="text-align:right">' . $max . '</span>
                    </div>';
            $html .='<br/><section style="width:60%" class=' . $que_id . '>
                        <div id="slider"></div>
                    </section>';
            $html .='<div style="width:60%;height:30px;">
                        <span class="equal">' . $left . '</span>
                        <span class="equal" style="text-align:center">' . $middle . '</span>
                        <span class="equal" style="text-align:right">' . $right . '</span>
                    </div>';
            $html .= "</div>";
            return $html;
            break;
        case 'Matrix':
            $display_type = $advancetype == 'Checkbox' ? 'checkbox' : 'radio';
            $rows = array();
            $rows = json_decode($matrix_row);
            $cols = json_decode($matrix_col);

            // Initialize counter - count number of rows & columns
            $row_count = 1;
            $col_count = 1;
            // Do the loop
            foreach ($rows as $result) {
                // increment row counter
                $row_count++;
            }
            foreach ($cols as $result) {
                // increment  column counter
                $col_count++;
            }
            // adjusting div width as per column
            $width = 100 / ($col_count + 1) . "%";

            $html = '<div class="matrix-tbl-contner">';
            $html .= "<table class='survey_tmp_matrix' id='{$que_id}_div'>";
            $op = 0;
            for ($i = 1; $i <= $row_count; $i++) {

                $html .= '<tr class="row">';

                for ($j = 1; $j <= $col_count + 1; $j++) {
                    $row = $i - 1;
                    $col = $j - 1;
                    //First row & first column as blank
                    if ($j == 1 && $i == 1) {
                        $html .= "<th class='matrix-span' style='width:" . $width . "'>&nbsp;</th>";
                    }
                    // Rows Label
                    else if ($j == 1 && $i != 1) {
                        if (!empty($list_lang_detail[$que_id . '_row' . $row])) {
                            $row_header = $list_lang_detail[$que_id . '_' . $row];
                    } else {
                            $row_header = $rows->$row;
                        }
                        $html .= "<th class='matrix-span' style='font-weight:bold; width:" . $width . ";text-align:left;'>" . $row_header . "</th>";
                    } else {
                        //Columns label
                        if ($j <= ($col_count + 1) && $cols->$col != null && !($j == 1 && $i == 1) && ($i == 1 || $j == 1)) {
                            if (!empty($list_lang_detail[$que_id . '_col' . $col])) {
                                $col_header = $list_lang_detail[$que_id . '_col' . $col];
                            } else {
                                $col_header = $cols->$col;
                        }
                            $html .= "<th class='matrix-span' style='font-weight:bold; width:" . $width . "'>" . $col_header . "</th>";
                        }
                        //Display answer input (RadioButton or Checkbox)
                        else if ($j != 1 && $i != 1 && $cols->$col != null) {
                            $html .= "<td class='matrix-span' style='width:" . $width . "; '>"
                                    . "<span class='md-" . $display_type . "'><input type='" . $display_type . "'  id='{$que_id}_{$op}' class='{$que_id} md-check' name='matrix" . $row . "'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></span>"
                                    . "</td>";
                        }
                        // If no value then display none
                        else {
                            $html .= "";
                        }
                    }
                    $op++;
                }
                $html .= "</tr>";
            }
            $html .= "</table></div>";
            return $html;
            break;
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">
        <title>Survey Template</title>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <script src="custom/include/js/survey_js/jquery.datetimepicker.js"></script>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/jquery.datetimepicker.css">

        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/custom-form.css' ?>" rel="stylesheet">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/survey-form.css' ?>" rel="stylesheet">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/' . $survey->theme . '.css'; ?>" rel="stylesheet">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/jquery.bxslider.css' ?>" rel="stylesheet">

        <script src="<?php echo $sugar_config['site_url'] . '/custom/include/js/survey_js/jquery.bxslider.min.js' ?>"></script>
        <script src="<?php echo $sugar_config['site_url'] . '/custom/include/js/survey_js/rate.js' ?>"></script>
        <script src="<?php echo $sugar_config['site_url'] . '/custom/include/js/survey_js/custom_code.js' ?>"></script>
        <style type="text/css">
            .hideBtn{
                visibility:hidden;
            }
            .showBtn{
                visibility:visible;
            }
        </style>



        <script type="text/javascript">
            jQuery(document).ready(function () {

                var maxWidth = 0;
                $('.ew-ul li').width('auto').each(function () {
                    maxWidth = $(this).width() > maxWidth ? $(this).width() : maxWidth;
                }).width(maxWidth);

                //initially active first page
                $('.progress-bar').children('li:nth-child(1)').addClass('active');
                //set datetime picker for datetime question type
                $('.setdatetime').click(function (el) {
                    $(el.currentTarget).datetimepicker().datetimepicker("show");
                });
                //set date picker for datetime question type
                $('.setdate').click(function (el) {
                    $(el.currentTarget).datepicker().datepicker("show");
                });
                // ajax call for getting survey detail
                var survey_detail = Array();
                var data_survey = Array();
                var min, max, maxsize, precision, scale_slot = 0;
                $.ajax({
                    url: "index.php?entryPoint=preview_survey",
                    type: "POST",
                    action: 'get_survey_detail',
                    data: {
                        'method': 'get_survey',
                        'record_id': '<?php echo $survey_id; ?>'},
                    success: function (result) {
                        data_survey = JSON.parse(result);
                        survey_detail = data_survey['survey_details'];
                        var slider_detail = new Object();
                        $.each(survey_detail, function (pindex, page_data) {
                            $.each(page_data, function (qindex, que_data) {
                                if (qindex == 'page_questions') {
                                    $.each(que_data, function (qi, q_data) {
                                        if (q_data['que_type'] == 'Scale')
                                        {
                                            var detail = new Object();
                                            // if min-max-slot value is not set then set default value
                                            if (!q_data['min'] || !q_data['max']) {
                                                detail['min'] = 0;
                                                detail['max'] = 10;
                                                detail['scale_slot'] = 1;
                                            } else {
                                            detail['min'] = q_data['min'];
                                            detail['max'] = q_data['max'];
                                            detail['scale_slot'] = q_data['scale_slot'];
                                            }
                                            slider_detail[q_data['que_id']] = detail;
                                        }
                                    });
                                }
                            });
                        });
                        //bind next prev button click function
                        $(".bx-next").click(function () {
                            var currentSlidePage = slider.getCurrentSlide() + 1;
                            var totalPageCount = slider.getSlideCount();
                            if (currentSlidePage == totalPageCount - 1) {
                                $(this).removeClass('showBtn').addClass('hideBtn');
                                if($('.thanks-page').length != 0){
                                    $('.description').remove();
                                }
                            } else if (currentSlidePage == totalPageCount - 2) {
                                if ($('.thanks-page').length != 0) {
                                    $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            } else {
                                $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            }
                            } else {
                                $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            }
                            slider.goToNextSlide();
                            $('html, body').animate({scrollTop: 0}, 800);
                            if ($(this).hasClass('hideBtn')) {
                                $("#btnsend").show();
                                $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            }

                        });
                        $(".bx-prev").click(function () {

                            $('.validation-tooltip').fadeOut();
                            var currentSlidePage = slider.getCurrentSlide();
                            if (currentSlidePage == 1) {
                                $(this).removeClass('showBtn').addClass('hideBtn');
                                $("#btnnext").removeClass('hideBtn').addClass('showBtn');
                                $("#btnnext").show();
                            } else {
                                $("#btnnext").removeClass('hideBtn').addClass('showBtn');
                                $("#btnnext").show();
                            }
                            slider.goToPrevSlide();
                            $('html, body').animate({scrollTop: 0}, 800);
                            //$("#btnsend").hide();
                        });

                        //setting slider
                        $(function () {
                            var que_id = '';
                            $.each(slider_detail, function (qid, slider_data) {
                                var slider = $('.' + qid).find("#slider").slider({
                                    slide: function (event, ui) {
                                        $(ui.handle).find('.tooltip').html('<div>' + ui.value + '</div>');
                                    },
                                    range: "min",
                                    value: slider_data.min,
                                    min: parseInt(slider_data.min),
                                    max: parseInt(slider_data.max),
                                    step: parseInt(slider_data.scale_slot),
                                    create: function (event, ui) {
                                        var tooltip = $('<div class="tooltip" />');
                                        $(event.target).find('.ui-slider-handle').append(tooltip);
                                    },
                                    change: function (event, ui) {
                                        $('#hidden').attr('value', ui.value);
                                    }
                                });
                                    });
                                });
                    }
                });
                var slider = jQuery('.bxslider').bxSlider({
                    adaptiveHeight: true,
                    infiniteLoop: false,
                    hideControlOnEnd: true,
                    touchEnabled:false,
                    mode: 'fade',
                    pager: true,
                    controls: false,
                    nextSelector: '#btnnext',
                    prevSelector: '#btnprev',
                    onSliderLoad: function (currentIndex) {
                        $('.bx-viewport').find('.bxslider').children().eq(currentIndex).addClass('active-slide');

                        //hide propgress bar at welcomepage
                        if ($('.active-slide').find('.welcome-form').length != 0 || $('.active-slide').find('.thanks-page').length != 0)
                        {
                            //    $('.progress-bar').hide();
                            $('.form-desc').hide();
                        } else {
                            //    $('.progress-bar').show();
                            $('.form-desc').show();
                        }
                    },
                    onSlideBefore: function ($slideElement) {
                        $('#btnnext').show();
                        $('.bx-viewport').find('.bxslider').children().removeClass('active-slide');
                        $slideElement.addClass('active-slide');
                        var total_pages = parseInt($('.page-no').length);
                        var page_no = parseInt($('.active-slide').find('.page-no > i').html());
                        if (!page_no) {
                            $('.progress-bar').hide();
                            $('.btn-submit').hide();

                            // $('.btn-submit').attr('value','End Survey');

                        } else if (total_pages == page_no && $('.thanks-page').length != 0)
                        {
                            if ($('#submit_button_label').length != 0 && $('#submit_button_label').val())
                            {
                                $('#btnnext').attr('value', $('#submit_button_label').val());
                            } else {
                                //$('#btnnext').attr('value', 'End Survey');
                            }
                        } else if($('.thanks-page').length != 0) {
                            if ($('#next_button_label').length != 0 && $('#next_button_label').val())
                            {
                                $('#btnnext').attr('value', $('#next_button_label').val());
                            } else {
                                $('#btnnext').attr('value', 'Next');
                            }
                        } else if(total_pages == page_no && $('.thanks-page').length == 0){
                            $('#btnnext').hide();
                        }
                        page_no = page_no - 1;
                        var progress_percentage = Math.floor((page_no * 100) / total_pages);
                        // page progress bar
                        for (var i = 1; i <= total_pages; i++) {
                            if (i < page_no)
                            {
                                $('.progress-bar').children('li:nth-child(' + i + ')').addClass('completed');
                                $('.progress-bar').children('li:nth-child(' + i + ')').removeClass('active');
                            } else if (i == page_no) {
                                $('.progress-bar').children('li:nth-child(' + i + ')').addClass('active');
                                $('.progress-bar').children('li:nth-child(' + i + ')').removeClass('completed');
                            } else {
                                $('.progress-bar').children('li:nth-child(' + i + ')').removeClass('completed');
                                $('.progress-bar').children('li:nth-child(' + i + ')').removeClass('active');
                            }
                        }


                        var progress = $("#progress").slider({
                            range: "min",
                            value: progress_percentage,
                            disabled: true,
                        });
                        //add extra div for designing
                        $('#progress').find('.tooltip').html('<div>' + progress_percentage + '<div>');
                        $('#pagecount').html(page_no + "/" + total_pages);
                        $('#progress-percentage').html(progress_percentage + "%");

                        //hide propgress bar at welcomepage
                        if ($('.active-slide').find('.welcome-form').length != 0)
                        {
                            // $('.progress-bar').hide();
                            $('.form-desc').hide();
                        } else {
                            //   $('.progress-bar').show();
                            $('.form-desc').show();
                        }
                        if (isNaN(page_no)) {
                            $('#btnnext').show();
                        }
                    },
                    onSlideAfter: function () {

                        var currentSlidePage = slider.getCurrentSlide() + 1;
                        var totalPageCount = slider.getSlideCount();
                        if (currentSlidePage == 1) {
                            $("#btnprev").removeClass('showBtn').addClass('hideBtn');
                            $('#btnnext').removeClass('hideBtn').addClass('showBtn');
                            $("#btnsend").hide();
                        } else if (currentSlidePage == totalPageCount) {
                            $("#btnsend").show();
                            $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            $('#btnnext').removeClass('showBtn').addClass('hideBtn');
                        } else {
                            $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            $('#btnnext').removeClass('hideBtn').addClass('showBtn');
                            $("#btnsend").hide();
                        }
                    },
                });
                var total_pages = parseInt($('.page-no').length);
                var page_no = 0;
                var progress_percentage = Math.floor((page_no * 100) / total_pages);

                var progress = $("#progress").slider({
                    range: "min",
                    value: progress_percentage,
                    disabled: true,
                    create: function (event, ui) {

                        var tooltip = $('<div></div><div class="tooltip"><div>' + progress_percentage + '<div></div>');
                        $(event.target).find('.ui-slider-handle').append(tooltip);
                    },
                });

                $('#pagecount').html(page_no + "/" + total_pages);
                $('#progress-percentage').html(progress_percentage + "%");

                 $('.setdatetime').keypress(function (e) {
                    //if the letter is  digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && (e.which > 48 || e.which < 57)) {

                        return false;
                    }
                });

                $('.setdate').keypress(function (e) {
                    //if the letter is  digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && (e.which > 48 || e.which < 57)) {

                        return false;
                    }
                });

                $('#selected_lang').change(function () {
                    // change survey language
                    if (confirm('Are you sure want to change survey language ?'))
                    {
                        var url = window.location.href;
                        url = window.location.href.split('&selected_lang=');
                        window.location.assign(url[0] + '&selected_lang=' + $('#selected_lang').val());
                    }
            });

            });
            function addOtherField(el) {
               var que_id = $(el).parents('.form-body').find('.questionHiddenField').val();
                var placeholder_label = $('[name=placeholder_label_other_' + que_id + ']').val();
                if (!placeholder_label)
                {
                    placeholder_label = 'Other';
                }
                var isOtherSelected = false;
                // Radio type of answer
                if (el.type == 'radio')
                {
                    var value_selected = $(el).attr('class');
                }
                // Dropdown type of answer
                else if (el.type == 'select-one')
                {
                    var value_selected = $('[value=' + $(el).val() + ']').attr('class');
                }
                // Multi select list
                else if (el.type == 'select-multiple') {
                    var selected_ans_ids = $(el).val();
                    var value_selected = '';
                    $.each(selected_ans_ids, function (id)
                    {
                        value_selected += $('[value=' + $(el).val() + ']').attr('class');
                    });
                }
                // other than check box type than get value from array of selected values
                if (el.type != 'checkbox' && value_selected.includes('is_other_option'))
                {
                    isOtherSelected = true;
                }
                // if check box then retrieve value from all selected values by class id
                else if (el.type == 'checkbox')
                {
                    value_selected = el.classList[0];
                    var sel_array = new Array();
                    $.each($('.' + value_selected + ':checked'), function () {
                        if (this.className.includes('is_other_option'))
                        {
                            isOtherSelected = true;
                        }
                    });
                }
                // if othet input field not exists and other option selected then show it
                if (isOtherSelected && $(el).parents('.option').find('.other_option_input').length == 0)
                {
                    var add='';
                    if (el.type == 'select-one')
                    {
                        add = 'style="margin-top: 18px;width:55%;display: inline-block;position: relative;"';
                    } else if (el.type == 'select-multiple') {
                        add = 'style="margin-top: 18px;width:55%;display: inline-block;position: relative;"';
                    } else {
                        add = 'style="margin-top: 18px;width:55%;display: inline-block;position: relative;"';
                    }
                    $(el).parents('.option').append("<input " + add + " class='form-control {$que_id} other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='"+placeholder_label+"'>");

                    var newHeight = $('.bx-viewport').height() + $(el).parents('.option').find('.other_option_input').height();
                    $('.bx-viewport').height(newHeight);

                }
                // other option not selected and if other input field exists then remove it
                else if (!isOtherSelected) {
                    var newHeight = $('.bx-viewport').height() - $(el).parents('.option').find('.other_option_input').height();
                    $('.bx-viewport').height(newHeight);
                    $(el).parents('.option').find('.other_option_input').remove();

                }
            }


        </script>
    </head>
    <body>
        <div id='tooltipDiv'></div>
        <div class="bg"></div>
        <?php
                               if(count($available_lang) != 0)
                        { ?>
                            <div id="lang_selection">
                                <p>
                                Select Survey Language : <select id="selected_lang">
                                    <option value="<?php echo $sugar_config['default_language']; ?>"><?php echo $langValues[$sugar_config['default_language']]; ?></option>
                                    <?php
                                    foreach ($available_lang as $key => $lang) {
                                        $selected = '';

                                        if ($key == $selected_lang) {
                                            $selected = 'selected';
                                        }
                                        ?>
                                        <option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $lang ?> </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                </p>
                            </div>
                             <?php } ?>
        <div class="main-container">
            <div class="clip">
                <span class="clip-0"><img src="custom/include/survey-img/paperclip-last.png"></span>
                <span class="clip-1"><img src="custom/include/survey-img/paperclip.png"></span>
                <span class="clip-2"><img src="custom/include/survey-img/paperclip.png"></span>
                <!-- <span class="clip-3"><img src="custom/include/survey-img/redpin-new.png"></span> -->
            </div>
            <div class="top-section">
                <div class="header">
                    <div class="">
                        <h1 class="logo">
                            <img src="<?php
                            if ($survey->logo) {
                                echo "custom/include/surveylogo_images/{$survey->logo}";
                            }
                            ?>" alt="" title="">

                        </h1>

                        <?php $total_pages = count($survey_details)?>
                        <div class="survey-header"><h2>
                                <?php
                                if (!empty($list_lang_detail[$survey_id])) {
                                    echo $list_lang_detail[$survey_id];
                                } else {
                                    echo $survey->name;
                                }
                                ?></h2></div>
                    </div>
                </div>
            </div>
            <div class="survey-container">
                <div class="container">
                    <div class="survey-form form-desc">
                        <?php if($total_pages > 1) {?>
                        <ul class="progress-bar">
                            <!--    setting number & page title designing for page completion status-->
                            <?php
                            // Setting Page Header
                            if ($is_progress_indicator != 1) {
                                foreach ($survey_details as $page_sequence => $detail) {
                                    if ($survey->survey_theme == 'theme2' || $survey->survey_theme == 'theme6' || $survey->survey_theme == 'theme7' || $survey->survey_theme == 'theme8') {
                                        ?>

                                        <li class="hexagon" style='cursor: default'><span class="pro-text"><?php echo 'Page ' . $page_sequence; ?></span><a style='cursor: default'><?php echo $page_sequence; ?></a></li>

                                    <?php
                                    } else {
                                        ?>

                                        <li class="hexagon" style='cursor: default'><span class="pro-text"><?php echo 'Page'; ?></span><a style='cursor: default'><?php echo $page_sequence; ?></a></li>

                                    <?php
                                    }
                                }
                            } else {
                                ?>
                                <!--    setting progressbar for page completion status-->
                                <section style="width:100%">
                                    <div id="pagecount" class="equal text"  style="width:5%"></div>
                                    <div id="progress" class="equal" style="width:85%"></div>
                                    <div id="progress-percentage" class="equal text last" style="width:5%"></div>
                                </section>
                            <?php } ?>
                            <div class="shape">
                                <span class="arr-right"></span>
                            </div>
                            </li>
                        </ul>
                        <?php } ?>
                        <div class="form-body description">
                                            <?php
                                            if (!empty($list_lang_detail[$survey_id . '_description'])) {
                                                echo nl2br($list_lang_detail[$survey_id . '_description']);
                                            } else {
                                                echo nl2br($survey->description);
                                            }
                                            ?>
                        </div>
                    </div>
                    <ul class="bxslider">
                        <?php
                        $addClass = '';
                        $totalpages = count($survey_details);
                        if ($totalpages <= 1 && empty($survey->welcome_page)) {
                            $addClass = 'hideBtn';
                        }
                        $img_flag = false;
                        $que_no = 0;
                        // set up WELCOME Page
                        if (!empty($survey->welcome_page)) {
                            ?>
                            <li>
                                <div class="survey-form welcome-form">
                                    <?php
                                                if (!empty($list_lang_detail['welcome_page'])) {
                                                    $welcome_content = base64_decode($list_lang_detail['welcome_page']);
                                                } else {
                                    $welcome_content = $survey->welcome_page;
                                                }
                                                echo '<div class="form-body">' . html_entity_decode($welcome_content) . '</div>';
                                    ?>

                                </div>
                            </li>
                            <?php
                        }
                        foreach ($survey_details as $page_sequence => $detail) {
                            ?>
                            <li>
                                <div class="survey-form">
                                    <div class="form-header">
                                        <h1><?php echo $detail['page_title']; ?></h1>
                                        <span class="page-no"><i><?php echo $page_sequence ?></i></span>
                                    </div>
                                    <?php foreach ($detail['page_questions'] as $que_sequence => $question) { ?>
                                        <div class="form-body <?php echo $question['que_type']; ?>">
                                            <input type="hidden" class="questionHiddenField" name="questions[]" value="<?php echo $question['que_id'] ?>"  >
                                            <?php
                                            if ($question['que_type'] == 'question_section') {
                                                        $que_class = 'section-header-div';
                                            } else {
                                                        $que_class = '';
                                                    }
                                                    ?>

                                                    <h3 class="questions <?php echo $que_class ?>">
                                                <?php
                                                if ($question['que_type'] == 'Image' || $question['que_type'] == 'Video') {
                                                    echo $question['question_help_comment'];
                                                } else if ($question['que_type'] == 'question_section') {
                                                    echo '<div class="question-section">' . $question['que_title'] . '</div>';
                                                } else {
                                                    $que_no++;
                                                    $img_flag = false;
                                                    echo $que_no . '.&nbsp;';
                                                    echo $question['que_title'];
                                                    ?>  <?php if ($question['is_required'] == 1) { ?>
                                                        <span class="is_required" style="color:red;">   *</span>
                                                        <?php
                                                    }
                                                }
                                                if ($question['que_type'] == 'Image' || $question['que_type'] == 'Video') {
                                                    // do not display help comment on top-right side
                                                } else if (!empty($question['question_help_comment'])) {
                                                    ?> <div style="display: inline-block;float: right;"><img class="questionImgIcon" onmouseout="removeHelpTipPopUpDiv();" onmouseover="openHelpTipsPopUpSurvey(this, '<?php echo $question['question_help_comment']; ?>');" src="custom/include/survey-img/question.png" ></div>
                                                <?php } ?></h3>
                                            <?php
                                                        $elementHTML = getMultiselectHTML($question['answers'], $question['que_type'], $question['que_id'], $question['is_required'], $question['maxsize'], $question['min'], $question['max'], $question['precision'], $question['scale_slot'], $question['is_sort'], $question['is_datetime'], $question['advance_type'], $question['que_title'], $question['matrix_row'], $question['matrix_col'], $question['description'], $list_lang_detail);
                                            echo $elementHTML;
                                            ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </li>

                            <?php
                        }
                        if (!empty($survey->thanks_page)) {
                            ?>
                            <li>
                                <div class="survey-form thanks-page">
                                    <?php
                                                if (!empty($list_lang_detail['thanks_page'])) {
                                                    $thanks_page = base64_decode($list_lang_detail['thanks_page']);
                                                } else {
                                    $thanks_page = $survey->thanks_page;
                                                }
                                    // $welcome_content = '<div style="text-align: center; background-color: #666;"><br /><span style="color: #99cc00; font-size: large;">Welcome To Survey</span><br /><br /><br /><img style="width: 970px; height: 413px;" alt="" src="http://localhost/SurveyRocketS2.5/rest/v10/bc_survey/26d42dbc-931e-8d79-6808-57f4b146a9af/file/survey_logo?format=sugar-html-json&amp;platform=base&amp;_hash=66a3e4ee-24b3-6361-b38e-57f4af98cd04" /><br /><br /><br /><span style="color: #ff6600;"><span style="font-size: medium;">Please click <span style="color: #993366; background-color: #ffffff;"> next button </span> to start up a survey</span><br /><br /><br /></span></div>';
                                    echo '<div class="form-body">' . html_entity_decode($thanks_page) . '</div>';
                                    ?>
                                </div>
                            </li>

                            <?php
                        }?>
                            </ul><?php
                                    if (!empty($list_lang_detail['next_button'])) {
                                        $next_button_label = $list_lang_detail['next_button'];
                        ?>
                                        <input type="hidden" id="next_button_label" value="<?php echo $list_lang_detail['next_button']; ?>"/>
                                        <?php
                                    } else {
                                        $next_button_label = 'Next';
                        }
                                    if (!empty($list_lang_detail['prev_button'])) {
                                        $prev_button_label = $list_lang_detail['prev_button'];
                                    } else {
                                        $prev_button_label = 'Prev';
                                    }
                                    if (!empty($list_lang_detail['submit_button'])) {
                        ?>
                                        <input type="hidden" id="submit_button_label" value="<?php echo $list_lang_detail['submit_button']; ?>"/>
                                        <?php
                                        $submit_button_label = $list_lang_detail['submit_button'];
                                    } else {
                                        $submit_button_label = 'Prev';
                                    }
                                    ?>
                    </ul>
                                <div class = "action-block">
                                    <div style="display: inline-block;float: right;"> <input class='bx-prev button hideBtn'  type='button' value='<?php echo $prev_button_label; ?>' name="btnprev" id="btnprev">
                                        <input class='bx-next button <?php echo $addClass; ?>'  type='button' value='<?php echo $next_button_label; ?>' name="btnnext" id="btnnext"></div></div>
                    <div class="btm-link"><a href="#"></a></div>

                </div>
            </div>
        </div>
    </body>
</html>
