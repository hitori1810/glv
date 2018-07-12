<?php

/**
 * save survey template,edit and detail of survey in survey template module
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once('include/MVC/Controller/SugarController.php');

/**
 * save data in survey template module
 *
 * @author     Original Author Biztech Co.
 */
class bc_survey_templateController extends SugarController {

    function action_save() {
        $template = new bc_survey_template();
        if ($_REQUEST['record'] != '') {
            $template->retrieve($_REQUEST['record']);
        }
        $template->name = $_REQUEST['name'];
        $template->assigned_user_id = $_REQUEST['assigned_user_id'];
        $template->description = $_REQUEST['description'];
        $template->save();
        $page_numbers = $_REQUEST['page_number'];
        $page_count = 1;
        foreach ($page_numbers as $pg_index => $page_number) {
            // Save Page
            $survey_page = new bc_survey_pages();
            $survey_page->id = $_REQUEST['page_id'][$pg_index];
            if (isset($survey_page->id)) {
                $survey_page->retrieve($survey_page->id);
            }
            $survey_page->name = $_REQUEST['page_title'][$pg_index];
            $survey_page->page_number = $page_count;
            $survey_page->type = 'SurveyTemplate';
            $survey_page->page_sequence = $page_count;
            $survey_page->save();
            // Set replationship b/w Template and Survey page
            $survey_page->bc_survey_pages_bc_survey_template->delete($survey_page->id, $template->id);
            $survey_page->load_relationship('bc_survey_pages_bc_survey_template');
            $survey_page->bc_survey_pages_bc_survey_template->add($template->id);
            $que_count = 1;
            foreach ($_REQUEST['que_title'][$pg_index] as $que_index => $que_title) {
                //Save Questions
                $survey_que = new bc_survey_questions();
                $survey_que->id = $_REQUEST['que_id'][$pg_index][$que_index];
                if (isset($survey_que->id)) {
                    $survey_que->retrieve($survey_que->id);
                }
                $survey_que->name = $que_title;
                $survey_que->question_type = $_REQUEST['que_type'][$pg_index][$que_index];
                $survey_que->is_required = isset($_REQUEST['is_required'][$pg_index][$que_index]) ? $_REQUEST['is_required'][$pg_index][$que_index] : 0;
                $survey_que->question_sequence = $que_count;
                $survey_que->question_help_comment = $_REQUEST['question_help_comment'][$pg_index][$que_index];
                $survey_que->enable_scoring = $_REQUEST['enable_scoring_dropdownlist'][$pg_index][$que_index];
                $survey_que->enable_other_option = $_REQUEST['enable_option'][$pg_index][$que_index];
                $survey_que->selection_limit = $_REQUEST['limit_dropdown'][$pg_index][$que_index];
                $que_type = $_REQUEST['que_type'][$pg_index][$que_index];
                if ($_REQUEST['enable_scoring_dropdownlist'][$pg_index][$que_index]) {
                    if ($que_type == "MultiSelectList" || $que_type == "Checkbox") {
                        $sum = 0;
                        foreach ($_REQUEST['score_dropdownlist'][$pg_index][$que_index] as $score_index => $row) {
                            $value = (int) $_REQUEST['score_dropdownlist'][$pg_index][$que_index][$score_index];
                            if ($value > 0 && $value != '') {
                                $sum = $sum + $value;
                            }
                        }
                        $score = 0;
                        if (isset($_REQUEST['enable_option'][$pg_index][$que_index])) {
                            $score = $_REQUEST['score_dropdownlist_other'][$pg_index][$que_index];
                        }
                        $sum = $sum + $score;
                        $survey_que->base_weight = $sum;
                    } else {
                        $max = 0;
                        foreach ($_REQUEST['score_dropdownlist'][$pg_index][$que_index] as $score_index => $row) {
                            $value = (int) $_REQUEST['score_dropdownlist'][$pg_index][$que_index][$score_index];
                            if ($value > 0 && $value != '') {
                                if ($value > $max) {
                                    $max = $value;
                                }
                            }
                        }
                        $score = 0;
                        if (isset($_REQUEST['enable_option'][$pg_index][$que_index])) {
                            $score = $_REQUEST['score_dropdownlist_other'][$pg_index][$que_index];
                        }
                        if ($score > $max) {
                            $max = $score;
                        }
                        $survey_que->base_weight = $max;
                    }
                }
                //for save textbox advaced type
                if ($que_type == "Textbox") {
                    $survey_que->advance_type = isset($_REQUEST['datatype_textbox'][$pg_index][$que_index]) ? $_REQUEST['datatype_textbox'][$pg_index][$que_index] : '';
                    $datatype = $_REQUEST['datatype_textbox'][$pg_index][$que_index];
                    if ($datatype == "Integer") {
                        $survey_que->max = isset($_REQUEST['max_integer'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['max_integer'][$pg_index][$que_index]) : '';
                        $survey_que->min = isset($_REQUEST['min_integer'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['min_integer'][$pg_index][$que_index]) : '';
                    } else if ($datatype == "Float") {
                        $survey_que->max = isset($_REQUEST['max_float'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['max_float'][$pg_index][$que_index]) : '';
                        $survey_que->min = isset($_REQUEST['min_float'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['min_float'][$pg_index][$que_index]) : '';
                        $survey_que->precision_value = isset($_REQUEST['precision_textbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['precision_textbox'][$pg_index][$que_index]) : '';
                    } else {
                        $survey_que->maxsize = isset($_REQUEST['size_textbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['size_textbox'][$pg_index][$que_index]) : '';
                    }
                }
                //for save CommentTextbox advaced type
                if ($que_type == "CommentTextbox") {
                    $survey_que->maxsize = isset($_REQUEST['size_textbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['size_textbox'][$pg_index][$que_index]) : '';
                    $survey_que->max = isset($_REQUEST['cols_commentbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['cols_commentbox'][$pg_index][$que_index]) : '';
                    $survey_que->min = isset($_REQUEST['rows_commentbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['rows_commentbox'][$pg_index][$que_index]) : '';
                }
                //for save Rating advaced type
                if ($que_type == "Rating") {
                    $survey_que->maxsize = isset($_REQUEST['starno_rating'][$pg_index][$que_index]) ? $_REQUEST['starno_rating'][$pg_index][$que_index] : '';
                }
                //for save ContactInformation advaced type
                if ($que_type == "ContactInformation") {
                    if ($_REQUEST['is_required'][$pg_index][$que_index]) {
                        $required_fields = isset($_REQUEST['cname'][$pg_index][$que_index]) ? "Name" . ' ' : '';
                        $required_fields.=isset($_REQUEST['email'][$pg_index][$que_index]) ? "Email" . ' ' : '';
                        $required_fields.=isset($_REQUEST['company'][$pg_index][$que_index]) ? "Company" . ' ' : '';
                        $required_fields.=isset($_REQUEST['phone'][$pg_index][$que_index]) ? "Phone" . ' ' : '';
                        $required_fields.=isset($_REQUEST['address'][$pg_index][$que_index]) ? "Address" . ' ' : '';
                        $required_fields.=isset($_REQUEST['address2'][$pg_index][$que_index]) ? "Address2" . ' ' : '';
                        $required_fields.=isset($_REQUEST['city'][$pg_index][$que_index]) ? "City" . ' ' : '';
                        $required_fields.=isset($_REQUEST['state'][$pg_index][$que_index]) ? "State" . ' ' : '';
                        $required_fields.=isset($_REQUEST['zip'][$pg_index][$que_index]) ? "Zip" . ' ' : '';
                        $required_fields.=isset($_REQUEST['country'][$pg_index][$que_index]) ? "Country" . ' ' : '';
                        $survey_que->advance_type = $required_fields;
                    }
                }
                //for save multiple choice advaced type
                if ($que_type == "MultiSelectList" || $que_type == "DrodownList") {
                    $survey_que->is_sort = isset($_REQUEST['is_sort_check-box'][$pg_index][$que_index]) ? $_REQUEST['is_sort_check-box'][$pg_index][$que_index] : 0;
                }
                //for save multiple choice advaced type
                if ($que_type == "Checkbox" || $que_type == "RadioButton") {
                    $survey_que->is_sort = isset($_REQUEST['is_sort_check-box'][$pg_index][$que_index]) ? $_REQUEST['is_sort_check-box'][$pg_index][$que_index] : 0;
                    $survey_que->advance_type = isset($_REQUEST['display_radio_button'][$pg_index][$que_index]) ? $_REQUEST['display_radio_button'][$pg_index][$que_index] : '';
                }
                //for save Image advaced type
                if ($que_type == "Image") {
                    if ($_REQUEST['que_title'][$pg_index][$que_index] == "upload") {
                        $img_id = create_guid();
                        if (is_array($_FILES)) {
                            if (is_uploaded_file($_FILES['img_url']['tmp_name'][$pg_index][$que_index])) {
                                $sourcePath = $_FILES['img_url']['tmp_name'][$pg_index][$que_index];
                                $targetPath = "custom/include/Image_question/" . $img_id;
                                unlink($targetPath);
                                if (move_uploaded_file($sourcePath, $targetPath)) {
                                    $survey_que->advance_type = $img_id;
                                }
                            }
                        }
                    } else {
                        $survey_que->advance_type = isset($_REQUEST['img_url'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['img_url'][$pg_index][$que_index]) : '';
                    }
                }
                //for save video advaced type
                if ($que_type == "Video") {
                    $survey_que->description = isset($_REQUEST['video_desc'][$pg_index][$que_index]) ? $_REQUEST['video_desc'][$pg_index][$que_index] : '';
                }
                //for save scale advaced type
                if ($que_type == "Scale") {
                    $label = '';
                    $label.= isset($_REQUEST['left'][$pg_index][$que_index]) ? $_REQUEST['left'][$pg_index][$que_index] . '-' : '';
                    $label.=isset($_REQUEST['middle'][$pg_index][$que_index]) ? $_REQUEST['middle'][$pg_index][$que_index] . '-' : '';
                    $label.=isset($_REQUEST['right'][$pg_index][$que_index]) ? $_REQUEST['right'][$pg_index][$que_index] : '';
                    $survey_que->advance_type = $label;
                    $survey_que->max = isset($_REQUEST['end'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['end'][$pg_index][$que_index]) : '';
                    $survey_que->min = isset($_REQUEST['start'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['start'][$pg_index][$que_index]) : '';
                    $survey_que->scale_slot = isset($_REQUEST['step_value'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['step_value'][$pg_index][$que_index]) : '';
                }
                //for save date advaced type
                if ($que_type == "Date") {
                    $survey_que->is_datetime = isset($_REQUEST['is_datetime'][$pg_index][$que_index]) ? $_REQUEST['is_datetime'][$pg_index][$que_index] : 0;
                    $survey_que->min = isset($_REQUEST['start_date_time'][$pg_index][$que_index]) ? $_REQUEST['start_date_time'][$pg_index][$que_index] : '';
                    $survey_que->max = isset($_REQUEST['end_date_time'][$pg_index][$que_index]) ? $_REQUEST['end_date_time'][$pg_index][$que_index] : '';
                }
                //for save matrix advaced type
                if ($que_type == "Matrix") {
                    $survey_que->advance_type = isset($_REQUEST['display_type_matrix'][$pg_index][$que_index]) ? $_REQUEST['display_type_matrix'][$pg_index][$que_index] : '';
                    //save matrix row
                    $row_count = 1;
                    $row_detail = array();
                    foreach ($_REQUEST['row'][$pg_index][$que_index] as $row_index => $row) {
                        $row_detail[$row_count] .= $_REQUEST['row'][$pg_index][$que_index][$row_index];
                        $row_count++;
                    }
                    $survey_que->matrix_row = base64_encode(json_encode($row_detail));
                    //save matrix columns
                    $col_count = 1;
                    $col_detail = array();
                    foreach ($_REQUEST['col'][$pg_index][$que_index] as $col_index => $col) {
                        $col_detail[$col_count] .= $_REQUEST['col'][$pg_index][$que_index][$col_index];
                        $col_count++;
                    }
                    $survey_que->matrix_col = base64_encode(json_encode($col_detail));
                }
                $survey_que->save();
                // Set replationship b/w Survey page and Survey Questions

                $survey_que->bc_survey_pages_bc_survey_questions->delete($survey_que->id, $survey_page->id);
                $survey_que->load_relationship('bc_survey_pages_bc_survey_questions');
                $survey_que->bc_survey_pages_bc_survey_questions->add($survey_page->id);

                //Save Survey Answers
                if (isset($_REQUEST['enable_scoring_dropdownlist'][$pg_index][$que_index])) {
                    $ans_count = 1;
                    foreach ($_REQUEST['answer'][$pg_index][$que_index] as $ans_index => $answer) {
                        $survey_ans = new bc_survey_answers();
                        $survey_ans->id = $_REQUEST['ans_id'][$pg_index][$que_index][$ans_index];
                        if (isset($survey_ans->id)) {
                            $survey_ans->retrieve($survey_ans->id);
                        }
                        $survey_ans->score_weight = isset($_REQUEST['score_dropdownlist'][$pg_index][$que_index][$ans_index]) ? $_REQUEST['score_dropdownlist'][$pg_index][$que_index][$ans_index] : 0;
                        $survey_ans->name = $answer;
                        $survey_ans->answer_sequence = $ans_count;
                        $survey_ans->save();
                        //set rlationship b/w Survey question and Survey answers
                        $survey_ans->bc_survey_answers_bc_survey_questions->delete($survey_ans->id, $survey_que->id);
                        $survey_ans->load_relationship('bc_survey_answers_bc_survey_questions');
                        $survey_ans->bc_survey_answers_bc_survey_questions->add($survey_que->id);
                        $ans_count++;
                    }
                } else {
                    $ans_count = 1;
                    foreach ($_REQUEST['answer'][$pg_index][$que_index] as $ans_index => $answer) {
                        $survey_ans = new bc_survey_answers();
                        $survey_ans->id = $_REQUEST['ans_id'][$pg_index][$que_index][$ans_index];
                        if (isset($survey_ans->id)) {
                            $survey_ans->retrieve($survey_ans->id);
                        }
                        $survey_ans->name = $answer;
                        $survey_ans->answer_sequence = $ans_count;
                        $survey_ans->save();
                        //set rlationship b/w Survey question and Survey answers
                        $survey_ans->bc_survey_answers_bc_survey_questions->delete($survey_ans->id, $survey_que->id);
                        $survey_ans->load_relationship('bc_survey_answers_bc_survey_questions');
                        $survey_ans->bc_survey_answers_bc_survey_questions->add($survey_que->id);
                        $ans_count++;
                    }
                }
                if (isset($_REQUEST['enable_option'][$pg_index][$que_index])) {
                    $survey_ans = new bc_survey_answers();
                    $survey_ans->id = $_REQUEST['ans_id_other'][$pg_index][$que_index];
                    if (isset($survey_ans->id)) {
                        $survey_ans->retrieve($survey_ans->id);
                    }
                    $survey_ans->score_weight = isset($_REQUEST['score_dropdownlist_other'][$pg_index][$que_index]) ? $_REQUEST['score_dropdownlist_other'][$pg_index][$que_index] : 0;
                    $survey_ans->ans_type = 'other';
                    $survey_ans->name = $_REQUEST['answer_other'][$pg_index][$que_index];
                    $survey_ans->answer_sequence = $ans_count;
                    $survey_ans->save();
                    //set rlationship b/w Survey question and Survey answers
                    $survey_ans->bc_survey_answers_bc_survey_questions->delete($survey_ans->id, $survey_que->id);
                    $survey_ans->load_relationship('bc_survey_answers_bc_survey_questions');
                    $survey_ans->bc_survey_answers_bc_survey_questions->add($survey_que->id);
                } else {
                    $survey_ans = new bc_survey_answers();
                    $survey_ans->id = $_REQUEST['ans_id_other'][$pg_index][$que_index];
                    if (isset($survey_ans->id)) {
                        $survey_ans->retrieve($survey_ans->id);
                    }
                    $survey_ans->deleted = 1;
                    $survey_ans->save();
                    $survey_ans->bc_survey_answers_bc_survey_questions->delete($survey_ans->id, $survey_que->id);
                }
                $que_count++;
            }
            $page_count++;
        }

        $pages_deleted_array = array();
        $pages_deleted = $_REQUEST['page_deleted'];
        //for remove page and its question answer records while edit
        if (isset($pages_deleted) && $pages_deleted != '') {
            if (strstr($pages_deleted, ',')) {
                $pages_deleted_array = explode(',', $pages_deleted);
            } else {
                $pages_deleted_array[0] = $pages_deleted;
            }
            foreach ($pages_deleted_array as $page_for_delete) {
                $survey_page = new bc_survey_pages();
                $survey_page->retrieve($page_for_delete);
                $survey_page->load_relationship('bc_survey_pages_bc_survey_template');
                foreach ($survey_page->bc_survey_pages_bc_survey_template->getBeans() as $questions) {
                    $survey_que = new bc_survey_questions();
                    $survey_que->retrieve($questions->id);
                    $survey_que->load_relationship('bc_survey_answers_bc_survey_questions');
                    foreach ($survey_que->bc_survey_answers_bc_survey_questions->getBeans() as $answers) {
                        $survey_ans = new bc_survey_answers();
                        $survey_ans->retrieve($answers->id);
                        $survey_ans->deleted = 1;
                        $survey_ans->save();
                    }
                    $survey_que->deleted = 1;
                    $survey_que->save();
                }
                $survey_page->deleted = 1;
                $survey_page->save();
            }
        }
        //for remove question and its answer while edit
        $questions_deleted = $_REQUEST['question_deleted'];
        $questions_deleted_array = array();
        if (isset($questions_deleted) && $questions_deleted != '') {
            if (strstr($questions_deleted, ',')) {
                $questions_deleted_array = explode(',', $questions_deleted);
            } else {
                $questions_deleted_array[0] = $questions_deleted;
            }
            foreach ($questions_deleted_array as $question_for_delete) {
                $survey_que = new bc_survey_questions();
                $survey_que->retrieve($question_for_delete);
                $survey_que->load_relationship('bc_survey_answers_bc_survey_questions');
                foreach ($survey_que->bc_survey_answers_bc_survey_questions->getBeans() as $answers) {
                    $survey_ans = new bc_survey_answers();
                    $survey_ans->retrieve($answers->id);
                    $survey_ans->deleted = 1;
                    $survey_ans->save();
                }
                $survey_que->deleted = 1;
                $survey_que->save();
            }
        }
        // for remove answer while edit
        $answers_deleted = $_REQUEST['answer_deleted'];
        $answers_deleted_array = array();
        if (isset($answers_deleted) && $answers_deleted != "") {
            if (strstr($answers_deleted, ',')) {
                $answers_deleted_array = explode(',', $answers_deleted);
            } else {
                $answers_deleted_array[0] = $answers_deleted;
            }
            foreach ($answers_deleted_array as $answer_for_delete) {
                $survey_ans = new bc_survey_answers();
                $survey_ans->retrieve($answer_for_delete);
                $survey_ans->deleted = 1;
                $survey_ans->save();
            }
        }
        header("Location: index.php?module=bc_survey_template&action=DetailView&record={$template->id}");
        exit;
    }

    /**
     * create page
     *
     * @author     Original Author Biztech Co.
     * @return     string
     */
    function action_createPage() {
        $page_number = $_REQUEST['page_number'];
        include 'custom/include/modules/bc_survey_template/html/page_setting.php';
        exit;
    }

    /**
     * DetailView Of Survey module
     *
     * @author     Original Author Biztech Co.
     * @return     array
     */
    function action_detail_template() {
        include_once 'custom/include/pagination.class.php';
        //$sugarSmarty = new Sugar_Smarty();
        $action = array('action' => 'DetailView');
        $record_id = (!empty($_REQUEST['record'])) ? $_REQUEST['record'] : '';
        $survey_details = array();
        $template = new stdClass();
        global $app_list_strings;
        if (isset($record_id) && $record_id != '') {
            $template = new bc_survey_template();
            $template->retrieve($record_id);
            $template->load_relationship('bc_survey_pages_bc_survey_template');
            $page_count = 0;
            $questions = array();
            foreach ($template->bc_survey_pages_bc_survey_template->getBeans() as $pages) {
                unset($questions);
                $survey_details[$pages->page_sequence]['page_title'] = $pages->name;
                $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
                $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
                $survey_details[$pages->page_sequence]['page_id_del'] = $pages->id;
                $pages->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
                    $questions[$survey_questions->question_sequence]['que_id_del'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_title'] = $survey_questions->name;
                    $questions[$survey_questions->question_sequence]['que_type'] = $app_list_strings['question_type_list'][$survey_questions->question_type];
                    $questions[$survey_questions->question_sequence]['question_help_comment'] = $survey_questions->question_help_comment;
                    $questions[$survey_questions->question_sequence]['is_required'] = $survey_questions->is_required;
                    $questions[$survey_questions->question_sequence]['matrix_row'] = json_decode(base64_decode($survey_questions->matrix_row));
                    $questions[$survey_questions->question_sequence]['matrix_col'] = json_decode(base64_decode($survey_questions->matrix_col));
                    $que_type = $survey_questions->question_type;
                    if ($que_type = "Image") {
                        $questions[$survey_questions->question_sequence]['advanced_type'] = $survey_questions->advance_type;
                    }
                    $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
                    foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
                        $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence] = array('id' => $survey_answers->id, 'name' => $survey_answers->name, 'and_id_del' => $survey_answers->id);
                    }
                    if (isset($questions[$survey_questions->question_sequence]['answers']) && is_array($questions[$survey_questions->question_sequence]['answers']))
                        ksort($questions[$survey_questions->question_sequence]['answers']);
                }
                ksort($questions);
                $survey_details[$pages->page_sequence]['page_questions'] = $questions;
            }
        }
        ksort($survey_details);
        if (count($survey_details)) {
            // Create the pagination object
            $page = (isset($_REQUEST['page']) && !is_null($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $pagination = new pagination($survey_details, $page, 1);
            // Decide if the first and last links should show
            $pagination->setShowFirstAndLast(true);
            // You can overwrite the default seperator
            // Parse through the pagination class
            $surveyTemplatePagesData = $pagination->getResults();
            // If we have items
            if (count($surveyTemplatePagesData) != 0) {
                unset($_REQUEST['action_view']);
                unset($_REQUEST['action']);
                $_REQUEST = array_merge($_REQUEST, $action);
                // Create the page numbers
                $pageNumbers = '<div class="numbers">' . $pagination->getLinks($_REQUEST) . '</div>';

                // print out the page numbers beneath the results
            }
        }
        foreach ($survey_details as $inx => $arr_element) {
            if ($page != $inx) {
                unset($survey_details[$inx]);
            }
        }
        $survey_details['pageNumbersArr'] = $pageNumbers;
        echo json_encode($survey_details);
        exit;
    }

    /**
     * EditView Of Survey module
     *
     * @author     Original Author Biztech Co.
     * @return     array
     */
    function action_edit_template() {
        include_once 'custom/include/pagination.class.php';
        $sugarSmarty = new Sugar_Smarty();
        $action = array('action' => 'DetailView');
        $record_id = (!empty($_REQUEST['record'])) ? $_REQUEST['record'] : '';
        $survey_details = array();
        $template = new stdClass();
        if (isset($record_id) && $record_id != '') {
            $template = new bc_survey_template();
            $template->retrieve($record_id);
            $template->load_relationship('bc_survey_pages_bc_survey_template');
            $page_count = 0;
            $questions = array();
            foreach ($template->bc_survey_pages_bc_survey_template->getBeans() as $pages) {
                unset($questions);
                $survey_details[$pages->page_sequence]['page_title'] = $pages->name;
                $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
                $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
                $survey_details[$pages->page_sequence]['page_id_del'] = $pages->id;
                $pages->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
                    $questions[$survey_questions->question_sequence]['que_id_del'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_title'] = $survey_questions->name;
                    $questions[$survey_questions->question_sequence]['que_type'] = $survey_questions->question_type;
                    $questions[$survey_questions->question_sequence]['question_help_comment'] = $survey_questions->question_help_comment;
                    $questions[$survey_questions->question_sequence]['is_required'] = $survey_questions->is_required;
                    $questions[$survey_questions->question_sequence]['enable_scoring'] = $survey_questions->enable_scoring;
                    $questions[$survey_questions->question_sequence]['is_enable'] = $survey_questions->enable_other_option;
                    $questions[$survey_questions->question_sequence]['selection_limit'] = $survey_questions->selection_limit;
                    $que_type = $survey_questions->question_type;
                    //for textbox advanced type
                    if ($que_type == "Textbox") {
                        $adv_type = $survey_questions->advance_type;
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                        if ($adv_type == "Integer") {
                            $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                            $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        } else if ($adv_type == "Float") {
                            $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                            $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                            $questions[$survey_questions->question_sequence]['precision_value'] = $survey_questions->precision_value;
                        } else {
                            $questions[$survey_questions->question_sequence]['maxsize'] = $survey_questions->maxsize;
                        }
                    }
                    //for CommentTextbox advanced type
                    if ($que_type == "CommentTextbox") {
                        $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                        $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        $questions[$survey_questions->question_sequence]['maxsize'] = $survey_questions->maxsize;
                    }
                    //for ContactInformation advanced type
                    if ($que_type == "ContactInformation") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for Rating advanced type
                    if ($que_type == "Rating") {
                        $questions[$survey_questions->question_sequence]['maxsize'] = $survey_questions->maxsize;
                    }
                    //for multiple choice advanced type
                    if ($que_type == "MultiSelectList" || $que_type == "DrodownList") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                    }
                    //for multiple choice advanced type
                    if ($que_type == "Checkbox" || $que_type == "RadioButton") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for image advanced type
                    if ($que_type == "Image") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for video advanced type
                    if ($que_type == "Video") {
                        $questions[$survey_questions->question_sequence]['descvideo'] = $survey_questions->description;
                    }
                    //for matrix advanced type
                    if ($que_type == "Matrix") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                        $questions[$survey_questions->question_sequence]['matrix_row'] = json_decode(base64_decode($survey_questions->matrix_row));
                        $questions[$survey_questions->question_sequence]['matrix_col'] = json_decode(base64_decode($survey_questions->matrix_col));
                    }
                    //for scale advanced type
                    if ($que_type == "Scale") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                        $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                        $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        $questions[$survey_questions->question_sequence]['step_value'] = $survey_questions->scale_slot;
                    }
                    //for date advanced type
                    if ($que_type == "Date") {
                        $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                        $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        $questions[$survey_questions->question_sequence]['is_datetime'] = $survey_questions->is_datetime;
                    }
                    $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
                    foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
                        $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence] = array('id' => $survey_answers->id, 'name' => $survey_answers->name, 'and_id_del' => $survey_answers->id, 'score_value' => $survey_answers->score_weight,'option_type' => $survey_answers->ans_type);
                    }
                    if (isset($questions[$survey_questions->question_sequence]['answers']) && is_array($questions[$survey_questions->question_sequence]['answers']))
                        ksort($questions[$survey_questions->question_sequence]['answers']);
                }
                ksort($questions);
                $survey_details[$pages->page_sequence]['page_questions'] = $questions;
            }
        }
        ksort($survey_details);
        echo json_encode($survey_details);
        exit;
    }

}
?>

