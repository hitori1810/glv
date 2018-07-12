<?php
/**
 * The file used to handle survey submission form
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
if (!defined('sugarEntry') || !sugarEntry)
    define('sugarEntry', true);
include_once('config.php');
require_once('include/entryPoint.php');
require_once('data/SugarBean.php');
require_once('data/BeanFactory.php');
require_once('include/utils.php');
require_once('include/database/DBManager.php');
require_once('include/database/DBManagerFactory.php');
require_once('modules/Administration/Administration.php');
//ini_set('default_charset', 'UTF-8');
$themeObject = SugarThemeRegistry::current();
$favicon = $themeObject->getImageURL('sugar_icon.ico', false);
global $sugar_config, $db;

// survey is currently submitted by whom : receipient or sender
if (isset($_REQUEST['submitted_by'])) {
    $submitted_by = $_REQUEST['submitted_by'];
} else {
    $submitted_by = 'recipient';
}

$encoded_param = $_REQUEST['q'];
$decoded_param = base64_decode($encoded_param);

$survey_id = substr($decoded_param, 0, 36);

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if (getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if (getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if (getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if (getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if (getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

// if open URL
if (strlen($_REQUEST['q']) == 6) {
    $isOpenSurveyLink = true;
    $survey = new bc_survey();
    $survey->retrieve_by_string_fields(array('survey_submit_unique_id' => $_REQUEST['q']));
    $survey_id = $survey->id;
    if (!empty($survey_id)) {
        $ip_address = get_client_ip();
        $isNotSubmitted = false;
        $subList = $survey->get_linked_beans('bc_survey_submission_bc_survey', 'bc_survey_submission');
        foreach ($subList as $beanSubmission) {
            if ($beanSubmission->submission_ip_address == $ip_address && $beanSubmission->status != 'Pending' && !$isNotSubmitted) {
                $submission_id = $beanSubmission->id;
                $isAlreadySubmissionEntry = true;
                $customer_name = $beanSubmission->customer_name;
            }
            if ($beanSubmission->submission_ip_address == $ip_address && $beanSubmission->status == 'Pending') {
                $submission_id = $beanSubmission->id;
                $isNotSubmitted = true;
                $isAlreadySubmissionEntry = true;
                $customer_name = $beanSubmission->customer_name;
            }
        }
        // first submission from given ip
        if (!$isAlreadySubmissionEntry || ($survey->allow_redundant_answers == 1)) {
            if (!$isNotSubmitted) {
                $gmtdatetime = TimeDate::getInstance()->nowDb();
                $objSubmission = BeanFactory::getBean('bc_survey_submission');
                $objSubmission->submission_ip_address = $ip_address;
                $objSubmission->submission_type = 'Open Ended';

                $objSubmission->email_opened = 1;
                $objSubmission->survey_send = 1;
                $web_link_updated = $survey->web_link_counter + 1;
                $objSubmission->customer_name = 'Web Link ' . $web_link_updated;
                $objSubmission->schedule_on = $gmtdatetime;
                $objSubmission->status = 'Pending';
                $objSubmission->recipient_as = 'to';
                $objSubmission->base_score = $survey->base_score;
                $objSubmission->save();
                $objSubmission->load_relationship('bc_survey_submission_bc_survey');
                $objSubmission->bc_survey_submission_bc_survey->add($survey->id);
                $customer_name = $objSubmission->customer_name;
                $survey->web_link_counter = $web_link_updated;
                $survey->save();
                $customer_name = $objSubmission->customer_name;
                $submission_id = $objSubmission->id;
            }
        }
    }
}
// If email link
else {
    $module_type_array = split('=', substr($decoded_param, strpos($decoded_param, 'ctype='), 42));
    $module_type_array = split('&', $module_type_array[1]);
    $module_type = $module_type_array[0];
    $module_id_array = split('=', substr($decoded_param, strpos($decoded_param, 'cid='), 40));
    $module_id = $module_id_array[1];
    $survey = new bc_survey();
    $survey->retrieve($survey_id);
}
$survey_type = $survey->survey_type;
$default_survey_language = $survey->default_survey_language;
$survey_submission = BeanFactory::getBean('bc_survey_submission', $submission_id);
$submission_language = $survey_submission->submitted_survey_language;
// get survey supported language
if (empty($_REQUEST['selected_lang'])) {
    $selected_lang = $default_survey_language;
} else if (isset($_REQUEST['selected_lang']) && !empty($_REQUEST['selected_lang'])) {
    $selected_lang = $_REQUEST['selected_lang'];
} else if (!empty($submission_language)) {
    $selected_lang = $submission_language;
} else {
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
// redirect url for redirecting user after successfull submission
$redirect_url = $survey->redirect_url;
// if set 1 than show page compelition progressbar
$is_progress_indicator = $survey->is_progress;
$reSubmitCount = (!empty($survey->allowed_resubmit_count)) ? $survey->allowed_resubmit_count : 1; // counter of allowed resubmit of response

if ($isOpenSurveyLink && $survey->allow_redundant_answers == 1) {
    $survey_submission = BeanFactory::getBean('bc_survey_submission', $submission_id);
    $reSubmitCount = (int) $survey_submission->resubmit_counter + 1;
} else {
    $reSubmitCount = (!empty($survey->allowed_resubmit_count)) ? $survey->allowed_resubmit_count : 1;
}

$survey->load_relationship('bc_survey_pages_bc_survey');
$survey_details = array();
$questions = array();
$skip_logicArrForHideQues = array();
$skip_logicArrForAll = array();
$showHideQuesArrayOnPageload = array();
$msg = '';

// get piping data
$enable_data_piping = $survey->enable_data_piping;
if ($enable_data_piping == 1) {
    $sync_module = $survey->sync_module;
    $sync_type = $survey->sync_type;
}

if (!empty($module_type) && !empty($module_id)) {
    $moduleBeanObj = BeanFactory::getBean($module_type);
    $moduleBeanObj->retrieve($module_id);
}
if (strlen($_REQUEST['q']) == 6) {
    $query = "SELECT bc_survey_submission.status, bc_survey_submission.id AS submission_id,resubmit,resubmit_counter
FROM bc_survey_submission
  JOIN bc_survey_submission_bc_survey_c
    ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
      AND bc_survey_submission_bc_survey_c.deleted = 0
  JOIN bc_survey
    ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
      AND bc_survey.deleted = 0
WHERE bc_survey_submission.deleted = 0
    AND bc_survey.id = '{$survey_id}' AND bc_survey_submission.customer_name = '{$customer_name}'";
} else {
    $query = "SELECT bc_survey_submission.status, bc_survey_submission.id AS submission_id,resubmit,resubmit_counter
FROM bc_survey_submission
  JOIN bc_survey_submission_bc_survey_c
    ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
      AND bc_survey_submission_bc_survey_c.deleted = 0
  JOIN bc_survey
    ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
      AND bc_survey.deleted = 0
WHERE bc_survey_submission.module_id = '" . $db->quote($module_id) . "'
    AND bc_survey_submission.target_module_name = '" . $db->quote($module_type) . "'
    AND bc_survey_submission.deleted = 0
    AND bc_survey.id = '{$survey_id}'";
}
$submission_result = $db->query($query);
$submission_row = $db->fetchByAssoc($submission_result);
$submission_status = $submission_row['status'];
$survey_submission = new bc_survey_submission();
$survey_submission->retrieve($submission_row['submission_id']);
$current_date = gmdate('Y-m-d H:i:s');
$timeDate = new TimeDate();
$oStart_date = $survey->start_date;
$oEnd_date = $survey->end_date;
if (!empty($oStart_date)) {
    $survey_start_date = $oStart_date;
} else {
    $survey_start_date = '';
}
if (!empty($oEnd_date)) {
    $survey_end_date = $oEnd_date;
} else {
    $survey_end_date = '';
}

$submisstion_id = $submission_row['submission_id'];

$administrationObj = new Administration();
$administrationObj->retrieveSettings('SurveyPlugin');
//$reSubmitCount = (!empty($administrationObj->settings['SurveyPlugin_ReSubmitCount'])) ? $administrationObj->settings['SurveyPlugin_ReSubmitCount'] : 1;

$user = new User();
$user->retrieve($survey->created_by);

$startDateTime = TimeDate::getInstance()->to_display_date_time($survey_start_date, true, true, $user);
$endDateTime = TimeDate::getInstance()->to_display_date_time($survey_end_date, true, true, $user);

/*
 * Get Already submitted details
 */
require_once 'custom/include/utilsfunction.php';
$customer_name = $survey_submission->customer_name;
$sbmtSurvData = getPerson_SubmissionExportData($survey_id, $module_id, false, $customer_name);
$GLOBALS['log']->fatal("This is the sbmtSurvData : " . print_r($sbmtSurvData, 1));
$deleteAnsIdsOnResubmitArray = array();
foreach ($sbmtSurvData as $questionId => $ansDetails) {
    if (!is_null($ansDetails['answerId']) && !empty($ansDetails['answerId'])) {
        $deleteAnsIdsOnResubmitArray[] = $ansDetails['answerId'];
    }
}
$deleteAnsIdsOnResubmit = "'" . implode("','", $deleteAnsIdsOnResubmitArray) . "'";

// Static value
$userSbmtCount = $submission_row['resubmit_counter'];
$requestApproved = $submission_row['resubmit'];
// end static
// create resubmit request URL with encoded URL
$survey_resubmit_request_url = $sugar_config['site_url'] . '/survey_re_submit_request.php?survey_id=';

$sugar_survey_Url = $survey_resubmit_request_url; //create survey submission url
$encoded_param = base64_encode($survey_id . '&ctype=' . $module_type . '&cid=' . $module_id);
$sugar_survey_Url = str_replace('survey_id=', 'q=', $sugar_survey_Url);
$surveyReQURL = $sugar_survey_Url . $encoded_param . '&selected_lang=' . $selected_lang;

//retrieve module record
$rec_table = strtolower($module_type);
$focus_recivier_qry = "select deleted from $rec_table where id = '{$module_id}'";
$isdeletedResult = $db->query($focus_recivier_qry);
$isDeletedRecipient = $db->fetchByAssoc($isdeletedResult);
$GLOBALS['log']->fatal("This is the result : " . print_r($isDeletedRecipient, 1));
if (!$isOpenSurveyLink) {
    $resubmit_request_msg = " <a href='{$surveyReQURL}'>Click here...</a>";
}
$already_sub_msg = "You have already submitted this " . ucfirst($survey->survey_type) . ".";
if (!empty($list_lang_detail['already_sub_msg'])) {
    $already_sub_msg = $list_lang_detail['already_sub_msg'];
}

if ($isOpenSurveyLink) {
    $already_sub_msg = "" . ucfirst($survey->survey_type) . " has been already submitted from the same location.";
    if (!empty($list_lang_detail['location_already_sub_msg'])) {
        $already_sub_msg = $list_lang_detail['location_already_sub_msg'];
    }
}

if (!$isOpenSurveyLink) {
    $req_msg = "For request to admin to resubmit your " . ucfirst($survey->survey_type) . "";
    if (!empty($list_lang_detail['req_msg'])) {
        $req_msg = $list_lang_detail['req_msg'];
    }
}

$survey_notstart_msg = "This " . ucfirst($survey->survey_type) . " has not started yet, Please try after {$startDateTime} ";
if (!empty($list_lang_detail['survey_notstart_msg'])) {
    $survey_notstart_msg = $list_lang_detail['survey_notstart_msg'];
    $survey_notstart_msg = str_replace('$startDateTime', $startDateTime, $survey_notstart_msg);
}

$survey_exp_msg = "Sorry... This " . ucfirst($survey->survey_type) . " expired on {$endDateTime} ";
if (!empty($list_lang_detail['survey_exp_msg'])) {
    $survey_exp_msg = $list_lang_detail['survey_exp_msg'];
    $survey_exp_msg = str_replace('$endDateTime', $endDateTime, $survey_exp_msg);
}

$survey_deleted_msg = " Sorry! This " . ucfirst($survey->survey_type) . " has been deactivated by the owner. You can't attend it.";
if (!empty($list_lang_detail['survey_deleted_msg'])) {
    $survey_deleted_msg = $list_lang_detail['survey_deleted_msg'];
}

$rec_deleted_msg = "  Sorry! This recipient record is deleted by the owner. You can't attend it.";
if (!empty($list_lang_detail['rec_deleted_msg'])) {
    $rec_deleted_msg = $list_lang_detail['rec_deleted_msg'];
}

