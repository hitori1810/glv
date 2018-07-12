<?php

/**
* survey save , send survey,edit survey or detail survey in survey module
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Original Author Biztech Co.
*/
require_once('include/MVC/Controller/SugarController.php');
include_once 'custom/include/pagination.class.php';

/**
* save data, and generate report,send survey popup etc.
*
* @author     Original Author Biztech Co.
*/
class bc_surveyController extends SugarController {

    function action_save() {
        require_once('modules/bc_survey_template/bc_survey_template.php');
        require_once('modules/bc_survey_pages/bc_survey_pages.php');
        require_once('modules/bc_survey_answers/bc_survey_answers.php');
        require_once('modules/bc_survey_questions/bc_survey_questions.php');
        global $current_user, $sugar_config;
        $survey = new bc_survey();
        if ($_REQUEST['record'] != '') {
            $survey->retrieve($_REQUEST['record']);
            $surveyID = $survey->id;
        } else {
            $surveyID = create_guid();
            $survey->id = $surveyID;
            $survey->new_with_id = true;
        }
        $survey->name = $_REQUEST['name'];
        $survey->assigned_user_id = $_REQUEST['assigned_user_id'];
        $survey->description = $_REQUEST['description'];
        $survey->email_template = $_REQUEST['email_template'];
        $survey->email_template_subject = $_REQUEST['email_template_subject'];
        $survey->allow_redundant_answers = $_REQUEST['allow_redundant_answers'];
        $survey->enable_data_piping = $_REQUEST['enable_data_piping'];
        $survey->allowed_resubmit_count = $_REQUEST['allowed_resubmit_count'];
        if ($survey->enable_data_piping) {
            $survey->sync_module = $_REQUEST['sync_module'];
            $survey->sync_type = $_REQUEST['sync_type'];
        } else {
            $survey->sync_module = '';
            $survey->sync_type = '';
        }
        if ($_REQUEST['survey_type']) {
            $type = $_REQUEST['survey_type'];
        } else {
            $type = 'survey';
        }
        $survey->survey_type = $type;
        if (strpos($_REQUEST['redirect_url'], 'http://') !== false || strpos($_REQUEST['redirect_url'], 'https://') !== false) {
            $survey->redirect_url = $_REQUEST['redirect_url'];
        } else {
            $new_url = 'http://' . $_REQUEST['redirect_url'];
            $survey->redirect_url = $new_url;
        }
        $survey->is_progress = isset($_REQUEST['is_progress']) ? $_REQUEST['is_progress'] : 0;
        if ($_FILES['logo_file']['tmp_name'] != '') {
            if (is_array($_FILES)) {
                if (is_uploaded_file($_FILES['logo_file']['tmp_name'])) {
                    $sourcePath = $_FILES['logo_file']['tmp_name'];
                    $file_ext = pathinfo($_FILES['logo_file']['name'], PATHINFO_EXTENSION);
                    $targetPath = "custom/include/surveylogo_images/" . $surveyID ."_". $_FILES['logo_file']['name'];
                    unlink($targetPath);
                    if (move_uploaded_file($sourcePath, $targetPath)) {
                        $survey->logo = $surveyID . "_". $_FILES['logo_file']['name'];
                    }
                }
            }
        }
        if ($_REQUEST['start_date']) {
            $survey->start_date = convertDateInGMT($_REQUEST['start_date']);
            $survey->end_date = convertDateInGMT($_REQUEST['end_date']);
        } else {
            $survey->start_date = $_REQUEST['start_date'];
            $survey->end_date = $_REQUEST['end_date'];
        }
        $survey->theme = $_REQUEST['survey_theme'];
        $survey->welcome_page = '';
        $survey->thanks_page  = '';
        $welcomePage = trim(strip_tags(html_entity_decode($_REQUEST['welcome_page'])));
        if(!empty($welcomePage))
            $survey->welcome_page = html_entity_decode($_REQUEST['welcome_page']);
        $thanks_page = trim(strip_tags(html_entity_decode($_REQUEST['thanks_page'])));
        if(!empty($thanks_page))
            $survey->thanks_page = html_entity_decode($_REQUEST['thanks_page']);
        $survey->save();
        //create relationship between survey and survey_template
        $template_id = $_REQUEST['template_id'];
        if ($template_id) {
            $survey_template = new bc_survey_template();
            $survey_template->retrieve($template_id);
            $survey_template->load_relationship('bc_survey_bc_survey_template');
            $survey_template->bc_survey_bc_survey_template->add($survey->id);
        }
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
            $survey_page->type = 'Survey';
            $survey_page->page_sequence = $page_count;
            $survey_page->save();
            // Set replationship b/w Survey and Survey page
            $survey_page->bc_survey_pages_bc_survey->delete($survey_page->id, $survey->id);
            $survey_page->load_relationship('bc_survey_pages_bc_survey');
            $survey_page->bc_survey_pages_bc_survey->add($survey->id);
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
                if (isset($_REQUEST['survey_type'])) {
                    $survey_que->is_required = 1;
                } else {
                    $survey_que->is_required = isset($_REQUEST['is_required'][$pg_index][$que_index]) ? $_REQUEST['is_required'][$pg_index][$que_index] : 0;
                }
                $survey_que->question_sequence = $que_count;
                $survey_que->question_help_comment = $_REQUEST['question_help_comment'][$pg_index][$que_index];
                $survey_que->enable_scoring = $_REQUEST['enable_scoring_dropdownlist'][$pg_index][$que_index];
                $survey_que->enable_other_option = isset($_REQUEST['enable_option'][$pg_index][$que_index]) ? $_REQUEST['enable_option'][$pg_index][$que_index] : 0;
                $survey_que->selection_limit = $_REQUEST['limit_dropdown'][$pg_index][$que_index];
                $survey_que->desable_piping = isset($_REQUEST['desable_piping_que'][$pg_index][$que_index]) ? $_REQUEST['desable_piping_que'][$pg_index][$que_index] : 0;
                if ($survey_que->desable_piping) {
                    $survey_que->sync_fields = '';
                } else {
                    $survey_que->sync_fields = $_REQUEST['sync_fields'][$pg_index][$que_index];
                }
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
                //for textbox question advanced type save
                if ($que_type == "Textbox") {
                    $survey_que->advance_type = isset($_REQUEST['data_type_hidden'][$pg_index][$que_index]) ? $_REQUEST['data_type_hidden'][$pg_index][$que_index] : '';
                    $datatype = $_REQUEST['data_type_hidden'][$pg_index][$que_index];
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
                    //for CommentTextbox question advanced type save
                }if ($que_type == "CommentTextbox") {
                    $survey_que->maxsize = isset($_REQUEST['size_textbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['size_textbox'][$pg_index][$que_index]) : '';
                    $survey_que->max = isset($_REQUEST['cols_commentbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['cols_commentbox'][$pg_index][$que_index]) : '';
                    $survey_que->min = isset($_REQUEST['rows_commentbox'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['rows_commentbox'][$pg_index][$que_index]) : '';
                }
                //for Rating question advanced type save
                if ($que_type == "Rating") {
                    $survey_que->maxsize = isset($_REQUEST['starno_rating'][$pg_index][$que_index]) ? $_REQUEST['starno_rating'][$pg_index][$que_index] : '';
                }
                //for ContactInformation question advanced type save
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
                //for multiple choice question advanced type save
                if ($que_type == "MultiSelectList" || $que_type == "DrodownList") {
                    $survey_que->is_sort = isset($_REQUEST['is_sort_check-box'][$pg_index][$que_index]) ? $_REQUEST['is_sort_check-box'][$pg_index][$que_index] : 0;
                    $value = 0;
                    foreach ($_REQUEST['option_value'][$pg_index][$que_index] as $logic_index => $logic) {
                        if ($_REQUEST['option_value'][$pg_index][$que_index][$logic_index] != "no_logic" && $_REQUEST['option_value'][$pg_index][$que_index][$logic_index] != null) {
                            $value = 1;
                        }
                    }
                    $survey_que->is_skip_logic = $value;
                }
                //for multiple choice question advanced type save
                if ($que_type == "Checkbox" || $que_type == "RadioButton") {
                    $survey_que->is_sort = isset($_REQUEST['is_sort_check-box'][$pg_index][$que_index]) ? $_REQUEST['is_sort_check-box'][$pg_index][$que_index] : 0;
                    $survey_que->advance_type = isset($_REQUEST['display_radio_button'][$pg_index][$que_index]) ? $_REQUEST['display_radio_button'][$pg_index][$que_index] : '';
                    $value = 0;
                    foreach ($_REQUEST['option_value'][$pg_index][$que_index] as $logic_index => $logic) {
                        if ($_REQUEST['option_value'][$pg_index][$que_index][$logic_index] != "no_logic" && $_REQUEST['option_value'][$pg_index][$que_index][$logic_index] != null) {
                            $value = 1;
                        }
                    }
                    $survey_que->is_skip_logic = $value;
                }
                //for Image question save
                if ($que_type == "Image") {
                    if ($_REQUEST['que_title'][$pg_index][$que_index] == "upload") {
                        if (!isset($_REQUEST['img_src'][$pg_index][$que_index]) || $_REQUEST['img_src'][$pg_index][$que_index] == "") {
                            if (is_array($_FILES)) {
                                $img_id = create_guid();
                                if (is_uploaded_file($_FILES['img_url']['tmp_name'][$pg_index][$que_index])) {
                                    $sourcePath = $_FILES['img_url']['tmp_name'][$pg_index][$que_index];
                                    $targetPath = "custom/include/Image_question/" . $img_id ."_". $_FILES['img_url']['name'][$pg_index][$que_index];
                                    unlink($targetPath);
                                    if (move_uploaded_file($sourcePath, $targetPath)) {
                                        $survey_que->advance_type = $img_id ."_". $_FILES['img_url']['name'][$pg_index][$que_index];
                                    }
                                }
                            }
                        } else {
                            $survey_que->advance_type = $_REQUEST['img_src'][$pg_index][$que_index];
                        }
                    } else {
                        $survey_que->advance_type = isset($_REQUEST['img_url'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['img_url'][$pg_index][$que_index]) : '';
                    }
                }
                //for Video question save
                if ($que_type == "Video") {
                    $survey_que->description = isset($_REQUEST['video_desc'][$pg_index][$que_index]) ? $_REQUEST['video_desc'][$pg_index][$que_index] : '';
                }
                //for Scale question save
                if ($que_type == "Scale") {
                    $label = isset($_REQUEST['left'][$pg_index][$que_index]) ? $_REQUEST['left'][$pg_index][$que_index] . '-' : '';
                    $label.=isset($_REQUEST['middle'][$pg_index][$que_index]) ? $_REQUEST['middle'][$pg_index][$que_index] . '-' : '';
                    $label.=isset($_REQUEST['right'][$pg_index][$que_index]) ? $_REQUEST['right'][$pg_index][$que_index] : '';
                    $survey_que->advance_type = $label;
                    $survey_que->max = isset($_REQUEST['end'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['end'][$pg_index][$que_index]) : '';
                    $survey_que->min = isset($_REQUEST['start'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['start'][$pg_index][$que_index]) : '';
                    $survey_que->scale_slot = isset($_REQUEST['step_value'][$pg_index][$que_index]) ? str_replace(' ', '', $_REQUEST['step_value'][$pg_index][$que_index]) : '';
                }
                //for Date question save
                if ($que_type == "Date") {
                    $survey_que->is_datetime = isset($_REQUEST['is_datetime'][$pg_index][$que_index]) ? $_REQUEST['is_datetime'][$pg_index][$que_index] : 0;
                    $survey_que->min = isset($_REQUEST['start_date_time'][$pg_index][$que_index]) ? $_REQUEST['start_date_time'][$pg_index][$que_index] : '';
                    $survey_que->max = isset($_REQUEST['end_date_time'][$pg_index][$que_index]) ? $_REQUEST['end_date_time'][$pg_index][$que_index] : '';
                }
                //for Matrix question save
                if ($que_type == "Matrix") {

                    $survey_que->advance_type = isset($_REQUEST['display_type_matrix'][$pg_index][$que_index]) ? $_REQUEST['display_type_matrix'][$pg_index][$que_index] : '';
                    //matrix row save
                    $row_count = 1;
                    $row_detail = array();
                    foreach ($_REQUEST['row'][$pg_index][$que_index] as $row_index => $row) {
                        $row_detail[$row_count] = $_REQUEST['row'][$pg_index][$que_index][$row_index];
                        $row_count++;
                    }
                    $survey_que->matrix_row = base64_encode(json_encode($row_detail));
                    //matrix column save
                    $col_count = 1;
                    $col_detail = array();
                    foreach ($_REQUEST['col'][$pg_index][$que_index] as $col_index => $col) {
                        $col_detail[$col_count] = $_REQUEST['col'][$pg_index][$que_index][$col_index];
                        $col_count++;
                    }
                    $survey_que->matrix_col = base64_encode(json_encode($col_detail));
                }
                $survey_que->save();
                // Set replationship b/w Survey page and Survey Questions
                $survey_que->bc_survey_pages_bc_survey_questions->delete($survey_que->id, $survey_page->id);
                $survey_que->load_relationship('bc_survey_pages_bc_survey_questions');
                $survey_que->bc_survey_pages_bc_survey_questions->add($survey_page->id);
                // Set replationship b/w Survey and Survey Questions
                $survey_que->bc_survey_bc_survey_questions->delete($survey_que->id, $survey->id);
                $survey_que->load_relationship('bc_survey_bc_survey_questions');
                $survey_que->bc_survey_bc_survey_questions->add($survey->id);

                //Save Survey Answers
                if (isset($_REQUEST['enable_scoring_dropdownlist'][$pg_index][$que_index])) {
                    $ans_count = 1;
                    foreach ($_REQUEST['answer'][$pg_index][$que_index] as $ans_index => $answer) {
                        $queston = '';
                        $survey_ans = new bc_survey_answers();
                        $ans_id = $_REQUEST['ans_id'][$pg_index][$que_index][$ans_index];
                        $answer_id = 'answer' . $pg_index . $que_index . $ans_index;
                        if ($ans_id == $answer_id) {
                            $ans_id = '';
                        }
                        $survey_ans->id = $ans_id;
                        if (isset($survey_ans->id)) {
                            $survey_ans->retrieve($survey_ans->id);
                        }
                        $survey_ans->score_weight = isset($_REQUEST['score_dropdownlist'][$pg_index][$que_index][$ans_index]) ? $_REQUEST['score_dropdownlist'][$pg_index][$que_index][$ans_index] : 0;
                        $select_option = $_REQUEST['option_value'][$pg_index][$que_index][$ans_index];
                        if ($select_option != NULL) {
                            if ($select_option == "redirect_page") {
                                $survey_ans->logic_target = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                            } else if ($select_option == "redirect_url") {
                                if (strpos($_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index], 'http://') !== false || strpos($_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index], 'https://') !== false) {
                                    $survey_ans->logic_target = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                } else {
                                    $new_redirect_url = 'http://' . $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                    $survey_ans->logic_target = $new_redirect_url;
                                }
                            } else if ($select_option == "show_hide_question") {
                                $multi_select_que = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                if (is_array($multi_select_que)) {
                                    foreach ($multi_select_que as $que_id => $queston_id) {
                                        $queston .= $queston_id . ',';
                                    }
                                    $que_id = rtrim($queston, ",");
                                    $survey_ans->logic_target = $que_id;
                                } else {
                                    $survey_ans->logic_target = $multi_select_que;
                                }
                            } else if ($select_option == "end_page") {
                                $survey_ans->logic_target = "end_of_page";
                            } else if ($select_option == "no_logic") {
                                $survey_ans->logic_target = "";
                            }
                            $survey_ans->logic_action = $select_option;
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
                } else {
                    $ans_count = 1;
                    foreach ($_REQUEST['answer'][$pg_index][$que_index] as $ans_index => $answer) {
                        $queston = '';
                        $survey_ans = new bc_survey_answers();
                        $ans_id = $_REQUEST['ans_id'][$pg_index][$que_index][$ans_index];
                        $answer_id = 'answer' . $pg_index . $que_index . $ans_index;
                        if ($ans_id == $answer_id) {
                            $ans_id = '';
                        }
                        $survey_ans->id = $ans_id;
                        if (isset($survey_ans->id)) {
                            $survey_ans->retrieve($survey_ans->id);
                        }
                        $select_option = $_REQUEST['option_value'][$pg_index][$que_index][$ans_index];
                        if ($select_option != NULL) {
                            if ($select_option == "redirect_page") {
                                $survey_ans->logic_target = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                            } else if ($select_option == "redirect_url") {
                                if (strpos($_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index], 'http://') !== false || strpos($_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index], 'https://') !== false) {
                                    $survey_ans->logic_target = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                } else {
                                    $new_redirect_url = 'http://' . $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                    $survey_ans->logic_target = $new_redirect_url;
                                }
                            } else if ($select_option == "show_hide_question") {
                                $multi_select_que = $_REQUEST['page_skipp_logic'][$pg_index][$que_index][$ans_index];
                                if (is_array($multi_select_que)) {
                                    foreach ($multi_select_que as $que_id => $queston_id) {
                                        $queston .= $queston_id . ',';
                                    }
                                    $que_id = rtrim($queston, ",");
                                    $survey_ans->logic_target = $que_id;
                                } else {
                                    $survey_ans->logic_target = $multi_select_que;
                                }
                            } else if ($select_option == "end_page") {
                                $survey_ans->logic_target = "end_of_page";
                            } else if ($select_option == "no_logic") {
                                $survey_ans->logic_target = "";
                            }
                            $survey_ans->logic_action = $select_option;
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
                $survey_page->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($survey_page->bc_survey_pages_bc_survey_questions->getBeans() as $questions) {
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
        $survey->load_relationship('bc_survey_bc_survey_questions');
        foreach ($survey->bc_survey_bc_survey_questions->getBeans() as $questions) {
            $base_score = $base_score + (int) $questions->base_weight;
        }
        if ($_REQUEST['record'] == '') {
            $survey->default_survey_language = $sugar_config['default_language'];
        }
        $survey->base_score = $base_score;
        $survey->save();
        header("Location: index.php?module=bc_survey&action=DetailView&record={$survey->id}");
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
    * view reports
    *
    * @author     Original Author Biztech Co.
    * @return     view
    */
    function action_viewreport() {
        $this->view = 'report';
        $GLOBALS['view'] = $this->view;
    }

    function action_translate_survey() {
        $this->view = 'translate';
        $GLOBALS['view'] = $this->view;
    }

    /**
    * get Question wise report
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_getReports() {
        global $db;
        $survey_id = $_REQUEST['survey_id'];
        $module_id = $_REQUEST['module_id'];
        $customer_name = $_REQUEST['customer_name'];
        $submission_id = $_REQUEST['submission_id'];
        require_once 'custom/include/utilsfunction.php';
        $lineChart = getLineChart($type, $survey_id);

        $page = $_REQUEST['page'];
        $html = "";
        $get_result_query = " SELECT
        questions.matrix_row AS matrix_rows,
        questions.matrix_col AS matrix_cols,
        questions.name AS question_title,
        questions.maxsize AS max_size,
        questions.base_weight AS base_weight,
        questions.enable_scoring AS enable_scoring,
        questions.id           AS question_id,
        submission_ids.id      AS submission_id,
        bc_survey_answers.name,
        bc_survey_answers.score_weight,
        bc_survey_answers.id   AS answer_id,
        survey.name as survey_name,
        submission_ids.status,
        submission_ids.target_module,
        submission_ids.customer_name,
        survey.description,
        submission_ids.send_date,
        submission_ids.receive_date,
        submission_ids.base_score,
        submission_ids.obtain_score,
        submission_ids.submitted_survey_language,
        submission_ids.submission_type,
        questions.question_type AS question_type,
        bc_survey_pages.page_sequence as page_seq
        FROM bc_survey_questions AS questions
        LEFT JOIN bc_survey_bc_survey_questions_c AS question_rel
        ON questions.id = question_rel.bc_survey_bc_survey_questionsbc_survey_questions_idb
        AND question_rel.deleted = 0
        LEFT JOIN bc_survey AS survey
        ON survey.id = question_rel.bc_survey_bc_survey_questionsbc_survey_ida
        AND survey.deleted = 0
        LEFT OUTER JOIN (SELECT
        bc_survey_submission.id,bc_survey_submission.status,bc_survey_submission.target_module_name AS target_module,
        bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida AS survey_id,
        bc_survey_submission.customer_name AS customer_name,
        bc_survey_submission.date_entered AS send_date,
        bc_survey_submission.date_modified AS receive_date,
        bc_survey_submission.base_score AS base_score,
        bc_survey_submission.obtain_score AS obtain_score,
        bc_survey_submission.submission_type AS submission_type,
        bc_survey_submission.submitted_survey_language AS submitted_survey_language
        FROM bc_survey_submission
        LEFT JOIN bc_survey_submission_bc_survey_c
        ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
        AND bc_survey_submission_bc_survey_c.deleted = 0
        WHERE bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
        AND (bc_survey_submission.module_id = '{$module_id}' OR bc_survey_submission.customer_name = '{$customer_name}') AND bc_survey_submission.deleted = 0) AS submission_ids
        ON submission_ids.survey_id = survey.id
        LEFT OUTER JOIN (SELECT
        bc_submission_data_bc_survey_questions_c.bc_submission_data_bc_survey_questionsbc_survey_questions_ida AS sub_que_id,
        bc_submission_data_bc_survey_answers_c.bc_submission_data_bc_survey_answersbc_survey_answers_ida       AS sub_ans_id
        FROM bc_submission_data
        LEFT JOIN bc_submission_data_bc_survey_questions_c
        ON bc_submission_data_bc_survey_questions_c.bc_submission_data_bc_survey_questionsbc_submission_data_idb = bc_submission_data.id
        AND bc_submission_data_bc_survey_questions_c.deleted = 0
        LEFT JOIN bc_submission_data_bc_survey_answers_c
        ON bc_submission_data_bc_survey_answers_c.bc_submission_data_bc_survey_answersbc_submission_data_idb = bc_submission_data.id
        AND bc_submission_data_bc_survey_answers_c.deleted = 0
        LEFT JOIN bc_submission_data_bc_survey_submission_c
        ON bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_submission_data_idb = bc_submission_data.id
        AND bc_submission_data_bc_survey_submission_c.deleted = 0
        LEFT JOIN bc_survey_submission
        ON bc_survey_submission.id = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
        AND bc_survey_submission.deleted = 0
        WHERE bc_survey_submission.module_id = '{$module_id}' OR bc_survey_submission.customer_name = '{$customer_name}') AS submission_data
        ON submission_data.sub_que_id = questions.id
        LEFT JOIN bc_survey_answers
        ON bc_survey_answers.id = submission_data.sub_ans_id
        AND bc_survey_answers.deleted = 0
        LEFT JOIN bc_survey_pages_bc_survey_questions_c
        ON bc_survey_pages_bc_survey_questions_c.bc_survey_pages_bc_survey_questionsbc_survey_questions_idb = questions.id
        AND bc_survey_pages_bc_survey_questions_c.deleted = 0
        LEFT JOIN bc_survey_pages
        ON bc_survey_pages_bc_survey_questions_c.bc_survey_pages_bc_survey_questionsbc_survey_pages_ida = bc_survey_pages.id
        AND bc_survey_pages.deleted = 0
        WHERE survey.id = '{$survey_id}'
        AND questions.deleted = 0
        ORDER BY bc_survey_pages.page_sequence,questions.question_sequence,bc_survey_answers.answer_sequence
        ";
        $result = $db->query($get_result_query);
        $row_data = array();
        $detail_array = array();
        $i = 0;
        while ($row = $db->fetchByAssoc($result)) {
            $row['matrix_rows'] = json_decode(base64_decode(($row['matrix_rows'])));
            $row['matrix_cols'] = json_decode(base64_decode(($row['matrix_cols'])));
            $row_data[$row['page_seq']][$row['question_id']] = $row;
            $row_data[$row['page_seq']]['status'] = $row['status'];
            $row_data[$row['page_seq']]['description'] = $row['description'];
            $row_data[$row['page_seq']]['send_date'] = $row['send_date'];
            $row_data[$row['page_seq']]['receive_date'] = $row['receive_date'];
            $row_data[$row['page_seq']]['survey_name'] = $row['survey_name'];
            $row_data[$row['page_seq']]['customer_name'] = $row['customer_name'];
            $row_data[$row['page_seq']]['base_score'] = $row['base_score'];
            $row_data[$row['page_seq']]['obtain_score'] = $row['obtain_score'];
            $row_data[$row['page_seq']]['submitted_survey_language'] = $row['submitted_survey_language'];

            if ($row['status'] == 'Pending') {
                $html = "<div id='individual'>There is no submission response for this Survey.</div>";
            } else if ($row['status'] == null) {
                $html = '';
            } else {
                if ($row['status'] == 'Submitted') {
                    //Contact Information then retrieve all answer from db & store in variable
                    if (!empty($row['question_type']) && $row['question_type'] == 'ContactInformation') {
                        $contact_information = JSON::decode(html_entity_decode($row['name']));
                        $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']][$row['answer_id']] = $contact_information;
                    }
                    // Matrix type then get rows & columns value & generate selected answer layout
                    else if (!empty($row['question_type']) && $row['question_type'] == 'Matrix') {
                        // set matrix answer to question array
                        // set matrix answer to question array
                        if (empty($module_id)) {
                            $rec_id = $row['customer_name'];
                        } else {
                            $rec_id = $module_id;
                        }
                        $answers = getAnswerSubmissionDataForMatrix($survey_id, $rec_id, $row['question_id'], $row['submission_type']);
                        $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['matrix_answer'] = $answers;
                        $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['matrix_rows'] = $row['matrix_rows'];
                        $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['matrix_cols'] = $row['matrix_cols'];
                    }
                    // Rating then generate selected star value
                    elseif (!empty($row['question_type']) && $row['question_type'] == 'Rating') {
                        $rating = "";
                        if (!empty($row['max_size'])) {
                            $starCount = $row['max_size'];
                        } else {
                            $starCount = 5;
                        }
                        for ($i = 0; $i < $starCount; $i++) {
                            if ($i < $row['name']) {
                                $selected = "selected";
                            } else {
                                $selected = "";
                            }
                            $rating .= "<li class='rating {$selected}' style='display: inline;font-size: x-large'>&#9733;</li>";
                        }

                        $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']][$row['answer_id']] = $rating;
                    } // Other type of Question
                    else {
                        if ($row['question_type'] == 'RadioButton' || $row['question_type'] == 'Checkbox' || $row['question_type'] == 'MultiSelectList' || $row['question_type'] == 'DrodownList') {
                            if (array_key_exists($row['question_title'], $detail_array)) {
                                $score_weight = $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['obtained_que_score'];
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['obtained_que_score'] = $score_weight + $row['score_weight'];

                                $bc_survey_que = BeanFactory::getBean('bc_survey_questions', $row['question_id']);
                                $bc_survey_que->load_relationship('bc_survey_answers_bc_survey_questions');
                                foreach ($bc_survey_que->bc_survey_answers_bc_survey_questions->getBeans() as $bc_survey_answers) {
                                    if ($bc_survey_answers->id == $row['answer_id']) {
                                        $all_options[$row['question_id']][$bc_survey_answers->id] = array('ans' => $bc_survey_answers->name, 'selected' => true, 'type' => $row['question_type']);
                                    } else {
                                        $all_options[$row['question_id']][$bc_survey_answers->id] = array('ans' => $bc_survey_answers->name, 'selected' => false);
                                    }
                                }
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['all_answers'] = $all_options[$row['question_id']];
                            } else {
                                $bc_survey_que = BeanFactory::getBean('bc_survey_questions', $row['question_id']);
                                $bc_survey_que->load_relationship('bc_survey_answers_bc_survey_questions');
                                foreach ($bc_survey_que->bc_survey_answers_bc_survey_questions->getBeans() as $bc_survey_answers) {
                                    if ($bc_survey_answers->id == $row['answer_id']) {
                                        $all_options[$row['question_id']][$bc_survey_answers->id] = array('ans' => $bc_survey_answers->name, 'selected' => true, 'type' => $row['question_type']);
                                    } else {
                                        if (!is_array($all_options[$row['question_id']][$bc_survey_answers->id])) {
                                            $all_options[$row['question_id']][$bc_survey_answers->id] = array('ans' => $bc_survey_answers->name, 'selected' => false, 'type' => $row['question_type']);
                                        }
                                    }
                                }
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['all_answers'] = $all_options[$row['question_id']];
                                $score_weight = $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['obtained_que_score'];
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['obtained_que_score'] = $score_weight + $row['score_weight'];
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['base_que_score'] = $row['base_weight'];
                                $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']]['enable_scoring'] = $row['enable_scoring'];
                            }
                        } else if ($row['question_type'] != "Image" && $row['question_type'] != "Video" && $row['question_type'] != "question_section") {
                            $ans = !empty($row['name']) ? $row['name'] : 'N/A';
                            $answer_submitted = '<span>' . $ans . '</span>';
                            $detail_array[$row['page_seq']][$row['question_id']][$row['question_title']][$row['answer_id']] = $answer_submitted;
                        }
                    }
                }
                $detail_array[$row['page_seq']][$row['question_id']]['page_id'] = $row['page_seq'];
                if ($detail_array[$row['page_seq']][$row['question_id']]['page_id'] != $page) {
                    unset($detail_array[$row['page_seq']][$row['question_id']]);
                }
                $survey_details[$row['page_seq']]['page'] = $detail_array;
            }
            $customer_name = $row['customer_name'];
            $survey_send_date = $row['send_date'];
            $survey_receive = $row['receive_date'];
            $description = $row['description'];
            $base_score = $row['base_score'];
            $obtain_score = $row['obtain_score'];
            $status = $row['status'];
            $submission_language = $row['submitted_survey_language'];
        }
        //set pagination
        if (count($survey_details) && $module_id != 'Select Record') {
            $pagination = new pagination($survey_details, (isset($_GET['page']) ? $_GET['page'] : 1), 1);
            $pagination->setShowFirstAndLast(true);
            $IndivisualReportData = $pagination->getResults();
            if (count($IndivisualReportData) != 0) {
                $_POST['action'] = 'viewreport';
                $_POST['type'] = 'individual';

                $queReoort_pageNumbers = "<div class='numbers'> " . $pagination->getLinks($_POST, $survey_id, $page, $module_id, $submission_id, $customer_name) . "</div>";
            }
        }
        $html .= "<div class='middle-content'>";
        //get module_name
        $list_lang_detail = return_app_list_strings_language($submission_language)[$survey_id];
        if ($status != "Pending") {
            $record_name = $customer_name;
            $html .= "<h2 class='title'>Individual Report for {$record_name}</h2>";
            if ($page == 1) {
                if (isset($survey_send_date)) {
                    $sdate = date('Y-m-d h:i:s',strtotime($survey_send_date));
                    $send_date = TimeDate::getInstance()->to_display_date_time($sdate);
                }
                if (isset($survey_receive)) {
                    $rdate = date('Y-m-d h:i:s',strtotime($survey_receive));
                    $receive_date = TimeDate::getInstance()->to_display_date_time($rdate);
                }
                $description_value = (!empty($list_lang_detail[$survey_id . '_description']) ? $list_lang_detail[$survey_id . '_description'] : $description );
                $html .= "<div class='send-receive-time'><div class='description' style='font-size: 14px;'><label><strong>Description : </strong></label>{$description_value}</div>";
                if (isset($send_date)) {
                    $html .= "<div class='send-date'><label><strong>Survey Send date : </strong></label> {$send_date}</div>";
                }
                if (isset($receive_date)) {
                    $html .= "<div class='receive-date'><label><strong>Survey Receive date : </strong></label> {$receive_date}</div>";
                    if (!empty($base_score)) {
                        $html .= "<div class='send-date'><label><strong>Survey Score : </strong></label>Obtained Score<strong> {$obtain_score} </strong>Out of<strong> {$base_score}</strong></div>";
                    }
                    $html .= "</div>";
                }
            }
        }
        foreach ($detail_array as $page_id => $page_data) {

            foreach ($page_data as $que_id => $que_title) {
                unset($que_title['page_id']);
                foreach ($que_title as $title => $answers) {
                    $que_title_value = (!empty($list_lang_detail[$que_id . '_quetitle'])) ? $list_lang_detail[$que_id . '_quetitle'] : $title;
                    $html .= " <div class='que-rwo'>";
                    if ($answers['enable_scoring']) {
                        $html .= "<p class='que'><label>Question</label>{$que_title_value}<span style=' float:right;font-weight:bold;background-color: #DDDDDD; border-radius: 4px; height: 18px; padding:5px;'>{$answers['obtained_que_score']} / {$answers['base_que_score']} </span></p>";
                    } else {
                        $html .= "<p class='que'><label>Question</label>{$que_title_value}</p>";
                    }
                    if ($que_title[$title]['matrix_rows']) {
                        $row_count = 1;
                        $col_count = 1;
                        $rows = $que_title[$title]['matrix_rows'];
                        $cols = $que_title[$title]['matrix_cols'];
                        if ((!empty($list_lang_detail[$que_id . '_row1']))) {
                            foreach ($rows as $key => $row) {
                                $rows = $list_lang_detail[$que_id . '_row' . $key];
                            }

                            foreach ($cols as $key => $col) {
                                $cols = $list_lang_detail[$que_id . '_col' . $key];
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
                        }$width = 100 / ($col_count + 1) . "%";

                        foreach ($answers as $key => $answer) {
                            if ($key != 'matrix_rows' && $key != 'matrix_cols' && $key != "matrix_answer") {
                                $ans_count = count($answer);
                                if (is_array($answer)) {
                                    $html .= "<td width='110' style='margin:0 0 8px 0; padding:5px; font-size:15px; color:#808080; border:1px solid #e5e5e5;background-color: #ececec;background-color: #f3f3f3;' ><strong>Answer:</strong></td>";
                                }
                            } else if ($key == "matrix_answer") {
                                $is_matrix = true;
                                $matrix_answer_array[$que_id] = array();
                                foreach ($answer as $ans_label => $ans_matrix) {
                                    foreach ($ans_matrix as $ans_cnt => $aval) {
                                        array_push($matrix_answer_array[$que_id], $aval);
                                    }
                                }
                            }
                        }
                        $matrix_html = '';
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
                                        $matrix_html .= "<td class='matrix-span {$que_id}' value='{$row}' style='font-weight:bold; width:" . $width . ";text-align:left;padding:5px;'>" . $rows->$row . "</td>";
                                    } else {
                                        //Columns label
                                        if ($j <= ($col_count + 1) && $cols->$col != null && !($j == 1 && $i == 1) && ($i == 1 || $j == 1)) {
                                            $matrix_html .= "<td class='matrix-span' style='font-weight:bold; width:" . $width . ";padding:5px;'>" . $cols->$col . "</td>";
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
                        }
                        $html .= $matrix_html;
                    } else {
                        //                        // Retrieve Other Submitted answer for current submission
                        $query = "SELECT bc_survey_answers.id,bc_survey_answers.ans_type,bc_survey_answers.name "
                        . "FROM bc_submission_data_bc_survey_submission_c as submission "
                        . "JOIN bc_submission_data_bc_survey_questions_c as question "
                        . "ON  question.bc_submission_data_bc_survey_questionsbc_submission_data_idb = submission.bc_submission_data_bc_survey_submissionbc_submission_data_idb "
                        . "JOIN bc_submission_data_bc_survey_answers_c as answers "
                        . "ON question.bc_submission_data_bc_survey_questionsbc_submission_data_idb = answers.bc_submission_data_bc_survey_answersbc_submission_data_idb "
                        . "JOIN bc_survey_answers ON bc_survey_answers.id = answers.bc_submission_data_bc_survey_answersbc_survey_answers_ida "
                        . "WHERE submission.bc_submission_data_bc_survey_submissionbc_survey_submission_ida = '{$submission_id}' "
                        . "AND submission.deleted=0 "
                        . "AND question.deleted = 0 "
                        . "AND answers.deleted=0 AND bc_survey_answers.deleted=0 "
                        . "AND question.bc_submission_data_bc_survey_questionsbc_survey_questions_ida = '{$que_id}'";
                        $ansee = $db->query($query);
                        foreach ($answers as $key => $answer) {
                            if ($key != 'obtained_que_score' && $key != 'base_que_score' && $key != 'enable_scoring') {
                                if (is_array($answer)) {
                                    $answer_submitted = false;
                                    $html .= "<span class='ans'><label style='width:100px'>Answer</label><div style='margin-top: -28px; margin-left: 36px;'><ul>";
                                    $html1 = '';
                                    foreach ($answer as $ans_label => $ans) {
                                        if ($ans['selected'] == true) {
                                            while ($ro = $db->fetchByAssoc($ansee)) {
                                                if ($ro['ans_type'] != "other") {
                                                    $ans = (!empty($list_lang_detail[$ro['id']])) ? $list_lang_detail[$ro['id']] : $ro['name'];
                                                    $html1 .= "<li>" . $ans . "" . "</li>";
                                                }
                                            }
                                            $answer_submitted = true;
                                        } else if($ans['selected'] != false){
                                            $html1 .= "<b>" .$ans_label .'</b>:' . $ans;
                                            $answer_submitted = true;
                                        }
                                    }
                                    if (!$answer_submitted) {
                                        $html .= 'N/A</ul></div>';
                                    } else {
                                        $html .= $html1 . "</ul></div>";
                                    }
                                } else {
                                    $submitted_answer = $answer != '' ? nl2br($answer) : 'N/A';
                                    $html .= "<span class='ans'><label>Answer</label> <div style='display: inline-block;vertical-align: top'>{$submitted_answer}</div></p>";
                                }
                            }
                        }
                    }
                    $html .= "</div>";
                }
            }
        }
        $html .= "</div>";
        $html .= $queReoort_pageNumbers;
        echo $html;
        exit;
    }

    /**
    * editview click
    *
    * @author     Original Author Biztech Co.
    * @return     view
    */
    function action_EditView() {
        global $db;
        $total_submission = 0;
        if (!empty($_REQUEST['record'])) {
            $query = "SELECT
            COUNT(bc_survey_submission.id) AS total_sibmission
            FROM bc_survey_submission
            JOIN bc_survey_submission_bc_survey_c
            ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
            AND bc_survey_submission.deleted = 0
            JOIN bc_survey
            ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
            AND bc_survey_submission_bc_survey_c.deleted = 0
            WHERE bc_survey.deleted = 0  AND bc_survey.id = '{$_REQUEST['record']}'";
            $result = $db->query($query);
            $row = $db->fetchByAssoc($result);
            $total_submission = $row['total_sibmission'];
        }
        if ($total_submission > 0) {
            header("Location:index.php?module=bc_survey&action=DetailView&record={$_REQUEST['record']}&flag=1");
        } else {
            $this->view = 'edit';
            $GLOBALS['view'] = $this->view;
        }
    }

    /**
    * survey Search on popupbox
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_getSearchResult() {
        require_once 'custom/include/utilsfunction.php';
        $getRequestData = $_REQUEST['newData'];
        $dataArray = json_decode(html_entity_decode($getRequestData));
        $postDataArray = array(
            'search_value' => $dataArray->search_value,
            'module_type' => $dataArray->module_type,
            'survey_status' => $dataArray->survey_status,
            'survey_id' => $dataArray->survey_id,
            'survey_type' => $dataArray->survey_type,
            'report_type' => $dataArray->report_type,
            'page' => $dataArray->page,
            'sorting_type' => $dataArray->sorting_type,
            'sorting_name' => $dataArray->sorting_name,
        );
        $name = $postDataArray['search_value'];
        $type = $postDataArray['module_type'];
        $status = $postDataArray['survey_status'];
        $survey_id = $postDataArray['survey_id'];
        $survey_type = $postDataArray['survey_type'];
        $report_type = $postDataArray['report_type'];
        $page = $postDataArray['page'];
        $sorting_type = $postDataArray['sorting_type'];
        $sorting_name = $postDataArray['sorting_name'];
        $records = getReportData($report_type, $survey_id, $name, $type, $status, $survey_type, $sorting_type, $sorting_name);
        if (count($records) == 0) {
            $html = "<tr><td colspan='3'>No records found</td></tr>";
            echo $html;
            exit;
        }
        $html = "";
        if (count($records) > 0) {
            //set pagination
            $pagination = new pagination($records, (isset($page) ? $page : 1), 10);
            $pagination->setShowFirstAndLast(true);
            $IndividualReportData = $pagination->getResults();
            $post_data = array();
            if (count($IndividualReportData) != 0) {
                $post_data = $postDataArray;
                unset($post_data['search_value']);
                unset($post_data['module_type']);
                unset($post_data['survey_status']);
                $Individual_Report_pageNumbers = "<div class='numbers'> " . $pagination->getIndividual_SearchLinks($post_data, $survey_id) . "</div>";
            }
            $html .= " <tr class='thead'>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='name_label' style='cursor: pointer;'>Name&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></td>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='module_label' style='cursor: pointer;'>Module&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></td>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='type_label' style='cursor: pointer;'>Type&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></td>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='status_label' style='cursor: pointer;'>Status&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></td>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='send_date_label' style='cursor: pointer;'>Survey Send Date&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></th>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='recieve_date_label' style='cursor: pointer;'>Survey Receive Date&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></th>
            <th><label onclick='getSearchResult(this,\"individual\", \"{$survey_id}\", 1,\"search\")' class='Ascending' id='change_request_label' style='cursor: pointer;'>Change Request&nbsp;&nbsp;<i class='fa fa-sort' aria-hidden='true'></i></th>
            <th style='width:80px;'>Resend</th>
            <th style='width:80px;'>Delete</th>
            </tr>";
            foreach ($IndividualReportData as $module_id => $module_detail) {
                $record_name = $module_detail['customer_name'];
                $record_id = $module_detail['module_id'];
                $html .= "
                <tr>
                <td><a href='javascript:void(0);' onclick='getReports(\"{$survey_id}\",{$page},\"{$record_id}\",\"{$record_name}\");'>$record_name</a>
                </td>
                <td>{$module_detail['module_name']}</td>
                <td>{$module_detail['type']}</td>
                <td>{$module_detail['survey_status']}</td>
                <td>{$module_detail['send_date']}</td>
                <td>{$module_detail['receive_date']}</td><td>";
                if ($module_detail['change_request'] == 'N/A' || $module_detail['change_request'] == 'Approved') {
                    $html .= "{$module_detail['change_request']}";
                } else {
                    $html .= "<a href='#' onclick='ApproveChRequest(this, \"{$survey_id}\", \"{$module_detail['module_id']}\", \"{$module_detail['module_type']}\");'>{$module_detail['change_request']}</a>";
                }
                $html .="</td><td id='re-send'>";
                if (($module_detail['type'] == 'Email' && ($status == 'Submitted' || $status == 'Viewed') || ($module_detail['type'] == 'Email' && ($status == '')))) {
                    $html .= "<a title='Re-send' href='javascript:reSendSurvey(\"{$survey_id}\",\"{$module_detail['module_id']}\",\"{$module_detail['module_type']}\");'><img src='custom/include/images/re-send.png' style='height: 22px;'></a>";
                }
                $html .= '</td><td style="text-align: center;">
                <a title="Delete Response" onclick="deleteSubmission(this, ' . $survey_id . ', ' . $module_detail['submission_id'] . ', ' . $module_detail['module_id'] . ',' . $module_detail['type'] . ');" href="javascript:void(0);"><img src="custom/include/images/trash.png" style="height: 22px;"></a>
                </td></tr>';
            }
            $page_html = $Individual_Report_pageNumbers;
            echo $html . "||" . $page_html;
            exit;
        }
    }

    /**
    * survey Configuration
    *
    * @author     Original Author Biztech Co.
    * @return     view
    */
    public function action_surveyconfig() {
        global $current_user;
        if (is_admin($current_user)) {
            $this->view = "SurveyConfiguration";
            $GLOBALS['view'] = $this->view;
        } else {
            $this->view = "noaccess";
            $GLOBALS['view'] = $this->view;
        }
    }

    /**
    * Validate license
    *
    * @author     Original Author Biztech Co.
    * @return
    */
    function action_validateLicence() {
        require_once('custom/include/modules/Administration/plugin.php');
        $key = $_REQUEST['k'];
        $CheckResult = checkPluginLicense($key);
        echo json_encode($CheckResult);
        exit;
    }

    /**
    * Enable desable survey module
    *
    * @author     Original Author Biztech Co.
    * @return
    */
    function action_enableDisableSurvey() {
        require_once('modules/Administration/Administration.php');
        $enabled = $_REQUEST['enabled'];
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        switch ($_REQUEST['enabled']) {
            case '1':
                $administrationObj->saveSetting("SurveyPlugin", "ModuleEnabled", 1);
                $administrationObj->saveSetting("SurveyPlugin", "LastValidationMsg", "");
                break;
            case '0':
                $administrationObj->saveSetting("SurveyPlugin", "ModuleEnabled", 0);
                $administrationObj->saveSetting("SurveyPlugin", "LastValidationMsg", "This module is disabled, please contact Administrator.");
                break;
            default:
                $administrationObj->saveSetting("SurveyPlugin", "ModuleEnabled", 0);
                $administrationObj->saveSetting("SurveyPlugin", "LastValidationMsg", "This module is disabled, please contact Administrator.");
        }
    }

    /**
    * Export data in comma seprated
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_exportToExcel() {
        require_once 'custom/include/utilsfunction.php';
        global $app_list_strings;
        $name = $_REQUEST['module_name'];
        $type = $_REQUEST['module_type'];
        $status = $_REQUEST['survey_status'];
        $survey_id = $_REQUEST['survey_id'];
        $report_type = $_REQUEST['report_type'];
        $page = $_REQUEST['page'];
        $survey_type = $_REQUEST['survey_type'];
        $surveyObj = new bc_survey();
        $surveyObj->retrieve($survey_id);
        $filename = str_replace(" ", "_", $surveyObj->name);
        $finalExportData = getAllExportData($report_type, $survey_id, $name, $type, $status, $survey_type);

        /*
        * Get all fields array for export headers
        */
        $ResetedRes = array_values($finalExportData);
        $FirstRes = (isset($ResetedRes[0])) ? $ResetedRes[0] : array();
        $Ques_keys = array();
        foreach ($FirstRes['Response'] as $Q => $a) {
            $Que = array_keys($a);
            array_push($Ques_keys, $Que[0]);
        }
        unset($FirstRes['Response']);
        $filed_keys = array_keys($FirstRes);
        echo "<pre>" . print_r($finalExportData, true);
        $exportFields = array_merge($filed_keys, $Ques_keys);
        /*
        * Export headers end
        */

        function cleanData(&$str) {
            $str = preg_replace("/\t/", "\\t", $str);
            $str = preg_replace("/\r?\n/", "\\n", $str);
            $str = preg_replace("/&nbsp;/", "", $str);
            if (strstr($str, '"'))
                $str = '"' . str_replace('"', '""', $str) . '"';
        }

        //clean the ob before writing to xl
        ob_clean();
        header("Content-Disposition: attachment; filename={$filename}.xls");
        header("Content-Type: application/vnd.ms-excel");
        echo "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" xmlns:x=\"urn:schemas-microsoft-com:office:excel\" xmlns=\"http://www.w3.org/TR/REC-html40\">
        <html>
        <head><meta http-equiv=\"Content-type\" content=\"text/html;charset=utf-8\" /></head>
        <body>
        ";

        $content = '<table><tr>';
        array_walk($exportFields, 'cleanData');
        foreach($exportFields as $th_value){
            $content .= "<th>$th_value</th>";
        }
        $content .= '</tr>';
        //$content .= htmlspecialchars_decode(implode("\t", array_values($exportFields)), ENT_QUOTES) . "\r\n";

        foreach ($finalExportData as $row) {
            $response = $row['Response'];
            unset($row['Response']);
            $clientData = array_values($row);
            $surValues = array();
            foreach ($response as $key => $value) {
                foreach ($value as $que => $ans) {
                    if (is_array($ans)) {
                        $val = array_values($ans);
                        $pushVal = (empty($val[0])) ? 'N/A' : $val[0];
                    } else {
                        $val = $ans;
                        $pushVal = (empty($val)) ? 'N/A' : $val;
                    }
                    array_push($surValues, $pushVal);
                }
            }
            $ExportRow = array_merge($clientData, $surValues);
            array_walk($ExportRow, 'cleanData');
            $content .= '<tr>';
            foreach($ExportRow as $td_value){
                $content .= "<td>$td_value</td>";
            }
            $content .= '</tr>';
            //$content .= htmlspecialchars_decode(implode("\t", array_values($ExportRow)), ENT_QUOTES) . "\r\n";
        }
        $content .= '</table>';
        echo $content;
        echo "</body></html>";
        exit;
    }

    function action_healthstatus() {
        global $current_user;
        if (is_admin($current_user)) {
            $this->view = "healthstatus";
            $GLOBALS['view'] = $this->view;
        } else {
            $this->view = "noaccess";
            $GLOBALS['view'] = $this->view;
        }
    }

    function action_saveSurveysmtpSetting() {
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->saveSetting("SurveySmtp", "survey_notify_fromname", $_REQUEST['survey_notify_fromname']);
        $administrationObj->saveSetting("SurveySmtp", "survey_notify_fromaddress", $_REQUEST['survey_notify_fromaddress']);
        $administrationObj->saveSetting("SurveySmtp", "survey_smtp_email_provider", $_REQUEST['survey_smtp_email_provider']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtp_host", $_REQUEST['survey_mail_smtp_host']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtpport", $_REQUEST['survey_mail_smtpport']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtpssl", $_REQUEST['survey_mail_smtpssl']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtp_username", $_REQUEST['survey_mail_smtp_username']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtp_password", $_REQUEST['survey_mail_smtp_password']);
        $administrationObj->saveSetting("SurveySmtp", "survey_mail_smtp_retype_password", $_REQUEST['survey_mail_smtp_retype_password']);

        header("Location: index.php?module=Administration&action=index"); /* Redirect browser */
        exit();
    }

    /**
    * resubmit count
    *
    * @author     Original Author Biztech Co.
    * @return     integer
    */
    function action_submitReSubmitCount() {
        require_once('modules/Administration/Administration.php');
        $resubmitcount = $_REQUEST['resubmitcount'];
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $administrationObj->saveSetting("SurveyPlugin", "ReSubmitCount", !empty($resubmitcount) ? $resubmitcount : 1);
    }

    /**
    * approve resubmit request of survey
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_approveRequest() {
        require_once 'custom/include/utilsfunction.php';
        global $db, $sugar_config;
        $query = "SELECT bc_survey_submission.id AS submission_id
        FROM bc_survey_submission
        JOIN bc_survey_submission_bc_survey_c
        ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
        AND bc_survey_submission_bc_survey_c.deleted = 0
        JOIN bc_survey
        ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
        AND bc_survey.deleted = 0
        WHERE bc_survey_submission.module_id = '{$_REQUEST['module_id']}'
        AND bc_survey_submission.target_module_name = '{$_REQUEST['module_name']}'
        AND bc_survey_submission.deleted = 0
        AND bc_survey.id = '{$_REQUEST['survey_id']}'";
        $submission_result = $db->query($query);
        $submission_row = $db->fetchByAssoc($submission_result);


        if (empty($_REQUEST['status']) && $_REQUEST['resubmit']) {
            $updQry = "UPDATE bc_survey_submission SET resubmit=1 WHERE bc_survey_submission.id='{$submission_row['submission_id']}'";
        } else {
            $updQry = "UPDATE bc_survey_submission SET bc_survey_submission.change_request = '{$_REQUEST['status']}',resubmit=1 WHERE bc_survey_submission.id='{$submission_row['submission_id']}'";
        }


        $surveyObj = new bc_survey();
        $surveyObj->retrieve($_REQUEST['survey_id']);
        $reponse = array();
        if ($db->query($updQry)) {
            $reponse['status'] = 'sucess';
            $reponse['request_status'] = $_REQUEST['status'];

            switch ($_REQUEST['module_name']) {
                case "Accounts":
                    $focus = new Account();
                    break;
                case "Contacts":
                    $focus = new Contact();
                    break;
                case "Users":
                    $focus = new User();
                    break;
                case "Leads":
                    $focus = new Lead();
                    break;
                case "Prospects":
                    $focus = new Prospect();
                    break;
            }
            $focus->retrieve($_REQUEST['module_id']);
            $record_name = $focus->name;
            $getSurveyEmailTemplateID = getEmailTemplateBySurveyID($surveyObj->id, $_REQUEST['module_name']);
            $emailtemplateObj = new EmailTemplate();
            $emailtemplateObj->retrieve($getSurveyEmailTemplateID);
            $mailSubject = (!empty($emailtemplateObj->subject)) ? $emailtemplateObj->subject : $surveyObj->name;
            $emailSubject = htmlspecialchars_decode($mailSubject, ENT_QUOTES);

            $survey_url = $sugar_config['site_url'] . '/survey_submission.php?survey_id=';
            $sugar_survey_Url = $survey_url; //create survey submission url
            $encoded_param = base64_encode($_REQUEST['survey_id'] . '&ctype=' . $_REQUEST['module_name'] . '&cid=' . $_REQUEST['module_id']);
            $sugar_survey_Url = str_replace('survey_id=', 'q=', $sugar_survey_Url);
            $surveyURL = $sugar_survey_Url . $encoded_param;

            $to_Email = $focus->email1;
            $optEmailID = getOptOutEmailCustomers($_REQUEST['module_id'], $_REQUEST['module_name']);
            $opt_out_url = $sugar_config['site_url'] . '/unsubscribe.php?target=' . $optEmailID['email_add_id'];
            if (empty($_REQUEST['status']) && $_REQUEST['resubmit']) {
                $emailBody = "Dear " . $focus->name . ",<br/><br/>Admin has requested you to re-submit your response for <b>" . $surveyObj->name . "</b> survey.<br/><br/>You can re-submit your response on following link.<br/><br/><a href='" . $surveyURL . "' target='_blank'>click here</a>";
                $emailBody .= '<br/><br/><span style="font-size:0.8em">To remove yourself from this email list  <a href="' . $opt_out_url . '" target="_blank">click here</a></span>';
            } else {
                $emailBody = "Dear " . $focus->name . ",<br/><br/>Admin has approved your request to edit your response for <b>" . $surveyObj->name . "</b> survey.<br/><br/>You can re-submit your response on following link.<br/><br/><a href='" . $surveyURL . "' target='_blank'>click here</a>";
                $emailBody .= '<br/><br/><span style="font-size:0.8em">To remove yourself from this email list  <a href="' . $opt_out_url . '" target="_blank">click here</a></span>';
            }
            CustomSendEmail($to_Email, $emailSubject, htmlspecialchars_decode($emailBody, ENT_QUOTES), $_REQUEST['module_name'], $_REQUEST['module_id'], $toemail = 'to');
        } else {
            $reponse['status'] = 'error';
        }
        echo json_encode($reponse);
        exit;
    }

    /**
    * send survey email
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_SendSurveyEmail() {
        global $db;
        require_once 'custom/include/utilsfunction.php';
        $customersSummary = array();
        $returnDataArray = array();
        $records = $_REQUEST['records'];
        $module_name = $_REQUEST['module_name'];
        $survey_id = $_REQUEST['id_survey'];
        $total_seleted_records = (!empty($_REQUEST['total_selected'])) ? $_REQUEST['total_selected'] : 1;
        $schedule_on = (!empty($_REQUEST['schedule_on'])) ? $_REQUEST['schedule_on'] : '';
        $records_explode = explode(',', $records);
        $dataArray = sendSurveyEmailsModuleRecords($records, $module_name, $survey_id, $schedule_on);
        $is_processed = $dataArray['is_send'];
        $customersSummary['MailSentSuccessfullyFirstTime'] = $dataArray['MailSentSuccessfullyFirstTime'];
        $customersSummary['ResponseSubmitted'] = $dataArray['ResponseSubmitted'];
        $customersSummary['ResponseNotSubmitted'] = $dataArray['ResponseNotSubmitted'];
        $customersSummary['unsubscribeCustomers'] = $dataArray['unsubscribeCustomers'];
        $customersSummary['alreadyOptOut'] = $dataArray['alreadyOptOut'];
        $contentPopUP = createContentForMailStatusPopup($customersSummary, $survey_id, $module_name, $total_seleted_records);
        $returnDataArray['mailStatus'] = $is_processed;
        $returnDataArray['contentPopUP'] = $contentPopUP;
        $returnDataArray = json_encode($returnDataArray);
        echo $returnDataArray;
        exit;
    }

    /**
    * send survey reminder
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_SendSurveyReminder() {
        require_once 'custom/include/utilsfunction.php';
        $module_ids = json_decode(html_entity_decode($_REQUEST['moduleID']));
        $module = $_REQUEST['moduleName'];
        $survey_id = $_REQUEST['surveyID'];
        if ($module == 'ProspectLists') {
            $recipients = manageTargetListsModuleForSendSurvey($module_ids, $module);
        } else {
            $recipients[$module] = $module_ids;
        }
        foreach ($recipients as $moduleName => $module_id) {
            if (!is_array($module_id)) {
                $mailStatus = sendSurveyReminderEmails($module_id, $moduleName, $survey_id);
            } else {
                foreach ($module_id as $modID) {
                    $mailStatus = sendSurveyReminderEmails($modID, $moduleName, $survey_id);
                }
            }
        }
        echo $mailStatus;
        exit;
    }

    /**
    * get all records of survey
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_GetSurveys() {
        global $db;
        require_once 'custom/include/utilsfunction.php';
        $condition = '';
        $current_date = gmdate('Y-m-d H:i:s');
        $surveyModule = $_REQUEST['surveyModule'];
        $clear = 'hidden';
        if (!empty($_REQUEST['search_query'])) {
            $condition = $_REQUEST['search_query'];
            $clear = "visible";
        }
        $html = "";
        $html .= "<div id='survey_list'>";
        $html .= "<table class=\"list view table footable-loaded footable default suite-model-table\" cellpadding='0' cellspacing='0' style='width: 100%;'>";
        if ($_REQUEST['survey_type'] == "poll") {
            $html .= "<thead><tr><th style='vertical-align: middle; width:100px;'><div><div style='margin: 7px 0px 3px 35px;'><b>No.</b></div></th><th colspan='3' style='width:500px;'><div><form class='survey_search_form' name='survey_search_form' onsubmit=''><label style='margin-right: 12px;'>Poll</label><input type='text' name='query' value='" . $condition . "' style='vertical-align: bottom;'> <input style='margin:0;' type='button' name='search' value='Search' onclick='search_surveys(this,\"survey\")'>&nbsp;<input type='button' style='visibility : " . $clear . "margin:0;' value='Clear' onclick='select_surveys(\"survey\",\"poll\")'></form></div></th></tr></thead>";
        } else {
            $html .= "<thead><tr><th style='vertical-align: middle; width:100px;'><div><div style='margin: 7px 0px 3px 35px;'><b>No.</b></div></th><th colspan='3' style='width:500px;'><div><form class='survey_search_form' name='survey_search_form' onsubmit=''><label style='margin-right: 12px;'>Survey</label><input type='text' name='query' value='" . $condition . "' style='vertical-align: bottom;'> <input style='margin:0;' type='button' name='search' value='Search' onclick='search_surveys(this,\"survey\")'>&nbsp;<input type='button' style='visibility : " . $clear . "margin:0;' value='Clear' onclick='select_surveys(\"survey\",\"survey_type\")'></form></div></th></tr></thead>";
        }
        $html .= "<tbody>";
        if ($_REQUEST['survey_type'] == "poll") {
            $query = "SELECT
            bc_survey.name as title,
            bc_survey.id as id
            FROM bc_survey
            WHERE deleted = 0 AND bc_survey.survey_type = 'poll'";
            if (!empty($condition)) {
                $condition = $_REQUEST['search_query'];
                $query .= " AND bc_survey.name LIKE '%{$condition}%'";
            }
            $query .= " ORDER BY title";
        } else {
            $query = "SELECT
            bc_survey.name as title,
            bc_survey.id as id,
            bc_survey.sync_module,
            bc_survey.enable_data_piping
            FROM bc_survey
            WHERE bc_survey.deleted = 0 AND bc_survey.survey_type = 'survey' AND (bc_survey.enable_data_piping = 0 OR (bc_survey.enable_data_piping = 1 AND bc_survey.sync_module='{$surveyModule}'))";
            if (!empty($condition)) {
                $condition = $_REQUEST['search_query'];
                $query .= " AND bc_survey.name LIKE '%{$condition}%'";
            }
            $query .= " ORDER BY title";
        }
        $query_result = $db->query($query);
        $index = 1;
        $count_num_rows = 0;
        while ($survey = $db->fetchByAssoc($query_result)) {
            $emailTempID = getEmailTemplateBySurveyID($survey['id'], $surveyModule);
            $ext_btn = '';
            if($_POST['surveyModule'] == 'ProspectLists' && $_POST['survey_type'] == 'export_target_list')
                $ext_btn = "<input type='button' title='Export Target List of Survey link' value='Export' onclick=\"export_survey_list('{$survey['id']}', '{$_POST['surveyModuleId']}');\">";
            else $ext_btn = "<input type='button' value='Send' onclick=\"schedule_survey('{$survey['id']}','{$current_date}');\">&nbsp;<input type='button' value='Send Later' onclick=\"schedule_survey_form(this,'{$survey['id']}');\">&nbsp;<input type='button' value='Preview' onclick=\"redirectToEmailTemplate('{$survey['id']}');\">";
            $html .= "<tr><td style='width: 10%;'>{$index}</td><td style='width: 60%;'>{$survey['title']}</td><td style='width: 20%; text-align: right;white-space: nowrap;' colspan='1'>$ext_btn</td></tr>";
            $index++;
            $count_num_rows++;
        }
        if ($count_num_rows == 0) {
            $html .= "<tr><td colspan='4' align='center'>No records found.</td><td></td></tr>";
        }
        if ($_REQUEST['survey_type'] == "poll") {
            $html .= "</tbody><tfoot><td id='show_error' style=''>&nbsp;</td><td align='right' style='margin-left: 592px;'><input type='button' style='margin-left: 533px;' value='Close' id='close' onclick='close_survey_div();'></td></tr></tfoot></table>";
        } else {
            $html .= "</tbody><tfoot><tr><td style=''><input type='button' value='Back' id='back' onclick='goback();'></td><td id='show_error' style=''>&nbsp;</td><td align='right' style='margin-left: 592px;'><input type='button' style='margin-left: 533px;' value='Close' id='close' onclick='close_survey_div();'></td></tr></tfoot></table>";
        }
        $html .= "</div>";
        echo $html;
        exit;
    }

    /**
    * get all records of survey template
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_GetSurveyTemplates() {
        global $db;
        $condition = '';
        $clear = 'hidden';
        if (!empty($_REQUEST['search_query'])) {
            $condition = $_REQUEST['search_query'];
            $clear = "visible";
        }
        $html = "";
        $html .= "<div id='survey_template_list'>";
        $html .= "<table class=\"list view table footable-loaded footable default suite-model-table\" cellpadding='0' cellspacing='0' style='width: 100%;'>
        <thead>
        <tr>
        <th style='vertical-align: middle;width: 10%; padding:10px 10px 10px 10px; height: 21px;'>
        <div style='margin-top: 4px; font-size: 14px;'>No.</div>
        </th>
        <th style='width: 84%; padding:10px 10px 10px 10px;height: 21px;'  colspan='2'>
        <div style='float:left; margin-top: 4px; font-size:14px;'>Survey Templates</div>
        <div style='float:right;'>
        <form name='survey_template_search_form'>
        <input type='text' name='query' value='" . $condition . "' style='vertical-align: bottom;'>
        <input type='button' name='search' value='Search' onclick='search_surveys(this,\"survey_template\")'>&nbsp;
        <input type='button' style='visibility : " . $clear . "' value='Clear' onclick='select_surveys(\"survey_template\")'>
        </form>
        </div>
        </th>
        </tr>
        </thead>
        <tbody>";
        $query = 'SELECT
        bc_survey_template.name as title,
        bc_survey_template.id as id
        FROM bc_survey_template
        WHERE deleted = 0';
        if (!empty($condition)) {
            $query .= " AND bc_survey_template.name LIKE '%{$condition}%'";
        }
        $query .= "  ORDER BY title";
        $query_result = $db->query($query);
        $index = 1;
        $count_num_rows = 0;
        while ($survey = $db->fetchByAssoc($query_result)) {
            $html .= "<tr class='survay_template cursor'><td style='width: 10%;'>{$index}</td>
            <td style='width: 65%;'><input type='hidden' name='survey_template_id[]' value='" . $survey['id'] . "'>{$survey['title']}</td>
            <td style='width: 15%; text-align: right;'><input type='button' name='create_survey_link' value='Create Survey' onclick='createSurvey(\"{$survey['id']}\")'></td>
            </tr>";
            $index++;
            $count_num_rows++;
        }
        if ($count_num_rows == 0) {
            $html .= "<tr><td colspan='3' align='center'>No recouds found.</td></tr>";
        }
        $html .= "</tbody>";
        $html.="<tfoot>
        <tr>
        <td style='width: 20%; float:left; padding:10px 10px 10px 10px; height:inherit;'>
        <input type='button' value='Back' id='back' onclick='goback();'>
        </td>
        <td id='show_error' style='text-align: center; width: 51%; float:left;padding:15px 10px 10px 10px; height:inherit;'>&nbsp;</td>
        <td align='right' style='width: 20%; float:left; padding:10px 10px 10px 10px; height:inherit;'>
        <input type='button' value='Close' style='margin-left: 292px;' id='close' onclick='close_survey_div();'>
        </td>
        </tr>
        </tfoot>";
        $html.="</table>";
        $html .= "</div>";
        echo $html;
        exit;
    }

    /**
    * check email template for survey
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_checkEmailTemplateForSurvey() {
        require_once 'custom/include/utilsfunction.php';
        global $db;
        $emailTempID = '';
        $survey_ID = $_REQUEST['survey_ID'];
        $return = 'false';
        $emailTempID = getEmailTemplateBySurveyID($survey_ID);
        echo $emailTempID;
        exit;
    }

    /**
    * pending response
    *
    * @author     Original Author Biztech Co.
    * @return     view
    */
    function action_DisplayPendingResView() {
        $this->view = 'pending_response';
        $GLOBALS['view'] = $this->view;
    }

    /**
    * EditView Of Survey module
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_edit_survey() {
        $record_id = $_REQUEST['record']['record'];
        $template_id = $_REQUEST['record']['template_id'];
        $prefill_type = $_REQUEST['record']['prefill_type'];

        // generate Data from Survey Template
        if (isset($template_id) && $template_id != ''){
            if ($prefill_type != 'bc_survey') { // create survey from template
                $template = new bc_survey_template();
                $template->retrieve($template_id);
                $template->load_relationship('bc_survey_pages_bc_survey_template');
                $related_pages = $template->bc_survey_pages_bc_survey_template->getBeans();
            } else { // Duplicate Survey
                $template = new bc_survey();
                $template->retrieve($template_id);
                $template->load_relationship('bc_survey_pages_bc_survey');
                $related_pages = $template->bc_survey_pages_bc_survey->getBeans();
            }
            $page_count = 0;
            $questions = array();
            $survey_details = array();
            $survey_details['name'] = htmlspecialchars_decode($template->name, ENT_QUOTES);
            $survey_details['description'] = htmlspecialchars_decode($template->description, ENT_QUOTES);
            foreach ($related_pages as $pages) {
                unset($questions);
                $survey_details[$pages->page_sequence]['page_title'] = $pages->name;
                $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
                $survey_details[$pages->page_sequence]['page_id'] = '';
                $survey_details[$pages->page_sequence]['page_id_del'] = '';
                $pages->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
                    $questions[$survey_questions->question_sequence]['que_id'] = '';
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
                    //for muliple choice question advanced type
                    if ($que_type == "MultiSelectList" || $que_type == "DrodownList") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                    }
                    //for muliple choice question advanced type
                    if ($que_type == "Checkbox" || $que_type == "RadioButton") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for Image question advanced type
                    if ($que_type == "Image") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for video question advanced type
                    if ($que_type == "Video") {
                        $questions[$survey_questions->question_sequence]['descvideo'] = $survey_questions->description;
                    }
                    //for matrix question advanced type
                    if ($que_type == "Matrix") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                        $questions[$survey_questions->question_sequence]['matrix_row'] = json_decode(base64_decode($survey_questions->matrix_row));
                        $questions[$survey_questions->question_sequence]['matrix_col'] = json_decode(base64_decode($survey_questions->matrix_col));
                    }
                    //for scale question advanced type
                    if ($que_type == "Scale") {
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                        $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                        $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        $questions[$survey_questions->question_sequence]['step_value'] = $survey_questions->scale_slot;
                    }
                    //for date question advanced type
                    if ($que_type == "Date") {
                        $questions[$survey_questions->question_sequence]['min'] = $survey_questions->min;
                        $questions[$survey_questions->question_sequence]['max'] = $survey_questions->max;
                        $questions[$survey_questions->question_sequence]['is_datetime'] = $survey_questions->is_datetime;
                    }
                    $questions[$survey_questions->question_sequence]['que_id_del'] = '';
                    $survey_questions->load_relationship('bc_survey_answers_bc_survey_questions');
                    foreach ($survey_questions->bc_survey_answers_bc_survey_questions->getBeans() as $survey_answers) {
                        $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence] = array('id' => '', 'name' => $survey_answers->name, 'and_id_del' => '', 'score_value' => $survey_answers->score_weight, 'option_type' => $survey_answers->ans_type);
                    }
                    if (isset($questions[$survey_questions->question_sequence]['answers']) && is_array($questions[$survey_questions->question_sequence]['answers']))
                        ksort($questions[$survey_questions->question_sequence]['answers']);
                }
                ksort($questions);
                $survey_details[$pages->page_sequence]['page_questions'] = $questions;
            }
        }
        if (isset($record_id) && $record_id != '') {
            $survey = new bc_survey();
            $survey->retrieve($record_id);
            $survey->load_relationship('bc_survey_pages_bc_survey');
            $page_count = 0;
            $questions = array();
            $survey_details['theme'] = $survey->theme;
            $survey_details['name'] = htmlspecialchars_decode($survey->name, ENT_QUOTES);
            $survey_details['description'] = $survey->description;
            $survey_details['survey_type'] = $survey->survey_type;
            $survey_details['sync_module'] = $survey->sync_module;
            $survey_details['enable_data_piping'] = $survey->enable_data_piping;
            foreach ($survey->bc_survey_pages_bc_survey->getBeans() as $pages) {
                unset($questions);
                $survey_details[$pages->page_sequence]['page_title'] = $pages->name;
                $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
                $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
                $survey_details[$pages->page_sequence]['page_id_del'] = $pages->id;
                $pages->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
                    $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_title'] = $survey_questions->name;
                    $questions[$survey_questions->question_sequence]['que_type'] = $survey_questions->question_type;
                    $questions[$survey_questions->question_sequence]['is_required'] = $survey_questions->is_required;
                    $questions[$survey_questions->question_sequence]['question_help_comment'] = $survey_questions->question_help_comment;
                    $questions[$survey_questions->question_sequence]['que_id_del'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['enable_scoring'] = $survey_questions->enable_scoring;
                    $questions[$survey_questions->question_sequence]['is_enable'] = $survey_questions->enable_other_option;
                    $questions[$survey_questions->question_sequence]['selection_limit'] = $survey_questions->selection_limit;
                    $questions[$survey_questions->question_sequence]['desable_piping'] = $survey_questions->desable_piping;
                    $questions[$survey_questions->question_sequence]['sync_fields'] = $survey_questions->sync_fields;
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
                    //for multiple choice question advanced type
                    if ($que_type == "MultiSelectList" || $que_type == "DrodownList") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                    }
                    //for multiple choice question advanced type
                    if ($que_type == "Checkbox" || $que_type == "RadioButton") {
                        $questions[$survey_questions->question_sequence]['is_sort'] = $survey_questions->is_sort;
                        $questions[$survey_questions->question_sequence]['advance_type'] = $survey_questions->advance_type;
                    }
                    //for image question advanced type
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
                        $questions[$survey_questions->question_sequence]['answers'][$survey_answers->answer_sequence] = array('id' => $survey_answers->id, 'name' => $survey_answers->name, 'and_id_del' => $survey_answers->id, 'score_value' => $survey_answers->score_weight, 'skip_action' => $survey_answers->logic_action, 'skip_target' => $survey_answers->logic_target, 'option_type' => $survey_answers->ans_type);
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

    /**
    * DetailView Of Survey module
    *
    * @author     Original Author Biztech Co.
    * @return     array
    */
    function action_detail_survey() {
        require_once('custom/include/utilsfunction.php');
        global $app_list_strings;
        include_once 'custom/include/pagination.class.php';
        $record_id = $_REQUEST['record'];
        $template_id = $_REQUEST['template_id'];
        $action = array('action' => 'DetailView');
        if (isset($record_id) && $record_id != '') {
            $survey = new bc_survey();
            $survey->retrieve($record_id);
            $survey->load_relationship('bc_survey_pages_bc_survey');
            $page_count = 0;
            $questions = array();
            foreach ($survey->bc_survey_pages_bc_survey->getBeans() as $pages) {
                unset($questions);
                $survey_details[$pages->page_sequence]['page_title'] = $pages->name;
                $survey_details[$pages->page_sequence]['page_number'] = $pages->page_number;
                $survey_details[$pages->page_sequence]['page_id'] = $pages->id;
                $survey_details[$pages->page_sequence]['page_id_del'] = $pages->id;
                $pages->load_relationship('bc_survey_pages_bc_survey_questions');
                foreach ($pages->bc_survey_pages_bc_survey_questions->getBeans() as $survey_questions) {
                    $questions[$survey_questions->question_sequence]['que_id'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['que_title'] = $survey_questions->name;
                    $questions[$survey_questions->question_sequence]['que_type'] = $app_list_strings['question_type_list'][$survey_questions->question_type];
                    $questions[$survey_questions->question_sequence]['is_required'] = $survey_questions->is_required;
                    $questions[$survey_questions->question_sequence]['question_help_comment'] = $survey_questions->question_help_comment;
                    $questions[$survey_questions->question_sequence]['que_id_del'] = $survey_questions->id;
                    $questions[$survey_questions->question_sequence]['matrix_row'] = json_decode(base64_decode($survey_questions->matrix_row));
                    $questions[$survey_questions->question_sequence]['matrix_col'] = json_decode(base64_decode($survey_questions->matrix_col));
                    $questions[$survey_questions->question_sequence]['desable_piping'] = $survey_questions->desable_piping;
                    $fields_name = getFieldsModule($survey->sync_module);
                    $questions[$survey_questions->question_sequence]['sync_field'] = $fields_name[$survey_questions->sync_fields];
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
            $new_Req = array();
            // Create the pagination object
            $page = (isset($_REQUEST['page']) && !is_null($_REQUEST['page'])) ? $_REQUEST['page'] : 1;
            $pagination = new pagination($survey_details, $page, 1);
            // Decide if the first and last links should show
            $pagination->setShowFirstAndLast(true);
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
        if ($survey->survey_type == "poll") {
            $survey_details['survey_type'] = $survey->survey_type;
        }
        echo json_encode($survey_details);
        exit;
    }

    /**
    * Remove survey logo
    *
    * @author     Original Author Biztech Co.
    * @return     string
    */
    function action_removeImageFromSurveyEdit() {
        global $db;
        $module = $_REQUEST['module'];
        $record = $_REQUEST['record'];
        $db->query("update bc_survey set logo = '' where id = '{$record}'");
        unlink("custom/include/surveylogo_images/" . $record);
        echo 'done';
        exit;
    }

    /**
    * get user current time formet
    *
    * @author     Original Author Biztech Co.
    * @return     date formet
    */
    function action_currentdateformat() {
        global $current_user;
        $user_tz = $current_user->getUserDateTimePreferences();
        $formet = $user_tz['date'];
        echo $formet;
        exit;
    }

    function action_deleteSubmissionFromIndividual() {
        global $db;
        $survey_id = $_REQUEST['survey_id'];
        $submission_id = $_REQUEST['submission_id'];
        $rec_id = !empty($_REQUEST['module_id']) ? $_REQUEST['module_id'] : $_REQUEST['customer_name'];
        $submission = "SELECT * FROM bc_submission_data_bc_survey_submission_c "
        . "WHERE bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida = '{$submission_id}' AND deleted = 0";
        $result = $db->query($submission);
        while ($row = $db->fetchByAssoc($result)) {
            $submitted_data = "UPDATE bc_submission_data SET deleted = 1 "
            . "WHERE id = '{$row['bc_submission_data_bc_survey_submissionbc_submission_data_idb']}'";
            $db->query($submitted_data);

            $submitteddata_question = "UPDATE bc_submission_data_bc_survey_questions_c SET deleted = 1 "
            . "WHERE bc_submission_data_bc_survey_questionsbc_submission_data_idb = '{$row['bc_submission_data_bc_survey_submissionbc_submission_data_idb']}'";
            $db->query($submitteddata_question);

            $submitteddata_answer = "UPDATE bc_submission_data_bc_survey_answers_c SET deleted = 1 "
            . "WHERE bc_submission_data_bc_survey_answersbc_submission_data_idb = '{$row['bc_submission_data_bc_survey_submissionbc_submission_data_idb']}'";
            $db->query($submitteddata_answer);
        }
        $update = "UPDATE bc_submission_data_bc_survey_submission_c SET deleted = 1 "
        . "WHERE bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida = '{$submission_id}'";
        $db->query($update);
        $rm_old_qry = "delete from bc_survey_submit_answer_calculation "
        . "WHERE survey_receiver_id = '{$rec_id}'";
        $db->query($rm_old_qry);

        $submission_delete = "UPDATE bc_survey_submission SET deleted=1 "
        . "WHERE id = '{$submission_id}'";
        $rm_survey = BeanFactory::getBean($_REQUEST['module_type'], $rec_id);
        foreach ($rm_survey->field_defs as $field) {
            // If related module survey submission exists then remove related submission
            if ($field['module'] == 'bc_survey') {
                $relationship_name = $field['relationship'];
                $rm_survey->load_relationship($relationship_name);
                foreach ($rm_survey->$relationship_name->getBeans() as $oAns) {
                    //Delete relationship
                    $rm_survey->$relationship_name->delete($rec_id, $survey_id);
                }
            }
        }
        $db->query($submission_delete);

        if (empty($submission->target_parent_id)) {
            $rm_old_qry = "delete from bc_survey_submit_answer_calculation WHERE
            survey_receiver_id = '{$rec_id}'
            ";
        } else {

            $rm_old_qry = "delete from bc_survey_submit_answer_calculation WHERE
            survey_receiver_id = '{$rec_id}'
            ";
        }
        $db->query($rm_old_qry);
        echo "done";
        exit;
    }

    function action_save_new_language() {
        $new_language = $_REQUEST['newlang'];
        $direction = $_REQUEST['direction'];
        $survey = new bc_survey();
        $survey->retrieve($_REQUEST['survey_id']);
        if ($survey->supported_survey_language != "") {
            $survey->supported_survey_language = $survey->supported_survey_language . ',^' . $new_language . '^';
        } else {
            $survey->supported_survey_language = '^' . $new_language . '^';
        }
        $survey->save();
        $oLnaguage = new bc_survey_language();
        $oLnaguage->bc_survey_id_c = $_REQUEST['survey_id'];
        $oLnaguage->survey_language = $new_language;
        $oLnaguage->text_direction = $direction;
        $oLnaguage->save();
        $languageID = $oLnaguage->id;
        echo $languageID;
        exit;
    }

    function action_delete_language() {
        global $db;
        $rem_language = $_REQUEST['remlang'];
        $survey = new bc_survey();
        $survey->retrieve($_REQUEST['survey_id']);
        $array_lang = unencodeMultienum($survey->supported_survey_language);
        foreach ($array_lang as $key => $value) {
            if ($value == $rem_language) {
                unset($array_lang[$key]);
            }
        }
        foreach ($array_lang as $k => $lang) {
            $store_language .= "^" . $lang . "^,";
        }
        $survey->supported_survey_language = trim($store_language, ",");
        $query = "UPDATE bc_survey_language SET deleted=1 WHERE bc_survey_id_c='{$_REQUEST['survey_id']}' AND survey_language='{$rem_language}'";
        $db->query($query);
        $survey->save();
        echo "done";
        exit;
    }

    function action_save_DefaultLanguage() {
        $survey = new bc_survey();
        $survey->retrieve($_REQUEST['survey_id']);
        $survey->default_survey_language = $_REQUEST['langauge_name'];
        $survey->save();
        echo "done";
        exit;
    }

    function action_generate_unique_survey_submit_id() {
        global $sugar_config;
        $GLOBALS['log']->fatal("This is the generate uid survey submit : " . print_r($args, 1));
        $survey_id = $_REQUEST['survey_id'];
        $oSurvey = BeanFactory::getBean('bc_survey', $survey_id);
        if (empty($oSurvey->survey_submit_unique_id) && empty($_REQUEST['status'])) {
            $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
            $uid = array(); //remember to declare $pass as an array
            $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
            for ($i = 0; $i < 6; $i++) {
                $n = rand(0, $alphaLength);
                $uid[] = $alphabet[$n];
            }
            $oSurvey->survey_submit_unique_id = implode($uid);
            $oSurvey->save();
        }
        // sharable survey link
        if (empty($oSurvey->survey_submit_unique_id)) {
            echo "false";
            exit;
        } else {
            $survey_sharable_link = $sugar_config['site_url'] . '/survey_submission.php?q=' . $oSurvey->survey_submit_unique_id;
            echo $survey_sharable_link;
            exit;
        }
    }

}
?>


