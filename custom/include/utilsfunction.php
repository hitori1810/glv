<?php

/**
 * get data of line chart and email opened or not etc.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

/**
 * send email to cutomer
 *
 * @author     Original Author Biztech Co.
 * @param      string - $to,$subject,$body,$module_id,$module_type
 */
function CustomSendEmail($to, $subject, $body, $module_id = '', $module_type = '', $toemail) {
    $GLOBALS['log']->fatal("recipient Email Utils: " . $to);
    $administrationObj = new Administration();
    global $current_user;
    if ($to != '') {
        $emailObj = new Email();
        $defaults = $emailObj->getSystemDefaultEmail();
        $admin = new Administration();
        $admin->retrieveSettings();
        $mail = new SugarPHPMailer();
        $mail->setMailerForSystem();
        /* Survey Rocket Sprint 2.4
         * Custom SMTP Settings For Survey Rocket.
         * Change By Govind On 18-07-2016
         */
        $ssltls = (!empty($admin->settings['SurveySmtp_survey_mail_smtpssl'])) ? $admin->settings['SurveySmtp_survey_mail_smtpssl'] : $admin->settings['mail_smtpssl'];
        if (isset($ssltls) && !empty($ssltls)) {
            $mail->protocol = "ssl://";
            if ($ssltls == 1) {
                $mail->SMTPSecure = 'ssl';
            } // if
            if ($ssltls == 2) {
                $mail->SMTPSecure = 'tls';
            } // if
        } else {
            $mail->protocol = "tcp://";
        }

        $mail->From = (!empty($admin->settings['SurveySmtp_survey_notify_fromaddress'])) ? $admin->settings['SurveySmtp_survey_notify_fromaddress'] : $admin->settings['notify_fromaddress'];
        $mail->FromName = (!empty($admin->settings['SurveySmtp_survey_notify_fromname'])) ? $admin->settings['SurveySmtp_survey_notify_fromname'] : $admin->settings['notify_fromname'];
        $mail->Username = (!empty($admin->settings['SurveySmtp_survey_mail_smtp_username'])) ? $admin->settings['SurveySmtp_survey_mail_smtp_username'] : $admin->settings['mail_smtpuser'];
        $mail->Password = (!empty($admin->settings['SurveySmtp_survey_mail_smtp_password'])) ? $admin->settings['SurveySmtp_survey_mail_smtp_password'] : $admin->settings['mail_smtppass'];
        $mail->Host = (!empty($admin->settings['SurveySmtp_survey_mail_smtp_host'])) ? $admin->settings['SurveySmtp_survey_mail_smtp_host'] : $admin->settings['mail_smtpserver'];
        $mail->Port = (!empty($admin->settings['SurveySmtp_survey_mail_smtpport'])) ? $admin->settings['SurveySmtp_survey_mail_smtpport'] : $admin->settings['mail_smtpport'];
        // End
        $mail->Subject = html_entity_decode($subject, ENT_QUOTES, 'UTF-8');
        $mail->Body = from_html($body);
        $mail->IsHTML(true);
        if ($toemail == "to") {
            $mail->AddAddress($to);
        } else if ($toemail == "cc") {
            $mail->AddCC($to);
        } else if ($toemail == "bcc") {
            $mail->AddBCC($to);
        }
        if (!$mail->send()) {
            $is_send = 'notsend';
            //$administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", $mail->ErrorInfo);
        } else {
            $is_send = 'send';
            //$administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", 'success');
            $emailObj->to_addrs = $to;
            // $emailObj->type = 'out';
            $emailObj->deleted = '0';
            $emailObj->name = $subject;
            $emailObj->description = null;
            $emailObj->description_html = from_html($body);
            $emailObj->from_addr = $mail->From;
            $emailObj->parent_type = $module_type;
            $emailObj->parent_id = $module_id;
            $user_id = $current_user->id;
            $emailObj->date_sent = TimeDate::getInstance()->nowDb();
            $emailObj->assigned_user_id = $user_id;
            $emailObj->modified_user_id = $user_id;
            $emailObj->created_by = $user_id;
            $emailObj->status = 'sent';
            $emailObj->sentFrom = 'Survey module';
            $survey_submission->processed = true;
        //    $emailObj->save();  Do Not create Email in History. Edit by Lap nguyen
        }
    } else {
        $is_send = 'notsend';
        $administrationObj->saveSetting("SurveyPlugin", "HealthCheck-SMTP", $mail->ErrorInfo);
    }
    return $is_send;
}

/**
 * get data of reports
 *
 * @author     Original Author Biztech Co.
 * @param      string - $type,$survey_id,$name,$module_id,$status
 */
function getReportData($type, $survey_id, $name = '', $module_type = '', $status = '', $survey_type = '', $sorting_type = '', $sorting_name = '') {
    global $db, $app_list_strings;
    if ($type == 'status') {
        $query = "SELECT
                    bc_survey_submission.status,bc_survey.name,
                    bc_survey.id,bc_survey_submission.email_opened ,bc_survey_submission.id AS sub_id
                  FROM bc_survey_submission
                    JOIN bc_survey_submission_bc_survey_c
                      ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
                    JOIN bc_survey
                      ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                        AND bc_survey.deleted = 0
                  WHERE bc_survey_submission.deleted = 0
                      AND bc_survey.id = '{$survey_id}'
                ";
        $status_result = $db->query($query);
        $survey_pending = array();
        $survey_submitted = array();
        $email_not_opened = 0;
        $pending = 0;
        $submitted = 0;
        while ($status_row = $db->fetchByAssoc($status_result)) {
            if ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 0) {
                $email_not_opened++;
            } elseif ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 1) {
                $pending++;
            } elseif ($status_row['status'] == 'Submitted') {
                $submitted++;
            }
            $survey['name'] = htmlspecialchars_decode($status_row['name'], ENT_QUOTES);
        }
        $survey['Pending'] = $pending;
        $survey['Submitted'] = $submitted;
        $survey['email_not_opened'] = $email_not_opened;
        if ($survey['Pending'] == 0 && $survey['Submitted'] == 0 && $survey['email_not_opened'] == 0) {
            $survey = "There is no submission for this Survey.";
        }
        return $survey;
    }if ($type == 'status_email' || $type == 'status_open_ended' || $type == 'status_combined') {
        $query = "SELECT
                    bc_survey_submission.status,bc_survey.name,
                    bc_survey.id,bc_survey_submission.email_opened ,bc_survey_submission.id AS sub_id
                  FROM bc_survey_submission
                    JOIN bc_survey_submission_bc_survey_c
                      ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
                    JOIN bc_survey
                      ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                        AND bc_survey.deleted = 0
                  WHERE bc_survey_submission.deleted = 0
                      AND bc_survey.id = '{$survey_id}'
                ";
        if (!empty($survey_type)) {
            if ($survey_type == 'Combined') {
                $query .= "";
            } else {
                $query .= " AND bc_survey_submission.submission_type = '{$survey_type}'";
            }
        }
        $status_result = $db->query($query);
        $survey_pending = array();
        $survey_submitted = array();
        $email_not_opened = 0;
        $pending = 0;
        $submitted = 0;
        while ($status_row = $db->fetchByAssoc($status_result)) {
            if ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 0) {
                $email_not_opened++;
            } elseif ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 1) {
                $pending++;
            } elseif ($status_row['status'] == 'Submitted') {
                $submitted++;
            }
            $survey['name'] = htmlspecialchars_decode($status_row['name'], ENT_QUOTES);
        }
        $survey['Pending'] = $pending;
        $survey['Submitted'] = $submitted;
        $survey['email_not_opened'] = $email_not_opened;
        if ($survey['Pending'] == 0 && $survey['Submitted'] == 0 && $survey['email_not_opened'] == 0) {
            $survey = "There is no submission for this Survey.";
        }
        return $survey;
    } elseif ($type == 'question' || $type == 'question_combined' || $type == 'question_open_ended' || $type == 'question_email') {
        $get_question_query = "SELECT  questions.name,questions.id,bc_survey_pages.page_sequence as page_seq,bc_survey_pages.name as pagename
                  FROM bc_survey_questions AS questions
                    JOIN bc_survey_bc_survey_questions_c AS question_rel
                      ON questions.id = question_rel.bc_survey_bc_survey_questionsbc_survey_questions_idb
                        AND questions.deleted = 0
                    JOIN bc_survey AS survey
                      ON survey.id = question_rel.bc_survey_bc_survey_questionsbc_survey_ida
                        AND survey.deleted = 0
                     LEFT JOIN bc_survey_pages_bc_survey_questions_c
                            ON bc_survey_pages_bc_survey_questions_c.bc_survey_pages_bc_survey_questionsbc_survey_questions_idb = questions.id
                              AND bc_survey_pages_bc_survey_questions_c.deleted = 0
                          LEFT JOIN bc_survey_pages
                            ON bc_survey_pages_bc_survey_questions_c.bc_survey_pages_bc_survey_questionsbc_survey_pages_ida = bc_survey_pages.id
                              AND bc_survey_pages.deleted = 0
                        WHERE survey.id = '{$survey_id}' AND questions.deleted = 0
                        ORDER BY bc_survey_pages.page_sequence,questions.question_sequence";
        $que_result = $db->query($get_question_query);
        while ($que_row = $db->fetchByAssoc($que_result)) {
            $question_array[$que_row['id']] = array($que_row['name'], $que_row['page_seq'], $que_row['id'], $que_row['pagename']);
        }
        return $question_array;
    } elseif ($type == 'individual') {

        $query = "SELECT submission.target_module_name AS module_name,
            submission.submission_type AS type,
                    submission.module_id AS module_id,submission.status,
                    submission.email_opened,submission.customer_name AS customer_name,
                    submission.date_entered as send_date,
                    submission.id,
                    submission.date_modified AS receive_date,
                    submission.change_request
                    FROM bc_survey_submission AS submission
                      JOIN bc_survey_submission_bc_survey_c AS related_submission
                        ON submission.id = related_submission.bc_survey_submission_bc_surveybc_survey_submission_idb
                          AND submission.deleted = 0
                          AND submission.status = 'Submitted'
                      JOIN bc_survey AS survey
                        ON survey.id = related_submission.bc_survey_submission_bc_surveybc_survey_ida
                          AND survey.deleted = 0
                          WHERE survey.id = '{$survey_id}'";

        if (!empty($name)) {
            $name_where = "AND submission.customer_name LIKE '%{$name}%'";
        }

        if (!empty($module_type)) {
            $module_name = " AND submission.target_module_name = '{$module_type}'";
        }
        if (!empty($survey_type)) {
            if ($survey_type == 'Combined') {
                $type_where = "";
            } else {
                $type_where = " AND submission.submission_type = '{$survey_type}'";
            }
        }
        if (!empty($status)) {
            if ($status == 'Pending') {
                $status_where = " AND submission.status = 'Pending' AND submission.email_opened = 0";
            } elseif ($status == 'Pending_open') {
                $status_where = " AND submission.status = 'Pending' AND submission.email_opened = 1";
            } else {
                $status_where = " AND submission.status = 'Submitted'";
            }
        }

        if (!empty($name_where)) {
            $query .= $name_where;
        }
        if (!empty($module_name)) {
            $query .= $module_name;
        }
        if (!empty($type_where)) {
            $query .= $type_where;
        }
        if (!empty($status_where)) {
            $query .= $status_where;
        }
        // set order by
        // $query .= " ORDER BY receive_date DESC";
        if (!empty($sorting_type) && !empty($sorting_name)) {
            if ($sorting_type == "Ascending") {
                if ($sorting_name == "name_label") {
                    $query .= " ORDER BY submission.customer_name ASC";
                } else if ($sorting_name == "module_label") {
                    $query .= " ORDER BY submission.target_module_name ASC";
                } else if ($sorting_name == "type_label") {
                    $query .= " ORDER BY submission.submission_type ASC";
                } else if ($sorting_name == "status_label") {
                    $query .= " ORDER BY submission.status ASC";
                } else if ($sorting_name == "send_date_label") {
                    $query .= " ORDER BY submission.date_entered ASC";
                } else if ($sorting_name == "recieve_date_label") {
                    $query .= " ORDER BY submission.date_modified ASC";
                } else if ($sorting_name == "change_request_label") {
                    $query .= " ORDER BY submission.change_request ASC";
                }
            } else {
                if ($sorting_name == "name_label") {
                    $query .= " ORDER BY submission.customer_name DESC";
                } else if ($sorting_name == "module_label") {
                    $query .= " ORDER BY submission.target_module_name DESC";
                } else if ($sorting_name == "type_label") {
                    $query .= " ORDER BY submission.submission_type DESC";
                } else if ($sorting_name == "status_label") {
                    $query .= " ORDER BY submission.status DESC";
                } else if ($sorting_name == "send_date_label") {
                    $query .= " ORDER BY submission.date_entered DESC";
                } else if ($sorting_name == "recieve_date_label") {
                    $query .= " ORDER BY submission.date_modified DESC";
                } else if ($sorting_name == "change_request_label") {
                    $query .= " ORDER BY submission.change_request DESC";
                }
            }
        } else {
            $query .= " ORDER BY
                        CASE submission.status
                        WHEN 'Submitted' THEN submission.date_modified
                        ELSE 1 END
                    DESC";
        }
        $result = $db->query($query);
        $module_types = array();
        while ($row = $db->fetchByAssoc($result)) {
            if ($row['status'] == 'Pending' && $row['email_opened'] == 0) {
                $status_value = "Not viewed";
            } elseif ($row['status'] == 'Pending' && $row['email_opened'] == 1) {
                $status_value = "Viewed";
            } elseif ($row['status'] == 'Submitted') {
                $status_value = "Submitted";
            }
            if ($row['status'] == "Submitted") {
                 $rec_date = date('Y-m-d h:i:s',  strtotime($row['receive_date']));
                $receive_date = TimeDate::getInstance()->to_display_date_time($rec_date);
            } else {
                $receive_date = 'N/A';
            }

            if (empty($row['module_id'])) {
                $send_date = date('Y-m-d h:i:s',  strtotime($row['send_date']));
                $module_types[$row['customer_name']] = array(
                    'customer_name' => $row['customer_name'],
                    'module_name' => $app_list_strings['moduleList'][$row['module_name']],
                    'type' => $row['type'],
                    'survey_status' => $status_value,
                    'send_date' => TimeDate::getInstance()->to_display_date_time($send_date),
                    'module_id' => $row['module_id'],
                    'receive_date' => $receive_date,
                    'change_request' => $row['change_request'],
                    'submission_id' => $row['id']);
            } else {
                $send_date = date('Y-m-d h:i:s',  strtotime($row['send_date']));
                $module_types[$row['module_id']] = array(
                    'customer_name' => $row['customer_name'],
                    'module_name' => $app_list_strings['moduleList'][$row['module_name']],
                    'type' => $row['type'],
                    'survey_status' => $status_value,
                    'send_date' => TimeDate::getInstance()->to_display_date_time($send_date),
                    'module_id' => $row['module_id'],
                    'receive_date' => $receive_date,
                    'change_request' => $row['change_request'],
                    'submission_id' => $row['id']);
            }
        }

        return $module_types;
    }
}