// if preview from email template
if ($_REQUEST['survey_id'] == 'SURVEY_PARAMS') {
    $msg1 = "<div class='failure_msg'> " . ucfirst($survey->survey_type) . " Preview not available here. Please preview " . ucfirst($survey->survey_type) . " from survey module.</div>";
}
// if user has already submitted this survey & also not a request is approved for the re submission
elseif (($submission_status == 'Submitted') && !($requestApproved) && ($userSbmtCount >= $reSubmitCount) && ($survey->allow_redundant_answers == 0 || !$isOpenSurveyLink)) {
    $msg1 = "<div class='success_msg'> {$already_sub_msg} {$req_msg} {$resubmit_request_msg}</div>";
}
//  if survey not started yet
elseif (($submission_status == 'Submitted' || $submission_status == 'Pending') && !empty($oStart_date) && !empty($oEnd_date) && ((strtotime($current_date) < strtotime($survey_start_date)))) {
    $msg1 = "<div class='failure_msg'>$survey_notstart_msg</div>";
}
// if survey is already expired
elseif (($submission_status == 'Submitted' || $submission_status == 'Pending') && !empty($oStart_date) && !empty($oEnd_date) && (strtotime($current_date) > strtotime($survey_end_date))) {
    $msg1 = "<div class='failure_msg'>$survey_exp_msg</div>";
}
// if user re submission count is reached then make request to resubmit
elseif (!($requestApproved) && ($userSbmtCount >= $reSubmitCount) && (!$isOpenSurveyLink)) {
    $msg1 = "<div class='success_msg'>$already_sub_msg {$req_msg} {$resubmit_request_msg}</div>";
}
// if user re submission count is reached then make request to resubmit for open ended
elseif (!($requestApproved) && ($userSbmtCount >= $reSubmitCount) && ($survey->allow_redundant_answers == 0 && $isOpenSurveyLink)) {
    $msg1 = "<div class='success_msg'>$already_sub_msg {$req_msg} {$resubmit_request_msg}</div>";
}
// if survey is deactivated / deleted by the sender
elseif (empty($survey->id)) {
    $msg1 .= "<div class='failure_msg'>$survey_deleted_msg</div>";
}
// if survey is deactivated / deleted by the sender
elseif (($isDeletedRecipient['deleted'] == 1 || $submisstion_id == '')|| (empty($survey_submission->id) && empty($survey->survey_submit_unique_id))) {
    $msg1 = "<div class='failure_msg'>$rec_deleted_msg</div>";
}
// survey is still not submitted then allow to submit or come for re submission then prefill response data
else {
    $survey_answer_prefill = array();
    $survey_answer_update_module_field_name = array();
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
            $questions[$survey_questions->question_sequence]['enable_otherOption'] = (isset($survey_questions->enable_other_option) ) ? $survey_questions->enable_other_option : '';
            $questions[$survey_questions->question_sequence]['matrix_row'] = (isset($survey_questions->matrix_row)) ? base64_decode($survey_questions->matrix_row) : '';
            $questions[$survey_questions->question_sequence]['matrix_col'] = (isset($survey_questions->matrix_col)) ? base64_decode($survey_questions->matrix_col) : '';
            $questions[$survey_questions->question_sequence]['description'] = (isset($survey_questions->description)) ? $survey_questions->description : '';
            $questions[$survey_questions->question_sequence]['is_skip_logic'] = (isset($survey_questions->is_skip_logic)) ? $survey_questions->is_skip_logic : 0;

            // Retrieve Sync Field
            $sync_field = $survey_questions->sync_fields;
            $questions[$survey_questions->question_sequence]['sync_field'] = (isset($survey_questions->sync_fields)) ? $survey_questions->sync_fields : '';

            // check if sync field is added to question and is not email
            if ($enable_data_piping == 1 && $sync_type != 'create' && !empty($sync_field)) {
                $moduleFieldValue = $moduleBeanObj->$sync_field;

                // set prefill data
                $survey_answer_prefill[$survey_questions->id] = (isset($moduleFieldValue)) ? $moduleFieldValue : '';
                // set sync field for question
                $survey_answer_update_module_field_name[$survey_questions->id] = $sync_field;
            } else if($enable_data_piping == 1 && $sync_type == 'create' && !empty($sync_field)) {

                // set prefill data
                $survey_answer_prefill[$survey_questions->id] =  '';
                // set sync field for question
                $survey_answer_update_module_field_name[$survey_questions->id] = $sync_field;
            }

            $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
            $questions[$survey_questions->question_sequence]['answers'] = array();
            foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
                if ($questions[$survey_questions->question_sequence]['is_required'] && !isset($survey_answers->name)) {
                    continue;
                } else {
                    $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence][$survey_answers->id] = (!empty($list_lang_detail[$survey_answers->id])) ? $list_lang_detail[$survey_answers->id] : $survey_answers->name;
                    if ($survey_answers->logic_action == "show_hide_question") {
                        $showHideQuesArrayOnPageload[$pages->page_sequence][$survey_answers->id] = explode(",", $survey_answers->logic_target);
                        $skip_logicArrForHideQues[$survey_answers->logic_action][$pages->page_sequence][] = explode(",", $survey_answers->logic_target);
                        $skip_logicArrForAll[$survey_answers->id][$survey_answers->logic_action] = explode(",", $survey_answers->logic_target);
                    } else {
                        //$skip_logicArrForHideQues[$survey_answers->logic_action][$pages->page_sequence] = $survey_answers->logic_target;
                        $skip_logicArrForAll[$survey_answers->id][$survey_answers->logic_action] = $survey_answers->logic_target;
                    }
                }
                $optionIds[] = $survey_answers->id;
            }
            ksort($questions[$survey_questions->question_sequence]['answers']);

            // Retrieve Other Submitted answer for current submission
            $query = "SELECT bc_survey_answers.id,bc_survey_answers.ans_type,bc_survey_answers.name "
                    . "FROM bc_submission_data_bc_survey_submission_c as submission "
                    . "JOIN bc_submission_data_bc_survey_questions_c as question "
                    . "ON  question.bc_submission_data_bc_survey_questionsbc_submission_data_idb = submission.bc_submission_data_bc_survey_submissionbc_submission_data_idb "
                    . "JOIN bc_submission_data_bc_survey_answers_c as answers "
                    . "ON question.bc_submission_data_bc_survey_questionsbc_submission_data_idb = answers.bc_submission_data_bc_survey_answersbc_submission_data_idb "
                    . "JOIN bc_survey_answers ON bc_survey_answers.id = answers.bc_submission_data_bc_survey_answersbc_survey_answers_ida "
                    . "WHERE submission.bc_submission_data_bc_survey_submissionbc_survey_submission_ida = '{$submisstion_id}' "
                    . "AND submission.deleted=0 "
                    . "AND question.deleted = 0 "
                    . "AND answers.deleted=0 AND bc_survey_answers.deleted=0 "
                    . "AND question.bc_submission_data_bc_survey_questionsbc_survey_questions_ida = '{$survey_questions->id}'";
            $ans = $db->query($query);
            while ($rows = $db->fetchByAssoc($ans)) {
                if (!in_array($rows['id'], $optionIds) && $rows['ans_type'] != 'other') {
                    $questions[$survey_questions->question_sequence]['answer_other'][$rows['id']] = $rows['name'];
                }
            }
        }
        $GLOBALS['log']->fatal("This is the questions : " . print_r($questions, 1));
        ksort($questions);
        $survey_details[$pages->page_sequence]['page_questions'] = $questions;
        ksort($survey_details);
    }
}
/* * Create layout of question and answer for the preview
 *
 * @param type $answers - options for multi choice
 * @param type $type - question type
 * @param type $que_id - question id of 36 char
 * @param type $is_required - is required or not
 * @param type $submittedAns - submitted answer to prefill data
 * @param type $maxsize - max size allowed for answer
 * @param type $min - min value for answer
 * @param type $max - max value for answer
 * @param type $precision - precision value for float type
 * @param type $scale_slot - scale slot value for scale type
 * @param type $is_sort - sorting
 * @param type $is_datetime - is datetime selected or not for date-time question
 * @param type $advancetype - advance option for question
 * @param type $que_title - question title
 * @param type $matrix_row - matrix rows detail
 * @param type $matrix_col - matrix cols detail
 * @param type $description - question description
 * @return string
 */

