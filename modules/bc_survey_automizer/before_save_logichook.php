<?php

/**
 * The file used to handle Before Save Logic for checking survey automizer conditions and perform actions based on that.
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

require_once 'custom/include/utilsfunction.php';

/**
 * Before Save Logighook Class For Survey Automizer
 *
 * @author     Original Author Biztech Consultancy
 */
class before_save_logichook {

/**
     * function when any record save and modified this function call.
 *
     * @param $bean - array
     * @param $arguments - array
     * @param $isNew - bool
     * @param $related_module - array
     * @param $current_saving_module - String
     * @param $listApplicableAutomizer - array
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
 */
    function check_survey_automizer_method($bean, $event, $arguments) {

        // Record is new or already created
        $isNew = !empty($bean->fetched_row['id']) ? false : true;
        $related_module = $bean->fetched_rel_row;

        global $db, $beanList, $app_list_strings;
        $current_saving_module = $bean->module_dir;
//        get automizer whose target module is currently saving module
        $app_list_strings['bc_moduleList'] = $app_list_strings['moduleList'];

        if (!empty($app_list_strings['bc_moduleList'])) {
            foreach ($app_list_strings['bc_moduleList'] as $mkey => $mvalue) {
                if ((!isset($beanList[$mkey]) || str_begin($mkey, 'bc_')) || (!isset($beanList[$mkey]) || str_begin($mkey, 'AOW_'))) {
                    unset($app_list_strings['bc_moduleList'][$mkey]);
                }
            }
        }
        $app_list_strings['bc_moduleList'] = array_merge((array) array('' => ''), (array) $app_list_strings['bc_moduleList']);
        $allowed_modules = $app_list_strings['bc_moduleList'];
        if (in_array($current_saving_module, $allowed_modules)) {
            $getAutomizerQry = "SELECT * FROM bc_survey_automizer WHERE flow_module = '$current_saving_module' AND status='active' AND deleted=0";
            $resultAutomizer = $db->query($getAutomizerQry);

            //list applicable survey automizer 
            $listApplicableAutomizer = array();

            while ($row = $db->FetchByAssoc($resultAutomizer)) {
                // $GLOBALS['log']->fatal("This is the survey automizer id : " . print_r($row['id'], 1));
                $listApplicableAutomizer[] = $row['id'];
            }
            $result = array();
            foreach ($listApplicableAutomizer as $automizerId) {
                $oAutomizer = new bc_survey_automizer();
                $oAutomizer->retrieve($automizerId);
                $execution_type = $oAutomizer->flow_run_on;
                $result_flag = $this->check_valid_survey_automizer_conditions($automizerId, $isNew, $bean);
                $result[] = $result_flag;

                $GLOBALS['log']->fatal("This is the condition result : " . print_r($result, 1));
                if (!in_array(false, $result)) {
                    $GLOBALS['log']->fatal("This is the no false : " . print_r('', 1));
                    $this->send_survey_from_automizer_action($automizerId, $bean, $execution_type);
                }
            }
        }
    }