/**
 * get data of reports
 *
 * @author     Original Author Biztech Co.
 * @param      string - $records, $module_name, $survey_id,$schedule_on_time,$schedule_on_date
 * @param      bool - $isSendNow
 */
function sendSurveyEmailsModuleRecords($records, $module_name, $survey_id, $schedule_on_date = '', $schedule_on_time = '', $isSendNow = false, $beanFromAutomizer = '', $toemail = 'to') {
    global $sugar_config, $db, $current_user, $timeDate;
    $schedule_date = "";
    $returnDataSurvey = array();
    $is_send = '';
    if (!empty($schedule_on_date)) {
        $gmtdatetime = TimeDate::getInstance()->to_db($schedule_on_date);
    } else {
        $gmtdatetime = TimeDate::getInstance()->nowDb();
    }
    if ($records == 'all') {
        $table_name = '';
        switch ($module_name) {
            case "Accounts":
                $table_name = 'accounts';
                break;
            case "Contacts":
                $table_name = 'contacts';
                break;
            case "Users":
                $table_name = 'users';
                break;
            case "Leads":
                $table_name = 'leads';
                break;
            case "Prospects":
                $table_name = 'prospects';
                break;
            case "ProspectLists":
                $table_name = 'prospect_lists';
                break;
        }
        $bean_records = array();
        $all_query = "SELECT id
                        FROM " . $table_name . "
                        WHERE deleted = 0";
        $all_res = $db->query($all_query);
        while ($rec_form_all = $db->fetchByAssoc($all_res)) {
            $bean_records[] = $rec_form_all['id'];
        }
    } else {
        $bean_records = explode(",", $records);
    }
    /*
     * Get survey details
     */

    $survey = new bc_survey();
    $survey->retrieve($survey_id);
    // survey detail end
    if ($module_name == 'ProspectLists') {
        $recipients = manageTargetListsModuleForSendSurvey($bean_records, $module_name);
    } else {
        $recipients[$module_name] = $bean_records;
    }
    $optOutCount = 0;
    $firsttimecount = 0;
    $alreadySendCount = 0;
    $ResponseSubmitted = 0;
    $ResponseNotSubmitted = 0;

    foreach ($recipients as $rec_module => $rec_module_ids) {
        foreach ($rec_module_ids as $rec_module_id) {
            switch ($rec_module) {
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

            $focus->retrieve($rec_module_id);
            $record_name = $focus->name;
            if (empty($record_name)) {
                $focus = $beanFromAutomizer;
                $record_name = $focus->name;
            }
            $checkQry = "SELECT
                              submission.id,
                              submission.status,
                              submission.survey_send,
                              submission.date_entered,
                              submission.date_modified
                            FROM bc_survey_submission as submission
                              INNER JOIN bc_survey_submission_bc_survey_c as relation
                                ON relation.bc_survey_submission_bc_surveybc_survey_submission_idb = submission.id
                            WHERE submission.module_id = '{$rec_module_id}'
                                AND relation.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                                AND submission.deleted = 0 order by submission.customer_name asc";
            $checkQryRes = $db->query($checkQry);
            //$count_num_rows = 0;
            $result = $db->fetchByAssoc($checkQryRes);
            if(!empty($result)) {
               $count_num_rows++;
            }
            if ($count_num_rows != 0) {
                $is_send = "already_sent";

                $returnDataSurvey['submission_id'] = $result['id'];

                $customersOptOutArr = getOptOutEmailCustomers($rec_module_id, $rec_module);
                if ($result['survey_send'] == '0' && $result['status'] == 'Pending') {
                    if ($customersOptOutArr['email_optOut'] == 1) {
                        $optOutCount++;
                        $returnDataSurvey['alreadyOptOut']['records'][$rec_module_id] = $record_name;
                    }
                }
                if ($result['survey_send'] == '1' && $result['status'] == 'Pending') {
                    if ($customersOptOutArr['email_optOut'] == 1) {
                        $optOutCount++;
                        $returnDataSurvey['alreadyOptOut']['records'][$rec_module_id] = $record_name;
                    } else {
                        $ResponseNotSubmitted++;
                        $returnDataSurvey['ResponseNotSubmitted']['records'][$rec_module_id] = $record_name;
                    }
                }
                $returnDataSurvey['ResponseNotSubmitted']['count'] = $ResponseNotSubmitted;

                if ($result['survey_send'] == '1' && $result['status'] == 'Submitted') {
                    if ($customersOptOutArr['email_optOut'] == 1) {
                        $optOutCount++;
                        $returnDataSurvey['alreadyOptOut']['records'][$rec_module_id] = $record_name;
                    }
                    $ResponseSubmitted++;
                }
                $returnDataSurvey['ResponseSubmitted'] = $ResponseSubmitted;
                $alreadySendCount++;
                //set status for further checking of survey submission status
                $returnDataSurvey['status'] = $result['status'];
                //already scheduled
                if ($result['survey_send'] == '0' && $result['status'] == 'Pending') {
                    $firsttimecount++;
                }
                // continue with next record if survey is already sent for the record
                continue;
            }
            if ($focus->email_opt_out == 0) {
                /*
                 * Store survey data start
                 */
                $base_score = $survey->base_score;
                $survey_submission = new bc_survey_submission();
                $survey_submission->target_module_name = $rec_module;
                $survey_submission->module_id = $rec_module_id;
                $survey_submission->submission_date = '';
                $survey_submission->email_opened = 0;
                if (!empty($schedule_on)) {
                    $survey_submission->survey_send = 0;
                }
                $survey_submission->submission_type = 'Email';
                $survey_submission->recipient_email_field = $toemail;
                $survey_submission->schedule_on = $gmtdatetime;
                $survey_submission->status = 'Pending';
                $survey_submission->customer_name = $record_name;
                $survey_submission->base_score = $base_score;
                $survey_submission->processed = true;
                $survey_submission->save();
                $survey_submission->load_relationship('bc_survey_submission_bc_survey');
                $survey_submission->bc_survey_submission_bc_survey->add($survey->id);
                /*
                 * relate to modules
                 */
                $relationship = 'bc_survey_submission_' . strtolower($rec_module);
                $focus->load_relationship($relationship);
                $focus->$relationship->add($survey_submission->id);

                $survey_relationship = 'bc_survey_' . strtolower($rec_module);
                $focus->load_relationship($survey_relationship);
                $focus->$survey_relationship->add($survey->id);
                if ($isSendNow) {
                    $returnDataSurvey['submission_id'] = $survey_submission->id;
                }
                $is_send = 'scheduled';
                $firsttimecount++;
            } else {
                $returnDataSurvey['alreadyOptOut']['records'][$rec_module_id] = $record_name;
                $optOutCount++;
                $is_send = 'optOut';
            }
        }
        $returnDataSurvey['MailSentSuccessfullyFirstTime'] = $firsttimecount;
        $returnDataSurvey['alreadyOptOut']['count'] = $optOutCount;
    }
    $returnDataSurvey['is_send'] = $is_send;
    return $returnDataSurvey;
}

/**
 * get individual reports data
 *
 * @author     Original Author Biztech Co.
 * @param      string - $survey_id, $module_id, $module_type
 * @return     array
 */
function getPerson_SubmissionData($survey_id, $module_id, $module_type) {
    global $db, $sugar_config;
    $html = "";
    $focus = "";
    $get_result_query = " SELECT
                          questions.matrix_row AS matrix_rows,
                          questions.matrix_col AS matrix_cols,
                          questions.name AS question_title,
                          questions.maxsize AS star_no,
                          questions.id           AS question_id,
                          submission_ids.id      AS submission_id,
                          bc_survey_answers.name,
                          bc_survey_answers.id   AS answer_id,
                          survey.name as survey_name,
                          submission_ids.status,
                          submission_ids.target_module_name,
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
                                             bc_survey_submission.id,bc_survey_submission.status,bc_survey_submission.target_module_name,bc_survey_submission.submission_type,
                                             bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida AS survey_id
                                           FROM bc_survey_submission
                                             LEFT JOIN bc_survey_submission_bc_survey_c
                                               ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
                                                 AND bc_survey_submission_bc_survey_c.deleted = 0
                                           WHERE bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                                               AND bc_survey_submission.module_id = '{$module_id}' AND bc_survey_submission.deleted = 0) AS submission_ids
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
                                           WHERE bc_survey_submission.module_id = '{$module_id}') AS submission_data
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
    $detail_array = array();
    $i = 0;
    while ($row = $db->fetchByAssoc($result)) {
        //get module_name
        $moduleName = (is_null($row['module_name']) || empty($row['module_name'])) ? $module_type : $row['module_name'];
        if (empty($record_name)) {
            switch ($moduleName) {
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
            $focus->retrieve($module_id);
            $record_name = $focus->name;
            $html .= "<h2 class='title'>Individual Report for {$record_name}</h2>";
        }

        if ($row['status'] == 'Pending') {
            $html = "<div id='individual'>There is no submission response for this Survey.</div>";
        } else if ($row['status'] == null) {
            $html = '';
        } else {
            if ($row['status'] == 'Submitted') {
                //Contact Information then retrieve all answer from db & store in variable
                if (!empty($row['question_type']) && $row['question_type'] == 'ContactInformation') {
                    $contact_information = JSON::decode(html_entity_decode($row['name']));
                    $detail_array[$row['question_id']][$row['question_title']][$row['answer_id']] = $contact_information;
                }
                // Matrix type then get rows & columns value & generate selected answer layout
                else if (!empty($row['question_type']) && $row['question_type'] == 'Matrix') {
                    // set matrix answer to question array
                    $matrix_row = json_decode(base64_decode(($row['matrix_rows'])));
                    $matrix_col = json_decode(base64_decode(($row['matrix_cols'])));
                    $detail_array[$row['question_id']][$row['question_title']]['matrix_rows'] = $matrix_row;
                    $detail_array[$row['question_id']][$row['question_title']]['matrix_cols'] = $matrix_col;
                    $answer = getAnswerSubmissionDataForMatrix($survey_id, $module_id, $row['question_id'], $row['submission_type']);
                    $detail_array[$row['question_id']][$row['question_title']]['answer_detail'] = $answer;
                }
                // Rating then generate selected star value
                elseif (!empty($row['question_type']) && $row['question_type'] == 'Rating') {
                    $rating_value = $row['name'];
                    $site_url = $sugar_config['site_url'];
                    $rating = '';
                    for ($star = 1; $star <= $rating_value; $star++) {
                        $rating .= "<img src='{$site_url}/custom/include/survey-img/fullstar.png' alt='{$star} star'>";
                    }

                    $total_star = $row['star_no'];
                    $remaining_star_value = $total_star - $rating_value;
                    for ($star = 1; $star <= $remaining_star_value; $star++) {
                        $rating .= "<img src='{$site_url}/custom/include/survey-img/nullstar.png' alt='no star'>";
                    }

                    $detail_array[$row['question_id']][$row['question_title']][$row['answer_id']] = $rating;
                }
                // Other type of Question
                elseif (!empty($row['question_type']) && $row['question_type'] != 'Image' && $row['question_type'] != 'Video' && $row['question_type'] != 'question_section') {
                    if (array_key_exists($row['question_title'], $detail_array)) {
                        $detail_array[$row['question_id']][$row['question_title']][$row['answer_id']] = $row['name'];
                    } else {
                        $detail_array[$row['question_id']][$row['question_title']][$row['answer_id']] = $row['name'];
                    }
                }
            }
        }
    }
    return $detail_array;
}

