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
Class bc_surveyViewpending_response extends SugarView{
    function display(){
        echo "<script src='custom/include/js/survey_js/custom_code.js' type='text/javascript'></script>";
        global $db;
        $survey_id = $_REQUEST['survey_id'];
        $module_name = $_REQUEST['module_name'];
        $type = $_REQUEST['type'];

        $limit = 50;
        if(trim($type) == 'pending_res'){
            if(empty($_REQUEST['pending_res_record'])){
                $PendingResQry = "SELECT
                              submission.id,
                              submission.status,
                              submission.survey_send,
                              submission.date_entered,
                              submission.date_modified,
                              submission.module_id
                            FROM bc_survey_submission as submission
                              INNER JOIN bc_survey_submission_bc_survey_c as relation
                                ON relation.bc_survey_submission_bc_surveybc_survey_submission_idb = submission.id
                            WHERE submission.target_module_name = '{$module_name}'
                                AND relation.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                                AND submission.status = 'Pending' AND submission.survey_send = 1
                                AND submission.deleted = 0 order by submission.customer_name asc";
                $PendingRes = $db->query($PendingResQry);
                $pending_res_record = "";
                while ($PendingRows = $db->fetchByAssoc($PendingRes)) {
                    if (empty($pending_res_record)) {
                        $pending_res_record = $PendingRows['module_id'];
                    } else {
                        $pending_res_record .= "," . $PendingRows['module_id'];
                        ;
                    }
                }
                $pending_res_record = str_replace(',', "','", $pending_res_record);
            } else {
                $pending_res_record = str_replace(',', "','", $_REQUEST['pending_res_record']);
            }
            $checkQry = "SELECT
                              submission.id,
                              submission.status,
                              submission.survey_send,
                              submission.date_entered,
                              submission.date_modified,
                              submission.module_id
                            FROM bc_survey_submission as submission
                              INNER JOIN bc_survey_submission_bc_survey_c as relation
                                ON relation.bc_survey_submission_bc_surveybc_survey_submission_idb = submission.id
                               WHERE submission.target_module_name = '{$module_name}'
                                AND relation.bc_survey_submission_bc_surveybc_survey_ida = '{$survey_id}'
                                AND submission.status = 'Pending'
                                AND submission.survey_send = 1
                                AND submission.deleted = 0
                                AND submission.module_id IN ('{$pending_res_record}')
                                ORDER BY submission.customer_name asc";
            $countRes = $db->query($checkQry);
            $count_num_rows = 0;
            while ($answer_row = $db->fetchByAssoc($countRes)) {
                $count_num_rows++;
            }
            $result_count = $count_num_rows;
            $offset_val = (int) $_REQUEST['offset'];
            $offset = isset($offset_val) ? $offset_val : 0;
            if ($offset == $result_count) {
                $offset = 0;
            }
            if (isset($offset)) {
                $checkQry .= " LIMIT {$offset}, {$limit}";
            }

            $checkQryRes = $db->query($checkQry);
            $alreadySendCount = 0;
            $html = "";
            $returnDataSurvey = array();
            $customersPendingResponse = array();


            $html .= "<h3 style='text-align: center'>List of {$module_name} to whom same survey is already sent the but response not submitted</h3>";
            $html .="<table border='0' class='list view' style='margin: 20px 10%;width:80%'>
                 <thead><tr><td colspan='4'><input type='button' name='SendReminder' id='SendReminder' value='Send Reminder' onclick='sendSurveyReminder(\"{$survey_id}\",\"{$module_name}\");'></td></tr></thead>
             <thead>
               <tr>
                   <th style='text-align: left;width: 5%'><input type='checkbox' name='reminder_chkAll' class='reminder_chkAll' value='' onchange='selectDeselectReminderChk();'></th>
                   <th style='text-align: left;width: 5%'>No.</th>
                   <th style='text-align: left;width: 50%'>Customer Name</th>
                   <th style='text-align: left;width: 40%'>Survey Send Date</th>
               </tr></thead>";
            if ($result_count > 0) {
                while ($result = $db->fetchByAssoc($checkQryRes)) {
                    switch ($module_name) {
                        case "Accounts":
                            $module = 'Accounts';
                            break;
                        case "Contacts":
                            $module = 'Contacts';
                            break;
                        case "Users":
                            $module = 'Users';
                            break;
                        case "Leads":
                            $module = 'Leads';
                            break;
                        case "Prospects":
                            $module = 'Prospects';
                            break;
                        case "ProspectLists":
                            $module = 'ProspectLists';
                            break;
                    }
                    $bean = BeanFactory::getBean($module);
                    $bean->retrieve($result['module_id']);
                    $name = $bean->name;
                    $id = $bean->id;

                    $returnDataSurvey['ResponseNotSubmitted'][$alreadySendCount]['recordID'] = $id;
                    $returnDataSurvey['ResponseNotSubmitted'][$alreadySendCount]['recordName'] = $name;
                    $returnDataSurvey['ResponseNotSubmitted'][$alreadySendCount]['SurveySendDate'] = TimeDate::getInstance()->to_display_date_time($result['date_entered']);
                    $alreadySendCount++;
                }
                $customersPendingResponse = (isset($returnDataSurvey['ResponseNotSubmitted']) && !empty($returnDataSurvey['ResponseNotSubmitted'])) ? $returnDataSurvey['ResponseNotSubmitted'] : array();

                if (!empty($customersPendingResponse)) {
                    $count = 1;
                    foreach ($customersPendingResponse as $newKey => $respPendingCustDetails) {
                        $html .= "<tr class='oddListRowS1' style='height: 20'>
                         <td style='width: 5%'><input type='checkbox' name='reminder_records' class='reminder_chk' value='{$respPendingCustDetails["recordID"]}'></td>
                         <td style='width: 5%'>{$count}</td>
                         <td style='width: 50%'>{$respPendingCustDetails["recordName"]}</td>
                         <td style='width: 40%'>{$respPendingCustDetails["SurveySendDate"]}</td>
                         </tr>";
                        $count++;
                    }
                }


                $offset = $offset + $limit;


                if($offset <= $result_count){
                    $html .= "<tr>
                            <td colspan='4'><a href='index.php?module=bc_survey&action=DisplayPendingResView&survey_id={$survey_id}&module_name={$module_name}&offset={$offset}&type=pending_res'>Show More</a> </td>
                        </tr>";
                }

                $html .= "</table>";
            } else {
                $html .= "<tr><td colspan='4' style='text-align: center;'>No Records Found</td></tr>";
            }
        } elseif (trim($type) == 'opted_out') {
            $html = "";
            $optOutEmailDetails = array();
            $emailAddQry = "SELECT
                            email_addr_bean_rel.bean_id as moduleID,
                            email_addresses.email_address as email_add,
                            email_addresses.opt_out as email_optOut,
                            email_addr_bean_rel.email_address_id AS email_add_id,
                             email_addr_bean_rel.bean_module
                          FROM email_addr_bean_rel
                          INNER JOIN email_addresses on email_addresses.id = email_addr_bean_rel.email_address_id
                          WHERE email_addr_bean_rel.bean_module = '{$module_name}'
                              AND	email_addr_bean_rel.deleted = 0
                              AND email_addresses.opt_out  = 1
                              AND email_addr_bean_rel.primary_address = 1";
            $emailAddQryRes = $db->query($emailAddQry);
            $count_num_rows = 0;
            while ($anser_rows = $db->fetchByAssoc($emailAddQryRes)) {
                $count_num_rows++;
            }
            $result_count = $count_num_rows;




            $html .= "<h3 style='text-align: center'>List of All {$module_name} whose email addresses are opted out</h3>";
            $html .="<table border='0' class='list view' style='margin: 20px 10%;width:80%'>
             <thead>
               <tr>
                   <th style='text-align: left;width: 5%'>No.</th>
                   <th style='text-align: left;width: 50%'>Customer Name</th>
                   <th style='text-align: left;width: 40%'>Customer Email</th>
               </tr></thead>";
            
            if ($result_count != 0) {
                $count = 1;
                while ($emailrow = $db->fetchByAssoc($email_result)) {
                    switch ($emailrow['bean_module']) {
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
                    $focus->retrieve();
                    $focus->retrieve($emailrow['moduleID']);
                    $record_name = $focus->name;
                    $email = $focus->email1;
                        $html .= "<tr class='oddListRowS1' style='height: 20'>
                         <td style='width: 5%'>{$count}</td>
                         <td style='width: 50%'>{$record_name}</td>
                         <td style='width: 40%'>{$email}</td>
                         </tr>";
                    $count++;
                }
            } else {
                $html .= "<tr><td colspan='4' style='text-align: center;'>No Records Found</td></tr>";
            }
        }


        echo $html;
        parent::display();
    }

}