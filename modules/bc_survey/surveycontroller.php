<?php
/**
 * when priview survey is click this entry point is call
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
if ($_REQUEST['method'] == 'preview_survey') {
    global $sugar_config;
    $site_url = $sugar_config['site_url'];
    echo $site_url;
}

if ($_REQUEST['method'] == 'get_survey') {
    require_once 'data/BeanFactory.php';
    require_once 'custom/include/utilsfunction.php';
    $record_id = $_REQUEST['record_id'];
    $module_id = $_REQUEST['cid'];
    $oSurvey = new bc_survey();
    $oSurvey->retrieve($record_id);
    $oSurvey->load_relationship('bc_survey_pages_bc_survey');

    $oSurvey_details = array();
    $questions = array();
    $rel = $oSurvey->bc_survey_pages_bc_survey;

    foreach ($oSurvey->bc_survey_pages_bc_survey->getBeans() as $pages) {
        unset($questions);
        $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
        $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
        $pages->load_relationship('bc_survey_pages_bc_survey_questions');
        foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
            $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
            $questions[$survey_questions->question_sequence]['que_type'] = $survey_questions->question_type;
            $questions[$survey_questions->question_sequence]['is_required'] = ($survey_questions->is_required == 1) ? 'Yes' : 'No';
            //advance options
            $questions[$survey_questions->question_sequence]['advance_type'] = (isset($survey_questions->advance_type)) ? $survey_questions->advance_type : '';
            $questions[$survey_questions->question_sequence]['maxsize'] = (isset($survey_questions->maxsize)) ? $survey_questions->maxsize : '';
            $questions[$survey_questions->question_sequence]['min'] = (isset($survey_questions->min)) ? $survey_questions->min : '';
            $questions[$survey_questions->question_sequence]['max'] = (isset($survey_questions->max)) ? $survey_questions->max : '';
            $questions[$survey_questions->question_sequence]['precision'] = (isset($survey_questions->precision_value)) ? $survey_questions->precision_value : '';
            $questions[$survey_questions->question_sequence]['scale_slot'] = (isset($survey_questions->scale_slot)) ? $survey_questions->scale_slot : '';
            $questions[$survey_questions->question_sequence]['is_datetime'] = (isset($survey_questions->is_datetime) && $survey_questions->is_datetime == 1 ) ? 'Yes' : 'No';
            $questions[$survey_questions->question_sequence]['is_sort'] = (isset($survey_questions->is_sort) && $survey_questions->is_sort == 1 ) ? 'Yes' : 'No';
            $questions[$survey_questions->question_sequence]['matrix_row'] = (isset($survey_questions->matrix_row)) ? json_decode($survey_questions->matrix_row) : '';
            $questions[$survey_questions->question_sequence]['matrix_col'] = (isset($survey_questions->matrix_col)) ? json_decode($survey_questions->matrix_col) : '';
            $questions[$survey_questions->question_sequence]['selection_limit'] = (isset($survey_questions->selection_limit)) ? $survey_questions->selection_limit : 0;
            $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
            foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
                $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence][$survey_answers->id] = $survey_answers->name;
            }
            if (isset($questions[$survey_questions->question_sequence]['answers']) && is_array($questions[$survey_questions->question_sequence]['answers'])) {
                ksort($questions[$survey_questions->question_sequence]['answers']);
            } else if ($survey_questions->question_type == 'Scale') {
                $sbmtSurvData = getPerson_SubmissionExportData($record_id, $module_id);
                $queArray = $sbmtSurvData[$questions[$survey_questions->question_sequence]['que_id']];
                $queAnsArray = array_values($queArray);
                $answer = $queAnsArray[1];
                $questions[$survey_questions->question_sequence]['answers'] = $answer;
            }
        }
        ksort($questions);
       // $GLOBALS['log']->fatal("This is the answers : " . print_r($questions, 1));
        $survey_details[$pages->page_sequence]['page_questions'] = $questions;
    }

    ksort($survey_details);

    $data['survey_details'] = $survey_details;
    $data['lang_survey_details'] = return_app_list_strings_language($_REQUEST['selected_lang'])[$record_id];
    $result = json_encode($data);
    
    echo $result;
}





    