/**
 * get person submission data
 *
 * @author     Original Author Biztech Co.
 * @param      string - $survey_id, $module_id, $export
 * @return     array
 */
function getPerson_SubmissionExportData($survey_id, $module_id, $export, $customer_name = '') {
    if (strpos($module_id, 'Web') !== false) {
        $customer_name = $module_id;
    }
    global $db, $sugar_config;
    $get_result_query = " SELECT
                          questions.name AS question_title,
                          questions.matrix_row AS matrix_rows,
                          questions.matrix_col AS matrix_cols,
                          questions.id           AS question_id,
                          submission_ids.id      AS submission_id,
                          bc_survey_answers.name AS answer_name,
                          bc_survey_answers.id   AS answer_id,
                          survey.name as survey_name,
                          submission_ids.status,
                          submission_ids.target_module_name,
                          submission_ids.customer_name,
                          survey.description,
                          submission_ids.send_date,
                          submission_ids.receive_date,
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
                                             bc_survey_submission.id,bc_survey_submission.status,bc_survey_submission.target_module_name,
                                             bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida AS survey_id,
                                             bc_survey_submission.customer_name AS customer_name,
                                             bc_survey_submission.date_entered AS send_date,
                                             bc_survey_submission.date_modified AS receive_date,
                                             bc_survey_submission.submission_type AS submission_type
                                           FROM bc_survey_submission
                                             LEFT JOIN bc_survey_submission_bc_survey_c
                                               ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
                                                 AND bc_survey_submission_bc_survey_c.deleted = 0
                                           WHERE bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                                               AND bc_survey_submission.module_id = '{$module_id}' OR bc_survey_submission.customer_name = '{$customer_name}' AND bc_survey_submission.deleted = 0) AS submission_ids
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
    $survey_details = array();
    while ($row = $db->fetchByAssoc($result)) {
        $detail_array = array();
        if (!empty($row['question_type']) && $row['question_type'] == 'ContactInformation') {
            $contact_information = JSON::decode(html_entity_decode($row['name']));
            $contactString = '';
            if (!empty($contact_information)) {
                foreach ($contact_information as $key => $value) {
                    $contactString .= $key . " : " . $value . ",";
                }
                $detail_array[$row['question_title']] = $contactString;
            } else {
                $detail_array[$row['question_title']] = 'Not Answered';
            }
        }
        // Matrix type then get rows & columns value & generate selected answer layout
        else if (!empty($row['question_type']) && $row['question_type'] == 'Matrix' && !$export) {
            // set matrix answer to question array
            $matrix_row = json_decode(base64_decode(($row['matrix_rows'])));
            $matrix_col = json_decode(base64_decode(($row['matrix_cols'])));
            $answer = getAnswerSubmissionDataForMatrix($survey_id, $module_id, $row['question_id']);
            foreach ($answer as $key => $anss_matrix) {
                foreach ($anss_matrix as $key_seq => $anss_matrix_final) {
                    if (!empty($anss_matrix_final)) {
                        if (!empty($anss_matrix_new)) {
                            $anss_matrix_new = $anss_matrix_new . ',' . $anss_matrix_final;
                        } else {
                            $anss_matrix_new = $anss_matrix_final;
                        }
                        $detail_array[$row['question_title']] = $anss_matrix_new;
                    }
                }
            }
            $i++;
        } // Matrix type then get rows & columns value & generate selected answer layout when not export
        else if (!empty($row['question_type']) && $row['question_type'] == 'Matrix' && $export) {
            // set matrix answer to question array
            $matrix_row = json_decode(base64_decode(($row['matrix_rows'])));
            $matrix_col = json_decode(base64_decode(($row['matrix_cols'])));
            $answer = $row['answer_name'];
            if (!empty($answer)) {
                $splited_answer = split('_', $answer);
                $aRow = $splited_answer[0];
                $aCol = $splited_answer[1];
                $answer_row = $matrix_row->$aRow;
                $answer_col = $matrix_col->$aCol;
            }

            //if (!empty($answer_row) && !empty($answer_col) && empty($detail_array[$row['question_title'] . '(' . $answer_row . ')'])) {
                if (!empty($answer_row) && !empty($answer_col)) {
                                $survey_details[$row['question_id']][$row['question_title'] . '(' . $answer_row . ')'] = $answer_col;
                            }
           // }
        } else if (!empty($row['question_type']) && ($row['question_type'] == 'Checkbox' || $row['question_type'] == 'MultiSelectList' || $row['question_type'] == 'RadioButton' || $row['question_type'] == 'DrodownList') && !($export)) {
            if (array_key_exists($row['question_title'], $detail_array)) {
                array_push($detail_array[$row['question_title']], $row['answer_id']);
            } else {
                $detail_array[$row['question_title']][] = $row['answer_id'];
            }
        } else if (!empty($row['question_type']) && ($row['question_type'] == 'Checkbox' || $row['question_type'] == 'MultiSelectList' || $row['question_type'] == 'RadioButton' || $row['question_type'] == 'DrodownList') && ($export)) {
            if (array_key_exists($row['question_title'], $detail_array)) {
                $detail_array[$row['question_title']] = $detail_array[$row['question_title']] . ',' . $row['answer_id'];
            } else {
                $detail_array[$row['question_title']] = $row['answer_name'];
            }
        } else {
            if (array_key_exists($row['question_title'], $detail_array)) {
                array_push($detail_array[$row['question_title']], $row['answer_name']);
            } else {
                $detail_array[$row['question_title']][] = $row['answer_name'];
            }
        }
        //if key already exist then store multiple answer in same question array
        if (array_key_exists($row['question_id'], $survey_details) && !$export) {
            $queAnsArray = $survey_details[$row['question_id']];
            $queArr = array_keys($queAnsArray);
            $Question = $queArr[0];
            array_push($survey_details[$row['question_id']][$Question], $row['answer_name']);
        } //if not matrix then store single answer
        else if ($export && $row['question_type'] == 'Matrix') {
              if (empty($survey_details[$row['question_id']])) {
                    foreach ($matrix_row as $key => $row_value) {
                        $survey_details[$row['question_id']][$row['question_title'] . '(' . $row_value . ')'] = '';
                    }
                }
        } else if (array_key_exists($row['question_id'], $survey_details) && $export) {
            $queAnsArray = $survey_details[$row['question_id']];
            $queArr = array_keys($queAnsArray);
            $Question = $queArr[0];
            $survey_details[$row['question_id']][$Question] = $survey_details[$row['question_id']][$Question] . ',' . $row['answer_name'];
        }
        else if (!empty($row['question_type']) && $row['question_type'] == 'Matrix' && $export) {
            $Question = $row['question_title'];
            $survey_details[$row['question_id']][$Question] = $row['answer_name'];
        } else if ((!empty($row['question_type']) && $row['question_type'] != 'Matrix')) {
            $survey_details[$row['question_id']] = $detail_array;
        } else {
            $survey_details[$row['question_id']] = $detail_array;
        }
        if ($row['question_type'] == 'Scale') {
            $survey_details[$row['question_id']]['answer_name'] = $row['answer_name'];
        }
    }
    return $survey_details;
}

/**
 * get person submission data
 *
 * @author     Original Author Biztech Co.
 * @param      string - $type, $survey_id, $name,$module_type,$status
 * @return     array
 */
function getAllExportData($type, $survey_id, $name = '', $module_type = '', $status = '', $survey_type = '') {
    $submtData = array();
    $submtData = getReportData($type, $survey_id, $name, $module_type, $status, $survey_type);
    foreach ($submtData as $sbmtId => $sbmtData) {
        $questionAnsArray = array();
        $sbmtSurvData = getPerson_SubmissionExportData($survey_id, $sbmtId, true);
        foreach ($sbmtSurvData as $queAns) {
            $question = array_keys($queAns);
            foreach($question as $key => $answers)
            {
              $questionAnsArray[] = array($question[$key] => $queAns[$question[$key]]);
        }
        }
        $submtData[$sbmtId]['Response'] = $questionAnsArray;
        unset($submtData[$sbmtId]['module_id']);
    }
    return $submtData;
}

