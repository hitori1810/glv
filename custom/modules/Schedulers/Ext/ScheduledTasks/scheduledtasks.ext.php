<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$job_strings[] = 'sendScheduledSurveys';

function sendScheduledSurveys() {
    require_once('custom/include/modules/Administration/plugin.php');
    $checkSurveySubscription = validateSurveySubscription();
    if (!$checkSurveySubscription['success']) {
        return true;
    } else {
        $GLOBALS['log']->fatal("SendSchedultSurvey execution start : " . print_r('', 1));
        global $sugar_config, $db;
        $date = gmdate('Y-m-d H:i:s');
        require_once 'custom/include/utilsfunction.php';
        $recipients = array();
        $top = '';
        $limit = '';
        if($db->dbType == "mssql"){
            $top = "TOP 150";
        }else{
            $limit = "LIMIT 150";
        }
        $scDataQry = "SELECT {$top}
                      submission.id as submission_id,
                      submission.module_id,
                      submission.target_module_name,
                      submission.survey_send,
                      submission.recipient_email_field,
                      bc_survey.id AS survey_id
                    FROM bc_survey_submission AS submission
                      INNER JOIN bc_survey_submission_bc_survey_c AS relation
                        ON relation.bc_survey_submission_bc_surveybc_survey_submission_idb = submission.id
                          AND relation.deleted = 0
                      INNER JOIN bc_survey
                        ON bc_survey.id = relation.bc_survey_submission_bc_surveybc_survey_ida
                          AND bc_survey.deleted = 0
                    WHERE submission.schedule_on <= '{$date}'
                        AND submission.survey_send = 0 {$limit}";
           //$GLOBALS['log']->fatal("Query" . print_r($scDataQry, 1));
        $scDataQryRes = $db->query($scDataQry);
        $cnt = 0;
        while ($scDataQryResult = $db->fetchByAssoc($scDataQryRes)) {
            $recipients[$scDataQryResult['target_module_name']][$cnt]['module_id'] = $scDataQryResult['module_id'];
            $recipients[$scDataQryResult['target_module_name']][$cnt]['survey_id'] = $scDataQryResult['survey_id'];
            $recipients[$scDataQryResult['target_module_name']][$cnt]['submission_id'] = $scDataQryResult['submission_id'];
            $recipients[$scDataQryResult['target_module_name']][$cnt]['recipient_email_field'] = $scDataQryResult['recipient_email_field'];
            $cnt++;
        }
        $GLOBALS['log']->fatal("SendSchedultSurvey get recipient : " . print_r($recipients, 1));
        foreach ($recipients as $rec_module => $rec_module_ids) {
            foreach ($rec_module_ids as $rec_module_detail) {
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
                $focus->retrieve($rec_module_detail['module_id']);
                $survey = new bc_survey($rec_module_detail['survey_id']);
                $survey->retrieve($rec_module_detail['survey_id']);
                if ($focus->email_opt_out == 0) {
                    $emailAddQryRes = getOptOutEmailCustomers($focus->id, $rec_module);
                    $opt_out_url = $sugar_config['site_url'] . '/unsubscribe.php?target=' . $emailAddQryRes['email_add_id'];
                    $getSurveyEmailTemplateID = getEmailTemplateBySurveyID($survey->id);
                    $emailtemplateObj = new EmailTemplate();
                    $emailtemplateObj->retrieve($getSurveyEmailTemplateID);
                    $macro_nv = array();
                    $emailtemplateObj->parsed_entities = null;
                    $emailSubjectName = (!empty($emailtemplateObj->subject)) ? $emailtemplateObj->subject : $survey->name;
                    if ($rec_module == 'Leads' || $rec_module == 'Prospects' || $rec_module == 'Contacts') {
                        $email_module = 'Contacts';
                        $recip_prefix = '$contact';
                    } else {
                        $email_module = $module_name;
                        $recip_prefix = '$account';
                    }

                    //replace prefix for recipient name if exists email template for other module
                    if ($recip_prefix == '$contact') {
                        $search_prefix = '$account';
                    } else if ($recip_prefix == '$account') {
                        $search_prefix = '$contact';
                    }
                    if ($rec_module == 'Leads' || $rec_module == 'Prospects') {
                        $email_module = 'Contacts';
                    } else {
                        $email_module = $rec_module;
                    }
                    $emailtemplateObj->body_html = str_replace($search_prefix, $recip_prefix, $emailtemplateObj->body_html);
                    $template_data = $emailtemplateObj->parse_email_template(array(
                        "subject" => $emailSubjectName,
                        "body_html" => $emailtemplateObj->body_html,
                        "body" => $emailtemplateObj->body), $email_module, $focus, $macro_nv);

                    // create new url for survey with encryption*****************************************

                    $module_id = $focus->id; // module record id
                    $GLOBALS['log']->fatal("Module Id " .$module_id);
                    //$survey_id = split('=', substr($template_data['body_html'], strpos($template_data['body_html'], 'survey_id='), 46)); // survey id
                    $GLOBALS['log']->fatal("survey Id " . $survey->id);
                    // survey URL current with survey_id

                    $survey_url = split('&quot;', substr($template_data['body_html'], strpos($template_data['body_html'], 'href=')));

                    // host name
                    $host = strtok($survey_url[1], '?');

                    // data to be encoded sufficient data
                    $pure_data = $survey->id . '&ctype=' . $rec_module . '&cid=' . $module_id;
                    $GLOBALS['log']->fatal("pure URL :-" .$pure_data);
                    $encoded_data = base64_encode($pure_data);
                    $host = str_replace($survey->id, '/survey_submission.php', $host); //Fix by Lap Nguyen
                    $new_url = $host . '?q=' . $encoded_data;
                    $GLOBALS['log']->fatal("new URL :-" .$new_url);
                    //replace into current mail body for encoded survey URL
                    $template_data['body_html'] = str_replace($survey_url[1], $new_url, $template_data['body_html']);

                    // **************************************************************************************

                    $emailBody = $template_data["body_html"];
                    $mailSubject = $template_data["subject"];

                    $emailSubject = $mailSubject;
                    $to_Email = $focus->email1;
                    //$image_src = "{$sugar_config['site_url']}/check_email_opened.jpg/{$rec_module_detail['submission_id']}";
                    $image_src = "{$sugar_config['site_url']}/index.php?entryPoint=checkEmailOpened&submission_id={$rec_module_detail['submission_id']}";
                    $image_url = "<img src='{$image_src}'>";
                    $emailBody .= $image_url;
                    $emailBody .= '<br/><span style="font-size:0.8em">To remove yourself from this email list  <a href="' . $opt_out_url . '" target="_blank">click here</a></span>';
                    $GLOBALS['log']->fatal("recipient Email: " . $to_Email);
                    $sendMail = CustomSendEmail($to_Email, $emailSubject, $emailBody, $rec_module, $rec_module_detail['module_id'],$rec_module_detail['recipient_email_field']);
                    /*
                     * Store survey data
                     */
                    $GLOBALS['log']->fatal("SendSchedultSurvey sent mail status : " . print_r($sendMail, 1));
                    if (trim($sendMail) == 'send') {
                        $survey_submission = new bc_survey_submission();
                        $survey_submission->retrieve($rec_module_detail['submission_id']);
                        $survey_submission->survey_send = 1;
                        $survey_submission->save();
                    }
                }
            }
        }
        $GLOBALS['log']->fatal("SendSchedultSurvey END : " . print_r('', 1));
        return true;
    }
}

?>