    /**
     * Check Condition For Automizer
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $isNew - bool
     * @param $oAutomizer - object
     * @param $current_saving_module - String
     * @param $execution_type - string
     * @param $applied_to - string
     * @param $result_array - array
     * @param $isApplicable - bool
     * @param $isRelatedModuleCondition - bool
     * @param $relationship_name - String
     * @param $result - array
     * @param $conditionRelatedModule - String
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function check_valid_survey_automizer_conditions($automizerId, $isNew, $bean) {
        $result = false;
        $result_array = array();
        $result_array_related = array();
        $oAutomizer = new bc_survey_automizer();
        $oAutomizer->retrieve($automizerId);
        $execution_type = $oAutomizer->flow_run_on;
        $applied_to = $oAutomizer->run_when;

        //Check Applicable Fpr Automizer on basis of applied to field for new and update record
        if ($applied_to == 'new_and_updated_records') {
            $isApplicable = true;
        }
        //new record
        else if ($applied_to == 'new_records_only' && $isNew) {
            $isApplicable = true;
        }
        // update record
        else if ($applied_to == 'updated_records_only' && !$isNew) {
            $isApplicable = true;
        }
        // not applicable
        else {
            $isApplicable = false;
        }
        if ($isApplicable) {

            // Get Related Survey Automizer Conditions
            $oAutomizer->load_relationship('bc_survey_automizer_bc_automizer_condition');
            $conditionList = array();
            foreach ($oAutomizer->bc_survey_automizer_bc_automizer_condition->getBeans() as $con) {
                $conditionList[$con->condition_order] = $con;
            }

            foreach ($conditionList as $condition) {
                $rel_module_array = split(':', $condition->module);
                if (empty($rel_module_array[1])) {
                    $isRelatedModuleCondition = false;
                    $conditionTargetModule = $rel_module_array[0];
                } else {
                    $isRelatedModuleCondition = true;
                    $conditionRelatedModule = trim($rel_module_array[1]);
                }
                $GLOBALS['log']->fatal("This is the condition related module : " . print_r($conditionRelatedModule, 1));
                // Check all conditions for Target Module
                if (!$isRelatedModuleCondition && !empty($conditionTargetModule)) {
                    $GLOBALS['log']->fatal("Target Module : 1");
                    $result_flag = $this->checkTargetModuleConditions($condition, $automizerId, $bean, $execution_type);
                    $GLOBALS['log']->fatal("Target Module : 2");
                    $result_array[] = $result_flag;
                }
                // Check all conditions for Related Module
                if ($isRelatedModuleCondition && !empty($conditionRelatedModule)) {

                    foreach ($bean->field_defs as $field) {
                        // $GLOBALS['log']->fatal("This is the field : " . print_r($field['module'], 1));
                        if ($field['module'] == ucfirst($conditionRelatedModule)) {
                            if (empty($field['link'])) {
                            $relationship_name = $field['name'];
                            } else {
                                $relationship_name = $field['link'];
                            }
                            break;
                        }
                        // get linked fields related module
                        if ($field['type'] == 'link') {
                            if ($field['name'] == ($conditionRelatedModule)) {
                                $relationship_name = $field['name'];
                                break;
                            }
                        }
                    }
                    $GLOBALS['log']->fatal("This is the relationship name : " . print_r($relationship_name, 1));
                    //Retrieve related bean object
                    if ($bean->load_relationship($relationship_name)) {
                        
                        $getRelatedBean = $bean->$relationship_name->getBeans();

                        if (!empty($getRelatedBean)) {

                            foreach ($getRelatedBean as $relatedClass) {
                                //get condition status
                                $result_flag = $this->checkTargetModuleConditions($condition, $automizerId, $relatedClass, $execution_type);
                                // if condition satisfaction filter type is any related then match condition for any one record
                                if ($condition->filter_by == 'any_field' && $result_flag) {
                                    $reassign_array = array($result_flag);
                                    $result_array_related = array();
                                    $result_array_related = $reassign_array;
                                    break;
                                } else {
                                    $result_array_related[] = $result_flag;
                                }
                            }
                        }
                        // If no any related record
                        else {
                            $GLOBALS['log']->fatal("This is the relationship bean is null: " . print_r($getRelatedBean, 1));
                            $reassign_array = array(false);
                            $result_array_related = array();
                            $result_array_related = $reassign_array;
                            break;
                        }
                    }
                    // if no any relationship found for given relationship name
                    else {
                        $GLOBALS['log']->fatal("This is the relationship bean is null: " . print_r($getRelatedBean, 1));
                        $reassign_array = array(false);
                        $result_array_related = array();
                        $result_array_related = $reassign_array;
                        break;
                    }

                    $GLOBALS['log']->fatal("This is the related bean : $conditionRelatedModule : - Relationship name : " . print_r($relationship_name, 1));
                }
            }
        }
        $final_result = array_merge($result_array, $result_array_related);

        $GLOBALS['log']->fatal("This is the condition result in self function: " . print_r($final_result, 1));
        if ($final_result != NULL && !in_array(false, $final_result)) {
            $result = true;
        }
        return $result;
    }

    /**
     * Check Condition For Target Module
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $condition_field - string
     * @param $condition - String
     * @param $execution_type - string
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function checkTargetModuleConditions($condition, $automizerId, $bean, $execution_type) {
        $result = false;
        if ($condition->value_type == 'Value') {
            $result = $this->checkValueType_value_or_field($condition, $automizerId, $bean, 'Value', $execution_type);
        }
        // Check value for is null
        else if ($condition->operator == 'is_null') {
            $field = $condition->field;
            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for is empty', 1));
            if (empty($bean->$field)) {
                return true;
            }
        }
        // Check value for Any Change
        else if ($condition->operator == 'Any_change') {
            $condition_field = $condition->field;
            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$condition_field . ' is....check for Any Change', 1));

            if(!empty($bean->fetched_row)) {
            foreach ($bean->fetched_row as $field => $value) {
                // compare current and previous saved field value
                    if ($field == $condition_field && $bean->$condition_field != $value) {
                        return true;
                    }
                }
            }
            else if(!empty($bean->$condition_field)) {
                return true;
        }
            
        }
        //check for value type is field
        else if ($condition->value_type == 'Field') {
            $GLOBALS['log']->fatal("Target Module : 2");
            $result = $this->checkValueType_value_or_field($condition, $automizerId, $bean, 'Field', $execution_type);
        }
        //check for value type is field
        else if ($condition->value_type == 'Multi') {
            $result = $this->checkValueType_multi($condition, $automizerId, $bean, $execution_type);
        }
        return $result;
    }

    /**
     * check type of value that value or fields
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $condition_field - string
     * @param $condition - String
     * @param $execution_type - string
     * @param $value_type - string
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function checkValueType_value_or_field($condition, $automizerId, $bean, $value_type, $execution_type) {
        $result = false;
        //compare value and saving value
        $field = $condition->field; //Condition Field
        $com_field = $condition->value; // Comparing with Any Field Value
        //comparing value
        $compare_value = $value_type == 'Value' ? $condition->value : $bean->$com_field;
        // Check value for Equals to
        if ($condition->operator == 'Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for ' . $compare_value, 1));
            if ($bean->$field == $compare_value) {
                return true;
            }
        }
        // Check value for Not Equals to
        else if ($condition->operator == 'Not_Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Not_Equal_To' . $compare_value, 1));
            if ($bean->$field != $compare_value) {
                return true;
            }
        }
        // Check value forGreater Than
        else if ($condition->operator == 'Greater_Than') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Greater_Than' . $compare_value, 1));
            if ((int) $bean->$field > (int) $compare_value) {
                return true;
            }
        }
        // Check value for Less than
        else if ($condition->operator == 'Less_Than') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Less_Than' . $compare_value, 1));
            if ((int) $bean->$field < (int) $compare_value) {
                return true;
            }
        }
        // Check value for Greater than equals to
        else if ($condition->operator == 'Greater_Than_or_Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Greater_Than_or_Equal_To' . $compare_value, 1));
            if ((int) $bean->$field >= (int) $compare_value) {
                return true;
            }
        }
        // Check value for Less than equals to
        else if ($condition->operator == 'Less_Than_or_Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Less_Than_or_Equal_To' . $compare_value, 1));
            if ((int) $bean->$field <= (int) $compare_value) {
                return true;
            }
        }
        // Check value for Contais given string
        else if ($condition->operator == 'Contains') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Contains' . $compare_value, 1));
            if (strpos($compare_value, $bean->$field) !== false) {
                return true;
            }
        }
        // Check value for Starts with given string
        else if ($condition->operator == 'Starts_With') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Starts with' . $condition->compare_value, 1));
            if (startsWith($bean->$field, $compare_value)) {
                return true;
            }
        }
        // Check value for Ends with given string
        else if ($condition->operator == 'Ends_With') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Ends with' . $condition->compare_value, 1));
            if (endsWith($bean->$field, $compare_value)) {
                return true;
            }
        }
        return $result;
    }

    /**
     * check for value type multi
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $condition_field - string
     * @param $condition - String
     * @param $execution_type - string
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function checkValueType_multi($condition, $automizerId, $bean, $execution_type) {
        $result = false;
        //compare value and saving value
        $field = $condition->field; //Condition Field
        $compare_value = explode(',', $condition->compare_value); // Comparing with Any of given Value
        // Check value for Equals to
        if ($condition->operator == 'Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for ' . $compare_value, 1));
            if (in_array($bean->$field, $compare_value)) {
                return true;
            }
        }
        // Check value for Not Equals to
        else if ($condition->operator == 'Not_Equal_To') {

            $GLOBALS['log']->fatal("This is the checking of condition : " . print_r($bean->$field . ' is....check for Not_Equal_To' . $compare_value, 1));
            if (!in_array($bean->$field, $compare_value)) {
                return true;
            }
        }
        return $result;
    }

    /**
     * check for auto mizer action selected survey send
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $email_address - string
     * @param $toemail - String
     * @param $execution_type - string
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function send_survey_from_automizer_action($automizerId, $bean, $execution_type) {
        $oAutomizer = new bc_survey_automizer();
        $oAutomizer->retrieve($automizerId);
        $oAutomizer->load_relationship('bc_survey_automizer_bc_automizer_actions');

        foreach ($oAutomizer->bc_survey_automizer_bc_automizer_actions->getBeans() as $act) {

            // Send survey to target module email field **************************************************
            if ($act->recipient_type == 'target_module' && $act->recipient_field == 'email1') {
                $survey_id = $act->survey_id;
                $email_address = $bean->email1;
                $GLOBALS['log']->fatal("This is the action.................................. : " . print_r($email_address, 1));
                if (!empty($bean->id)) {
                    $module_id = $bean->id;
                } else {
                    $module_id = create_guid();
                    $bean->id = $module_id;
                    $bean->new_with_id = true;
                }
                $this->survey_submission_entry($module_id, $bean, $survey_id, $execution_type, $act);
            }
            // Send survey to target module email field **************************************************
            else if ($act->recipient_type == 'target_module' && $act->recipient_field == 'parent_name') {
                $survey_id = $act->survey_id;
                $RelatedTo = $bean->parent_type;
                $RelatedToId = $bean->parent_id;
                $allowed_modules = array('Accounts', 'Contacts', 'Leads', 'Prospects');
                if (in_array($RelatedTo, $allowed_modules)) {
                     $GLOBALS['log']->fatal("Target Module : 4");
                    $RelateBean = BeanFactory::getBean($RelatedTo, $RelatedToId);
                     $GLOBALS['log']->fatal("Target Module : 5");
                    $email_address = $RelateBean->email1;
                    $GLOBALS['log']->fatal("This is the action.................................. : " . print_r($email_address, 1));
                    if (!empty($RelatedToId)) {
                        $module_id = $RelatedToId;
                    } else {
                        $module_id = create_guid();
                        $RelateBean->id = $module_id;
                        $RelateBean->new_with_id = true;
                    }
                    $this->survey_submission_entry($module_id, $RelateBean, $survey_id, $execution_type, $act);
                } else {
                    $GLOBALS['log']->fatal("Related module $RelatedTo not match to send survey : " . print_r('', 1));
                }
            }
            // Send survey to related module *************************************************
            else {
                //get related bean Relationship
                $actionRelatedModule = $act->recipient_module;
                $relationship_name = $actionRelatedModule;
                //Retrieve related bean obj
                $bean->load_relationship($relationship_name);
                if ($bean->load_relationship($relationship_name)) {
                    $getRelatedBean = $bean->$relationship_name->getBeans();
                    if ($getRelatedBean) {

                        foreach ($getRelatedBean as $relatedClass) {
                            // Send survey to All related records 
                            if (empty($act->filter_by) || $act->filter_by == 'all_fields') {
                                $survey_id = $act->survey_id;
                                $email_address = $relatedClass->email1;
                                $GLOBALS['log']->fatal("This is the related action.................................. : " . print_r($email_address, 1));
                                if (!empty($relatedClass->id)) {
                                    $module_id = $relatedClass->id;
        }
                                $this->survey_submission_entry($module_id, $relatedClass, $survey_id, $execution_type, $act);
    }
                            // Send survey to those records whose conditions matches with action
                            else if ($act->filter_by == 'any_field') {
                                $rel_field = $act->recipient_field;
                                $operator = $act->recipient_operator;
                                $value = $act->compare_value;

                                if (($operator == 'Equal_To' && $relatedClass->$rel_field == $value) || ($operator == 'Not_Equal_To' && $relatedClass->$rel_field != $value)) {
                                    $survey_id = $act->survey_id;
                                    $email_address = $relatedClass->email1;
                                    $GLOBALS['log']->fatal("This is the related action.................................. : " . print_r($email_address, 1));
                                    if (!empty($relatedClass->id)) {
                                        $module_id = $relatedClass->id;
                                    }
                                    $this->survey_submission_entry($module_id, $relatedClass, $survey_id, $execution_type, $act);
                                } else {
                                    $GLOBALS['log']->fatal("This is the action recipient condition not match for $relatedClass->id : " . print_r('', 1));
                                }
                            }
                        }
                    }
                }// can be a relate field
                else {
                    $GLOBALS['log']->fatal("This is the else part of relationship : " . print_r('', 1));
                    $survey_id = $act->survey_id;
                    $allowed_modules = array('Accounts', 'Contacts', 'Leads', 'Prospects');
                    $beanFields = $bean->field_defs;
                    foreach ($beanFields as $field) {
                        if ($field['name'] == $act->recipient_module) {
                            if (in_array($field['module'], $allowed_modules)) {
                                $related_id_field = $field['id_name'];
                                $relatedId = $bean->$related_id_field;
                                $relatedBean = BeanFactory::getBean($field['module'], $relatedId);
                                $GLOBALS['log']->fatal("This is the relate module {$field['module']} and field id {$relatedId} : " . print_r('', 1));
                                $this->survey_submission_entry($relatedId, $relatedBean, $survey_id, $execution_type, $act);
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * entry in survey submission table
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $module_id - string
     * @param $toemail - String
     * @param $execution_type - string
     * @param $survey_id - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function survey_submission_entry($module_id, $bean, $survey_id, $execution_type, $act) {
        $GLOBALS['log']->fatal("This is the survey submission entry : " . print_r('', 1));
        $module_name = $bean->module_name;
        $recipient_as = $act->recipient_email_field;
        $dataArray = sendSurveyEmailsModuleRecords($module_id, $module_name, $survey_id, '', '', true, $bean, $recipient_as);
        $GLOBALS['log']->fatal("This is the survey submission entry : " . print_r($dataArray, 1));
        $survey_submission = new bc_survey_submission();
        $survey_submission->retrieve($dataArray['submission_id']);
        $resubmit = $survey_submission->resubmit;
        if ($dataArray['is_send'] == 'already_sent' && $dataArray['status'] == 'Submitted' && $resubmit == 0 && $execution_type == 'when_record_saved') {
            $this->send_survey($module_id, $module_name, $survey_id, $bean, $dataArray, 1, $act);
        }
        if ($dataArray['is_send'] == 'already_sent' && $dataArray['status'] == 'Submitted' && $resubmit == 0 && $execution_type == 'after_elapse_time') {
            // $this->schedule_survey($dataArray);
            $this->send_survey($module_id, $module_name, $survey_id, $bean, $dataArray, 1, $act);
        }
        if ($dataArray['is_send'] == 'scheduled' && $execution_type == 'when_record_saved') {
            $this->send_survey($module_id, $module_name, $survey_id, $bean, $dataArray, '', $act);
        }
    }

    /**
     * send survey directly
     * 
     * @param $automizerId - string
     * @param $bean - array
     * @param $macro_nv - array
     * @param $survey_url - array
     * @param $template_data - array
     * @param $module_id - string
     * @param $toemail - String
     * @param $execution_type - string
     * @param $survey_id - string
     * @param $recip_prefix - string
     * @param $module_name - string
     * @param $rec_module_detail - string
     * @param $emailBody - string
     * @param $pure_data - string
     * @param $encoded_data - string
     * @param $host - string
     * @param $survey_submission - object
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function send_survey($module_id, $module_name, $survey_id, $bean, $rec_module_detail, $resend, $act) {
        global $sugar_config;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $survey_url_for_email = $administrationObj->settings['SurveyPlugin_survey_url_for_email'];

        $oSurvey = new bc_survey();
        $oSurvey->retrieve($survey_id);
        $emailAddQryRes = getOptOutEmailCustomers($module_id, $module_name);
        $opt_out_url = $sugar_config['site_url'] . '/index.php?entryPoint=unsubscribe&target=' . $emailAddQryRes['email_add_id'];
        $getSurveyEmailTemplateID = getEmailTemplateBySurveyID($survey_id);
        $emailtemplateObj = new EmailTemplate();
        $emailtemplateObj->retrieve($getSurveyEmailTemplateID);
        $macro_nv = array();
        $emailtemplateObj->parsed_entities = null;
        $emailSubjectName = (!empty($emailtemplateObj->subject)) ? $emailtemplateObj->subject : $oSurvey->name;
        if ($module_name == 'Leads' || $module_name == 'Prospects' || $module_name == 'Contacts') {
            $email_module = 'Contacts';
            $recip_prefix = '$contact';
        } else {
            $email_module = $module_name;
            $recip_prefix = '$account';
        }

        //replace prefix for recipient name if exists email template for other module
        if ($recip_prefix == '$contact') {
            $search_prefix1 = '$account';
            $search_prefix2 = '$contact_user';
        } else if ($recip_prefix == '$account') {
            $search_prefix1 = '$contact';
            $search_prefix2 = '$contact_user';
        }

        $emailtemplateObj->body_html = str_replace($search_prefix1, $recip_prefix, $emailtemplateObj->body_html);
        $emailtemplateObj->body_html = str_replace($search_prefix2, $recip_prefix, $emailtemplateObj->body_html);

        $template_data = $emailtemplateObj->parse_email_template(array(
            "subject" => $emailSubjectName,
            "body_html" => $emailtemplateObj->body_html,
            "body" => $emailtemplateObj->body), $email_module, $bean, $macro_nv);

        // create new url for survey with encryption*****************************************
        // survey URL current with survey_id
        $survey_url = split('&quot;', substr($template_data['body_html'], strpos($template_data['body_html'], 'href=')));

        // host name
        $host = strtok($survey_url[1], '?');

        // data to be encoded sufficient data
        $pure_data = $survey_id . '&ctype=' . $module_name . '&cid=' . $module_id;

        $encoded_data = base64_encode($pure_data);

        $new_url = $host . '?q=' . $encoded_data;

        //replace into current mail body for encoded survey URL
        $template_data['body_html'] = str_replace($survey_url[1], $new_url, $template_data['body_html']);

        // **************************************************************************************

        $emailBody = $template_data["body_html"];
        $mailSubject = $template_data["subject"];

        $emailSubject = $mailSubject;
        $to_Email = $bean->email1;

        $image_src = "{$sugar_config['site_url']}/index.php?entryPoint=checkEmailOpened&submission_id={$rec_module_detail['submission_id']}";
        $image_url = "<img src='{$image_src}'>";

        $emailBody .= $image_url;
        $emailBody .= '<br/><span style="font-size:0.8em">To remove yourself from this email list  <a href="' . $opt_out_url . '" target="_blank">click here</a></span>';
        $sendMail = CustomSendEmail($to_Email, $emailSubject, $emailBody, $rec_module, $module_id, $act->recipient_email_field);
        /*
         * Store survey data
         */
        $GLOBALS['log']->fatal("Send sent mail status : " . print_r($sendMail, 1));
        if (trim($sendMail) == 'send') {
            $survey_submission = new bc_survey_submission();
            $survey_submission->retrieve($rec_module_detail['submission_id']);
            $survey_submission->survey_send = 1;
            if ($resend == 1) {
                $survey_submission->resubmit = 1;
            } else {
                $survey_submission->resubmit = 0;
            }
            $survey_submission->processed = true;
            $survey_submission->save();
        }
    }
}
