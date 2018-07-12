<?PHP

/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
/**
 * THIS CLASS IS FOR DEVELOPERS TO MAKE CUSTOMIZATIONS IN
 */
require_once('modules/bc_survey_submission/bc_survey_submission_sugar.php');

class bc_survey_submission extends bc_survey_submission_sugar {

    function bc_survey_submission() {
        parent::bc_survey_submission_sugar();
    }    

    function get_list_view_data() {
        $temp_array = parent::get_list_view_data();
        $survey_sub = new bc_survey_submission();
        $survey_sub->retrieve($this->id);
        $survey_sub->load_relationship('bc_survey_submission_bc_survey');
        $survey = $survey_sub->get_linked_beans('bc_survey_submission_bc_survey', 'bc_survey');
        $temp_array['SURVEY_NAME'] = $survey[0]->name;
//        $temp_array['ID'] = $survey[0]->id;
//        /$temp_array['ID'] = $survey[0]->id;
        return $temp_array;
    }
        
    /**
     * This function should be overridden in each module.  It marks an item as deleted.
     *
     * If it is not overridden, then marking this type of item is not allowed
     */
    public function mark_deleted($id) {
        global $current_user, $db;
        //$submission = BeanFactory::getBean('bc_survey_submission', $id);
        $submission = new bc_survey_submission();
        $submission->retrieve($id);

        // Retrieve related submited data
        $submission_id = $id;
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
            $rm_old_qry = "delete from bc_survey_submit_answer_calculation WHERE 
                                        survey_receiver_id = '{$submission->customer_name}'";
        }else{
            $rm_old_qry = "delete from bc_survey_submit_answer_calculation WHERE 
                                        survey_receiver_id = '{$submission->module_id}'";    
        }
        $result_deleted = $db->query($rm_old_qry);
        
        $submission_delete = "UPDATE bc_survey_submission SET deleted=1 "
                . "WHERE id = '{$submission_id}'";
        $db->query($submission_delete);
}

}

?>