/**
 * get person submission data
 *
 * @author     Original Author Biztech Co.
 * @param      string - $records, $module_name, $survey_id
 * @return     array
 */
function sendSurveyReminderEmails($records, $module_name, $survey_id) {
    global $db;
    $getSubmissionId = "SELECT
                        bc_survey_submission.id AS submission_id
                    FROM
                        bc_survey_submission
                            JOIN
                        bc_survey_submission_bc_survey_c ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
                            AND bc_survey_submission.deleted = 0
                            JOIN
                        bc_survey ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                            AND bc_survey.deleted = 0
                    WHERE
                        bc_survey.id = '{$survey_id}'
                            AND bc_survey_submission.module_id = '{$records}'
                            AND bc_survey_submission.target_module_name = '{$module_name}'";

    $runQuery = $db->query($getSubmissionId);
    $result = $db->fetchByAssoc($runQuery);
    $submission_id = $result['submission_id'];

    //load record of submission and update resend values
    $survey_submission = new bc_survey_submission();
    $survey_submission->retrieve($submission_id);
    $resend_counter = $survey_submission->resend_counter + 1; // update counter
    $survey_submission->resend_counter = $resend_counter;
    $survey_submission->resend = 1;
    $survey_submission->processed = true;
    $survey_submission->save();

    $is_send = 'scheduled';

    return $is_send;
}

/**
 * get person submission data
 *
 * @author     Original Author Biztech Co.
 * @param      string - $survey_id, $module_name,$isSendNow, $record_name
 * @param      array - $customersSummaryData
 * @param      integer - $total_seleted_records
 * @param      bool - $isSendNow
 * @return     array
 */
