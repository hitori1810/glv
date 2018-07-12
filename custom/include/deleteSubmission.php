<?php

/**
 * The file used to delete a survey submission of currently deleted record.
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Biztech Consultancy
 */
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class deletedSubmission {
    /*
     * this function is used for checking survey submission record is linked with deleted record or not if there then delete that related record
     */

    function deletedSubmission_method($bean, $event, $arguments) {
        global $db;
        $acceptable_modules = array('Accounts', 'Contacts', 'Leads', 'Prospects'); // acceptable module who contains survey submission record
        if (in_array($bean->target_module_name, $acceptable_modules)) {
            $GLOBALS['log']->fatal("This is the deleted record : " . print_r($bean->id, 1));
            foreach ($bean->field_defs as $field) {
                
                // If related module survey submission exists then remove related submission
                if ($field['module'] == 'bc_survey_submission') {
                    $relationship_name = $field['relationship']; // relation ship name for submission
                    $GLOBALS['log']->fatal("This is the rel name : " . print_r($relationship_name, 1));
                    $bean->load_relationship($relationship_name);
                    
                    if ($bean->load_relationship($relationship_name)) {
                        $GLOBALS['log']->fatal("This is in if condition " . print_r($bean->$relationship_name->getBeans(), 1));
                        foreach ($bean->$relationship_name->getBeans() as $submission) {
                            $submission_id = $submission->id;
                            $submission_query = "SELECT * FROM bc_submission_data_bc_survey_submission_c "
                                    . "WHERE bc_submission_data_bc_survey_submission_c.bc_submission_data_bc_survey_submissionbc_survey_submission_ida = '{$submission_id}' AND deleted = 0";
                            $result = $db->query($submission_query);
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
                            if(empty($submission->module_id)){
                            $rm_old_qry = "delete from bc_survey_submit_answer_calculation "
                                    . "WHERE survey_receiver_id = '{$submission->customer_name}'";
                            $db->query($rm_old_qry);
                            }else{
                            $rm_old_qry = "delete from bc_survey_submit_answer_calculation "
                                    . "WHERE survey_receiver_id = '{$submission->module_id}'";
                            $db->query($rm_old_qry);
                            }

                            $submission_delete = "UPDATE bc_survey_submission SET deleted=1 "
                                    . "WHERE id = '{$submission_id}'";
                            $db->query($submission_delete);
                        }
                    }
                }
            }
        }
    }
}