function getMultiselectHTML($skip_logicArrForAll, $answers, $type, $que_id, $is_required, $submittedAns, $submittedAnsOther, $maxsize, $min, $max, $precision, $scale_slot, $is_sort, $is_datetime, $advancetype, $que_title, $matrix_row, $matrix_col, $description, $is_skipp, $list_lang_detail, $survey_answer_prefill) {
    $ans_detail = json_encode($skip_logicArrForAll);
    $html = "";
    switch ($type) {
        case 'MultiSelectList':
            $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option multiselect-list  two-col' id='{$que_id}_div'>"
                    . "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />";
            if ($is_skipp == 1) {

                $html .= "<select class='form-control multiselect {$que_id}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' multiple='' size='10' name='{$que_id}[]' >";
            } else {
                $html .= "<select class='form-control multiselect {$que_id}' multiple='' size='10' name='{$que_id}[]' onchange='addOtherField(this);'>";
            }
            // if sorting
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options);

                foreach ($options as $ans_id => $answer) {
                    // check if answer is other type of or not
                    $is_other = '';
                    $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                    if ($oAnswer->ans_type == 'other') {
                        $is_other = 'is_other_option';
                    }
                    if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else {
                        $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    }
                    if (!empty($is_other)) {
                        $otherAnswer = true;
                    }
                }
                $html .= "</select>";
                // Retrieve Other answer from answers and submission
                if (is_array($submittedAnsOther) && $is_other) {
                    foreach ($submittedAnsOther as $aid => $subAns) {
                        // Other answer
                        $otherAnswerbyUser = $subAns;
                    }
                    $html .= "<input style='margin-top:20px;width:55%;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                }
            }
            // if not sorting
            else {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else {
                            $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        }

                        if (!empty($is_other)) {
                            $otherAnswer = true;
                        }
                    }
                }
                $html .= "</select>";
                // Retrieve Other answer from answers and submission
                if (is_array($submittedAnsOther) && $is_other) {
                    foreach ($submittedAnsOther as $aid => $subAns) {

                        // Other answer
                        $otherAnswerbyUser = $subAns;
                    }
                    $html .= "<input style='margin-top:20px;width:55%;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                }
            }

            $html .= "</div>";
            return $html;
            break;
        case 'Checkbox':
            $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option checkbox-list' id='{$que_id}_div'>"
                    . "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />";
            if ($advancetype == 'Horizontal') {
                $html .= '<ul class="horizontal-options">';
            } else {
                $html .= '<ul>';
            }
            // if sorting
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options); // sort options
                // if horizontal
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        }
                        $op++;
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='Other' value='{$otherAnswerbyUser}'>";
                    }
                }
                // if vertical
                else {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else {
                            if ($is_skipp == 1) {

                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        }
                        $op++;
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
            }
            // if not sorting
            else {
                // if horizontal
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            // check if answer is other type of or not
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail);  ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}'  onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox' style='display:inline;'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            }
                            $op++;
                        }
                    }
                    $html .= "</ul>";

                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
                // if vertical
                else {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            // check if answer is other type of or not
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            if (is_array($submittedAns) && in_array($answer, $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}'  onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}' checked='true'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' checked='true' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else {
                                if ($is_skipp == 1) {

                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-check {$is_other}'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= "<li class='md-checkbox'><label><input type='checkbox' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-check {$is_other}' onchange='addOtherField(this);'> " . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            }
                            $op++;
                        }
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
            }
            $html .= "</div>";
            return $html;
            break;
        case 'RadioButton':
            $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option radio-list' id='{$que_id}_div'>"
                    . "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' />";
            if ($advancetype == 'Horizontal') {
                $html .= '<ul class="horizontal-options">';
            } else {
                $html .= '<ul>';
            }
            // if sorting
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options); // sort options
                // if horizontal
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        } else {
                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        }
                        $op++;
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
                // if vertical
                else {
                    $op = 1;
                    foreach ($options as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail);' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail);' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                        } else {
                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        } else {
                            if ($is_skipp == 1) {

                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            }
                        }
                        $op++;
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
            }
            // if not sorting
            else {
                // if horizontal
                if ($advancetype == 'Horizontal') {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            // check if answer is other type of or not
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio' style='display:inline;'><label><input type='radio' value='{$ans_id}' id='{$que_id}_{$op}' name='{$que_id}[]' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            }
                            $op++;
                        }
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
                // if vertical
                else {
                    $op = 1;
                    foreach ($answers as $ans) {
                        foreach ($ans as $ans_id => $answer) {
                            // check if answer is other type of or not
                            $is_other = '';
                            $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                            if ($oAnswer->ans_type == 'other') {
                                $is_other = 'is_other_option';
                            }
                            if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                            } else {
                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}' checked='true'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' checked='true' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            } else {
                                if ($is_skipp == 1) {

                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail); ' class='{$que_id} md-radiobtn {$is_other}'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                } else {
                                    $html .= " <li class='md-radio'><label><input type='radio' value='{$ans_id}' name='{$que_id}[]' id='{$que_id}_{$op}' class='{$que_id} md-radiobtn {$is_other}' onchange='addOtherField(this);'>" . htmlspecialchars_decode($answer) . "<label for='{$que_id}_{$op}'>
                                    <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></label></li>";
                                }
                            }
                            $op++;
                        }
                    }
                    $html .= "</ul>";
                    // Retrieve Other answer from answers and submission
                    if (is_array($submittedAnsOther) && $is_other) {
                        foreach ($submittedAnsOther as $aid => $subAns) {

                            // Other answer
                            $otherAnswerbyUser = $subAns;
                        }
                        $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                    }
                }
            }
            $html .= "</div>";
            return $html;
            break;
        case 'DrodownList':
            $placeholder_label_other = '';
            if ($list_lang_detail[$que_id . '_other_placeholder_label']) {
                $placeholder_label_other = $list_lang_detail[$que_id . '_other_placeholder_label'];
            }
            $html = "<div class='option select-list two-col' id='{$que_id}_div'>"
                    . "<input type='hidden' name='placeholder_label_other_{$que_id}' value='{$placeholder_label_other}' /><ul><li><div class='styled-select'>";
            if ($is_skipp == 1) {
                $html .= "<select name='{$que_id}[]' class='form-control required {$que_id}' onchange='addOtherField(this); skipp_logic_question(this,$ans_detail);'><option selected='' value='selection_default_value_dropdown' class=''>Select</option>";
            } else {
                $html .= "<select name='{$que_id}[]' class='form-control required {$que_id}' onchange='addOtherField(this);'><option selected='' value='selection_default_value_dropdown' class=''>Select</option>";
            }
            // if sorting
            if ($is_sort == 1) {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        $options[$ans_id] = $answer;
                    }
                }
                asort($options); // sort options

                foreach ($options as $ans_id => $answer) {
                    // check if answer is other type of or not
                    $is_other = '';
                    $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                    if ($oAnswer->ans_type == 'other') {
                        $is_other = 'is_other_option';
                    }
                    if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                        $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    } else {
                        $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                    }
                }
                $html .= "</select></div></li></ul>";
                // Retrieve Other answer from answers and submission
                if (is_array($submittedAnsOther) && $is_other) {
                    foreach ($submittedAnsOther as $aid => $subAns) {

                        // Other answer
                        $otherAnswerbyUser = $subAns;
                    }
                    $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                }
            }
            // if not sorting
            else {
                foreach ($answers as $ans) {
                    foreach ($ans as $ans_id => $answer) {
                        // check if answer is other type of or not
                        $is_other = '';
                        $oAnswer = BeanFactory::getBean('bc_survey_answers', $ans_id);
                        if ($oAnswer->ans_type == 'other') {
                            $is_other = 'is_other_option';
                        }
                        if (is_array($submittedAns) && in_array($ans_id, $submittedAns)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (!is_array($submittedAns) && ($ans_id == $submittedAns)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (is_array($survey_answer_prefill) && in_array($answer, $survey_answer_prefill)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else if (!is_array($survey_answer_prefill) && ($answer == $survey_answer_prefill)) {
                            $html .= "<option value='{$ans_id}' selected='true' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        } else {
                            $html .= "<option value='{$ans_id}' class='{$is_other}'>" . htmlspecialchars_decode($answer) . "</option>";
                        }
                    }
                }
                $html .= "</select></div></li></ul>";
                // Retrieve Other answer from answers and submission
                if (is_array($submittedAnsOther) && $is_other) {
                    foreach ($submittedAnsOther as $aid => $subAns) {

                        // Other answer
                        $otherAnswerbyUser = $subAns;
                    }
                    $html .= "<input style='margin-top: 18px;width:55%;display: inline-block;position: relative;' class='form-control {$que_id}_other other_option_input' type='textbox' name='{$que_id}[]' class='{$que_id}' placeholder='{$placeholder_label_other}' value='{$otherAnswerbyUser}'>";
                }
            }

            $html .= "</div>";
            return $html;
            break;
        case 'Textbox':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            // if interger then add class to validate with prefill submitted answer
            if (!is_array($submittedAns) && !empty($submittedAns) && $advancetype == 'Integer') {
                $html .= "<input class='form-control {$que_id} numericField' type='textbox' name='{$que_id}[]' value='{$submittedAns}' class='{$que_id}'>";
            }
            // if float then add class to validate with prefill submitted answer
            else if (!is_array($submittedAns) && !empty($submittedAns) && $advancetype == 'Float') {
                $html .= "<input class='form-control {$que_id} decimalField' type='textbox' name='{$que_id}[]' value='{$submittedAns}' class='{$que_id}'>";
            }
            // if float then add class to validate with prefill submitted answer
            else if (!is_array($submittedAns) && !empty($submittedAns)) {
                $html .= "<input class='form-control {$que_id} ' type='textbox' name='{$que_id}[]' value='{$submittedAns}' class='{$que_id}'>";
            } else if (!is_array($survey_answer_prefill) && !empty($survey_answer_prefill) && $advancetype == 'Integer') {
                $html .= "<input class='form-control {$que_id} numericField' type='textbox' name='{$que_id}[]' value='{$survey_answer_prefill}' class='{$que_id}'>";
            }
            // if float then add class to validate with prefill submitted answer
            else if (!is_array($survey_answer_prefill) && !empty($survey_answer_prefill) && $advancetype == 'Float') {
                $html .= "<input class='form-control {$que_id} decimalField' type='textbox' name='{$que_id}[]' value='{$survey_answer_prefill}' class='{$que_id}'>";
            }
            // if float then add class to validate with prefill submitted answer
            else if (!is_array($survey_answer_prefill) && !empty($survey_answer_prefill)) {
                $html .= "<input class='form-control {$que_id} ' type='textbox' name='{$que_id}[]' value='{$survey_answer_prefill}' class='{$que_id}'>";
            }
            // if interger then add class to validate
            else if (empty($submittedAns) && $advancetype == 'Integer') {
                $html .= "<input class='form-control {$que_id} numericField' type='textbox' name='{$que_id}[]' class='{$que_id}'>";
            }
            // if float then add class to validate
            else if (empty($submittedAns) && $advancetype == 'Float') {
                $html .= "<input class='form-control {$que_id} decimalField' type='textbox' name='{$que_id}[]' class='{$que_id}'>";
            }
            // default textbox
            else {
                $html .= "<input class='form-control {$que_id} ' type='textbox' name='{$que_id}[]' class='{$que_id}'>";
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'CommentTextbox':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            // if already submitted answer then prefill data
            if (!is_array($submittedAns) && !empty($submittedAns)) {
                // layout with given rows and columns
                if (!empty($min) || !empty($max)) {
                    $html .= "<textarea style='height:auto;width:auto;' class='form-control {$que_id}' rows='{$min}' cols='{$max}' name='{$que_id}[]'>{$submittedAns}</textarea>";
                }
                // default textarea
                else {
                    $html .= "<textarea class='form-control {$que_id}' rows='4' cols='20' name='{$que_id}[]'>{$submittedAns}</textarea>";
                }
            } else if (!is_array($survey_answer_prefill) && !empty($survey_answer_prefill)) {
                // layout with given rows and columns
                if (!empty($min) || !empty($max)) {
                    $html .= "<textarea style='height:auto;width:auto;' class='form-control {$que_id}' rows='{$min}' cols='{$max}' name='{$que_id}[]'>{$survey_answer_prefill}</textarea>";
            }
                // default textarea
                else {
                    $html .= "<textarea class='form-control {$que_id}' rows='4' cols='20' name='{$que_id}[]'>{$survey_answer_prefill}</textarea>";
                }
            }
            // not submitted answer
            else {
                // layout with given rows and columns
                if (!empty($min) || !empty($max)) {
                    $html .= "<textarea style='height:auto;width:auto;' class='form-control {$que_id}' rows='{$min}' cols='{$max}' name='{$que_id}[]'></textarea>";
                }
                // default textarea
                else {
                    $html .= "<textarea class='form-control {$que_id}' rows='4' cols='20' name='{$que_id}[]'></textarea>";
                }
            }
            $html .= "</li></ul></div>";
            return $html;
            break;
        case 'Rating':
            $html = "<div class='option select-list' id='{$que_id}_div'>";
            $html .= "<ul onMouseOut='resetRating(\"{$que_id}\")'>";
            // star count is given
            if (!empty($maxsize)) {
                $starCount = $maxsize;
            }
            //default 5 star
            else {
                $starCount = 5;
            }
            //generate star as per given star numbers
            for ($i = 1; $i <= $starCount; $i++) {
                if (!is_array($submittedAns) && !empty($submittedAns) && (int) $submittedAns >= $i) {
                    $selected = "highlight";
                } else {
                    $selected = "";
                }
                $html .= "<li class='rating {$selected}' style='display: inline;font-size: x-large' onmouseover='highlightStar(this,\"{$que_id}\");'  onmouseout='removeHighlight(\"{$que_id}\");' onclick='addRating(this,\"{$que_id}\")'>&#9733;</li>";
            }
            $html .= "</ul>";
            $html .= "</div>";
            $html .= "<input type='hidden'  name='{$que_id}[]' class='{$que_id}' id='{$que_id}_hidden' value='{$submittedAns}'>";
            return $html;
            break;
        case 'ContactInformation':
            $placeholder_name = 'Name';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Name'])) {
                $placeholder_name = $list_lang_detail[$que_id . '_placeholder_label_Name'];
            }
            $placeholder_email = 'Email Address';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Email Address'])) {
                $placeholder_email = $list_lang_detail[$que_id . '_placeholder_label_Email Address'];
            }
            $placeholder_phone = 'Phone Number';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Phone Number'])) {
                $placeholder_phone = $list_lang_detail[$que_id . '_placeholder_label_Phone Number'];
            }
            $placeholder_address = 'Address';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Address'])) {
                $placeholder_address = $list_lang_detail[$que_id . '_placeholder_label_Address'];
            }
            $placeholder_address2 = 'Address2';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Address2'])) {
                $placeholder_address2 = $list_lang_detail[$que_id . '_placeholder_label_Address2'];
            }
            $placeholder_city = 'City/Town';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_City/Town'])) {
                $placeholder_city = $list_lang_detail[$que_id . '_placeholder_label_City/Town'];
            }
            $placeholder_state = 'State/Province';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_State/Province'])) {
                $placeholder_state = $list_lang_detail[$que_id . '_placeholder_label_State/Province'];
            }
            $placeholder_zip = 'ZIP/Postal Code';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_ZIP/Postal Code'])) {
                $placeholder_zip = $list_lang_detail[$que_id . '_placeholder_label_ZIP/Postal Code'];
            }
            $placeholder_country = 'Country';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Country'])) {
                $placeholder_country = $list_lang_detail[$que_id . '_placeholder_label_Country'];
            }
            $placeholder_company = 'Company';
            if (!empty($list_lang_detail[$que_id . '_placeholder_label_Company'])) {
                $placeholder_company = $list_lang_detail[$que_id . '_placeholder_label_Company'];
            }

            $contactInfo = array();
            if (is_array($submittedAns) && count($submittedAns) > 0) {
                foreach ($submittedAns as $inx => $ans) {
                    if (!empty($ans)) {
                        $cnArr = explode(':', $ans);
                        $cnArr_index_0 = (!empty($cnArr[0])) ? $cnArr[0] : '';
                        $cnArr_index_1 = (!empty($cnArr[1])) ? $cnArr[1] : '';
                        $contactInfo[str_replace(" ", "", $cnArr_index_0)] = trim($cnArr_index_1);
                    }
                }
            }
            if ($is_required == 1 && empty($advancetype)) {
                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>";
                if ((count($submittedAns) > 0) && !empty($contactInfo['Name'])) {
                    $html .= "<li><input placeholder='{$placeholder_name} *' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]' value='{$contactInfo['Name']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_name} *' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['EmailAddress'])) {
                    $html .= "<li><input placeholder='{$placeholder_email} *'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]' value='{$contactInfo['EmailAddress']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_email} *'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Company'])) {
                    $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]' value='{$contactInfo['Company']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['PhoneNumber'])) {
                    $html .= "<li><input placeholder='{$placeholder_phone} *' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]' value='{$contactInfo['PhoneNumber']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_phone} *' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Address'])) {
                    $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id} {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]' value='{$contactInfo['Address']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id} {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Address2'])) {
                    $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]' value='{$contactInfo['Address2']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['City/Town'])) {
                    $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]' value='{$contactInfo['City/Town']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['State/Province'])) {
                    $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]' value='{$contactInfo['State/Province']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Zip/PostalCode'])) {
                    $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]' value='{$contactInfo['Zip/PostalCode']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Country'])) {
                    $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]' value='{$contactInfo['Country']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                }
                $html .= "</ul></div>";
            }
            // if required fields array is given then set html as per the same
            else if ($is_required == 1 && !empty($advancetype)) {
                $requireFields = explode(' ', $advancetype);
                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>";
                //if name field is required then set placeholder
                if (in_array($placeholder_name, $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['{$placeholder_name}'])) {
                        $html .= "<li><input placeholder='{$placeholder_name} *' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]' value='{$contactInfo['Name']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_name} *' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Name'])) {
                        $html .= "<li><input placeholder='{$placeholder_name} ' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]' value='{$contactInfo['Name']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_name} ' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                    }
                }
                //if email field is required then set placeholder
                if (in_array('Email', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['EmailAddress'])) {
                        $html .= "<li><input placeholder='{$placeholder_email} *'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]' value='{$contactInfo['EmailAddress']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_email} *'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['EmailAddress'])) {
                        $html .= "<li><input placeholder='{$placeholder_email} '  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]' value='{$contactInfo['EmailAddress']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_email} '  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                    }
                }
                //if company field is required then set placeholder
                if (in_array('Company', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Company'])) {
                        $html .= "<li><input placeholder='{$placeholder_company} *' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]' value='{$contactInfo['Company']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_company} *' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Company'])) {
                        $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]' value='{$contactInfo['Company']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id} {$que_id}_company'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                    }
                }
                //if phone field is required then set placeholder
                if (in_array('Phone', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['PhoneNumber'])) {
                        $html .= "<li><input placeholder='{$placeholder_phone} *' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]' value='{$contactInfo['PhoneNumber']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_phone} *' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['PhoneNumber'])) {
                        $html .= "<li><input placeholder='{$placeholder_phone} ' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]' value='{$contactInfo['PhoneNumber']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_phone} ' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>";
                    }
                }
                //if address field is required then set placeholder
                if (in_array('Address', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Address'])) {
                        $html .= "<li><input placeholder='{$placeholder_address} *' class='form-control {$que_id} {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]' value='{$contactInfo['Address']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_address} *' class='form-control {$que_id} {$que_id}_address'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Address'])) {
                        $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address]' value='{$contactInfo['Address']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                    }
                }
                //if address2 field is required then set placeholder
                if (in_array('Address2', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Address2'])) {
                        $html .= "<li><input placeholder='{$placeholder_address2} *'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]' value='{$contactInfo['Address2']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_address2} *'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Address2'])) {
                        $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]' value='{$contactInfo['Address2']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id} {$que_id}_address2'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                    }
                }
                //if city field is required then set placeholder
                if (in_array('City', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['City/Town'])) {
                        $html .= "<li><input placeholder='{$placeholder_city} *' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]' value='{$contactInfo['City/Town']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_city} *' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['City/Town'])) {
                        $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]' value='{$contactInfo['City/Town']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id} {$que_id}_city'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                    }
                }
                //if state field is required then set placeholder
                if (in_array('State', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['State/Province'])) {
                        $html .= "<li><input placeholder='{$placeholder_state} *' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]' value='{$contactInfo['State/Province']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_state} *' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['State/Province'])) {
                        $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]' value='{$contactInfo['State/Province']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id} {$que_id}_state'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                    }
                }
                //if zip field is required then set placeholder
                if (in_array('Zip', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Zip/PostalCode'])) {
                        $html .= "<li><input placeholder='{$placeholder_zip} *' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]' value='{$contactInfo['Zip/PostalCode']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_zip} *' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Zip/PostalCode'])) {
                        $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]' value='{$contactInfo['Zip/PostalCode']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id} {$que_id}_zip'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                    }
                }
                //if email field is required then set placeholder
                if (in_array('Country', $requireFields)) {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Country'])) {
                        $html .= "<li><input placeholder='{$placeholder_country} *' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]' value='{$contactInfo['Country']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_country} *' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                    }
                } else {
                    if ((count($submittedAns) > 0) && !empty($contactInfo['Country'])) {
                        $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]' value='{$contactInfo['Country']}'></li>";
                    } else {
                        $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id} {$que_id}_country'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                    }
                }
                $html .= "</ul></div>";
            } else {
                $html = "<div class='option input-list two-col' id='{$que_id}_div'><ul>";
                if ((count($submittedAns) > 0) && !empty($contactInfo['{$placeholder_name}'])) {
                    $html .= "<li><input placeholder='{$placeholder_name}' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]' value='{$contactInfo['Name']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_name}' class='form-control {$que_id} {$que_id}_name'  type='text' name='{$que_id}[{$que_id}][Name]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['EmailAddress'])) {
                    $html .= "<li><input placeholder='{$placeholder_email}'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]' value='{$contactInfo['EmailAddress']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_email}'  class='form-control {$que_id} {$que_id}_email'  type='text' name='{$que_id}[{$que_id}][Email Address]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Company'])) {
                    $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Company]' value='{$contactInfo['Company']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_company}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Company]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['PhoneNumber'])) {
                    $html .= "<li><input placeholder='{$placeholder_phone}' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]' value='{$contactInfo['PhoneNumber']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_phone}' class='form-control {$que_id} {$que_id}_phone'  type='text' name='{$que_id}[{$que_id}][Phone Number]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Address'])) {
                    $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Address]' value='{$contactInfo['Address']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_address}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Address]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Address2'])) {
                    $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Address2]' value='{$contactInfo['Address2']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_address2}'class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Address2]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['City/Town'])) {
                    $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][City/Town]' value='{$contactInfo['City/Town']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_city}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][City/Town]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['State/Province'])) {
                    $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][State/Province]' value='{$contactInfo['State/Province']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_state}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][State/Province]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Zip/PostalCode'])) {
                    $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]' value='{$contactInfo['Zip/PostalCode']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_zip}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Zip/Postal Code]'></li>";
                }
                if ((count($submittedAns) > 0) && !empty($contactInfo['Country'])) {
                    $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Country]' value='{$contactInfo['Country']}'></li>";
                } else {
                    $html .= "<li><input placeholder='{$placeholder_country}' class='form-control {$que_id}'  type='text' name='{$que_id}[{$que_id}][Country]'></li>";
                }
                $html .="</ul></div>";
            }
            return $html;
            break;
        case 'Date':
            $html = "<div class='option select-list two-col' id='{$que_id}_div'><ul><li>";
            // already submitted answer
            if (!is_array($submittedAns) && !empty($submittedAns)) {
                // if is date and time
                if ($is_datetime == 1) {
                    $html .= "<input class='form-control setdatetime {$que_id}_datetime' value='{$submittedAns}' type='text' name='{$que_id}[]' class='{$que_id}'>";
                }
                // only date
                else {
                    $html .= "<input class='form-control setdate {$que_id}_datetime' type='text' value='{$submittedAns}' name='{$que_id}[]' class='{$que_id}'>";
                }
            }
            // not submitted answer
            else {
                // if is date and time
                if ($is_datetime == 1) {
                    $html .= "<input class='form-control setdatetime {$que_id}_datetime' type='text' name='{$que_id}[]' class='{$que_id}'>";
                }
                // only date
                else {
                    $html .= "<input class='form-control setdate {$que_id}_datetime' type='text' name='{$que_id}[]' class='{$que_id}'>";
                }
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
            if (!is_array($submittedAns) && !empty($submittedAns)) {
                $selected = $submittedAns;
            } else {
                $selected = "";
            }
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
            $html .= "<input type='hidden'  name='{$que_id}[]' class='{$que_id}_scale' id='{$que_id}_hidden' value='{$submittedAns[0]}'>";
            $html .= "</div>";
            return $html;
            break;
        case 'Matrix':
            $submittedAnsDetail = array();
            foreach ($submittedAns[$que_title] as $k => $AnsDetail) {
                foreach ($AnsDetail as $key => $SubAnsDetail) {
                    foreach ($SubAnsDetail as $i => $Ans) {
                        $submittedAnsDetail[$i] = $Ans;
                    }
                }
            }
            // display selection type for matrix
            $display_type = $advancetype == 'Checkbox' ? 'checkbox' : 'radio';
            $rows = array();
            $rows = json_decode($matrix_row);
            $cols = json_decode($matrix_col);

            // Initialize counter - count number of rows & columns
            $row_count = 1;
            $col_count = 1;
            if (is_array($submittedAnsDetail)) {
                // foreach ($matrix as $key => $data) {
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
                                $row_header = $list_lang_detail[$que_id . '_row' . $row];
                            } else {
                                $row_header = $rows->$row;
                            }
                            $html .= "<th class='matrix-span {$que_id}_matrix' value='{$row}' style='font-weight:bold; width:" . $width . ";text-align:left;'>" . $row_header . "</th>";
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
                                // $html .= "<td class='matrix-span' style='width:" . $width . "; '>";
                                $current_value = $row . '_' . $col;
                                if (in_array($current_value, $submittedAnsDetail)) {
                                    if ($display_type == 'checkbox') {
                                        $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-checkbox'><input checked type='" . $display_type . "' id='{$que_id}_{$op}'  value='{$row}_{$col}' class='{$que_id} md-check' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></td>";
                                    } else {
                                        $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-radio'><input checked type='" . $display_type . "' id='{$que_id}_{$op}' class='{$que_id} md-radio' value='{$row}_{$col}' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></td>";
                                    }
                                } else {
                                    if ($display_type == 'checkbox') {
                                        $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-checkbox'><input type='" . $display_type . "' id='{$que_id}_{$op}'  value='{$row}_{$col}' class='{$que_id} md-check' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></td>";
                                    } else {
                                        $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-radio'><input type='" . $display_type . "' id='{$que_id}_{$op}' class='{$que_id} md-radio' value='{$row}_{$col}' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></td>";
                                    }
                                }
                                //   $html .= "</td>";
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
                //   }
            } else {
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
                            $html .= "<th class='matrix-span {$que_id}_matrix' value='{$row}' style='font-weight:bold; width:" . $width . ";text-align:left;'>" . $rows->$row . "</th>";
                        } else {
                            //Columns label
                            if ($j <= ($col_count + 1) && $cols->$col != null && !($j == 1 && $i == 1) && ($i == 1 || $j == 1)) {
                                $html .= "<th class='matrix-span' style='font-weight:bold; width:" . $width . "'>" . $cols->$col . "</th>";
                            }
                            //Display answer input (RadioButton or Checkbox)
                            else if ($j != 1 && $i != 1 && $cols->$col != null) {
                                if ($display_type == 'checkbox') {
                                    $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-checkbox'><div class='matrix-span' style='width:" . $width . "; '><input type='" . $display_type . "'  value='{$row}_{$col}' id='{$que_id}_{$op}' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></div></td>";
                                } else {
                                    $html .= "<td class='matrix-span' style='width:" . $width . "; '><span class='md-radio'><div class='matrix-span' style='width:" . $width . "; '><input type='" . $display_type . "' id='{$que_id}_{$op}' value='{$row}_{$col}' name='{$que_id}[{$row}][]'/><label for='{$que_id}_{$op}'>
                                                            <span></span>
                                                            <span class='check'></span>
                                                            <span class='box'></span></label></span></div></td>";
                                }
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
            }
            $html .= "</table></div>";
            return $html;
            break;
    }
}

