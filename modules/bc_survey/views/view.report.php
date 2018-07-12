<?php

/**
* Send All submission Data To report.tpl file
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Biztech Consultancy
*/
require_once('include/MVC/View/SugarView.php');
require_once("include/Sugar_Smarty.php");
require_once 'custom/include/utilsfunction.php';
include_once 'custom/include/pagination.class.php';

/**
* generate report data and pass to report.tpl file
*
* @author     Original Author Biztech Co
*/
class bc_SurveyViewReport extends SugarView {

    function __construct() {
        parent::SugarView();
    }

    function display() {
        date_default_timezone_set('UTC');

        global $sugar_version, $db;
        require_once('custom/include/modules/Administration/plugin.php');
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }
        echo '<link href="custom/include/css/survey_css/pagination.css" rel="stylesheet">';
        echo '<script src="custom/include/js/survey_js/jquery-1.10.2.js"></script>';
        echo '<link href="custom/include/css/survey_css/jquery-ui.css" rel="stylesheet">';
        echo '<script type="text/javascript" src="custom/include/js/survey_js/jquery-ui.js"></script>';
        $checkSurveySubscription = validateSurveySubscription();
        if (!$checkSurveySubscription['success']) {
            if (!empty($checkSurveySubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkSurveySubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkSurveySubscription['message'])) {
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkSurveySubscription['message'] . '</div>';
            }
            global $sugar_config, $current_user;
            $re_suite_version = '/(7\.[0-9].[0-9])/';
            if ($sugar_config['suitecrm_version'] != '' && preg_match($re_suite_version, $sugar_config['suitecrm_version'])) {
                $current_theme = $current_user->getPreference('user_theme');
                $random_num = mt_rand();
                $file = "custom/include/css/survey_css/general_report.css";
                if (file_exists($file) && $current_theme == 'SuiteR') {
                    echo "<link href='{$file}?{$random_num}' rel='stylesheet'>";
                } else {
                    echo "<link href='custom/include/css/survey_css/suite7_report.css?{$random_num}' rel='stylesheet'>";
                }
            } else {
                echo "<link rel='stylesheet' href='custom/include/css/survey_css/general_report.css' type='text/css'>";
            }
            //echo '<link href="custom/include/css/survey_css/general_report.css" rel="stylesheet">';

            $survey_id = $_REQUEST['survey_id'];
            $type = isset($_REQUEST['type']) ? $_REQUEST['type'] : 'status';
            $survey_type = '';
            if ($type == 'status' || $type == 'status_combined') {
                $survey_type = 'Combined';
                $type = 'status_combined';
                $lineChart = getLineChart($type, $survey_id, $survey_type);
            }if ($type == 'status_open_ended') {
                $survey_type = 'Open Ended';
                $lineChart = getLineChart($type, $survey_id, $survey_type);
            }if ($type == 'status_email') {
                $survey_type = 'Email';
                $lineChart = getLineChart($type, $survey_id, $survey_type);
            }
            if ($type == 'question_email') {
                $survey_type = 'Email';
            }if ($type == 'question_open_ended') {
                $survey_type = 'Open Ended';
            }if ($type == 'question' || $type == 'question_combined') {
                $survey_type = 'Combined';
            }
            $survey = getReportData($type, $survey_id, '', '', '', $survey_type);
            $survey_obj = new bc_survey();
            $survey_obj->retrieve($survey_id);
            $details = array();
            $que_details = array();
            $name = array();
            $rating = "";
            $rating_final_count_array = array();
            $rating_pecent = array();
            $rating_count = array();
            $survey_start_dateArr = explode(' ', $survey_obj->start_date);
            $survey_end_dateArr = explode(' ', $survey_obj->end_date);
            $survey_start_date = $survey_start_dateArr[0];
            $survey_end_date = $survey_end_dateArr[0];
            //get total of send out survey
            $get_total_send_survey = "SELECT
            COUNT(bc_survey_submission.id) as total_send_survey
            FROM bc_survey_submission
            JOIN bc_survey_submission_bc_survey_c
            ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
            WHERE bc_survey_submission.deleted = 0
            AND bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id} '";
            if ($survey_type == 'Combined') {
                $get_total_send_survey .= "";
            } else {
                $get_total_send_survey .= "AND bc_survey_submission.submission_type = '{$survey_type}'";
            }
            $result_total_send_survey = $db->query($get_total_send_survey);
            $row_total_send_survey = $db->fetchByAssoc($result_total_send_survey);
            $total_send_survey = $row_total_send_survey['total_send_survey']; // total send out survey
            //calculated total submited person of survey
            $get_sub_que_query = "SELECT
            COUNT(bc_survey_submission.id) as total_submitted_que
            FROM bc_survey_submission
            JOIN bc_survey_submission_bc_survey_c
            ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
            WHERE bc_survey_submission.deleted = 0
            AND bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
            AND bc_survey_submission.status = 'Submitted'";
            if ($survey_type == 'Combined') {
                $get_sub_que_query .= "";
            } else {
                $get_sub_que_query .= "and bc_survey_submission.submission_type = '{$survey_type}'";
            }