function createContentForMailStatusPopup($customersSummaryData, $survey_id, $module_name, $total_seleted_records, $isSendNow, $record_name) {
    $customersFirstTimeSuccSentArray = (isset($customersSummaryData['MailSentSuccessfullyFirstTime']) && !empty($customersSummaryData['MailSentSuccessfullyFirstTime'])) ? $customersSummaryData['MailSentSuccessfullyFirstTime'] :
            array();
    $customersResponseSubmitted = (isset($customersSummaryData['ResponseSubmitted']) && !empty($customersSummaryData['ResponseSubmitted'])) ? $customersSummaryData['ResponseSubmitted'] :
            array();
    $customersPendingResponse = (isset($customersSummaryData['ResponseNotSubmitted']) && !empty($customersSummaryData['ResponseNotSubmitted'])) ? $customersSummaryData['ResponseNotSubmitted'] :
            array();
    $surveyUnsubscribeCustomers = (isset($customersSummaryData['unsubscribeCustomers']) && !empty($customersSummaryData['unsubscribeCustomers'])) ? $customersSummaryData['unsubscribeCustomers'] : array();
    $alreadyOptOutCustomers = (isset($customersSummaryData['alreadyOptOut']) && !empty($customersSummaryData['alreadyOptOut'])) ? $customersSummaryData['alreadyOptOut'] : array();
    $allOptCustomers = array_merge($surveyUnsubscribeCustomers, $alreadyOptOutCustomers);
    if ($customersPendingResponse['records']) {
        $pending_res_record = "";
        foreach ($customersPendingResponse['records'] as $record_id => $pending_res) {
            if (empty($pending_res_record)) {
                $pending_res_record = $record_id;
            } else {
                $pending_res_record .= "," . $record_id;
            }
        }
    }
    if ($allOptCustomers['records']) {
        $opted_out_record = "";
        foreach ($allOptCustomers['records'] as $record_id => $opted_out) {
            if (empty($opted_out_record)) {
                $opted_out_record = $record_id;
            } else {
                $opted_out_record .= "," . $record_id;
            }
        }
    }
    $html = '';
    $html .= "<html>
             <head>
             </head>
             <body>
              <div class='main-t-survey'>";
    // first section
    if (!empty($customersFirstTimeSuccSentArray) && $customersFirstTimeSuccSentArray > 0 && !$isSendNow) {
        $html .= "<table width='100%'>
                    <thead>
                    <tr class='title'>
                        <td colspan='3'><span>Your Survey email will be delivered to <strong style='color: #008000'>{$customersFirstTimeSuccSentArray}</strong> {$module_name} very soon.</td>
                    </tr>
                    </thead>
                  </table>";
    }

    // second section
    if (!empty($customersResponseSubmitted) && $customersResponseSubmitted > 0) {
        $html .= "<table width='100%'>
                    <thead>
                        <tr class='title'>
                            <td colspan='4'><span>Your Survey email has been already delivered to <strong style='color: #008000'>{$customersResponseSubmitted}</strong> {$module_name} and also received their response.</span></td>
                        </tr>
                    </thead>
                 </table>";
    }

    // third section
    if (!empty($customersPendingResponse['count']) && $customersPendingResponse['count'] > 0) {
        $html .= "<table width='100%'>
                    <thead>
                        <tr class='title'>
                            <td colspan='4'><span> Your Survey email has already delivered to <strong style='color: #008000'>{$customersPendingResponse['count']}</strong> {$module_name} and but not received response.
                            <strong>
                               <form method='post' action='index.php?module=bc_survey&action=DisplayPendingResView&survey_id={$survey_id}&module_name={$module_name}&type=pending_res' target='_blank'>
                                <textarea name='pending_res_record' style='display: none'>{$pending_res_record}</textarea>
                                <input type='submit' name='ViewPendingRes' value='View'>
                           </form>
                           </strong>
                           </span>
                           </td>
                        </tr>
                      </thead>
                 </table>";
    }

    // fourth section
    if (!empty($allOptCustomers['count']) && $allOptCustomers['count'] > 0) {
        $url = '#bc_survey/' . $survey_id . '/layout/survey_sent_summary_view/opted_out';
        $html .= "<table width='100%'><thead><tr class='title'>
                   <td colspan='3'><span>Your Survey email will not be send to <strong style='color: #008000'>{$allOptCustomers['count']}</strong> {$module_name} due to opted-out email-address.</span>
                       <strong>
                           <form method='post' action='index.php?module=bc_survey&action=DisplayPendingResView&survey_id={$survey_id}&module_name={$module_name}&type=opted_out' target='_blank'>
                                <textarea name='opted_out_record' style='display: none'>{$opted_out_record}</textarea>
                                <input type='submit' name='ViewOptedOutRes' value='View'>
                           </form>
                       </strong>
                   </td>
               </tr></thead>
             </table>";
    }

    // fourth section
    if ($isSendNow) {
        $url = '#bc_survey/' . $survey_id . '/layout/survey_sent_summary_view/opted_out';
        $html .= "<table width='100%'><thead><tr class='title'>
                   <td colspan='3'><span>Your Survey email successfully delievered to <strong style='color: #008000'>{$record_name}</strong>.</span>
                   </td>
               </tr></thead>
             </table>";
    }

    // for display button
    $html .="<table width='100%'><thead><tr><td style='background-color: #0b578f;font-size: 14px;color: #ffffff;width: 40%;text-align: center'>Total {$total_seleted_records} {$module_name} selected</td></tr><thead></table>";

    $html .="</div>
             <style type='text/css'>
             .main-t-survey{max-height:350px; padding-bottom:20px;}
             .main-t-survey table{border: 1px solid #DDD; padding:10px; margin:0px 0px 10px 0px;}
             .main-t-survey tr.title{background: #F7F7F7; border-top: 1px solid #DDD; border-bottom: 1px solid #DDD;}
             .main-t-survey tr{background: #fff; border-top: 1px solid #DDD; border-bottom: 1px solid #DDD;}
             .main-t-survey tr td, .main-t-survey tr th{color: #23527C; padding:8px; text-align:left;width: auto;float: none;}
             .dialog_style .ui-dialog-titlebar{ background: #FFF none repeat scroll 0% 0%; color: #565656;  border: medium none;}
             </style>";
    $html .="</body>
             </html>";
    return $html;
}

/**
 * customer unsubscribe for mail
 *
 * @author     Original Author Biztech Co.
 * @param      string - $moduleID, $module_name
 * @return     array
 */
function getOptOutEmailCustomers($moduleID, $moduleName) {
    global $db;
    switch ($moduleName) {
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
    $focus->retrieve($moduleID);
    $survey_reciever = $focus->name;
    $email = $focus->email1;
    $optOutEmailDetails = array();
    $emailAddQry = "SELECT
                            email_addr_bean_rel.bean_id as moduleID,
                            email_addresses.email_address as email_add,
                            email_addresses.opt_out as email_optOut,
                            email_addr_bean_rel.email_address_id AS email_add_id
                          FROM email_addr_bean_rel
                          INNER JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id
                          WHERE email_addr_bean_rel.bean_id = '{$focus->id}'
                              AND email_addr_bean_rel.bean_module = '{$moduleName}'
                              AND email_addresses.email_address = '{$email}'
                              AND	email_addr_bean_rel.deleted = 0
                              AND email_addr_bean_rel.primary_address = 1";
    $emailAddQryRes = $db->fetchByAssoc($db->query($emailAddQry));
    $optOutEmailDetails['email_add'] = $emailAddQryRes['email_add'];
    $optOutEmailDetails['email_add_id'] = $emailAddQryRes['email_add_id'];
    $optOutEmailDetails['email_optOut'] = $emailAddQryRes['email_optOut'];
    $optOutEmailDetails['beanID'] = $emailAddQryRes['moduleID'];
    return $optOutEmailDetails;
}

/**
 * get Email Template by Survey id
 *
 * @author     Original Author Biztech Co.
 * @param      string - $surveyID, $surveyModule
 * @return     string
 */
function getEmailTemplateBySurveyID($surveyID) {
    global $db;
    $checkEmailTemplateQuery = "SELECT email_templates.id as emailTempID
                                    FROM   email_templates
                                    WHERE  email_templates.survey_id='{$surveyID}'
                                    AND    email_templates.deleted = 0
                                    ";
    $runQuery = $db->query($checkEmailTemplateQuery);
    $emailTemplateData = $db->fetchByAssoc($runQuery);
    $emailTemplateID = (!empty($emailTemplateData['emailTempID'])) ? $emailTemplateData['emailTempID'] : '';
    return $emailTemplateID;
}

/**
 * manage target list module for send survey
 *
 * @author     Original Author Biztech Co.
 * @param      string - $bean_records, $module_name
 * @return     array
 */
function manageTargetListsModuleForSendSurvey($bean_records, $module_name) {
    $accounts = array();
    $contacts = array();
    $leads = array();
    $targets = array();
    $users = array();
    $recipients = array();

    foreach ($bean_records as $ProspectListId) {
        $targetList = new ProspectList();
        $targetList->retrieve($ProspectListId);

        $targetList->load_relationship('accounts');
        $accounts = array_merge($accounts, $targetList->accounts->get());

        $targetList->load_relationship('contacts');
        $contacts = array_merge($contacts, $targetList->contacts->get());

        $targetList->load_relationship('leads');
        $leads = array_merge($leads, $targetList->leads->get());


        $targetList->load_relationship('prospects');
        $targets = array_merge($targets, $targetList->prospects->get());

        $targetList->load_relationship('users');
        $users = array_merge($users, $targetList->users->get());
    }
    $recipients['Accounts'] = $accounts;
    $recipients['Contacts'] = $contacts;
    $recipients['Leads'] = $leads;
    $recipients['Prospects'] = $targets;
    $recipients['Users'] = $users;
    return $recipients;
}

/**
 * Submited survey Response calculation
 *
 * @author     Original Author Biztech Co.
 * @param      string - $submit_survey_answerID, $sent_surveyID, $survey_receiverID, $answerType, $submisstion_id, $submitted_que
 * @param      integer - $delete_flag,
 * @return     array
 */
function submitSurveyResponseCalulation($submit_survey_answerID, $sent_surveyID, $survey_receiverID, $answerType, $submisstion_id, $delete_flag, $submitted_que) {
    global $db;
    //check resubmission or not if resubmission then delete old data
    $check_resubmit_qry = "select resubmit from bc_survey_submission WHERE id = '{$submisstion_id}'";
    $chk_result = $db->query($check_resubmit_qry);
    $resubmit_status = $db->fetchbyAssoc($chk_result);
    if ($resubmit_status['resubmit'] == '1' && $delete_flag == 0) {
        //remove old data
        $rm_old_qry = "delete from bc_survey_submit_answer_calculation WHERE sent_survey_id = '{$sent_surveyID}'
                                  and   survey_receiver_id = '{$survey_receiverID}'
                                      and answer_type = '{$answerType}'";
        $result_deleted = $db->query($rm_old_qry);
    }
    // first get if we submitted answer exists
    $answerTypeArray = array('Textbox', 'CommentTextbox', 'ContactInformation', 'Rating', 'Image', 'Video');
    if (!in_array($answerType, $answerTypeArray)) {
        if ($db->dbType == "mssql") {
        $check_if_answer_AlreadySubmit = "Select
                                          id,
                                          answer_type as AnswerType,
                                          submit_answer_id as SubmitAns
                                      from bc_survey_submit_answer_calculation
                                      where CONVERT(VARCHAR ,sent_survey_id) = '{$sent_surveyID}'
                                      and   CONVERT(VARCHAR ,survey_receiver_id) = '{$survey_receiverID}'
                                      and CONVERT(VARCHAR ,submit_answer_id) = '{$submit_survey_answerID}'";
        } else {
            $check_if_answer_AlreadySubmit = "Select
                                          id,
                                          answer_type as AnswerType,
                                          submit_answer_id as SubmitAns
                                      from bc_survey_submit_answer_calculation
                                      where sent_survey_id = '{$sent_surveyID}'
                                      and   survey_receiver_id = '{$survey_receiverID}'
                                      and submit_answer_id = '{$submit_survey_answerID}'";
        }
        $runQuery = $db->query($check_if_answer_AlreadySubmit);
        $count_num_rows = 0;
        while($existAns = $db->fetchbyAssoc($runQuery)){
          $count_num_rows++;
        }
        if ($count_num_rows != 0) {

            if ($answerType == 'Matrix') {
                if (is_array($submit_survey_answerID)) {
                    $answersData = '';
                    foreach ($submit_survey_answerID as $k1 => $data) {
                        foreach ($data as $k => $ans) {
                            $answersData .= $ans . ',';
                        }
                    }
                }
            } else {
                $answersData = implode(',', $submit_survey_answerID);
            }

            $db->query(" Update bc_survey_submit_answer_calculation
                       set submit_answer_id = '{$answersData}'
                           where sent_survey_id = '{$sent_surveyID}'
                                  and   survey_receiver_id = '{$survey_receiverID}'
                                      and answer_type = '{$answerType}'
                                          and question_id = '{$submitted_que}'
                ");
        } else {
            $id = create_guid();
            if ($answerType == 'Matrix') {
                if (is_array($submit_survey_answerID)) {
                    $answersData = '';
                    foreach ($submit_survey_answerID as $k1 => $data) {
                        foreach ($data as $k => $ans) {
                            $answersData .= $ans . ',';
                        }
                    }
                }
            } else {
                $answersData = implode(',', $submit_survey_answerID);
            }
            $db->query(" Insert into bc_survey_submit_answer_calculation
                         (id,answer_type,submit_answer_id,sent_survey_id,survey_receiver_id,question_id)
                         Values ('{$id}','{$answerType}','{$answersData}','{$sent_surveyID}','{$survey_receiverID}','{$submitted_que}')
                ");
        }
    }
}

/**
 * answer Submission Count
 *
 * @author     Original Author Biztech Co.
 * @param      string - $surveyID
 * @return     array
 */
function getAnswerSubmissionCount($surveyID, $survey_type) {
    global $db;
    $selectEachAnswerSubCount = "SELECT
                                bc_survey_submit_answer_calculation.survey_receiver_id AS recId,
                                bc_survey_submit_answer_calculation.question_id AS queId,
                                bc_survey_submit_answer_calculation.submit_answer_id AS ansSubmitCount,
                                bc_survey_submit_answer_calculation.answer_type AS ans_type
                            FROM
                                bc_survey_submit_answer_calculation
                            WHERE
                                bc_survey_submit_answer_calculation.sent_survey_id = '{$surveyID}'
    ";
    $runQuery = $db->query($selectEachAnswerSubCount);
    $submit_answer_Array = array();
    $is_matrix = false;
    $matri_all_count_array = array();

    while ($resultCountData = $db->fetchByAssoc($runQuery)) {
        $xRecId = str_split($resultCountData['recId'], 8);
        if (($survey_type == 'Open Ended' && $xRecId[0] == 'Web Link') || ($survey_type == 'Email' && $xRecId[0] != 'Web Link') || ($survey_type == 'Combined')) {
            $explodeData = explode(',', $resultCountData['ansSubmitCount']);
            if ($resultCountData['ans_type'] == 'Matrix') {
                $qid = $resultCountData['queId'];
                $is_matrix = true;
                if (is_array($explodeData)) {
                    foreach ($explodeData as $ansID) {
                        $matrix = split('_', $ansID);
                        $count = isset($matri_all_count_array[$qid][$matrix[0]][$matrix[1]]) ? $matri_all_count_array[$qid][$matrix[0]][$matrix[1]] : 0;
                        $count++;
                        $matri_all_count_array[$qid][$matrix[0]][$matrix[1]] = $count;
                    }
                }
            }

            if ($resultCountData['ans_type'] == 'Scale') {
                $qid = $resultCountData['queId'];
                foreach ($explodeData as $k => $data) {
                    if (!empty($data)) {
                        $scale_answer_Array['scale'][$qid][] = $explodeData;
                    }
                }
            }
            if (is_array($explodeData)) {
                foreach ($explodeData as $ansID) {
                    $submit_answer_Array[] = $ansID;
                }
            } else {
                $submit_answer_Array[] = $explodeData;
            }
        }
    }

    $countEachAns = array();
    $countEachAns = array_count_values($submit_answer_Array);
    if ($is_matrix) {

        $countEachAns['matrix'] = $matri_all_count_array;
    }

    if (is_array($scale_answer_Array)) {
        $count = 0;
        foreach ($scale_answer_Array as $key => $scale) {
            foreach ($scale as $que_id => $ans) {
                foreach ($ans as $k => $answer) {
                    if (!empty($answer)) {
                        $count++;
                        $countEachAns['scale'][$que_id] = $count;
                    }
                }
            }
        }
    }

    return $countEachAns;
}

/**
 * answer Submission For Matrix Question
 *
 * @author     Original Author Biztech Co.
 * @param      string -$surveyID, $moduleID, $questionID
 * @return     array
 */
function getAnswerSubmissionDataForMatrix($surveyID, $moduleID, $questionID, $survey_type) {
    global $db;
    if (!empty($moduleID)) {
        $selectEachAnswerSubCount = "SELECT
                                bc_survey_submit_answer_calculation.question_id AS queId,
                                bc_survey_submit_answer_calculation.submit_answer_id AS ansSubmitCount,
                                bc_survey_submit_answer_calculation.answer_type AS ans_type,
                                bc_survey_submit_answer_calculation.survey_receiver_id AS survey_receiver_id
                            FROM
                                bc_survey_submit_answer_calculation
                            WHERE
                                bc_survey_submit_answer_calculation.sent_survey_id = '{$surveyID}'
                            AND
                                bc_survey_submit_answer_calculation.survey_receiver_id = '{$moduleID}'
                            AND
                                bc_survey_submit_answer_calculation.question_id = '{$questionID}' ";
    } else {
        $selectEachAnswerSubCount = "SELECT
                                bc_survey_submit_answer_calculation.question_id AS queId,
                                bc_survey_submit_answer_calculation.submit_answer_id AS ansSubmitCount,
                                bc_survey_submit_answer_calculation.answer_type AS ans_type,
                                bc_survey_submit_answer_calculation.survey_receiver_id AS survey_receiver_id
                            FROM
                                bc_survey_submit_answer_calculation
                            WHERE
                                bc_survey_submit_answer_calculation.sent_survey_id = '{$surveyID}'
                            AND
                                bc_survey_submit_answer_calculation.question_id = '{$questionID}'";
    }
    $runQuery = $db->query($selectEachAnswerSubCount);
    $explodeData = array();

    while ($resultCountData = $db->fetchByAssoc($runQuery)) {
        $xRecId = str_split($resultCountData['recId'], 8);
        if (($survey_type == 'Open Ended' && $xRecId[0] == 'Web Link') || ($survey_type == 'Email' && $xRecId[0] != 'Web Link') || ($survey_type == 'Combined')) {
            if (isset($explodeData[$resultCountData['survey_receiver_id']])) {
                $new_data = explode(',', $resultCountData['ansSubmitCount']);
                foreach ($new_data as $data) {
                    if (!empty($data)) {
                        array_push($explodeData[$resultCountData['survey_receiver_id']], $data);
                    }
                }
            } else {
                if (!empty($resultCountData['ansSubmitCount'])) {
                    $explodeData[$resultCountData['survey_receiver_id']] = explode(',', $resultCountData['ansSubmitCount']);
                }
            }
        }
    }
    return $explodeData;
}

/**
 * answer Submission For Matrix Question
 *
 * @author     Original Author Biztech Co.
 * @param      string -$survey_id, $que_id
 * @param      array - $question_obj
 * @return     array
 */
function getQuestionWiseData($survey_id, $que_id, $question_obj, $survey_type) {
    global $db;
    $query = "SELECT bc_survey_questions.name,bc_survey_answers.name,bc_survey.id,bc_survey_questions.question_type,
bc_survey_answers.id AS answer_id ,bc_survey_questions.id AS que_id
FROM bc_survey_questions
  JOIN bc_survey_bc_survey_questions_c
    ON bc_survey_questions.id = bc_survey_bc_survey_questions_c.bc_survey_bc_survey_questionsbc_survey_questions_idb
      AND bc_survey_questions.deleted = 0
      JOIN bc_survey ON bc_survey.id      = bc_survey_bc_survey_questions_c.bc_survey_bc_survey_questionsbc_survey_ida
      AND bc_survey_bc_survey_questions_c.deleted      = 0
          LEFT OUTER JOIN (SELECT
                                             bc_survey_submission.id,
                                             bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida AS survey_id,
                                             bc_survey_submission.customer_name AS customer_name,
                                             bc_survey_submission.submission_type AS submission_type
                                           FROM bc_survey_submission
                                             LEFT JOIN bc_survey_submission_bc_survey_c
                                               ON bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb = bc_survey_submission.id
                                                 AND bc_survey_submission_bc_survey_c.deleted = 0
                                           WHERE bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida =  '{$survey_id}'";
    if ($survey_type == 'Combined') {
        $query .= "";
    } else {
        $query .= " AND bc_survey_submission.submission_type = '{$survey_type}'";
    }

    $query .= ") AS submission_ids
                            ON submission_ids.survey_id = bc_survey.id
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
                                             RIGHT JOIN bc_survey_submission
                                               ON bc_survey_submission.id = bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida
                                                 AND bc_survey_submission.deleted = 0";
    if ($survey_type == 'Combined') {
        $query .= "";
    } else {
        $query .= " AND bc_survey_submission.submission_type = '{$survey_type}'";
    }
    $query .= " ) AS submission_data
                            ON submission_data.sub_que_id = bc_survey_questions.id
                                LEFT JOIN bc_survey_answers
                            ON bc_survey_answers.id = submission_data.sub_ans_id
                              AND bc_survey_answers.deleted = 0
      WHERE bc_survey.id      = '{$survey_id}'
      AND bc_survey_questions.id = '{$que_id}'
      AND bc_survey_questions.question_type = '{$question_obj->question_type}' ";
    if ($survey_type == 'Combined') {
        $query .= "";
    } else {
        $query .= " AND submission_ids.submission_type = '{$survey_type}'";
    }
    $query .= " GROUP BY submission_data.sub_ans_id";
    $result = $db->query($query);
    return $result;
}

/**
 * For Line Chart Get data
 *
 * @author     Original Author Biztech Co.
 * @param      string -$type, $survey_id
 * @return     array
 */
function getLineChart($type, $survey_id, $survey_type) {
    global $db;
    if ($survey_id) {
        $query = "SELECT
                        bc_survey_submission.status,
                        bc_survey.name,
                        bc_survey.start_date,
                        bc_survey.id,
                        bc_survey_submission.email_opened ,
                        bc_survey_submission.id AS sub_id,
                        bc_survey_submission.date_modified as receive_date,
                        bc_survey_submission.date_entered as send_date
                  FROM bc_survey_submission
                        JOIN bc_survey_submission_bc_survey_c
                          ON bc_survey_submission.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_submission_idb
                        JOIN bc_survey
                          ON bc_survey.id = bc_survey_submission_bc_survey_c.bc_survey_submission_bc_surveybc_survey_ida
                            AND bc_survey.deleted = 0
                  WHERE bc_survey_submission.deleted = 0
                          AND bc_survey.id = '{$survey_id}' ";
        if (!empty($survey_type)) {
            if ($survey_type == 'Combined') {
                $query .= "";
            } else {
                $query .= " AND bc_survey_submission.submission_type = '{$survey_type}'";
            }
        }
        $status_result = $db->query($query);

        $email_not_opened = 0;
        $pending = 0;
        $submitted = 0;
        $result[] = array('Date', 'Submitted', 'Viewed');
        $date_check = '';
        $count = 0;
        $flag = 0;
        $inserted_dates = array();
        while ($status_row = $db->fetchByAssoc($status_result)) {

          if ($status_row['status'] != 'Submitted') {
                if (!empty($status_row['start_date'])) {
                $status_row['receive_date'] = $status_row['start_date'];
                }else{
                    $status_row['receive_date'] = $status_row['send_date'];
            }
            }
            $recDate = date('Y-m-d h:i:s', strtotime($status_row['receive_date']));
            $date = TimeDate::getInstance()->to_display_date($recDate);
            if ($date != $date_check && $date_check != '') {
                if (!array_key_exists($date, $inserted_dates)) {
                    $pending = 0; // reset pending counter
                    $submitted = 0; // reset submitted counter
                }
            }

            if ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 0) {
                $email_not_opened++;
            } elseif ($status_row['status'] == 'Pending' && $status_row['email_opened'] == 1) {
                $pending++;
            } elseif ($status_row['status'] == 'Submitted') {
                $submitted++;
            }
            if ($date_check == '') { // first time only
                $date_check = $date;
                $count++; // update the counter
            }
            //get chart data
            if ($count <= 0) { // for date enter first time
                if ($date_check != $date) {
                    $result[] = array($date, (int) number_format($submitted), (int) number_format($pending));
                    $inserted_dates[$date] = $date;
                    $count++; // update the counter
                }
            } else { // new record
                if (array_key_exists($date, $inserted_dates) && ($submitted != 0 || $pending != 0)) { // if date already enter for chart display then update values
                    foreach ($result as $key => $value) {
                        if ($value[0] == $date) { // update existing values
                            $value[0] = $date;
                            $value[1] = (int) number_format($submitted);
                            $value[2] = (int) number_format($pending);
                            $result[$key] = $value;
                            $flag = 0;
                        }
                    }
                } else { // for new date update flag and insert new entry for chart data
                    $flag = 1;
                }

                if ($flag == 1) {
                    if (!array_key_exists($date, $inserted_dates)) { // check if date already not exist then insert date
                        $result[] = array($date, (int) number_format($submitted), (int) number_format($pending));
                        $inserted_dates[$date] = $date;
                        $flag = 0;
                    }
                }
            }
            $date_check = $date;  // set current date to compare it
        }
        return $result;
    }
}

/**
 * Function : getHealthStatus
 *    Get Health Status of CRM that all required configuration like license,schedule,SMTP setting,PHP version,site url,etc  are proper or not
 *
 * @return array -  'license_status' - license status
 *                  'scheduler_status' - scheduler status
 *                  'siteurl_status' - siteurl status
 *                  'smtp_status' - smtp status
 *                  'php_status' - php status
 *                  'file_permission_status' - file permission status
 *                  'curl_status' - curl status
 */
function getHealthStatus() {
    //Get Health Status of CRM
    global $sugar_config;
    $admin = new Administration();
    $admin->retrieveSettings('SurveyPlugin');

    //*********************************License Status
    $license_status = $admin->settings['SurveyPlugin_LastValidationMsg'];
    if (empty($license_status)) {
        $license_status = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green;">License is correctly configured for Survey Rocket Plugin</b>';
    } else {
        $license_status = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:red;">' . $license_status . '</b>';
    }


    //**********************************Get Scheduler Status of CRM to send scheduled survey
    $scheduler = BeanFactory::getBean('Schedulers');
    $scheduler->retrieve_by_string_fields(array('job' => 'function::sendScheduledSurveys'));
    if ($scheduler->id) {
        $scheduler_found = true;
        $scheduler_id = $scheduler->id;
        $scheduler_status = $scheduler->status;
        $last_ran = $scheduler->last_run;

        global $mod_strings, $current_language;
        $temp_mod_strings = $mod_strings;
        $mod_strings = return_module_language($current_language, 'Schedulers');

        $scheduler->get_list_view_data(); //sets some vars we need
        $interval = $scheduler->intervalHumanReadable;

        $mod_strings = $temp_mod_strings;

        //format last_ran
        if (!empty($last_ran)) {
            global $current_user, $timedate;
            if ($scheduler_status == 'Active') {
                $schedul_status['status'] = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green;">' . $scheduler_status . '</b>';
            } else {
                $schedul_status['status'] = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:red;">' . $scheduler_status . '</b>';
            }
            $schedul_status['desc'] = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""><b style="color:green;"> Last successful run of scheduler job ' . $scheduler->name . ' is ' . $timedate->to_display_date_time($last_ran, true, true, $current_user) . '</b>';
        } else {
            if ($scheduler_status == 'Active') {
                $schedul_status['status'] = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green;">' . $scheduler_status . '</b>';
            } else {
                $schedul_status['status'] = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:red;">' . $scheduler_status . '</b>';
            }
            $schedul_status['desc'] = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""><b style="color:green;"> Scheduler job ' . $scheduler->name . ' exists but there is no any successful run found.</b>';
        }
    } else {
        $schedul_status['desc'] = '<b style="color:red;">The Survey Rocket scheduler job is missing.</b>';
        $schedul_status['status'] = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> -';
    }

    //*******************************************************site URL status
    $site = $sugar_config['site_url'];
    $current = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    $current_url = explode('?', $current);
    $arr_url = explode('/', $current_url[0]);
    array_pop($arr_url);
    $current_ur = join('/', $arr_url);
    if (!strchr($current, $site)) {
        $site_url_status = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <span style="color:red;">' . 'Current URL is <b style="color:black">' . $current_ur . '</b> and Site URL is <b style="color:black">' . $site . '</b> which is <b style="color:red">not valid</b></span>';
    } else {
        $site_url_status = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green;">' . 'Current URL is <b style="color:black">' . $current_ur . '</b> and Site URL is <b style="color:black">' . $site . '</b> which is <b style="color:green">valid<b></b>';
    }

    //*******************************************************current PHP Version
    $php_version = phpversion();
    $re_php_version = '/(5\.[0-9]\.[0-9])/';
    //$php_version = '7.0.1';
    if (preg_match($re_php_version, $php_version)) {
        $php_ver = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green;">Current PHP version is ' . $php_version . '</b>';
    } else {
        $php_ver = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:red;">Current PHP version is ' . $php_version . ' which is not compatible for the plugin.</b>';
    }
    // CURL status
    if (!function_exists('curl_version')) {
        $curl_status = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> ' . '<b style="color:red">Please enable PHP CURL extension.</b>';
    } else {
        $curl_status = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> ' . '<b style="color:green">PHP CURL extension is enabled.</b>';
    }

    //********************************************************SMTP Status

    $admin->retrieveSettings();
    $smtp_status = $admin->settings['SurveyPlugin_HealthCheck-SMTP'];
    if ($smtp_status == 'success') {
        $smtp_status = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> ' . '<b style="color:green">SMTP configured correctly.</b>';
    } else {
        $smtp_status = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> ' . '<b style="color:red">' . $smtp_status . '</b>';
    }

    //********************************************************file permission status
    if (is_writable('custom/') && is_writable('modules/bc_submission_data') && is_writable('modules/bc_survey') && is_writable('modules/bc_survey_answers') && is_writable('modules/bc_survey_pages') && is_writable('modules/bc_survey_questions') && is_writable('modules/bc_survey_submission') && is_writable('modules/bc_survey_template') && is_writable('preview_survey.php') && is_writable('survey_submission.php') && is_writable('unsubscribe.php')) {
        $file_per = '<img src="themes/default/images/green_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:green"> File Permission is valid.</b>';
    } else {
        $file_per = '<img src="themes/default/images/red_camp.gif" width="16" height="16" align="baseline" border="0" alt=""> <b style="color:red"> File Permission is not valid.</b>';
    }

    //result of all health checkup
    $result = array();
    $result['license_status'] = $license_status;
    $result['scheduler_status'] = $schedul_status;
    $result['siteurl_status'] = $site_url_status;
    $result['smtp_status'] = $smtp_status;
    $result['php_status'] = $php_ver;
    $result['file_permission_status'] = $file_per;
    $result['curl_status'] = $curl_status;

    return $result;
}

/**
 * get related module fields
 *
 * @param $app_list_strings - array
 * @param $unset_array - array
 * @param $beanList - array
 * @param $invalid_modules - array
 * @param $sort_fields - array
 * @param $fields - array
 * @param $module - string
 *
 * @author   Original Author Biztech Consultancy
 */
function getRelationshipsModule($module, $view = 'EditView', $value = '') {
    global $beanList, $app_list_strings;

    $fields = array($module => $app_list_strings['moduleList'][$module]);
    $sort_fields = array();

    if (!empty($module) && isset($beanList[$module])) {

        $mod = new $beanList[$module]();
        foreach ($mod->get_linked_fields() as $name => $arr) {
            if (!empty($arr['module'])) {
                $rel_module = $arr['module'];
            }
            $relModuleName = isset($app_list_strings['moduleList'][$rel_module]) ? $app_list_strings['moduleList'][$rel_module] : $rel_module;
            if (isset($arr['vname']) && $arr['vname'] != '') {
                $sort_fields[$name] = translate($arr['vname'], $mod->module_dir);
            } else {
                $sort_fields[$name] = $name;
            }
            if ($arr['type'] == 'relate' && isset($arr['id_name']) && $arr['id_name'] != '') {
                if (isset($fields[$arr['id_name']]))
                    unset($fields[$arr['id_name']]);
            }
        } //End loop.
        array_multisort($sort_fields, SORT_ASC, $sort_fields);
        $fields = array_merge((array) $fields, (array) $sort_fields);
    }
    if ($view == 'EditView') {
        return get_select_options_with_id($fields, $value);
    } else {
        return $fields;
    }
}

/**
 * get related module fields
 *
 * @param $fields_name - string
 * @param $app_strings - array
 * @param $unset_array - array
 * @param $beanList - array
 * @param $module - string
 *
 * @author   Original Author Biztech Consultancy
 */
function getFieldsModule($module) {
    global $app_strings, $beanList;
    $fields_name = array('' => $app_strings['LBL_NONE']);
    $unset_array = array();

    if (!empty($module) && isset($beanList[$module])) {
        $mod = new $beanList[$module]();
        foreach ($mod->field_defs as $name => $arr) {
            if ($arr['type'] != 'link' && ((!isset($arr['source']) || $arr['source'] != 'non-db' || $arr['group'] == 'email1' ) || ($arr['type'] == 'relate' && isset($arr['id_name']))) && (empty($valid) || in_array($arr['type'], $valid)) && $name != 'currency_name' && $name != 'currency_symbol') {
                $fields_name[$name] = !empty($arr['vname']) ? rtrim(translate($arr['vname'], $mod->module_dir), ':') : $name;

                if ($arr['type'] == 'relate' && !empty($arr['id_name'])) {
                    $unset_array[] = $arr['id_name'];
                }
            }
        }

        if (!empty($fields_name)) {
            foreach ($unset_array as $name) {
                unset($fields_name[$name]);
            }
        }
    }
    return $fields_name;
}

/**
 * get related module list
 *
 * @param $module - string
 * @param $module_list - array
 * @param $beanList - array
 *
 * @author   Original Author Biztech Consultancy
 */
function getModuleRelated($module, $rel_field) {
    global $beanList;
    if ($module == $rel_field) {
        return $module;
    }
    $module_list = new $beanList[$module]();

    if (isset($arr['module']) && $arr['module'] != '') {
        return $arr['module'];
    } else if ($module_list->load_relationship($rel_field)) {
        return $module_list->$rel_field->getRelatedModuleName();
    }
    return $module;
}

/**
 * Get Valid value field For Survey Automizer Condition
 *
 * @param $file - string
 * @param $mod_strings - array
 * @param $fieldname - string
 * @param $aow_field - string
 * @param $beanList - array
 * @param $beanFiles - array
 * @param $focus - array
 * @param $vardef - array
 *
 * @author   Original Author Biztech Consultancy
 */
function getModuleField($module, $fieldname, $aow_field, $view = 'EditView', $value = '', $alt_type = '', $currency_id = '', $params = array()) {
    global $current_language, $app_strings, $app_list_strings, $current_user, $beanFiles, $beanList;

    // use the mod_strings for this module
    $mod_strings = return_module_language($current_language, $module);

    // set the filename for this control
    $file = create_cache_directory('modules/AOW_WorkFlow/') . $module . $view . $alt_type . $fieldname . '.tpl';

    $displayParams = array();

    if (!is_file($file) || inDeveloperMode() || !empty($_SESSION['developerMode'])) {

        if (!isset($vardef)) {
            require_once($beanFiles[$beanList[$module]]);
            $focus = new $beanList[$module];
            $vardef = $focus->getFieldDefinition($fieldname);
        }

        // Bug: check for value SecurityGroups value missing
        if (stristr($fieldname, 'securitygroups') != false && empty($vardef)) {
            require_once($beanFiles[$beanList['SecurityGroups']]);
            $module = 'SecurityGroups';
            $focus = new $beanList[$module];
            $vardef = $focus->getFieldDefinition($fieldname);
        }


        // if this is the id relation field, then don't have a pop-up selector.
        if ($vardef['type'] == 'relate' && $vardef['id_name'] == $vardef['name']) {
            $vardef['type'] = 'varchar';
        }

        if (isset($vardef['precision']))
            unset($vardef['precision']);

        //temp work around
        if ($vardef['type'] == 'datetimecombo') {
            $vardef['type'] = 'datetime';
        }

        // trim down textbox display
        if ($vardef['type'] == 'text') {
            $vardef['rows'] = 2;
            $vardef['cols'] = 32;
        }

        // create the dropdowns for the parent type fields
        if ($vardef['type'] == 'parent_type') {
            $vardef['type'] = 'enum';
        }

        if ($vardef['type'] == 'link') {
            $vardef['type'] = 'relate';
            $vardef['rname'] = 'name';
            $vardef['id_name'] = $vardef['name'] . '_id';
            if ((!isset($vardef['module']) || $vardef['module'] == '') && $focus->load_relationship($vardef['name'])) {
                $relName = $vardef['name'];
                $vardef['module'] = $focus->$relName->getRelatedModuleName();
            }
        }

        //check for $alt_type
        if ($alt_type != '') {
            $vardef['type'] = $alt_type;
        }

        // remove the special text entry field function 'getEmailAddressWidget'
        if (isset($vardef['function']) && ( $vardef['function'] == 'getEmailAddressWidget' || $vardef['function']['name'] == 'getEmailAddressWidget' ))
            unset($vardef['function']);

        if (isset($vardef['name']) && ($vardef['name'] == 'date_entered' || $vardef['name'] == 'date_modified')) {
            $vardef['name'] = 'aow_temp_date';
        }

        // load SugarFieldHandler to render the field tpl file
        static $sfh;

        if (!isset($sfh)) {
            require_once('include/SugarFields/SugarFieldHandler.php');
            $sfh = new SugarFieldHandler();
        }

        $contents = $sfh->displaySmarty('fields', $vardef, $view, $displayParams);

        // Remove all the copyright comments
        $contents = preg_replace('/\{\*[^\}]*?\*\}/', '', $contents);

        if ($view == 'EditView' && ($vardef['type'] == 'relate' || $vardef['type'] == 'parent')) {
            $contents = str_replace('"' . $vardef['id_name'] . '"', '{/literal}"{$fields.' . $vardef['name'] . '.id_name}"{literal}', $contents);
            $contents = str_replace('"' . $vardef['name'] . '"', '{/literal}"{$fields.' . $vardef['name'] . '.name}"{literal}', $contents);
        }

        // hack to disable one of the js calls in this control
        if (isset($vardef['function']) && ( $vardef['function'] == 'getCurrencyDropDown' || $vardef['function']['name'] == 'getCurrencyDropDown' ))
            $contents .= "{literal}<script>function CurrencyConvertAll() { return; }</script>{/literal}";

        // Save it to the cache file
        if ($fh = @sugar_fopen($file, 'w')) {
            fputs($fh, $contents);
            fclose($fh);
        }
    }

    // Now render the template we received
    $ss = new Sugar_Smarty();

    // Create Smarty variables for the Calendar picker widget
    global $timedate;
    $time_format = $timedate->get_user_time_format();
    $date_format = $timedate->get_cal_date_format();
    $ss->assign('USER_DATEFORMAT', $timedate->get_user_date_format());
    $ss->assign('TIME_FORMAT', $time_format);
    $time_separator = ":";
    $match = array();
    if (preg_match('/\d+([^\d])\d+([^\d]*)/s', $time_format, $match)) {
        $time_separator = $match[1];
    }
    $t23 = strpos($time_format, '23') !== false ? '%H' : '%I';
    if (!isset($match[2]) || $match[2] == '') {
        $ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M");
    } else {
        $pm = $match[2] == "pm" ? "%P" : "%p";
        $ss->assign('CALENDAR_FORMAT', $date_format . ' ' . $t23 . $time_separator . "%M" . $pm);
    }

    $ss->assign('CALENDAR_FDOW', $current_user->get_first_day_of_week());

    // populate the fieldlist from the vardefs
    $fieldlist = array();
    if (!isset($focus) || !($focus instanceof SugarBean))
        require_once($beanFiles[$beanList[$module]]);
    $focus = new $beanList[$module];
    // create the dropdowns for the parent type fields
    $vardefFields = $focus->getFieldDefinitions();
    if (isset($vardefFields[$fieldname]['type']) && $vardefFields[$fieldname]['type'] == 'parent_type') {
        $focus->field_defs[$fieldname]['options'] = $focus->field_defs[$vardefFields[$fieldname]['group']]['options'];
    }
    foreach ($vardefFields as $name => $properties) {
        $fieldlist[$name] = $properties;
        // fill in enums
        if (isset($fieldlist[$name]['options']) && is_string($fieldlist[$name]['options']) && isset($app_list_strings[$fieldlist[$name]['options']]))
            $fieldlist[$name]['options'] = $app_list_strings[$fieldlist[$name]['options']];
        // Bug 32626: fall back on checking the mod_strings if not in the app_list_strings
        elseif (isset($fieldlist[$name]['options']) && is_string($fieldlist[$name]['options']) && isset($mod_strings[$fieldlist[$name]['options']]))
            $fieldlist[$name]['options'] = $mod_strings[$fieldlist[$name]['options']];
        // Bug 22730: make sure all enums have the ability to select blank as the default value.
        if (!isset($fieldlist[$name]['options']['']))
            $fieldlist[$name]['options'][''] = '';
    }

    // fill in function return values
    if (!in_array($fieldname, array('email1', 'email2'))) {
        if (!empty($fieldlist[$fieldname]['function']['returns']) && $fieldlist[$fieldname]['function']['returns'] == 'html') {
            $function = $fieldlist[$fieldname]['function']['name'];
            // include various functions required in the various vardefs
            if (isset($fieldlist[$fieldname]['function']['include']) && is_file($fieldlist[$fieldname]['function']['include']))
                require_once($fieldlist[$fieldname]['function']['include']);
            $_REQUEST[$fieldname] = $value;
            $value = $function($focus, $fieldname, $value, $view);

            $value = str_ireplace($fieldname, $aow_field, $value);
        }
    }

    if (isset($fieldlist[$fieldname]['type']) && $fieldlist[$fieldname]['type'] == 'link') {
        $fieldlist[$fieldname]['id_name'] = $fieldlist[$fieldname]['name'] . '_id';

        if ((!isset($fieldlist[$fieldname]['module']) || $fieldlist[$fieldname]['module'] == '') && $focus->load_relationship($fieldlist[$fieldname]['name'])) {
            $relName = $fieldlist[$fieldname]['name'];
            $fieldlist[$fieldname]['module'] = $focus->$relName->getRelatedModuleName();
        }
    }

    if (isset($fieldlist[$fieldname]['name']) && ($fieldlist[$fieldname]['name'] == 'date_entered' || $fieldlist[$fieldname]['name'] == 'date_modified')) {
        $fieldlist[$fieldname]['name'] = 'aow_temp_date';
        $fieldlist['aow_temp_date'] = $fieldlist[$fieldname];
        $fieldname = 'aow_temp_date';
    }

    $quicksearch_js = '';
    if (isset($fieldlist[$fieldname]['id_name']) && $fieldlist[$fieldname]['id_name'] != '' && $fieldlist[$fieldname]['id_name'] != $fieldlist[$fieldname]['name']) {
        $rel_value = $value;

        require_once("include/TemplateHandler/TemplateHandler.php");
        $template_handler = new TemplateHandler();
        $quicksearch_js = $template_handler->createQuickSearchCode($fieldlist, $fieldlist, $view);
        $quicksearch_js = str_replace($fieldname, $aow_field . '_display', $quicksearch_js);
        $quicksearch_js = str_replace($fieldlist[$fieldname]['id_name'], $aow_field, $quicksearch_js);

        echo $quicksearch_js;

        if (isset($fieldlist[$fieldname]['module']) && $fieldlist[$fieldname]['module'] == 'Users') {
            $rel_value = get_assigned_user_name($value);
        } else if (isset($fieldlist[$fieldname]['module'])) {
            require_once($beanFiles[$beanList[$fieldlist[$fieldname]['module']]]);
            $rel_focus = new $beanList[$fieldlist[$fieldname]['module']];
            $rel_focus->retrieve($value);
            if (isset($fieldlist[$fieldname]['rname']) && $fieldlist[$fieldname]['rname'] != '') {
                $relDisplayField = $fieldlist[$fieldname]['rname'];
            } else {
                $relDisplayField = 'name';
            }
            $rel_value = $rel_focus->$relDisplayField;
        }

        $fieldlist[$fieldlist[$fieldname]['id_name']]['value'] = $value;
        $fieldlist[$fieldname]['value'] = $rel_value;
        $fieldlist[$fieldname]['id_name'] = $aow_field;
        $fieldlist[$fieldlist[$fieldname]['id_name']]['name'] = $aow_field;
        $fieldlist[$fieldname]['name'] = $aow_field . '_display';
    } else if (isset($fieldlist[$fieldname]['type']) && $view == 'DetailView' && ($fieldlist[$fieldname]['type'] == 'datetimecombo' || $fieldlist[$fieldname]['type'] == 'datetime' || $fieldlist[$fieldname]['type'] == 'date')) {
        $value = $focus->convertField($value, $fieldlist[$fieldname]);
        if (!empty($params['date_format']) && isset($params['date_format'])) {
            $convert_format = "Y-m-d H:i:s";
            if ($fieldlist[$fieldname]['type'] == 'date')
                $convert_format = "Y-m-d";
            $fieldlist[$fieldname]['value'] = $timedate->to_display($value, $convert_format, $params['date_format']);
        }else {
            $fieldlist[$fieldname]['value'] = $timedate->to_display_date_time($value, true, true);
        }
        $fieldlist[$fieldname]['name'] = $aow_field;
    } else if (isset($fieldlist[$fieldname]['type']) && ($fieldlist[$fieldname]['type'] == 'datetimecombo' || $fieldlist[$fieldname]['type'] == 'datetime' || $fieldlist[$fieldname]['type'] == 'date')) {
        $value = $focus->convertField($value, $fieldlist[$fieldname]);
        $fieldlist[$fieldname]['value'] = $timedate->to_display_date($value);
        //$fieldlist[$fieldname]['value'] = $timedate->to_display_date_time($value, true, true);
        //$fieldlist[$fieldname]['value'] = $value;
        $fieldlist[$fieldname]['name'] = $aow_field;
    } else {
        $fieldlist[$fieldname]['value'] = $value;
        $fieldlist[$fieldname]['name'] = $aow_field;
    }

    if (isset($fieldlist[$fieldname]['type']) && $fieldlist[$fieldname]['type'] == 'currency' && $view != 'EditView') {
        static $sfh;

        if (!isset($sfh)) {
            require_once('include/SugarFields/SugarFieldHandler.php');
            $sfh = new SugarFieldHandler();
        }

        if ($currency_id != '' && !stripos($fieldname, '_USD')) {
            $userCurrencyId = $current_user->getPreference('currency');
            if ($currency_id != $userCurrencyId) {
                $currency = new Currency();
                $currency->retrieve($currency_id);
                $value = $currency->convertToDollar($value);
                $currency->retrieve($userCurrencyId);
                $value = $currency->convertFromDollar($value);
            }
        }

        $parentfieldlist[strtoupper($fieldname)] = $value;

        return($sfh->displaySmarty($parentfieldlist, $fieldlist[$fieldname], 'ListView', $displayParams));
    }

    $ss->assign("QS_JS", $quicksearch_js);
    $ss->assign("fields", $fieldlist);
    $ss->assign("form_name", $view);
    $ss->assign("bean", $focus);

    // add in any additional strings
    $ss->assign("MOD", $mod_strings);
    $ss->assign("APP", $app_strings);

    return $ss->fetch($file);
}

/**
 * Get Valid value field For Survey Automizer Condition
 *
 * @param $focus - array
 * @param $vardef - array
 * @param $valid_type - array
 * @param $beanFiles - array
 * @param $beanList - array
 *
 * @author   Original Author Biztech Consultancy
 */
function getValidFieldsTypes($module, $field) {
    global $beanFiles, $beanList;

    require_once($beanFiles[$beanList[$module]]);
    $focus = new $beanList[$module];
    $vardef = $focus->getFieldDefinition($field);

    switch ($vardef['type']) {
        case 'double':
        case 'decimal':
        case 'float':
        case 'currency':
            $valid_type = array('double', 'decimal', 'float', 'currency');
            break;
        case 'uint':
        case 'ulong':
        case 'long':
        case 'short':
        case 'tinyint':
        case 'int':
            $valid_type = array('uint', 'ulong', 'long', 'short', 'tinyint', 'int');
            break;
        case 'date':
        case 'datetime':
        case 'datetimecombo':
            $valid_type = array('date', 'datetime', 'datetimecombo');
            break;
        case 'id':
        case 'relate':
        case 'link':
            $valid_type = array('relate', 'id');
            break;
        default:
            $valid_type = array();
            break;
    }
    return $valid_type;
}

/**
 * get answer submission response for answered and skipped persons
 *
 * @author     Biztech Consultancy
 * @param      string - $surveyID
 * @return array
 */
function getAnswerSubmissionAnsweredAndSkipped($surveyID, $survey_type, $total_submitted_response) {
    global $db;
    $selectEachAnswerSubCount = "SELECT
                                bc_survey_submit_answer_calculation.survey_receiver_id AS recId,
                                bc_survey_submit_answer_calculation.question_id AS queId,
                                bc_survey_submit_answer_calculation.submit_answer_id AS ansSubmitCount,
                                bc_survey_submit_answer_calculation.answer_type AS ans_type
                            FROM
                                bc_survey_submit_answer_calculation
                            WHERE
                                bc_survey_submit_answer_calculation.sent_survey_id = '{$surveyID}'
    ";
    $runQuery = $db->query($selectEachAnswerSubCount);
    $submit_answer_Array = array();
    $is_matrix = false;
    $matri_all_count_array = array();
    $rec_array = array();
    while ($resultCountData = $db->fetchByAssoc($runQuery)) {
        $xRecId = str_split($resultCountData['recId'], 8);
        if (($survey_type == 'Open Ended' && $xRecId[0] == 'Web Link') || ($survey_type == 'Email' && $xRecId[0] != 'Web Link') || ($survey_type == 'Combined')) {
            // Answer submission
            $explodeData = explode(',', $resultCountData['ansSubmitCount']);

            if (is_array($explodeData)) {
                $submit_answer_Array[$resultCountData['recId']][$resultCountData['queId']] = array();
                foreach ($explodeData as $ansID) {
                    $submit_answer_Array[$resultCountData['recId']][$resultCountData['queId']][] = $ansID;
                }
            } else {
                $submit_answer_Array[$resultCountData['recId']][$resultCountData['queId']][] = array();
            }
            $rec_array[$resultCountData['recId']] = $submit_answer_Array[$resultCountData['recId']];
        }
    }

    $final_que_count = array();
    //  $total_submitted_response = 0; // total number of submission for this survey
    foreach ($rec_array as $key => $submission) {
        //   $total_submitted_response++; // increment response counter
        // each question submission
        foreach ($submission as $que => $val) {
            $countEachAns = 0; // reset ans counter for each question
            if (!empty($val[0])) {
                $countEachAns++; // increment ans counter
                $final_que_count[$que] = $final_que_count[$que] + $countEachAns;
            } else {
                $final_que_count[$que] = $final_que_count[$que] + $countEachAns;
            }
        }
    }
    //Final Answered and skipped Persons for each question
    $result_array = array();
    foreach ($final_que_count as $que_id => $submission_count) {
        $result_array[$que_id] = array('answered' => $submission_count, 'skipped' => ($total_submitted_response - $submission_count));
    }
    $GLOBALS['log']->fatal("This is the count each ans : " . print_r($result_array, 1));
    return $result_array;
}

/**
 * get question which answer is submitted
 *
 * @author     Biztech Consultancy
 * @param      string - $surveyId
 * @param      string - $survey_recieverID
 * @return array
 */
function getSubmittedAnswerByReciever($surveyId, $survey_recieverID) {
    global $db;
    $ques_subAnsArray = array();
    $ques_subAnsdataArray = array();
    $getSubAnsQuery = "SELECT question_id, submit_answer_id FROM bc_survey_submit_answer_calculation
                       WHERE survey_receiver_id = '{$survey_recieverID}'
                       AND sent_survey_id = '{$surveyId}'
                       ";
    $runQuery = $db->query($getSubAnsQuery);
    while ($resultData = $db->fetchByAssoc($runQuery)) {
        if (strpos($resultData['submit_answer_id'], ',') !== false) {
            $ques_subAnsArray[] = explode(',', $resultData['submit_answer_id']);
        } else {
            $ques_subAnsArray[] = $resultData['submit_answer_id'];
        }
    }
    foreach ($ques_subAnsArray as $subAnswers) {
        if (is_array($subAnswers) && $subAnswers != '') {
            foreach ($subAnswers as $subAns) {
                $ques_subAnsdataArray[] = $subAns;
            }
        } else {
            $ques_subAnsdataArray[] = $subAnswers;
        }
    }
    return $ques_subAnsdataArray;
}

function get_sync_module_fields() {
    // Sync Module List
    $sync_module_array = array('Accounts', 'Contacts', 'Leads', 'Prospects');
    $allowed_field_type = array('name', 'text', 'int', 'varchar', 'enum', 'multienum', 'radioenum', 'phone', 'email');
    $result_sync_fields = array();

    // Getfields from vardef
    foreach ($sync_module_array as $module) {
        $lang = return_module_language('en_us', $module);
        // Retrieve Module Object
        $dom_Obj = BeanFactory::getBean($module);
        foreach ($dom_Obj->field_defs as $key => $field) {
            if (in_array($field['type'], $allowed_field_type)) {
                $labelValue = trim($lang[$field['vname']], ':');
                $result_sync_fields[$module][$field['name']] = $labelValue;
            }
        }
    }
    //     $GLOBALS['log']->fatal("This is the result sync fields-------------- : " . print_r($result_sync_fields, 1));
    return $result_sync_fields;
}