$redirect_flag = false;
// Insert in Submission Data Module
if (isset($_REQUEST['btnsend']) && $_REQUEST['btnsend'] != '') {

    // Check Submit button is already clicked or not for preventing duplicate entry
    // $btn_submit_click_flag = $_POST['btn_submit_click_flag'];
    if (empty($btn_submit_click_flag)) {
        $btn_submit_click_flag = "submitted";

        if (($submission_status == 'Submitted') && !($requestApproved) && ($userSbmtCount >= $reSubmitCount)) {
            if ($survey_type == 'poll') {
                $msg1 = "<div class='success_msg'>You have already submitted this Survey.</div>";
            } else {
                $msg1 = "<div class='success_msg'>You have already submitted this Survey. {$resubmit_request_msg}</div>";
            }
        } elseif (($submission_status == 'Pending') && !empty($oStart_date) && !empty($oEnd_date) && ((strtotime($current_date) < strtotime($survey_start_date)))) {
            $msg1 = "<div class='failure_msg'>This survey has not started yet, Please try after {$startDateTime}.</div>";
        } elseif (($submission_status == 'Pending') && !empty($oStart_date) && !empty($oEnd_date) && (strtotime($current_date) > strtotime($survey_end_date))) {
            $msg1 = "<div class='failure_msg'>Sorry... This survey expired on {$endDateTime}.</div>";
        } elseif (!($requestApproved) && ($userSbmtCount >= $reSubmitCount)) {
            if ($survey_type == 'poll') {
                $msg1 = "<div class='success_msg'>You have already submitted this Survey.</div>";
            } else {
                $msg1 = "<div class='success_msg'>You have already submitted this Survey. {$resubmit_request_msg}</div>";
            }
        } else {
            /* $query = "select distinct bc_survey_answers.id as answers_res from bc_survey_answers
              inner join bc_submission_data_bc_survey_answers_c as data_ans_rel
              on data_ans_rel.bc_submission_data_bc_survey_answersbc_survey_answers_ida = bc_survey_answers.id
              and data_ans_rel.deleted = 0
              inner join bc_submission_data_bc_survey_answers_c as sub_ans_rel
              on sub_ans_rel.bc_submission_data_bc_survey_answersbc_survey_answers_ida = bc_survey_answers.id
              and sub_ans_rel.deleted = 0
              inner join bc_submission_data_bc_survey_submission_c as data_sub_rel
              on data_sub_rel.bc_submission_data_bc_survey_submissionbc_submission_data_idb = data_sub_rel.bc_submission_data_bc_survey_submissionbc_submission_data_idb
              and data_sub_rel.deleted = 0
              inner join bc_survey_submission as submission
              on submission.id = data_sub_rel.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
              and submission.deleted = 0
              inner join bc_survey_submission_bc_survey_c as sub_surv_join
              on sub_surv_join.bc_survey_submission_bc_surveybc_survey_submission_idb = submission.id
              and sub_surv_join.deleted = 0
              inner join bc_survey
              on bc_survey.id = sub_surv_join.bc_survey_submission_bc_surveybc_survey_ida
              where bc_survey.id = '{$survey_id}'
              and submission.module_id = '{$_REQUEST['cid']}'";

              $resultAns = $db->query($query);
              $answersRes = "";
              while ($submission_row = $db->fetchByAssoc($resultAns)) {
              $answersRes[] = $submission_row['answers_res'];
              }
              $commAns = "'" . implode("','", $answersRes) . "'";
             */
            $ignoreQry = "SELECT que_ans_rel.bc_survey_answers_bc_survey_questionsbc_survey_answers_idb as ans_id
                            FROM bc_survey_answers_bc_survey_questions_c as que_ans_rel
                            INNER JOIN bc_survey_questions
                            ON bc_survey_questions.id = que_ans_rel.bc_survey_answers_bc_survey_questionsbc_survey_questions_ida
                            and que_ans_rel.deleted = 0
                            INNER JOIN bc_survey_bc_survey_questions_c as que_surv_rel
                            ON que_surv_rel.bc_survey_bc_survey_questionsbc_survey_questions_idb = bc_survey_questions.id
                            and bc_survey_questions.deleted = 0
                            INNER JOIN bc_survey
                            ON bc_survey.id = que_surv_rel.bc_survey_bc_survey_questionsbc_survey_ida
                            WHERE bc_survey.deleted = 0
                            AND bc_survey.id = '{$survey_id}'";
            $resultIgAns = $db->query($ignoreQry);
            $IganswersRes = "";
            while ($submissionIG_row = $db->fetchByAssoc($resultIgAns)) {
                $IganswersRes[] = $submissionIG_row['ans_id'];
            }
            $commIGAns = "'" . implode("','", $IganswersRes) . "'";

            $delQry = "UPDATE bc_survey_answers SET DELETED = 1 WHERE bc_survey_answers.id IN ({$deleteAnsIdsOnResubmit}) and bc_survey_answers.id not IN ($commIGAns) ";
            $delRes = $db->query($delQry);


            $subQry = "SELECT sub_data.id as submi_id FROM bc_submission_data as sub_data
                    INNER JOIN bc_submission_data_bc_survey_submission_c as data_sub_rel
                    ON data_sub_rel.bc_submission_data_bc_survey_submissionbc_submission_data_idb = sub_data.id
                    INNER JOIN bc_survey_submission
                    ON bc_survey_submission.id = data_sub_rel.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
                    AND data_sub_rel.deleted = 0
                    WHERE sub_data.deleted = 0 and bc_survey_submission.id= '{$submisstion_id}'";
            $subQryRes = $db->query($subQry);
            while ($subQryRes_row = $db->fetchByAssoc($subQryRes)) {
                $submi_id = $subQryRes_row['submi_id'];
                $db->query("UPDATE bc_submission_data_bc_survey_answers_c set deleted = 1 WHERE bc_submission_data_bc_survey_answersbc_submission_data_idb = '{$submi_id}'");
                $db->query("UPDATE bc_submission_data_bc_survey_questions_c set deleted = 1 WHERE bc_submission_data_bc_survey_questionsbc_submission_data_idb = '{$submi_id}'");
                $db->query("UPDATE bc_submission_data_bc_survey_submission_c set deleted = 1 WHERE bc_submission_data_bc_survey_submissionbc_submission_data_idb = '{$submi_id}'");
            }
            //delete from answers_calculation table only first time goes to submitSurveyResponseCalulation function
            $delete_flag = 0;
            $manage_que_type = array(); // manage flag for same type of question to delete resubmit time old data
            $obtained_score = 0;
            $showedQuestions = explode(',', $_POST['show_question_list']);
            foreach ($_REQUEST['questions'] as $submitted_que) {
                if (in_array($submitted_que, $showedQuestions)) {
                    $question_obj = new bc_survey_questions();
                    $question_obj->retrieve($submitted_que);
                    $submitted_ans = $_REQUEST[$submitted_que];

                    if (in_array($question_obj->question_type, $manage_que_type)) {
                        $delete_flag++;
                    } else {
                        $delete_flag = 0;
                    }
                    // Update and Insert answer on each submission.
                    if ($isOpenSurveyLink) {
                        $survey_receiverID = $survey_submission->customer_name;
                    } else {
                        $survey_receiverID = $module_id;
                    }
                    submitSurveyResponseCalulation($submitted_ans, $survey_id, $survey_receiverID, $question_obj->question_type, $submisstion_id, $delete_flag, $submitted_que, 0);

                    $manage_que_type[] = $question_obj->question_type;
                    // End
                    $isOtherSelected = false;
                    // check Other option is selected or not
                    $allAnswersBean = $question_obj->get_linked_beans('bc_survey_answers_bc_survey_questions', 'bc_survey_answers');
                    foreach ($allAnswersBean as $allAns) {
                        if ($allAns->ans_type == 'other' && in_array($allAns->id, $submitted_ans)) {
                            $isOtherSelected = true;
                        }
                    }
                    foreach ($submitted_ans as $sub_ans) {
                        if ($sub_ans != "selection_default_value_dropdown") {
                            $submission_data = new bc_submission_data();
                            $submission_data->save();
                            $submission_data->load_relationship('bc_submission_data_bc_survey_submission');
                            $submission_data->bc_submission_data_bc_survey_submission->add($survey_submission->id);

                            $submission_data->load_relationship('bc_submission_data_bc_survey_questions');
                            $submission_data->bc_submission_data_bc_survey_questions->add($submitted_que);


                            $pattern = "/^[a-z\d]{8}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{4}-[a-z\d]{12}+$/i";
                            if (preg_match($pattern, $sub_ans)) {
                                $submitted_weight = 0;
                                // Create new answer for other option
                                $submitted_ans_obj = new bc_survey_answers();
                                $submitted_ans_obj->retrieve($sub_ans);
                                if (empty($submitted_ans_obj->id) && $isOtherSelected == true) {
                                    $submitted_ans_obj = new bc_survey_answers();
                                    $submitted_ans_obj->name = $sub_ans;
                                    $submitted_ans_obj->save();
                                    $sub_ans = $submitted_ans_obj->id;
                                }
                                $submission_data->load_relationship('bc_submission_data_bc_survey_answers');
                                $submission_data->bc_submission_data_bc_survey_answers->add($sub_ans);
                                // if scoring is enabled than calculate each answer weight
                                $submitted_que_obj = new bc_survey_questions();
                                $submitted_que_obj->retrieve($submitted_que);

                                $submitted_ans_obj = new bc_survey_answers();
                                $submitted_ans_obj->retrieve($sub_ans);

                            if (array_key_exists($submitted_que, $survey_answer_prefill)) {
                                $answer_to_update[$survey_answer_update_module_field_name[$submitted_que]] = $submitted_ans_obj->name;
                            }

                                if ($submitted_que_obj->enable_scoring == 1) {
                                    $submitted_weight = $submitted_weight + number_format($submitted_ans_obj->score_weight);
                                }
                                //calculte obtained score
                                $obtained_score = $obtained_score + $submitted_weight;
                            } else {
                                if ($question_obj->question_type == 'ContactInformation') {
                                    $submitted_ans_obj = new bc_survey_answers();
                                    $submitted_ans_obj->name = json_encode($sub_ans, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
                                    $submitted_ans_obj->save();
                                    $submission_data->load_relationship('bc_submission_data_bc_survey_answers');
                                    $submission_data->bc_submission_data_bc_survey_answers->add($submitted_ans_obj->id);
                                } else {
                                    $submitted_ans_obj = new bc_survey_answers();
                                    if (is_array($sub_ans)) {
                                        foreach ($sub_ans as $value) {
                                            $ansFinal = $value;
                                            $submitted_ans_obj = new bc_survey_answers();
                                            $submitted_ans_obj->name = $ansFinal;
                                            $submitted_ans_obj->save();
                                            $submission_data->load_relationship('bc_submission_data_bc_survey_answers');
                                            $submission_data->bc_submission_data_bc_survey_answers->add($submitted_ans_obj->id);

                                        if (array_key_exists($submitted_que, $survey_answer_prefill)) {
                                            $answer_to_update[$survey_answer_update_module_field_name[$submitted_que]] = $submitted_ans_obj->name;
                                        }
                                    }
                                    } else {
                                        $submitted_ans_obj = new bc_survey_answers();
                                        $submitted_ans_obj->name = $sub_ans;
                                        $submitted_ans_obj->save();
                                        $submission_data->load_relationship('bc_submission_data_bc_survey_answers');
                                        $submission_data->bc_submission_data_bc_survey_answers->add($submitted_ans_obj->id);

                                    if (array_key_exists($submitted_que, $survey_answer_prefill)) {
                                        $answer_to_update[$survey_answer_update_module_field_name[$submitted_que]] = $submitted_ans_obj->name;
                                    }
                                }
                            }
                        }
                    }
                }
                    unset($submitted_ans);
                } else {
                    // delete hidden question answer from answer calculation
                    $submitted_ans = $_REQUEST[$submitted_que];
                    $question_obj = new bc_survey_questions();
                    $question_obj->retrieve($submitted_que);

                if (array_key_exists($submitted_que, $survey_answer_prefill)) {
                    $answer_to_update[$survey_answer_update_module_field_name[$submitted_que]] = $submitted_ans;
                }

                    // Update and Insert answer on each submission.
                    submitSurveyResponseCalulation($submitted_ans, $survey_id, $module_id, $question_obj->question_type, $submisstion_id, 0, $submitted_que, 1);
                }
            }
        $GLOBALS['log']->fatal("This is the answer to update : " . print_r($answer_to_update, 1));
        if (!empty($answer_to_update) && $enable_data_piping == 1 && $sync_type == 'create_update' && !empty($module_type) && !empty($module_id)) {
            // update record
            if (is_array($answer_to_update)) {
                foreach ($answer_to_update as $field_name => $field_value) {
                    $moduleBeanObj->$field_name = $field_value;
                }
                $moduleBeanObj->save();
            }
        }
        else if (!empty($answer_to_update) && $enable_data_piping == 1 && $sync_type == 'create' && !empty($sync_module) && !$requestApproved) {
            // create record
            if (is_array($answer_to_update)) {
                $moduleBeanObjNew = BeanFactory::getBean($sync_module);
               // $moduleBeanObjNew->disable_row_level_security = true;
                foreach ($answer_to_update as $field_name => $field_value) {
                    $moduleBeanObjNew->$field_name = $field_value;
                }
                $moduleBeanObjNew->save();
                $survey_submission->new_record_id = $moduleBeanObjNew->id;
            }
        }
        else if (!empty($answer_to_update) && $enable_data_piping == 1 && $sync_type == 'create' && !empty($sync_module) && $requestApproved) {
            // update record
            if (is_array($answer_to_update)) {
                $moduleBeanObjNew = BeanFactory::getBean($sync_module);
                $moduleBeanObjNew->retrieve($survey_submission->new_record_id);

                foreach ($answer_to_update as $field_name => $field_value) {
                    $moduleBeanObjNew->$field_name = $field_value;
                }
                $moduleBeanObjNew->save();
                $survey_submission->new_record_id = $moduleBeanObjNew->id;
            }
        } else if(!empty($answer_to_update) && $enable_data_piping == 1 && !empty($sync_module)){

            // create record
            if (is_array($answer_to_update)) {
                $moduleBeanObjNew = BeanFactory::getBean($sync_module);
            //    $moduleBeanObjNew->disable_row_level_security = true;
                foreach ($answer_to_update as $field_name => $field_value) {
                    $moduleBeanObjNew->$field_name = $field_value;
                }
                $moduleBeanObjNew->save();
                $survey_submission->new_record_id = $moduleBeanObjNew->id;
            }
        }
            if (!empty($survey->thanks_page)) {
                $isSumitted = true;
                if (!empty($list_lang_detail['thanks_page'])) {
                    $thanks_content = base64_decode($list_lang_detail['thanks_page']);
                } else {
                    $thanks_content = $survey->thanks_page;
                }
                $msg .= '<div class="container">
                            <div class="survey-form form-desc">';
                $msg .= '     <div class="form-body thanks-page" style="margin-top:20px; margin-bottom:20px;">' . html_entity_decode($thanks_content) . '</div>';
                $msg .= '   </div>
                          </div>';
            } else if (strlen($_REQUEST['q']) == 6) {
                $msg = " <div class='success_msg'>Your Response has been submitted successfully.</div>";
            } else {
                $msg = " <div class='success_msg'>Your Response has been submitted successfully and summary email send to your email address.</div>";
            }
            $redirect_flag = true;
            if ($_REQUEST['redirect_action'] != '') {
                $redirect_url = $_REQUEST['redirect_action'];
            }
            $resubmit_counter = ((int) $survey_submission->resubmit_counter) + 1;
            $gmtdatetime = TimeDate::getInstance()->nowDb();
            // Update Record in Survey Submission Module
            if ($survey->allow_redundant_answers != 1 && !$isOpenSurveyLink) {
                $survey_submission->resubmit = 0;
                $survey_submission->resend = 0;
            } else if (!$isOpenSurveyLink) {
                $survey_submission->resubmit = 0;
                $survey_submission->resend = 0;
            }

            $survey_submission->resubmit_counter = $resubmit_counter;
            $survey_submission->status = 'Submitted';
            $survey_submission->submitted_by = $submitted_by;
            $survey_submission->submission_date = $gmtdatetime;
            $survey_submission->submitted_survey_language = $selected_lang;

            //update obtained score
            $survey_submission->obtain_score = $obtained_score;
            $base_score = $survey_submission->base_score;
            $obtScorePer = $obtained_score * 100 / $base_score;

            if (empty($obtScorePer)) {
                $obtained_perc = 0;
            } else {
                $obtained_perc = $obtScorePer;
            }
            $survey_submission->score_percentage = $obtained_perc;

            $survey_submission->save();
            // Send Thanks mail to Customer
            if (!$isOpenSurveyLink) {
                $q = "SELECT ea.email_address FROM email_addresses ea
               LEFT JOIN email_addr_bean_rel ear ON ea.id = ear.email_address_id
               WHERE ear.bean_module = '" . $module_type . "'
               AND ear.bean_id = '" . $module_id . "'
               AND ear.deleted = 0
               AND ea.invalid_email = 0
               ORDER BY ear.primary_address DESC";
                $r = $db->limitQuery($q, 0, 1);
                $a = $db->fetchByAssoc($r);

                if (isset($a['email_address'])) {
                    $email_address = $a['email_address'];
                }

                $name = $survey_submission->customer_name;
                require_once 'custom/include/utilsfunction.php';
                $subject = "Reviewed your Survey for Improved Service";
                $survey_data = array();
                $survey_data = getPerson_SubmissionData($survey_id, $module_id, $module_type);
                // if recipient submitted the survey then mail content will be as following
                $list_lang_detail = return_app_list_strings_language($selected_lang)[$survey_id];

                if ($submitted_by == 'recipient') {
                    $html = "Dear {$name},<br><br>
             Thank you for taking time to reviewing and submitting the ". ucfirst($survey->survey_type) ." with your valuable views and opinions.<br>

            We’ve taken into account your concerns submitted with this ". ucfirst($survey->survey_type) .".<br>

            This will help us serve you better in future! <br><br>

            Thank you once again for your time and efforts!<br>";
                }
                // if sender submitted the survey then mail content will be as following
                else {
                    $html = "Dear {$name},<br><br>
                     Admin has successfully submitted the " . ucfirst($survey->survey_type) . " on behalf of you.<br>

                    We’ve taken into account your concerns submitted with this " . ucfirst($survey->survey_type) . ".<br>

                    This will help us serve you better in future! <br><br>

                    Thank you once again for your time and efforts!<br>";
                }
                $html .= "<br><body style='padding:0px; margin:0px; background:#fdfdfd;'>
                <table style='background:#fff;' border='0' cellpadding='0' cellspacing='0' width='100%'>
                  <tbody>
                    <tr>
                      <td><center>
                          <table style='padding:0px 0px; margin:0; font-family:Calibri, Arial;' border='0' cellpadding='0' cellspacing='0' width='840'>
                            <tr>
                                <td>
                                    <table width='840' cellspacing='0' cellpadding='0' border='0' bgcolor='#d4a633' style='padding: 0px; margin: 0px; font-family: Calibri,Arial; background-color: #5491d5;'>
                                        <tr>
                                            <td height='30'>&nbsp;</td>
                                        </tr>
                                        <tr>";
                                            if(!empty($list_lang_detail[$survey_id])){
                                                $html .= "<td valign='middle' align='center' style='padding:0px 0 38px;'><span style='color: #ffffff; font-family: Calibri,Arial;  font-size: 30px;  line-height: 34px; '>{$list_lang_detail[$survey_id]}</span></td>";
                                            }else{
                                                $html .= "<td valign='middle' align='center' style='padding:0px 0 38px;'><span style='color: #ffffff; font-family: Calibri,Arial;  font-size: 30px;  line-height: 34px; '>{$survey->name}</span></td>";
                                            }
                                       $html .=  "</tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                              <td>
                                <table width='838' cellspacing='0' cellpadding='0' border='0' bgcolor='#fff' style='padding: 0px; margin: 0px; font-family: Calibri,Arial;background-color: #fcfcfc; border-right:1px solid #ccc; border-left:1px solid #ccc;'>";


                $GLOBALS['log']->fatal("This is the submitted data : " . print_r($survey_data, 1));
                foreach ($survey_data as $que_id => $que_title) {
                    if (in_array($que_id, $showedQuestions)) {
                        $matrix_answer_array = array();
                        foreach ($que_title as $title => $answers) {
                            $is_matrix = false;
                            // Initialize counter - count number of rows & columns
                            $row_count = 1;
                            $col_count = 1;
                            $rows = $answers['matrix_rows'];
                            $cols = $answers['matrix_cols'];
                        if ((!empty($list_lang_detail[$que_id . '_row1']))) {
                            foreach ($rows as $key => $row) {
                                $rows->$key = $list_lang_detail[$que_id . '_row' . $key];

                            }

                            foreach ($cols as $key => $col) {
                                $cols->$key = $list_lang_detail[$que_id . '_col' . $key];
                            }
                        }
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

                        // Question title Language wise
                        $que_title = (!empty($list_lang_detail[$que_id . '_quetitle'])) ? $list_lang_detail[$que_id . '_quetitle'] : $title;
                            $html .= "<tr>
                      <td valign='top' align='left' style='padding:20px 19px 10px;'>
                        <table width='800' cellspacing='0' cellpadding='0' border='1' bgcolor='#fff' style='padding: 0px; margin: 0px; font-family: Calibri,Arial; background-color: #f7fafe; border:1px solid #e5e5e5; border-collapse:collapse; '>
                            <tbody>
                                <tr>
                                  <td width='110' style='margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #ececec;' ><strong>Question:</strong></td>
                                  <td style='margin:0 0 8px 0; padding:5px; font-size:14px; color:#808080; border:1px solid #e5e5e5;background-color: #ececec;'>{$que_title}</td>
                                </tr>";
                            foreach ($answers as $key => $answer) {
                                if ($key != 'matrix_rows' && $key != 'matrix_cols' && $key != 'answer_detail') {
                                    $ans_count = count($answer);
                                    if (is_array($answer)) {
                                        $html .= "<td width='110' style='margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #ececec;background-color: #f3f3f3;' ><strong>Answer:</strong></td>
                              <td colspan='{$ans_count}' style='margin:0 0 8px 0; padding:5px; font-size:14px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;'>
                                <table width='667' cellspacing='0' cellpadding='0' border='1' bgcolor='#fff' style='padding: 0px; margin: 0px; font-family: Calibri,Arial; background-color: #f7fafe; border:1px solid #e5e5e5; border-collapse:collapse; '>
                                    <tbody>";
                                        foreach ($answer as $ans_label => $ans) {
                                            $oAnswers = BeanFactory::getBean('bc_survey_answers', $key);
                                            if ($oAnswers->ans_type != 'other') {
                                                $ans = (!empty($list_lang_detail[$key])) ? $list_lang_detail[$key] : $ans;
                                                $submitted_ans = $ans != '' ? $ans : 'N/A';
                                                $html .= "<tr>
                                        <td width='150' style='margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;' ><strong>{$ans_label}</strong></td>
                                        <td style='margin:0 0 8px 0; padding:5px; font-size:14px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;'>{$submitted_ans}</td>
                                      </tr>";
                                            }
                                        }
                                        $html .= "</tbody>
                                    </table>
                                </td>";
                                    } else {
                                        $oAnswers = BeanFactory::getBean('bc_survey_answers', $key);
                                        if ($oAnswers->ans_type != 'other') {
                                            $answer = (!empty($list_lang_detail[$key])) ? $list_lang_detail[$key] : $answer;
                                            $submitted_answer = $answer != '' ? nl2br($answer) : 'N/A';
                                            $html .= "<tr>
                                <td colspan='{$ans_count}' width='110' style='margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;' ><strong>Answer:</strong></td>
                                <td style='margin:0 0 8px 0; padding:5px; font-size:14px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;'>{$submitted_answer}</td>
                              </tr>";
                                        }
                                    }
                                } else if ($key == 'answer_detail') {
                                    $is_matrix = true;
                                    $matrix_answer_array[$que_id] = array();
                                    foreach ($answer as $ans_label => $ans_matrix) {
                                        foreach ($ans_matrix as $ans_cnt => $aval) {
                                            array_push($matrix_answer_array[$que_id], $aval);
                                        }
                                    }
                                }
                            }
                            if ($is_matrix) {
                                $matrix_html .= '<td width="110" style="margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #ececec;"><strong>Answer : </td><td></strong><table >';
                                for ($i = 1; $i <= $row_count; $i++) {
                                    $matrix_html .= '<tr>';
                                    for ($j = 1; $j <= $col_count + 1; $j++) {
                                        $row = $i - 1;
                                        $col = $j - 1;
                                        //First row & first column as blank
                                        if ($j == 1 && $i == 1) {
                                            $matrix_html .= "<td class='matrix-span' style='width:" . $width . ";padding:5px;'>&nbsp;</td>";
                                        }
                                        // Rows Label
                                        else if ($j == 1 && $i != 1) {
                                            $matrix_html .= "<td class='matrix-span {$que_id}' value='{$row}' style='font-weight:bold;color: #808080; width:" . $width . ";text-align:left;padding:5px;'>" . $rows->$row . "</td>";
                                        } else {
                                            //Columns label
                                            if ($j <= ($col_count + 1) && $cols->$col != null && !($j == 1 && $i == 1) && ($i == 1 || $j == 1)) {
                                                $matrix_html .= "<td class='matrix-span' style='font-weight:bold;color: #808080; width:" . $width . ";padding:5px;'>" . $cols->$col . "</td>";
                                            }
                                            //Display answer input (RadioButton or Checkbox)
                                            else if ($j != 1 && $i != 1 && $cols->$col != null) {
                                                $matrix_html .= "<td class='matrix-span' style='width:" . $width . ";padding:5px; '>";
                                                $current_value = $row . '_' . $col;
                                                if (in_array($current_value, $matrix_answer_array[$que_id])) {
                                                    $matrix_html .= "<input type='radio' checked disabled>";
                                                } else {
                                                    $matrix_html .= "<input type='radio' disabled>";
                                                }
                                                $matrix_html .= "</td>";
                                            }
                                            // If no value then display none
                                            else {
                                                $matrix_html .= "";
                                            }
                                        }
                                    }
                                    $matrix_html .= "</tr>";
                                }

                                $matrix_html .= "</table></td>";
                                $html .= $matrix_html;

                                $matrix_html = '';
                            }
                            $html .= " </tbody>
                        </table>
                      </td>
                    </tr>";
                        }
                    }
                }
                // survey url encoded
                $survey_url = $sugar_config['site_url'] . '/survey_submission.php?survey_id=';
                $sugar_survey_Url = $survey_url; //create survey submission url
                $encoded_param = base64_encode($survey_id . '&ctype=' . $module_type . '&cid=' . $module_id);
                $sugar_survey_Url = str_replace('survey_id=', 'q=', $sugar_survey_Url);
                $surveyURL = $sugar_survey_Url . $encoded_param;

                // $survey_url = $sugar_config['site_url'] . '/survey_re_submit_request.php?survey_id=' . $survey_id . '&ctype=' . $module_type . '&cid=' . $module_id;
                $body = "{$html}.
            <tr><td style='text-align: center;margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #f3f3f3;' >Note: Admin allow you {$reSubmitCount} time to submit your survey. To edit your submitted response for survey  <a href='{$surveyURL}'>Click here....</a></td></tr>
            <tr><td height='20'>&nbsp;</td></tr>
                  </table>
              </td>
            </tr>
            <tr>
              <td>
                <table width='840' cellspacing='0' cellpadding='0' border='0' bgcolor='#f0c44a' style='padding: 0px; margin: 0px; font-family: Calibri,Arial; background-color: #5491d5;'>
                    <tr>
                    <td height='10'>&nbsp;</td>
                  </tr>
                  <tr>
                    <td align='center' valign='middle' style='color:#79685c;'>
                      <strong><p style='margin:0; padding:0; font-size:18px; color:#fff;'>Thank You</p></strong>
                    </td>
                  </tr>
                  <tr>
                    <td height='10'>&nbsp;</td>
                  </tr>
                </table>
              </td>
            </tr>

                  </table>
                </center></td>
            </tr>
          </tbody>
        </table>
        </body>";

                CustomSendEmail($email_address, $subject, $body, $module_id, $module_type, $to_email = 'to');
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?php echo $favicon; ?>" type="image/x-icon">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <?php if ($survey->survey_type == 'poll') { ?>
            <title>Poll</title>
        <?php } else { ?>
        <title>Survey</title>
        <?php } ?>
        <script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
        <script src="custom/include/js/survey_js/jquery.datetimepicker.js"></script>
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/jquery.datetimepicker.css">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/custom-form.css' ?>" rel="stylesheet">
        <link href="<?php echo $sugar_config['site_url'] . '/custom/include/css/survey_css/survey-form.css' ?>" rel="stylesheet">
        <link href="<?php
        $survey->theme = (!empty($survey->theme)) ? $survey->theme : 'theme1';
        echo $sugar_config['site_url'] . '/custom/include/css/survey_css/' . $survey->theme . '.css';
        ?>" rel="stylesheet">
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
            jQuery(document).ready(function (el) {

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
                $.ajax({
                    url: "index.php?entryPoint=preview_survey",
                    type: "POST",
                    data: {'method': 'get_survey', 'record_id': '<?php echo $survey_id; ?>', 'cid': '<?php echo $module_id; ?>', 'selected_lang': $('#selected_lang').val()},
                    success: function (result) {
                        survey_detail = JSON.parse(result);
                        var data_detail = survey_detail['survey_details'];
                        var lang_detail = survey_detail['lang_survey_details'];
                        var slider_detail = new Object();
                        $.each(data_detail, function (pindex, page_data) {
                            $.each(page_data, function (qindex, que_data) {
                                if (qindex == 'page_questions') {
                                    $.each(que_data, function (qi, q_data) {
                                        if (q_data['que_type'] == 'Scale')
                                        {
                                            var detail = new Object();
                                            // if min-max-slot value is not set then set default value
                                            if (!q_data['min'] && !q_data['max']) {
                                                detail['min'] = 0;
                                                detail['max'] = 10;
                                                detail['scale_slot'] = 1;
                                            } else {
                                                detail['min'] = q_data['min'];
                                                detail['max'] = q_data['max'];
                                                detail['scale_slot'] = q_data['scale_slot'];
                                            }
                                            detail['answer'] = q_data['answers'];
                                            slider_detail[q_data['que_id']] = detail;
                                        }
                                    });
                                }
                            });
                        });
                        //bind next prev button click function
                        var prev_slide;
                        $(".bx-next,#btnsend").click(function () {

                            // set submit type if not set for submission
                            if ($(this).attr('name') == 'btnsend')
                            {
                                $('#btnsend').prop('type', 'submit');
                            }
                            var obj =<?php echo json_encode($skip_logicArrForAll); ?>;
                            var flag = 0;
                            var selected_answer_ids = new Array();
                            var multi_select_ansid = new Array();
                            var question_wise_data = new Object();
                            //getting selected option ids
                            $.each($('.active-slide').find('.survey-form').find('div.form-body'), function () {
                                var question_type = $(this).attr('class').split(' ')[1];
                                var question_id = $(this).find('.questionHiddenField').val();
                                question_wise_data[question_id] = new Object();
                                if (question_type == 'DrodownList') {
                                    if ($(this).find('option:selected').val() != "selection_default_value_dropdown") {
                                        selected_answer_ids.push($(this).find('option:selected').val());
                                    }
                                } else if (question_type == "RadioButton") {
                                    selected_answer_ids.push($(this).find('input[type=radio]:checked').val());
                                } else if (question_type == "Checkbox") {
                                    if ($(this).find('input[type=checkbox]:checked').length == 1) {
                                        selected_answer_ids.push($(this).find('input[type=checkbox]:checked').val());
                                    } else {
                                        question_wise_data[question_id] = new Array();
                                        $.each($(this).find('input[type=checkbox]:checked'), function () {
                                            selected_answer_ids.push($(this).val());
                                            question_wise_data[question_id].push($(this).val());
                                            multi_select_ansid.push($(this).val());
                                        });
                                    }
                                } else if (question_type == "MultiSelectList") {
                                    if ($(this).find('option:selected').length == 1) {
                                        selected_answer_ids.push($(this).find('option:selected').val());
                                    } else {
                                        $.each($(this).find('option:selected'), function () {
                                            selected_answer_ids.push($(this).val());
                                            question_wise_data[question_id] = this.value;
                                            multi_select_ansid.push($(this).val());
                                        });
                                    }
                                }
                            });
                            var action = '';
                            var target = '';
                            var action_array = new Array();
                            var is_multi = true;
                            //while multi select option value ids
                            try {
                                $.each(selected_answer_ids, function (key_id, value) {
                                    if ($.inArray(value, multi_select_ansid) != -1) {
                                        $.each(question_wise_data, function (question_id, options) {
                                            if (question_id != undefined && value != "") {
                                                if (!action_array[question_id])
                                                {
                                                    action_array[question_id] = new Array();
                                                }
                                                if ($.inArray(value, options) != -1) {
                                                    $.each(obj[value], function (skip_action, skip_target) {
                                                        if (skip_action != "no_logic" && skip_target != "") {
                                                            if (skip_action != "show_hide_question" && !action_array[question_id][skip_action]) {

                                                                action_array[question_id][skip_action] = skip_target;
                                                                is_multi = true;
                                                            }
                                                        }
                                                    });
                                                }
                                            }
                                        });
                                    }
                                    //while single select option value ids
                                    else {
                                        if (value != undefined && value != "") {
                                            $.each(obj[value], function (skip_action, skip_target) {
                                                if (skip_action != "no_logic" && skip_target != "") {
                                                    if (skip_action != "show_hide_question") {
                                                        action = skip_action;
                                                        target = skip_target;
                                                        is_multi = false;
                                                    }
                                                }
                                            });
                                        }
                                    }
                                });
                            } catch (e) {
                            }
                            //first action is perform for multiple choice question
                            if (is_multi) {
                                for (var key in action_array) {
                                    for (var val in action_array[key])
                                    {
                                        action = val;
                                        target = action_array[key][val];
                                    }
                                }
                            }
                            //perform action
                            if (action == "redirect_url") {
                                flag = 1;
                                $('#btnnext').prop('type', 'submit');
                                $('#btnnext').prop('name', 'btnsend');
                                $('#btnnext').removeClass('bx-next');

                                $('#redirect_action_value').val(target);
                                var curr_slide = slider.getCurrentSlide();
                                var total_pages = parseInt($('.page-no').length);
                                if ($('.welcome-form').length != 0)
                                {
                                    total_pages = total_pages + 1;
                                }

                                // hide all after pages
                                var afterPages = new Object();
                                for (var i = curr_slide + 1; i < total_pages; i++) {
                                    afterPages[i] = i;
                                }
                                $.each(afterPages, function (key, pages)
                                {
                                    if (pages != gotoslide && pages != prev_slide)
                                    {
                                        var pageToHide = $('.survey-form')[pages + 1];
                                        $(pageToHide).addClass('hiddenPage');
                                    }
                                });
                            } else if (action == "end_page") {
                                flag = 1;
                                $('#btnnext').prop('type', 'submit');
                                $('#btnnext').prop('name', 'btnsend');
                                $('#btnnext').removeClass('bx-next');
                                var curr_slide = slider.getCurrentSlide();
                                var total_pages = parseInt($('.page-no').length);
                                if ($('.welcome-form').length != 0)
                                {
                                    total_pages = total_pages + 1;
                                }

                                // hide all after pages
                                var afterPages = new Object();
                                for (var i = curr_slide + 1; i < total_pages; i++) {
                                    afterPages[i] = i;
                                }
                                $.each(afterPages, function (key, pages)
                                {
                                    if (pages != gotoslide && pages != prev_slide)
                                    {
                                        var pageToHide = $('.survey-form')[pages + 1];
                                        $(pageToHide).addClass('hiddenPage');
                                    }
                                });
                            } else if (action == "redirect_page") {
                                // set type button to do not submit but redirect to page
                                if ($(this).attr('name') == 'btnsend')
                                {
                                    $('#btnsend').prop('type', 'button');
                                }
                                flag = 1;
                                $('#btnnext').prop('type', 'button');
                                $('#btnnext').prop('name', 'btnnext');
                                $('#btnnext').addClass('bx-next');
                                var gotoslide = parseInt($('#' + target).val()) - 1;
                                prev_slide = slider.getCurrentSlide();
                                var inbetweenPages = new Object();
                                for (var i = prev_slide + 1; i < gotoslide; i++) {
                                    inbetweenPages[i] = i;
                                }
                                $.each(inbetweenPages, function (key, pages)
                                {
                                    if (pages != gotoslide && pages != prev_slide)
                                    {
                                        var pageToHide = $('.survey-form')[pages + 1];
                                        $(pageToHide).addClass('hiddenPage');
                                    }
                                });
                                slider.goToSlide(gotoslide, 'next');
                                var pageToShow = $('.survey-form')[gotoslide + 1];
                                $(pageToShow).removeClass('hiddenPage');

                            }
                            var validationQuestionValue = new Array();
                            var validationReturnVal = '';
                            var allTtpeArray = new Array(
                                    'MultiSelectList', 'Checkbox',
                                    'RadioButton', 'DrodownList',
                                    'Textbox', 'CommentTextbox',
                                    'Rating', 'ContactInformation',
                                    'Date', 'Image', 'Video', 'Scale', 'Matrix'
                                    );
                            var is_require = 0;
                            var type = '';
                            var queID, datatype, is_datetime, is_sort = '';
                            var min, max, maxsize, precision, scale_slot, limit_min = 0;
                            $('.active-slide > .survey-form > .form-body').each(function () {
                                queID = $(this).find('.questionHiddenField').val();
                                var self = this;
                                //getting other question detail
                                $.each(data_detail, function (pindex, page_data) {
                                    $.each(page_data, function (qindex, que_data) {
                                        if (qindex == 'page_questions') {
                                            $.each(que_data, function (qi, q_data) {
                                                if (q_data['que_id'] == queID) {
                                                    min = q_data['min'];
                                                    max = q_data['max'];
                                                    maxsize = q_data['maxsize'];
                                                    precision = q_data['precision'];
                                                    scale_slot = q_data['scale_slot'];
                                                    datatype = q_data['advance_type'];
                                                    is_datetime = q_data['is_datetime'];
                                                    is_sort = q_data['is_sort'];
                                                    limit_min = q_data['selection_limit'];
                                                }
                                            });
                                        }
                                    });
                                });
                                var setTypaClass = $(self)[0].classList;
                                if (typeof setTypaClass == "undefined") {
                                    var setTypaClass = $(self)[0].className.split(" ");
                                }
                                $(setTypaClass).each(function (index) {
                                    if ($.inArray(setTypaClass[index], allTtpeArray) != -1) {
                                        type = setTypaClass[index];
                                    }
                                });
                                if ($(self).find('h3').find('span').hasClass('is_required')) {
                                    is_require = 1;
                                }
                                validationReturnVal = surveySliderValidationOnNextPrevClick(type, queID, is_require, min, max, maxsize, precision, datatype, is_datetime, is_sort, scale_slot, limit_min, lang_detail);
                                validationQuestionValue.push(validationReturnVal);
                                is_require = 0;
                                type = '';
                                queID = '';
                            });
                            if ($.inArray(false, validationQuestionValue) == -1) {
                                if (flag == 0) {
                                    prev_slide = slider.getCurrentSlide();
                                    var currentSlidePage = slider.getCurrentSlide() + 1;
                                    var totalPageCount = slider.getSlideCount();
                                    if (currentSlidePage == totalPageCount - 1) {
                                        $(this).removeClass('showBtn').addClass('hideBtn');
                                    } else {
                                        if ($(this)[0].id != 'btnsend') {
                                            $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                                        }
                                    }
                                    slider.goToNextSlide();
                                    $('html, body').animate({scrollTop: 0}, 800);
                                    if ($(this).hasClass('hideBtn')) {
                                        $("#btnsend").show();
                                        $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                                    }
                                }
                            } else {
                                $('.validation-tooltip').fadeIn();
                                return false;
                            }

                            // currently showing question ids
                            var ShowQueIds = '';
                            $.each($('.form-body'), function () {

                                var isHidden = false;
                                var isHiddenPageParent = $(this).parent('.survey-form');
                                if ($(isHiddenPageParent).hasClass('hiddenPage'))
                                {
                                    isHidden = true;
                                }
                                if ($(this).css('display') != 'none' && $(this).find('.questionHiddenField').val() && !isHidden)
                                {
                                    var queId = $(this).find('.questionHiddenField').val();
                                    ShowQueIds += queId + ',';
                                }
                            });
                            // set show que ids to hidden variable
                            $('.show_question_list').val(ShowQueIds);
                        });
                        $(".bx-prev").click(function () {

                            $('.validation-tooltip').fadeOut();
                            var currentSlidePage = slider.getCurrentSlide();
                            if (currentSlidePage == prev_slide) {
                                prev_slide = slider.getCurrentSlide() - 1;
                            }
                            //prev_slide = slider.getCurrentSlide();
                            if (currentSlidePage == 1) {
                                $(this).removeClass('showBtn').addClass('hideBtn');
                                $('#btnnext').removeClass('hideBtn').addClass('showBtn');
                            } else {
                                $("#btnnext").removeClass('hideBtn').addClass('showBtn');
                            }
                            slider.goToSlide(prev_slide);
                            $('html, body').animate({scrollTop: 0}, 800);
                            $("#btnsend").hide();
                        });

                        //setting slider
                        $(function () {
                            var que_id = '';
                            $.each(slider_detail, function (qid, slider_data) {
                                var answer = parseInt(slider_data.answer) ? parseInt(slider_data.answer) : 0;
                                // scale slider
                                var slider = $('.' + qid).find("#slider").slider({
                                    slide: function (event, ui) {
                                        $(ui.handle).find('.tooltip').html('<div>' + ui.value + '</div>');
                                        $('.' + qid + '_scale').val(ui.value);
                                    },
                                    range: "min",
                                    value: answer,
                                    min: parseInt(slider_data.min),
                                    max: parseInt(slider_data.max),
                                    step: parseInt(slider_data.scale_slot),
                                    create: function (event, ui) {
                                        if (slider_data.answer != null) {
                                            var tooltip = $('<div class="tooltip">' + slider_data.answer + '</div>');
                                        } else {
                                            var tooltip = $('<div class="tooltip"></div>');
                                        }
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
                        if ($('.active-slide').find('.welcome-form').length != 0 || $('.active-slide').find('.thanks-form').length != 0)
                        {
                            //    $('.progress-bar').hide();
                            $('.form-desc').hide();
                        } else {
                            //    $('.progress-bar').show();
                            $('.form-desc').show();
                        }
                    },
                    onSlideBefore: function ($slideElement) {

                        $('.bx-viewport').find('.bxslider').children().removeClass('active-slide');
                        $slideElement.addClass('active-slide');
                        var total_pages = parseInt($('.page-no').length);
                        var page_no = parseInt($('.active-slide').find('.page-no > i').html()) - 1;
                        // page progress bar
                        //Setting page state on the top
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
                        var progress_percentage = Math.floor((page_no * 100) / total_pages);
                        var progress = $("#progress").slider({
                            range: "min",
                            value: progress_percentage,
                            disabled: true,
                        });

                        //hide propgress bar at welcomepage
                        if ($('.active-slide').find('.welcome-form').length != 0 || $('.active-slide').find('.thanks-form').length != 0)
                        {
                            // $('.progress-bar').hide();
                            $('.form-desc').hide();
                        } else {
                            //   $('.progress-bar').show();
                            $('.form-desc').show();
                        }
                        //add extra div for designing
                        $('#progress').find('.tooltip').html('<div>' + progress_percentage + '<div>');
                        $('#pagecount').html(page_no + "/" + total_pages);
                        $('#progress-percentage').html(progress_percentage + "%");
                    },
                    onSlideAfter: function () {
                        var currentSlidePage = slider.getCurrentSlide() + 1;
                        var totalPageCount = slider.getSlideCount();
                        if (currentSlidePage == 1) {
                            $("#btnprev").removeClass('showBtn').addClass('hideBtn');
                            $('#btnnext').removeClass('hideBtn').addClass('showBtn');
                            $('#btnsend').hide();
                        } else if (currentSlidePage == totalPageCount) {
                            $("#btnsend").show();
                            $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            $('#btnnext').removeClass('showBtn').addClass('hideBtn');
                        } else {
                            $("#btnprev").removeClass('hideBtn').addClass('showBtn');
                            $('#btnnext').removeClass('hideBtn').addClass('showBtn');
                            $('#btnsend').hide();
                        }
                    },
                });

                var total_pages = parseInt($('.page-no').length);
                var page_no = 0;
                var progress_percentage = Math.floor((page_no * 100) / total_pages);
                // page progress bar
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


                if ($(".bx-prev").hasClass('hideBtn')) {
                    $("#btnsend").hide();
                }
                if ($(".bx-prev").hasClass('hideBtn') && $(".bx-next").hasClass('hideBtn')) {
                    $("#btnsend").show();
                }
                //Allow only Numeric Value to textbox validation
                $('.numericField').keypress(function (e) {
                    //if the letter is not digit then display error and don't type anything
                    if (e.which != 8 && e.which != 0 && e.which != 45 && (e.which < 48 || e.which > 57)) {

                        return false;
                    }
                });
                //Allow only Float Value to textbox validation
                $('.decimalField').keypress(function (e) {
                    //if dot already not entered
                    var dot_flag = $(e.currentTarget).val().includes('.');
                    //if the letter is not digit then display error and don't type anything
                    if ((e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) && e.which != 46) || (dot_flag && e.which == 46)) {

                        return false;
                    }
                });
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
            function skipp_logic_question(el, answers) {
                //hide question onload

                var question_type = $(el).parents('.form-body').attr('class').split(' ')[1];
                //while question type is radiobutton on showhide question
                if (question_type == "RadioButton") {
                    $.each($(el).parent().parent().parent().find('input[type=radio]'), function () {
                        $.each(answers[this.value], function (action, target) {
                            if (action == "show_hide_question") {
                                var showHideQuesIdsArr = target;
                                try {
                                    $.each(showHideQuesIdsArr, function (idx, queId) {
                                        if ($('#' + queId + '_div').parents('.form-body').css('display') != 'none') {
                                            var newHeight = $('.bx-viewport').height() - $('#' + queId + '_div').parents('.form-body').innerHeight();
                                            $('.bx-viewport').height(newHeight);
                                        }
                                        $('#' + queId + '_div').parents('.form-body').hide();
                                        //re-setting value while uncheck the element
                                        var hide_question_type = $('#' + queId + '_div').parents('.form-body').attr('class').split(' ')[1];
                                        if (hide_question_type == "RadioButton") {
                                            $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                $(this).prop('checked', false);
                                            });
                                        } else if (hide_question_type == "Checkbox") {
                                            $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                $(this).prop('checked', false);
                                            });
                                        } else if (hide_question_type == "Scale") {
                                            $('.' + queId).find('.ui-slider-range').css('width', '0%');
                                            $('.' + queId).find('.ui-slider-handle').css('left', '0%');
                                            $('.' + queId).find('.tooltip').find('div').html('0');
                                            $('.' + queId + '_scale').val('0')
                                        } else if (hide_question_type == "Date") {
                                            $('.' + queId + '_datetime').val('');
                                        } else if (hide_question_type == "Rating") {
                                            $('#' + queId + '_div').find('.rating').removeClass('selected');
                                            $('.' + queId).val('');
                                        } else if (hide_question_type == "Matrix") {
                                            $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                $(this).prop('checked', false);
                                            });
                                            $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                $(this).prop('checked', false);
                                            });
                                        } else {
                                            $('.' + queId).val('');
                                        }
                                    });
                                } catch (e) {
                                }
                            }
                        });
                    });

                }
                //while question type is checkbox on showhide question
                else if (question_type == "Checkbox") {

                    if (!$(el).prop('checked')) {
                        var answer_id = el.value;
                        var showHideQuesIds = $(el).parents('.form-body').parent().find('#show_hide_question_Ids_' + answer_id).val();
                        if (showHideQuesIds != null && showHideQuesIds != '') {
                            var showHideQuesIdsArr = showHideQuesIds.split(",");
                            try {
                                $.each(showHideQuesIdsArr, function (idx, queId) {

                                    var newHeight = $('.bx-viewport').height() - $('#' + queId + '_div').parents('.form-body').innerHeight();
                                    $('.bx-viewport').height(newHeight);
                                    $('#' + queId + '_div').parents('.form-body').hide();
                                    var hide_question_type = $('#' + queId + '_div').parents('.form-body').attr('class').split(' ')[1];
                                    //re-setting value while uncheck the element
                                    if (hide_question_type == "RadioButton") {
                                        $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                            $(this).prop('checked', false);
                                        });
                                    } else if (hide_question_type == "Checkbox") {
                                        $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                            $(this).prop('checked', false);
                                        });
                                    } else if (hide_question_type == "Scale") {
                                        $('.' + queId).find('.ui-slider-range').css('width', '0%');
                                        $('.' + queId).find('.ui-slider-handle').css('left', '0%');
                                        $('.' + queId).find('.tooltip').find('div').html('0');
                                        $('.' + queId + '_scale').val('0')
                                    } else if (hide_question_type == "Date") {
                                        $('.' + queId + '_datetime').val('');
                                    } else if (hide_question_type == "Rating") {
                                        $('#' + queId + '_div').find('.rating').removeClass('selected');
                                        $('.' + queId).val('');
                                    } else if (hide_question_type == "Matrix") {
                                        $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                            $(this).prop('checked', false);
                                        });
                                        $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                            $(this).prop('checked', false);
                                        });
                                    } else {
                                        $('.' + queId).val('');
                                    }
                                });
                            } catch (e) {
                            }
                        }
                    }
                }
                //while question type is multiselect on showhide question
                else if (question_type == "MultiSelectList") {
                    $.each($(el).parent().find('option'), function () {
                        if (this.value != '') {
                            try {
                                $.each(answers[this.value], function (action, target) {
                                    if (action == "show_hide_question") {
                                        var showHideQuesIdsArr = target;
                                        $.each(showHideQuesIdsArr, function (idx, queId) {
                                            if ($('#' + queId + '_div').parents('.form-body').css('display') != 'none') {
                                                var newHeight = $('.bx-viewport').height() - $('#' + queId + '_div').parents('.form-body').innerHeight();
                                                $('.bx-viewport').height(newHeight);
                                            }
                                            $('#' + queId + '_div').parents('.form-body').hide();
                                            var hide_question_type = $('#' + queId + '_div').parents('.form-body').attr('class').split(' ')[1];
                                            //re-setting value while uncheck the element
                                            if (hide_question_type == "RadioButton") {
                                                $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                            } else if (hide_question_type == "Checkbox") {
                                                $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                            } else if (hide_question_type == "Scale") {
                                                $('.' + queId).find('.ui-slider-range').css('width', '0%');
                                                $('.' + queId).find('.ui-slider-handle').css('left', '0%');
                                                $('.' + queId).find('.tooltip').find('div').html('0');
                                                $('.' + queId + '_scale').val('0')
                                            } else if (hide_question_type == "Date") {
                                                $('.' + queId + '_datetime').val('');
                                            } else if (hide_question_type == "Rating") {
                                                $('#' + queId + '_div').find('.rating').removeClass('selected');
                                                $('.' + queId).val('');
                                            } else if (hide_question_type == "Matrix") {
                                                $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                                $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                            } else {
                                                $('.' + queId).val('');
                                            }
                                        });
                                    }
                                });
                            } catch (e) {
                            }
                        }
                    });
                }
                //while question type is dropdown on showhide question
                else if (question_type == "DrodownList") {
                    $.each($(el).find('option'), function () {
                        if (this.value != '') {
                            try {
                                $.each(answers[this.value], function (action, target) {
                                    if (action == "show_hide_question") {
                                        var showHideQuesIdsArr = target;
                                        $.each(showHideQuesIdsArr, function (idx, queId) {
                                            if ($('#' + queId + '_div').parents('.form-body').css('display') != 'none') {
                                                var newHeight = $('.bx-viewport').height() - $('#' + queId + '_div').parents('.form-body').innerHeight();
                                                $('.bx-viewport').height(newHeight);
                                            }
                                            $('#' + queId + '_div').parents('.form-body').hide();
                                            var hide_question_type = $('#' + queId + '_div').parents('.form-body').attr('class').split(' ')[1];
                                            //re-setting value while uncheck the element
                                            if (hide_question_type == "RadioButton") {
                                                $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                    if ($(this).prop('checked')) {
                                                        $(this).prop('checked', false);
                                                    }
                                                });
                                            } else if (hide_question_type == "Checkbox") {
                                                $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                            } else if (hide_question_type == "Scale") {
                                                $('.' + queId).find('.ui-slider-range').css('width', '0%');
                                                $('.' + queId).find('.ui-slider-handle').css('left', '0%');
                                                $('.' + queId).find('.tooltip').find('div').html('0');
                                                $('.' + queId + '_scale').val('0')
                                            } else if (hide_question_type == "Date") {
                                                $('.' + queId + '_datetime').val('');
                                            } else if (hide_question_type == "Rating") {
                                                $('#' + queId + '_div').find('.rating').removeClass('selected');
                                                $('.' + queId).val('');
                                            } else if (hide_question_type == "Matrix") {
                                                $.each($('#' + queId + '_div').find('input[type=radio]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                                $.each($('#' + queId + '_div').find('input[type=checkbox]'), function () {
                                                    $(this).prop('checked', false);
                                                });
                                            } else {
                                                $('.' + queId).val('');
                                            }
                                        });
                                    }
                                });
                            } catch (e) {
                            }
                        }
                    });
                }
                var sel_optIds = new Array();
                //while multiple choice option for show hide question
                if (question_type == "MultiSelectList" || question_type == "Checkbox") {
                    if (question_type == "MultiSelectList") {
                        var ans_id = $(el).val();
                    } else {
                        var ans_id = new Array();
                        $.each($(el).parent().parent().parent().find('input[type=checkbox]:checked'), function () {
                            ans_id.push(this.value);
                        });
                    }
                    var action = new Object();
                    $.each(ans_id, function (indx, value) {
                        $.each(answers[value], function (key, value) {
                            if (key != "no_logic") {
                                action[key] = value
                            }
                        });
                    });
                    var logic_target = '';
                    var logic_action = '';
                    $.each(action, function (act, tar) {
                        if (act == 'show_hide_question') {
                            sel_optIds.push(true);
                            logic_action = act;
                            logic_target = tar;
                        } else {
                            sel_optIds.push(false);
                        }
                    });
                    if (sel_optIds.indexOf(true) !== -1) {
                        $.each(logic_target, function (idx, queId) {

                            var newHeight = $('#' + queId + '_div').parents('.form-body').innerHeight() + $('.bx-viewport').height();
                            $('.bx-viewport').height(newHeight);
                            $('#' + queId + '_div').parents('.form-body').show();
                        });
                    }
                }
                //while single choice option for show hide question
                else if (question_type == "RadioButton" || question_type == "DrodownList") {

                    var ans_id = el.value
                    $.each(answers[ans_id], function (key, value) {
                        logic_action = key;
                    });
                    var logic_target = answers[ans_id][logic_action];
                    var act = '';
                    $.each(answers[ans_id], function (key, value) {
                        act = key;
                    });
                    if (act == 'show_hide_question') {
                        $.each(logic_target, function (idx, queId) {
                            var newHeight = $('#' + queId + '_div').parents('.form-body').innerHeight() + $('.bx-viewport').height();
                            $('.bx-viewport').height(newHeight);
                            $('#' + queId + '_div').parents('.form-body').show();
                        });
                    }
                }
            }
            function addOtherField(el) {
                 var que_id = $(el).parents('.form-body').find('.questionHiddenField').val();
                var placeholder_label = $('[name=placeholder_label_other_' + que_id + ']').val();
                if (!placeholder_label)
                {
                    placeholder_label = 'Other';
                }
                if ($(el).val()) {
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
                        $.each(selected_ans_ids, function (key, id)
                        {
                            value_selected += $('[value=' + id + ']').attr('class');
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
                    var question_id = $(el).parents('.form-body').find('.questionHiddenField').val();
                    $(el).parents('.option').append("<input " + add + " class='form-control " + question_id + "_other other_option_input' type='textbox' name='" + el.name + "' class='{$que_id}' placeholder='" + placeholder_label + "'>");

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
        <div class="bg"></div>
        <?php if (count($available_lang) != 0) {
            ?>
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

            <div id='tooltipDiv'></div>
            <form method="post" name="survey_submisssion" action="" id="survey_submisssion">
                <input type="hidden" value="<?php $btn_submit_click_flag ?>" id="btn_submit_click_flag" name="btn_submit_click_flag" />
                <input type="hidden" value="" class="show_question_list" name="show_question_list" />
                <?php $total_pages = count($survey_details) ?>
                <?php foreach ($survey_details as $page_sequence => $detail) { ?>
                    <input type="hidden" value="<?php echo $page_sequence ?>" id="<?php echo $detail['page_id']; ?>" name="skipp_page_sequence"/>
                <?php } ?>
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
                            <div class="survey-header"><h2> <?php
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
                    <input type="hidden" name="redirect_action" value="" id="redirect_action_value">
                    <?php
                    if (!empty($redirect_url) && trim($redirect_url) != "http://" && $redirect_flag == true) {
                        if (strpos($redirect_url, 'http://') !== false || strpos($redirect_url, 'https://') !== false) {
                            echo "<script>window.location.replace('" . $redirect_url . "');</script>";
                        } else {
                            $new_url = 'http://' . $redirect_url;
                            echo "<script>window.location.replace('" . $new_url . "');</script>";
                        }
                    } else if ($msg != '') {
                        echo $msg;
                        exit;
                    }
                    ?>
                    <?php
                    if (isset($msg1) && $msg1 != '') {
                        echo "{$msg1}";
                    } else {
                        ?>
                        <div class="container">
                            <div class="survey-form form-desc">
                                <div class="form-body">
                                    <ul class="progress-bar">

                                        <?php
                                        if ($total_pages > 1) {
                                            // Setting Page Header
                                            if ($is_progress_indicator != 1) {
                                                foreach ($survey_details as $page_sequence => $detail) {
                                                    if ($survey->survey_theme == 'theme2' || $survey->survey_theme == 'theme6' || $survey->survey_theme == 'theme7' || $survey->survey_theme == 'theme8') {
                                                        ?>

                                                        <li class="hexagon" style='cursor: default'><span class="pro-text"><?php echo 'Page ' . $page_sequence; ?></span><a style='cursor: default'><?php echo $page_sequence; ?></a></li>

                                                        <?php
                                                    } else {
                                                        ?>

                                                        <li class="hexagon" style='cursor: default'><span class="pro-text"><?php echo 'Page ' . $page_sequence; ?></span><a style='cursor: default'><?php echo $page_sequence; ?></a></li>

                                                        <?php
                                                    }
                                                }
                                            } else {
                                                ?>
                                                <section style="width:100%">
                                                    <div id="pagecount" class="equal text"  style="width:5%"></div>
                                                    <div id="progress" class="equal" style="width:85%"></div>
                                                    <div id="progress-percentage" class="equal text last" style="width:5%"></div>
                                                </section>
                                            <?php }
                                        } ?>
                                        <div class="shape">
                                            <span class="arr-right"></span>
                                        </div>
                                        </li>
                                    </ul>

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
                                            // $welcome_content = '<div style="text-align: center; background-color: #666;"><br /><span style="color: #99cc00; font-size: large;">Welcome To Survey</span><br /><br /><br /><img style="width: 970px; height: 413px;" alt="" src="http://localhost/SurveyRocketS2.5/rest/v10/bc_survey/26d42dbc-931e-8d79-6808-57f4b146a9af/file/survey_logo?format=sugar-html-json&amp;platform=base&amp;_hash=66a3e4ee-24b3-6361-b38e-57f4af98cd04" /><br /><br /><br /><span style="color: #ff6600;"><span style="font-size: medium;">Please click <span style="color: #993366; background-color: #ffffff;"> next button </span> to start up a survey</span><br /><br /><br /></span></div>';
                                            echo '<div class="form-body">' . html_entity_decode($welcome_content) . '</div>';
                                            ?>

                                        </div>
                                    </li>
                                    <?php
                                }
                                foreach ($survey_details as $page_sequence => $detail) {
                                    $queArraylist[$page_sequence] = getSubmittedAnswerByReciever($survey_id, $module_id);
                                    ?>
                                    <li>
                                        <div class="survey-form">
                                            <div class="form-header">
                                                <h1><?php echo $detail['page_title']; ?></h1>
                                                <span class="page-no"><i><?php echo $page_sequence ?></i></span>
                                            </div>
                                            <?php
                                            foreach ($showHideQuesArrayOnPageload[$page_sequence] as $ans_ID => $hideQuesarray) {
                                                ?>
                                                <input type='hidden' id='show_hide_question_Ids_<?php echo $ans_ID; ?>' value='<?php echo implode(',', $hideQuesarray) ?>'/>
                                            <?php }
                                            ?>
                                            <?php foreach ($detail['page_questions'] as $que_sequence => $question) { ?>
                                                <?php
                                                $display_qes = "display:''";
                                                $showOnload = false;
                                                foreach ($queArraylist[$page_sequence] as $submitAns) {
                                                    if (in_array($question['que_id'], $showHideQuesArrayOnPageload[$page_sequence][$submitAns])) {
                                                        $showOnload = true;
                                                    }
                                                }
                                                foreach ($skip_logicArrForHideQues['show_hide_question'][$page_sequence] as $indx => $questionid) {
                                                    if (in_array($question['que_id'], $skip_logicArrForHideQues['show_hide_question'][$page_sequence][$indx]) && !$showOnload) {
                                                        $display_qes = 'display:none';
                                                    }
                                                }
                                                ?>
                                                <div class="form-body <?php echo $question['que_type']; ?>" style="<?php echo $display_qes; ?> ;margin-top:14px;">
                                                    <input type="hidden" class="questionHiddenField" name="questions[]" value="<?php echo $question['que_id'] ?>"  >
                                                    <?php
                                                    $queArray = $sbmtSurvData[$question['que_id']];
                                                    $queAnsArray = array_values($queArray);
                                                    if ($question['que_type'] == "Textbox" || $question['que_type'] == "CommentTextbox" || $question['que_type'] == "Rating") {
                                                        $answer = array_values($queAnsArray[0])[0];
                                                    } else {
                                                        $answer = $queAnsArray[0];
                                                    }
                                                    if ($question['que_type'] == "ContactInformation") {
                                                        $answer = explode(",", $answer);
                                                    }
                                                    if ($question['que_type'] == 'section-header') {
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
                                                            echo "<div class='question-section'>" . $question['que_title'] . "</div>";
                                                        } else {
                                                            $que_no++;
                                                            $img_flag = false;
                                                            echo $que_no . '.';
                                                            echo $question['que_title'];
                                                            if ($question['is_required'] == 1) {
                                                                ?>
                                                                <span class="is_required" style="color:red;">   *</span>
                                                                <?php
                                                            }
                                                        }
                                                        if ($question['que_type'] == 'Image' || $question['que_type'] == 'Video') {
                                                            //do nothing while image or videos
                                                        } else if (!empty($question['question_help_comment'])) {
                                                            ?> <div style="display: inline-block;float: right;"><img class="questionImgIcon" onmouseout="removeHelpTipPopUpDiv();" onmouseover="openHelpTipsPopUpSurvey(this, '<?php echo $question['question_help_comment']; ?>');" src="custom/include/survey-img/question.png" ></div>
                                                    <?php } ?></h3>
                                                    <?php
                                                    $elementHTML = getMultiselectHTML($skip_logicArrForAll, $question['answers'], $question['que_type'], $question['que_id'], $question['is_required'], $answer, $question['answer_other'], $question['maxsize'], $question['min'], $question['max'], $question['precision'], $question['scale_slot'], $question['is_sort'], $question['is_datetime'], $question['advance_type'], $question['que_title'], $question['matrix_row'], $question['matrix_col'], $question['description'], $question['is_skip_logic'], $list_lang_detail,$survey_answer_prefill[$question['que_id']]);
                                                    echo $elementHTML;
                                                    ?>
                                                </div>
        <?php } ?>
                                        </div>
                                    </li>

                            <?php } ?>
                            </ul>
                            <?php
                            if (!empty($list_lang_detail['next_button'])) {
                                $next_button_label = $list_lang_detail['next_button'];
                            } else {
                                $next_button_label = 'Next';
                            }
                            if (!empty($list_lang_detail['prev_button'])) {
                                $prev_button_label = $list_lang_detail['prev_button'];
                            } else {
                                $prev_button_label = 'Prev';
                            }
                            if (!empty($list_lang_detail['submit_button'])) {

                                $submit_button_label = $list_lang_detail['submit_button'];
                            } else {
                                $submit_button_label = 'Submit';
                            }
                            ?>
                            <div class="action-block">

                                <?php if (!empty($module_id) || !empty($survey->survey_submit_unique_id)) { ?>
                                    <input class='button btn-submit'  type='submit' value='<?php echo $submit_button_label; ?>' name="btnsend" id="btnsend">
    <?php } ?>


                                <div style="display: inline-block;float: right;"> <input class='bx-prev button hideBtn'  type='button' value='<?php echo $prev_button_label; ?>' name="btnprev" id="btnprev">
                                    <input class='bx-next button <?php echo $addClass; ?> '  type='button' value='<?php echo $next_button_label; ?>' name="btnnext" id="btnnext"></div>
                            </div>
                           <div class="btm-link"><a href="#"></a></div>
                        </div>
<?php } ?>
                </div>
            </form>
        </div>
    </body>
</html>