            $result = $db->query($get_sub_que_query);
            $row = $db->fetchByAssoc($result);
            $total_submitted_que = $row['total_submitted_que'];
            // Get count of each answer in total submission by customer
            $answerSubmissionCount = getAnswerSubmissionCount($survey_id, $survey_type);
            $answer_skipp = getAnswerSubmissionAnsweredAndSkipped($survey_id, $survey_type, $total_submitted_que);
            $AnsweredAndSkippedPerson = $answer_skipp;
            // End
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
            if ($type == 'question' || $type == 'question_combined' || $type == 'question_open_ended' || $type == 'question_email') {
                $multi_data_array = array(); // store value of  bar chart
                if ($type == 'question') {
                    $type = 'question_combined';
                }
                $contactAnswered = 0;
                $matrixAnsweredPerson = 0;
                foreach ($survey as $que_id => $question_name) {

                    $oQuestion = new bc_survey_questions();
                    $oQuestion->retrieve($que_id);
                    if ($oQuestion->question_type != 'Image' && $oQuestion->question_type != 'Video' && $oQuestion->question_type != 'question_section') {
                        $matrix_rows[$que_id] = !empty($oQuestion->matrix_row) ? json_decode(base64_decode($oQuestion->matrix_row)) : '';
                        $matrix_cols[$que_id] = !empty($oQuestion->matrix_col) ? json_decode(base64_decode($oQuestion->matrix_col)) : '';

                        $rating_final_count = array();
                        $page_seq = $question_name[1];
                        $details['page_title'][$page_seq] = $question_name[3];
                        $details[$que_id]['question_id'] = $que_id;
                        $details[$que_id]['page_id'] = $page_seq;
                        $details[$que_id]['name'] = $question_name[0];

                        $details[$que_id]['que_type'] = $oQuestion->question_type;
                        if ($oQuestion->enable_scoring) {
                            $details[$que_id]['enable_scoring'] = $oQuestion->enable_scoring;
                            $details[$que_id]['sum_score'] = $oQuestion->base_weight;
                        }
                        //calculated total count of submited question options
                        $get_sub_que_query = "SELECT COUNT(sub_que.id) AS total_submitted_que , "
                        . "bc_survey_submission.submission_type FROM bc_submission_data_bc_survey_questions_c "
                        . "AS sub_que join bc_submission_data_bc_survey_submission_c"
                        . " on sub_que.bc_submission_data_bc_survey_questionsbc_submission_data_idb = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_submission_data_idb join bc_survey_submission on bc_survey_submission.id = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida WHERE sub_que.bc_submission_data_bc_survey_questionsbc_survey_questions_ida = '{$que_id}' ";
                        if ($survey_type == 'Combined') {
                            $get_sub_que_query .= "";
                        } else {
                            $get_sub_que_query .= "and bc_survey_submission.submission_type = '{$survey_type}'";
                        }
                        $get_sub_que_query .= " GROUP BY  bc_survey_submission.submission_type";
                        $result = $db->query($get_sub_que_query);
                        $row = $db->fetchByAssoc($result);
                        $total_submitted_que = $row['total_submitted_que'];
                        $details[$que_id]['total_answer_count'] = $total_submitted_que;

                        //calculated total submited person of survey
                        $get_sub_que_query = "SELECT
                        COUNT(bc_survey_submission.id) as total_submitted_que
                        FROM bc_survey_submission
                        JOIN bc_survey_submission_bc_survey_c
                        ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
                        WHERE bc_survey_submission.deleted = 0
                        AND bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                        AND bc_survey_submission.status = 'Submitted'";
                        if ($survey_type == 'Combined') {
                            $get_sub_que_query .= "";
                        } else {
                            $get_sub_que_query .= "and bc_survey_submission.submission_type = '{$survey_type}'";
                        }

                        $result = $db->query($get_sub_que_query);
                        $row = $db->fetchByAssoc($result);
                        $total_submitted_que = $row['total_submitted_que'];
                        $details[$que_id]['total'] = $total_submitted_que;

                        $oQuestion->load_relationship('bc_survey_answers_bc_survey_questions');
                        $answer_objects = $oQuestion->get_linked_beans('bc_survey_answers_bc_survey_questions', 'bc_survey_answers');
                        $average = 0;
                        $sum_score = 0;
                        $individual_question_score = array();
                        //Caculator NPS
                        $weights = array_map(function($e) {
                            return is_object($e) ? $e->score_weight : $e['score_weight'];
                            }, $answer_objects);
                        $is_nps      = false;
                        $detractors  = 0;
                        $passive     = 0;
                        $promoters   = 0;
                        if(in_array('1',$weights) && in_array('-1',$weights) && in_array(0,$weights))
                            $is_nps  = true;



                        foreach ($answer_objects as $answer_object) {
                            $details[$que_id]['answers'][$answer_object->answer_sequence]['ans_name'] = $answer_object->name;
                            $get_sub_ans_query = "SELECT
                            COUNT(sub_ans.id) AS total_submitted_ans
                            FROM bc_submission_data_bc_survey_answers_c AS sub_ans
                            join bc_submission_data_bc_survey_submission_c
                            on sub_ans.bc_submission_data_bc_survey_answersbc_submission_data_idb= bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_submission_data_idb join bc_survey_submission on bc_survey_submission.id = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
                            WHERE sub_ans.bc_submission_data_bc_survey_answersbc_survey_answers_ida = '{$answer_object->id}' "
                            . " AND bc_survey_submission.deleted = 0";
                            if ($survey_type == 'Combined') {
                                $get_sub_que_query .= "";
                            } else {
                                $get_sub_que_query .= "and bc_survey_submission.submission_type = '{$survey_type}'";
                            }

                            $result = $db->query($get_sub_ans_query);
                            $row = $db->fetchByAssoc($result);
                            if ($row['total_submitted_ans'] != 0) {
                                if (!in_array($oQuestion->question_type, array('CommentTextbox', 'Textbox', 'ContactInformation', 'Date'))) {
                                    $details[$que_id]['answers'][$answer_object->answer_sequence]['sub_ans']    = (!is_null($answerSubmissionCount[$answer_object->id])) ? $answerSubmissionCount[$answer_object->id] : 0;
                                    $details[$que_id]['answers'][$answer_object->answer_sequence]['weight']     = $answer_object->score_weight;
                                    $details[$que_id]['answers'][$answer_object->answer_sequence]['percent']    = number_format(($answerSubmissionCount[$answer_object->id] * 100) / ((empty($AnsweredAndSkippedPerson[$que_id]['answered']) || $AnsweredAndSkippedPerson[$que_id]['answered'] == 0 || $AnsweredAndSkippedPerson[$que_id]['answered'] == '0') ? 1 : $AnsweredAndSkippedPerson[$que_id]['answered']), 2);
                                    // store individual response score for each answer
                                    if ($answerSubmissionCount[$answer_object->id] > 0) {
                                        $average = (int) $average + ($answerSubmissionCount[$answer_object->id] * $answer_object->score_weight);
                                        if($is_nps){
                                            if($answer_object->score_weight < 0)
                                                $detractors += $answerSubmissionCount[$answer_object->id];
                                            if($answer_object->score_weight == 0)
                                                $passive += $answerSubmissionCount[$answer_object->id];
                                            if($answer_object->score_weight > 0)
                                                $promoters += $answerSubmissionCount[$answer_object->id];
                                        }
                                    }
                                    $details[$que_id]['average']    = $average;
                                    $details[$que_id]['is_nps']     = $is_nps;
                                    $details[$que_id]['detractors'] = $detractors;
                                    $details[$que_id]['passive']    = $passive;
                                    $details[$que_id]['promoters']  = $promoters;
                                }
                            }
                        }
                        if (isset($details[$que_id]['answers']) && is_array($details[$que_id]['answers']))
                            ksort($details[$que_id]['answers']);
                        if (in_array($oQuestion->question_type, array('CommentTextbox', 'Textbox', 'ContactInformation', 'Rating', 'Date', 'Scale', 'Matrix'))) {
                            $result = getQuestionWiseData($survey_id, $que_id, $oQuestion, $survey_type);
                            $scale_ans_count = 0;
                            $scale_answers = array(); // storing answer name for scale type of questions for counting answers.
                            $contactAnsweredCountEach = 0;

                            while ($row = $db->fetchByAssoc($result)) {

                                if ($oQuestion->question_type == 'ContactInformation') {
                                    $contact_information = JSON::decode(html_entity_decode($row['name']));
                                    $contactAnsweredCountEach = 0;

                                    foreach ($contact_information as $k => $answer) {
                                        if (!empty($answer)) {
                                            $contactAnsweredCountEach++;
                                        }
                                    }
                                    if ($contactAnsweredCountEach > 0) {
                                        $contactAnswered++;
                                    }
                                    //Add answered and skipped persons of scale
                                    $AnsweredAndSkippedPerson[$que_id]['answered'] = $contactAnswered;
                                    $AnsweredAndSkippedPerson[$que_id]['skipped'] = ($total_submitted_que - (int) $contactAnswered);

                                    $details[$que_id]['answers'][$row['answer_id']]['ans_name'] = $contact_information;
                                }
                                else if ($oQuestion->question_type == 'Rating') {
                                    if (!empty($row['name'])) {
                                        $details[$row['que_id']]['answers'][$row['answer_id']]['ans_name'] = $row['name'];
                                        if (!empty($oQuestion->maxsize)) {
                                            $starCount = $oQuestion->maxsize;
                                        } else {
                                            $starCount = 5;
                                        }
                                        $details[$row['que_id']]['max_size'] = $starCount;
                                        for ($i = 0; $i <= $starCount; $i++) {
                                            if (empty($rating_count[$i])) {
                                                $rating_count[$i] = 0;
                                            }
                                        }


                                        //Add answered and skipped persons of rating
                                        $AnsweredAndSkippedPerson[$que_id]['answered'] = count($details[$row['que_id']]['answers']);
                                        $AnsweredAndSkippedPerson[$que_id]['skipped'] = ($total_submitted_que - (int) $AnsweredAndSkippedPerson[$que_id]['answered']);

                                    }
                                } else if ($oQuestion->question_type == 'Scale') {
                                    //Add answered and skipped persons of scale
                                    $answered = !empty($answerSubmissionCount['scale'][$que_id]) ? $answerSubmissionCount['scale'][$que_id] : 0;
                                    $AnsweredAndSkippedPerson[$que_id]['answered'] = $answered;
                                    $AnsweredAndSkippedPerson[$que_id]['skipped'] = ($total_submitted_que - (int) $answerSubmissionCount['scale'][$que_id]);
                                    $scale_ans_count++;
                                    if (!in_array($row['name'], $scale_answers)) {
                                        array_push($scale_answers, $row['name']);
                                        if (!empty($row['name'])) {
                                            $details[$que_id]['answers'][$row['name']]['sub_ans'] = (!is_null($row['name'])) ? $answerSubmissionCount[$row['name']] : 0;
                                            $details[$que_id]['answers'][$row['name']]['percent'] = number_format(($answerSubmissionCount[$row['name']] * 100) / ((empty($answerSubmissionCount['scale'][$que_id]) || $answerSubmissionCount['scale'][$que_id] == 0 || $answerSubmissionCount['scale'][$que_id] == '0') ? 1 : $answerSubmissionCount['scale'][$que_id]), 2);
                                            $details[$que_id]['answers'][$row['name']]['ans_name'] = $row['name'];
                                            $details[$que_id]['min'] = $oQuestion->min;
                                            $details[$que_id]['max'] = $oQuestion->max + 1;
                                            $details[$que_id]['scale_slot'] = $oQuestion->scale_slot;
                                        }
                                    }
                                }
                                else if ($oQuestion->question_type == 'Matrix') {
                                    $final_answers = array();
                                    $matrix = split('_', $row['name']);
                                    $answer = getAnswerSubmissionDataForMatrix($survey_id, '', $que_id, $survey_type);

                                    foreach ($answer as $recipient => $sub_answer) {
                                        foreach ($sub_answer as $akey => $aval) {
                                            array_push($final_answers, $aval);
                                        }
                                    }
                                    $details[$que_id]['answers']['ans_detail'] = $final_answers;
                                    $matrixAnsweredPerson = count($answer);

                                    $final_matrix_rowCount = array();
                                    foreach ($final_answers as $key => $matrix_ans_value) {
                                        $splited_value = split('_', $matrix_ans_value);
                                        if (isset($final_matrix_rowCount[$que_id][$splited_value[0]])) {
                                            $final_matrix_rowCount[$que_id][$splited_value[0]] = (int) $final_matrix_rowCount[$que_id][$splited_value[0]] + 1;
                                        } else {
                                            $final_matrix_rowCount[$que_id][$splited_value[0]] = 1;
                                        }
                                    }
                                    $GLOBALS['log']->fatal('matrix final answers : ', print_r($final_matrix_rowCount, 1));


                                    //Add answered and skipped persons of matrix
                                    $AnsweredAndSkippedPerson[$que_id]['answered'] = $matrixAnsweredPerson;
                                    $AnsweredAndSkippedPerson[$que_id]['skipped'] = ($total_submitted_que - (int) $matrixAnsweredPerson);

                                    $details[$que_id]['answers'][$row['name']]['sub_ans'] = (!is_null($answerSubmissionCount['matrix'][$que_id])) ? $answerSubmissionCount['matrix'][$que_id] : 0;
                                    $details[$que_id]['answers'][$row['name']]['percent'] = number_format(($answerSubmissionCount[$row['name']] * 100) / ((empty($matrixAnsweredPerson) || $matrixAnsweredPerson == 0 || $matrixAnsweredPerson == '0') ? 1 : $matrixAnsweredPerson), 2);

                                    $details[$que_id]['matrix_row'] = $matrix_rows[$que_id];
                                    $details[$que_id]['matrix_col'] = $matrix_cols[$que_id];
                                } else {
                                    $details[$row['que_id']]['answers'][$row['answer_id']]['ans_name'] = nl2br($row['name']);
                                }
                            }
                            if($oQuestion->question_type == 'Rating')
                            {
                                foreach($details[$que_id]['answers'] as $ans_id => $ans_details){
                                    foreach($ans_details as $ans_name => $ans_val){
                                        if (empty($ans_val)) {
                                            $rating_final_count[0] += 1;
                                        } else {
                                            $rating_final_count[$ans_val] = $rating_final_count[$ans_val] + 1;
                                        }
                                    }
                                }
                            }
                        }
                        // Rating Calculation start
                        if ($oQuestion->question_type == 'Rating') {

                            if (is_array($rating_final_count)) {
                                for ($i = 0; $i <= $starCount; $i++) {
                                    if (empty($rating_final_count[$i])) {
                                        $rating_final_count[$i] = 0;
                                    }
                                }
                            }
                            foreach ($rating_final_count as $key => $count) {
                                //check if total submitted question are greater than 1 or not and the key value is number or not
                                if ($AnsweredAndSkippedPerson[$que_id]['answered'] != 0 && !is_array($count)) {
                                    $rating_pecent[$key] = ($count * 100) / $AnsweredAndSkippedPerson[$que_id]['answered'];
                                }
                            }
                            $rating_final_count_array[$que_id] = $rating_final_count;

                            // Rating Calculation End
                            $rating[$que_id] = "

                            <script> $(document).ready(function () {
                            var starCount = $starCount;
                            var rating_pecent  = " . json_encode($rating_pecent) . ";
                            for (var i = 0; i <= starCount; i++) {
                            $('#progressbar-'+i+'_{$que_id}').progressbar({
                            value: rating_pecent[i]});
                            }

                            });
                            </script>";
                        }

                        if ($details[$que_id]['page_id'] != $page) {
                            unset($details[$que_id]);
                        }
                        $que_details[$page_seq]['page'] = $details;
                    }
                }
                //set pagination
                $module_types = getReportData($type, $survey_id);
                foreach ($module_types as $key => $val) {
                    $module_id = $key;
                }
                if (count($que_details)) {
                    $pagination = new pagination($que_details, (isset($_GET['page']) ? $_GET['page'] : 1), 1);
                    $pagination->setShowFirstAndLast(true);
                    $QueReportData = $pagination->getResults();
                    if (count($QueReportData) != 0) {
                        $queReoort_pageNumbers = '<div class="numbers">' . $pagination->getLinks($_GET) . '</div>';
                    }
                }
                //check any one submited data
                $check_submit_query = "SELECT
                bc_submission_data. *
                FROM bc_submission_data
                JOIN bc_submission_data_bc_survey_submission_c
                ON bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_submission_data_idb = bc_submission_data.id
                AND bc_submission_data_bc_survey_submission_c.deleted = 0
                JOIN bc_survey_submission
                ON bc_survey_submission.id = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
                AND bc_survey_submission.deleted = 0
                JOIN bc_survey_submission_bc_survey_c
                ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
                AND bc_survey_submission_bc_survey_c.deleted = 0
                JOIN bc_survey
                ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                AND bc_survey.deleted = 0
                WHERE bc_survey.id = '{$survey_id}'
                AND bc_submission_data.deleted = 0";
                if (!empty($survey_type)) {
                    if ($survey_type == 'Combined') {
                        $check_submit_query .= "";
                    } else {
                        $check_submit_query .= " AND bc_survey_submission.submission_type = '{$survey_type}'";
                    }
                }

                $result = $db->query($check_submit_query);
                $count_num_rows = 0;
                while($$answer = $db->fetchByAssoc($result)){
                    $count_num_rows++;
                }
                if ($count_num_rows == 0) {
                    $QueReportData = "There is no submission for this Survey.";
                }

                $status_response = array(
                    'survey_name' => $oSurvey->name,
                    'total_submitted_que' => $total_submitted_que,
                    'QueReportData' => $QueReportData,
                    'page' => $page,
                    'AnsweredAndSkippedPerson' => $AnsweredAndSkippedPerson,
                    'survey_for_rating' => $rating,
                    'rating_final_count' => $rating_final_count_array,
                    'queReoort_pageNumbers' => $queReoort_pageNumbers,
                );

                $chart_ids = array();
                $scale_chart_ids = array();
                $matrix_chart_ids = array();
                $chart_display_data = array();
                //getting coulmn chart data for multi choice type of question data
                if (is_array($QueReportData)) {
                    foreach ($QueReportData as $key => $QueReportData) {
                        $matrix_col_counter = 1;
                        $count = 0;

                        foreach ($QueReportData['page'] as $qid => $survey_detail) {
                            $multi_data = array();
                            $counter_display_matrix[$qid] = array();
                            if ($qid != 'page_title') {

                                if ($survey_detail['que_type'] == 'Scale') {
                                    $multi_data[] = array('Task', 'Count', array(role => 'style'));
                                    array_push($scale_chart_ids, $qid);
                                    asort($survey_detail['answers']);
                                    $min = $survey_detail['min'] ? $survey_detail['min'] : 0;
                                    $max = $survey_detail['max'] ? $survey_detail['max'] : 100;
                                    $scale_slot = $survey_detail['scale_slot'] ? $survey_detail['scale_slot'] : 10;
                                    $column_array = array();
                                    for ($val = $min; $val < $max;) {
                                        $column_array[$val] = 0;
                                        $val = $val + $scale_slot;
                                    }
                                } else if ($survey_detail['que_type'] == 'Matrix') {
                                    $matrix = split('_', $row['name']);
                                    // Initialize counter - count number of rows & columns
                                    $row_count = 0;
                                    $col_count = 0;
                                    // Do the loop
                                    foreach ($matrix_rows[$qid] as $result) {
                                        // increment row counter
                                        $row_count++;
                                    }
                                    foreach ($matrix_cols[$qid] as $result) {
                                        // increment  column counter
                                        $col_count++;
                                    }
                                    $multi_data[] = array('Rows');
                                    for ($i = 1; $i <= $row_count; $i++) {
                                        // increment row counter
                                        $multi_data[$i] = array($matrix_rows[$qid]->$i);
                                        for ($j = 1; $j <= $col_count + 1; $j++) {
                                            // increment  column counter
                                            if (!empty($matrix_cols[$qid]->$j)) {
                                                $multi_data[0][$j] = $matrix_cols[$qid]->$j;

                                                //updating column count to array rows
                                                array_push($multi_data[$i], 0);
                                            }
                                        }
                                    }

                                    array_push($matrix_chart_ids, $qid);
                                } else {
                                    $multi_data[] = array('Task', 'Percentage', array(role => 'style'));
                                    array_push($chart_ids, $qid);
                                }
                                $matri_all_count_array = array();
                                $matri_all_count_array_main = array();
                                foreach ($survey_detail['answers'] as $answers) {
                                    if ($survey_detail['que_type'] == 'Scale') {
                                        if (is_array($answers) && is_array($column_array)) {
                                            foreach ($column_array as $answer => $count) {
                                                // if answer submitted for given value then update the counter
                                                if ($answer == $answers['ans_name']) {
                                                    $column_array[$answer] = $answers['sub_ans'];
                                                }
                                            }
                                        }
                                        foreach ($column_array as $answer => $count) {
                                            $random_color = rand(200000, 700000);
                                            $random_color = '#' . $random_color;
                                            $multi_data[] = array($answer, (int) number_format($count), $random_color);
                                            $chart_display_data[$qid]['chart_values'] = $multi_data;
                                            $chart_display_data[$qid]['chart_title'] = $survey_detail['name'];
                                            $flag = 1;
                                        }
                                        $multi_data = array();
                                        $multi_data[] = array('Task', 'Count', array(role => 'style'));
                                    } else if ($survey_detail['que_type'] != 'ContactInformation' && $survey_detail['que_type'] != 'CommentTextbox' && $survey_detail['que_type'] != 'Textbox' && $survey_detail['que_type'] != 'Rating' && $survey_detail['que_type'] != 'Date' && $survey_detail['que_type'] != 'Scale' && $survey_detail['que_type'] != 'Matrix') {
                                        if (is_array($answers)) {
                                            $random_color = rand(200000, 800000);
                                            $random_color = '#' . $random_color;
                                            $multi_data[] = array($answers['ans_name'], (int) number_format($answers['percent']), $random_color);
                                            //                                            $chart_display_data[$qid] = $multi_data;
                                            $chart_display_data[$qid]['chart_values'] = $multi_data;
                                            $chart_display_data[$qid]['chart_title'] = $survey_detail['name'];
                                            $flag = 1;
                                        }
                                    } else if ($survey_detail['que_type'] == 'Matrix') {
                                        if (is_array($answers)) {
                                            foreach ($survey_detail['answers']['ans_detail'] as $aAns) {
                                                if (!empty($aAns)) {
                                                    $matrix = split('_', $aAns);
                                                    for ($i = 1; $i <= $row_count; $i++) {
                                                        // increment row counter
                                                        for ($j = 1; $j <= $col_count + 1; $j++) {
                                                            // increment  column counter
                                                            if ($matrix[0] == $i && $matrix[1] == $j) {
                                                                $matrixAnsweredPerson = $final_matrix_rowCount[$qid][$i];
                                                                $matri_all_count_array[$i][$j] = $answers['sub_ans'][$i][$j];
                                                                $matri_all_count_array_main[$i][$j] = $answers['sub_ans'][$i][$j] . '&nbsp;(' . number_format(($answers['sub_ans'][$i][$j] * 100) / ((empty($matrixAnsweredPerson) || $matrixAnsweredPerson == 0 || $matrixAnsweredPerson == '0') ? 1 : $matrixAnsweredPerson), 2) . '%)';
                                                            }
                                                            if (!empty($matrix_cols[$qid]->$j)) {
                                                                $multi_data[$i][$j] = !empty($matri_all_count_array[$i][$j]) ? $matri_all_count_array[$i][$j] : 0;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                            $chart_display_data[$qid]['chart_values'] = $multi_data;
                                            $chart_display_data[$qid]['chart_title'] = $survey_detail['name'];
                                            $flag = 1;
                                            $matrix_col_counter++;
                                        }
                                    }

                                    if ($flag == 1) {
                                        $flag = 0; // reset flag for other question
                                        $chart_flag = 1; // to set flag for chart after all records data will be set in array and html
                                    }
                                }
                                if (empty($survey_detail['answers']) && $survey_detail['que_type'] == 'Scale') {
                                    $multi_data[] = array('0', (int) 0, '#fff');
                                    $chart_display_data[$qid]['chart_values'] = $multi_data;
                                    $chart_display_data[$qid]['chart_title'] = $survey_detail['name'];
                                    $chart_flag = 1;
                                }
                            }
                            $counter_display_matrix[$qid] = $matri_all_count_array_main;
                            // $GLOBALS['log']->fatal("This is the matrix all count array : " . print_r($counter_display_matrix, 1));
                        }
                        //  $GLOBALS['log']->fatal("This is the chart display data : " . print_r($chart_display_data, 1));
                        if ($chart_flag == 1) {
                            $multi_data_array = $chart_display_data;
                            $chart_flag = 0;
                        }
                    }
                }
            } elseif ($type == 'individual') {
                $module_types = getReportData($type, $survey_id);
                foreach ($module_types as $id => $module_detail) {
                    $name[$id] = array(
                        'name' => $module_detail['customer_name'],
                        'module_name' => $module_detail['module_name'],
                        'type' => $module_detail['type'],
                        'survey_status' => $module_detail['survey_status'],
                        'module_id' => $module_detail['module_id'],
                        'send_date' => $module_detail['send_date'],
                        'receive_date' => $module_detail['receive_date'],
                        'change_request' => $module_detail['change_request'],
                        'submission_id' => $module_detail['submission_id'],
                    );
                    //set pagination
                    $page = isset($_GET['page']) ? $_GET['page'] : 1;
                    if (count($name)) {
                        $pagination = new pagination($name, (isset($_GET['page']) ? $_GET['page'] : 1), 50);
                        $pagination->setShowFirstAndLast(true);
                        $Individual_ReportData = $pagination->getResults();
                        if (count($Individual_ReportData) != 0) {
                            $Individual_Report_pageNumbers = '<div class="numbers">' . $pagination->getLinks($_GET) . '</div>';
                        }
                    }
                }
            }
            $datadisplay = $QueReportData;
            $answer_skipp = $AnsweredAndSkippedPerson;
            $sugarSmarty = new Sugar_Smarty();
            // print out the page numbers beneath the results
            $sugarSmarty->assign('Que_pageNumbers', isset($queReoort_pageNumbers) ? $queReoort_pageNumbers : "");
            $sugarSmarty->assign('Individual_pageNumbers', isset($Individual_Report_pageNumbers) ? $Individual_Report_pageNumbers : "");
            $sugarSmarty->assign('survey', isset($QueReportData) ? $QueReportData : "");
            $sugarSmarty->assign('rating', $rating);
            $sugarSmarty->assign('survey_status', $survey);
            $sugarSmarty->assign('ans_skipp', $answer_skipp);


            $sugarSmarty->assign('line_chart', json_encode($lineChart));
            $sugarSmarty->assign('survey_start_date', $survey_start_date);
            $sugarSmarty->assign('survey_end_date', $survey_end_date);
            $sugarSmarty->assign('linechart_max_count', $total_send_survey);
            $sugarSmarty->assign('chart_id', json_encode($chart_ids));
            $sugarSmarty->assign('data_display', json_encode($multi_data_array));
            $sugarSmarty->assign('displaymatrix', json_encode($datadisplay));
            $sugarSmarty->assign('matrix_data', json_encode($counter_display_matrix));


            $sugarSmarty->assign('matrix_chart_ids', json_encode($matrix_chart_ids));
            $sugarSmarty->assign('scale_chart_ids', json_encode($scale_chart_ids));

            $sugarSmarty->assign('survey_id', $survey_id);
            $sugarSmarty->assign('type', $type);
            $sugarSmarty->assign('name', isset($Individual_ReportData) ? $Individual_ReportData : "");
            $sugarSmarty->assign('survey_name', $survey_obj->name);
            $sugarSmarty->assign('page', $page);
            $sugarSmarty->assign('total_responses', $total_submitted_que);
            $sugarSmarty->assign('rating_count', json_encode($rating_final_count_array));
            $sugarSmarty->display('modules/bc_survey/tpl/report.tpl');
            parent::display();
        }
    }

}
