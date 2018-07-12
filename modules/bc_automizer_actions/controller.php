<?php

require_once('custom/include/utilsfunction.php');

/**
 * Survey Automizer action controller for save ,update ,delete action records.
 *
 * @author     Original Author Biztech Consultancy
 */
class bc_automizer_actionsController extends SugarController {

    /**
     * to get the related module which can we send the survey
     * 
     * @param $dom_name - string
     * @param $allowed_modules - array
     * @param $result_array - array
     * @param $html - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_get_module_list() {
        require_once 'data/SugarBean.php';
        $dom_name = $_REQUEST['module_name'];
        //get recipient type
        $result_array = array();
        $allowed_modules = array('Accounts', 'Contacts', 'Leads', 'Prospects');
        $dom_Obj = BeanFactory::getBean($dom_name);
        $rel_field = $dom_Obj->field_defs;
        foreach ($rel_field as $k => $field) {
            // get linked fields related module
            if ($field['type'] == 'link') {
                $rel_mod_name = ($field['name']);
                $dom_Obj->load_relationship($rel_mod_name);

                //Get Related module name from given relationship name
                if (!empty($dom_Obj->field_defs[$rel_mod_name]['module']) && $dom_Obj->$rel_mod_name->relationship->def['true_relationship_type'] == 'many-to-many') {
                    $rel_mod_name = $dom_Obj->field_defs[$rel_mod_name]['module'];
                } else if (!empty($dom_Obj->$rel_mod_name->relationship->def['lhs_module']) && $dom_Obj->$rel_mod_name->relationship->def['lhs_module'] != $dom_name && $dom_Obj->$rel_mod_name->relationship->def['true_relationship_type'] == 'many-to-many') {
                    $rel_mod_name = $dom_Obj->$rel_mod_name->relationship->def['lhs_module'];
                } else if (!empty($dom_Obj->$rel_mod_name->relationship->def['lhs_module']) && $dom_Obj->$rel_mod_name->relationship->def['lhs_module'] != $dom_name && empty($dom_Obj->$rel_mod_name->relationship->def['true_relationship_type']) && $dom_Obj->$rel_mod_name->relationship->def['relationship_type'] == 'many-to-many') {
                    $rel_mod_name = $dom_Obj->$rel_mod_name->relationship->def['lhs_module'];
                } else if (!empty($dom_Obj->$rel_mod_name->relationship->def['rhs_module']) && !empty($dom_Obj->$rel_mod_name->relationship->def['true_relationship_type']) && $dom_Obj->$rel_mod_name->relationship->def['true_relationship_type'] == 'many-to-many') {
                    $rel_mod_name = $dom_Obj->$rel_mod_name->relationship->def['rhs_module'];
                } else if (!empty($dom_Obj->$rel_mod_name->relationship->def['rhs_module']) && empty($dom_Obj->$rel_mod_name->relationship->def['true_relationship_type']) && !empty($dom_Obj->$rel_mod_name->relationship->def['relationship_type']) && $dom_Obj->$rel_mod_name->relationship->def['relationship_type'] == 'many-to-many') {
                    $rel_mod_name = $dom_Obj->$rel_mod_name->relationship->def['rhs_module'];
                } else if (empty($rel_mod_name)) {
                    $rel_mod_name = ucfirst($args['rel_mod_name']);
                }

                if (in_array($rel_mod_name, $allowed_modules)) {
                    $result_array[$field['name']] = $rel_mod_name . ' ( ' . $field['name'] . ' )';
                }
            }
        }
        asort($result_array);
        $html = '<option value="0">Select Module</option>';
        foreach ($result_array as $k => $value) {
            $html .= '<option value="' . $k . '">' . $value . '</option>';
        }
        echo $html;
        exit;
    }

    /**
     * to get the related module which can we send the survey
     * 
     * @param $surveyResult - array
     * @param $list - array
     * @param $html - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_getsurvey() {
        // get automizer
        global $db;
        $automizer_id = $_REQUEST['record'];
        $module_name = $_REQUEST['module_name'];
        $oAutomizer = BeanFactory::getBean('bc_survey_automizer', $automizer_id);
        // retrieve related action survey
        $actions = $oAutomizer->get_linked_beans('bc_survey_automizer_bc_automizer_actions', 'bc_automizer_actions');
        $excludeSurveyList = array();
        foreach ($actions as $oAction) {
            if ($oAction->id != $_REQUEST['action_id']) {
                array_push($excludeSurveyList, $oAction->survey_id);
            }
        }
        $oSurvey = BeanFactory::getBean('bc_survey');
        $surveyResult = $oSurvey->get_full_list();
        $GLOBALS['log']->fatal("rthiws is the result" . print_r($surveyResult, true));
        $html = '<option value="0">Select Survey</option>';
        foreach ($surveyResult as $survey) {
            if (!in_array($survey->id, $excludeSurveyList)) {
                if ($survey->enable_data_piping == 0) {
                $html .= "<option value='{$survey->id}'>{$survey->name}</option>";
            }
        }
        }
        echo $html;
        exit;
    }

    /**
     * Save And Upade Action Record
     * 
     * @param $action - object
     * @param $ans - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_save_actionview() {
        global $db;
        if ($_REQUEST['action_id']) {
            $sql = "Update bc_automizer_actions set recipient_module='{$_REQUEST['module_name']}' , recipient_email_field='{$_REQUEST['email_type']}',survey_id='{$_REQUEST['survey_name']}',email_template_id='{$_REQUEST['email_id']}',filter_by='{$_REQUEST['filter_by']}',recipient_field='{$_REQUEST['fields_list']}',recipient_operator='{$_REQUEST['operator_selection']}',compare_value='{$_REQUEST['value']}' where id='{$_REQUEST['action_id']}'";
            $ans = $db->query($sql);
        } else {
            $action = new bc_automizer_actions();
            $action->recipient_module = $_REQUEST['module_name'];
            $action->bc_workflow_id = $_REQUEST['automizer_id'];
            $action->recipient_email_field = $_REQUEST['email_type'];
            $action->survey_id = $_REQUEST['survey_name'];
            $action->email_template_id = $_REQUEST['email_id'];
            $action->recipient_type = $_REQUEST['rec_type'];
            $action->filter_by = $_REQUEST['filter_by'];
            $action->recipient_field = $_REQUEST['fields_list'];
            $action->recipient_operator = $_REQUEST['operator_selection'];
            $action->compare_value = $_REQUEST['value'];
            $sql = "Select bc_workflow_id from bc_automizer_actions where bc_workflow_id = '{$_REQUEST['automizer_id']}'";
            $ans = $db->query($sql);
            $count_num_rows = 0;
            while($anser_rows = $db->fetchByAssoc($ans)){
                $count_num_rows++;
            }
            $cnt = $count_num_rows;
            $action->action_order = $cnt + 1;
            $action->save();
            $action->load_relationship("bc_survey_automizer_bc_automizer_actions");
            $action->bc_survey_automizer_bc_automizer_actions->add($_REQUEST['automizer_id']);
        }
        echo "done";
        exit;
    }

    /**
     * retrive all action for perticular automizer
     * 
     * @param $action - object
     * @param $action_details - array
     * @param $res - array
     * @param $recipient_module - string
     * @param $cnt - int
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_retrive_actions() {
        global $db;
        $sql = "Select * from bc_automizer_actions where bc_workflow_id = '{$_REQUEST['automizer_id']}' AND deleted=0";
        $ans = $db->query($sql);
        $action_details = array();
        $cnt = 1;
        while ($row = $db->fetchByAssoc($ans)) {
            $action_details[$cnt]['module'] = $row['recipient_module'];
            if ($row['recipient_type'] == "target_module") {
                $recipient_module = "Recipient associated with the target module";
            } else {
                $recipient_module = "Recipient associated with a related module";
            }
            $action_details[$cnt]['recipient_module'] = $recipient_module;
            if ($row['recipient_email_field'] == 'bcc') {
                $email_field = 'Bcc';
            } else if ($row['recipient_email_field'] == 'cc') {
                $email_field = 'Cc';
            } else {
                $email_field = 'To';
            }
            $action_details[$cnt]['recipient_email_field'] = $email_field;
            $query = "Select name from bc_survey where id='{$row['survey_id']}'";
            $answer = $db->query($query);
            $res = $db->fetchByAssoc($answer);
            $action_details[$cnt]['survey'] = $res['name'];
            $action_details[$cnt]['email_template_id'] = $row['email_template_id'];
            $action_details[$cnt]['id'] = $row['id'];
            $cnt += 1;
        }
        echo json_encode($action_details);
        exit;
    }

    /**
     * Delete Action Record From list at detailview of survey Automizer
     * 
     * @param $result - array
     * @param $query - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_delete_record() {
        global $db;
        $query = "Update bc_automizer_actions set deleted = 1 where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        echo "done";
        exit;
    }

    /**
     * Edit Action Record From list at detailview of survey Automizer
     * 
     * @param $result - array
     * @param $query - string
     * @param $action_details - array
     * @param $cnt - int
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_edit_record() {
        global $db;
        $query = "Select * from bc_automizer_actions where id='{$_REQUEST['record_id']}' AND deleted=0";
        $result = $db->query($query);
        $action_details = array();
        while ($row = $db->fetchByAssoc($result)) {
            $action_details[$cnt]['recipient_type'] = $row['recipient_type'];
            $action_details[$cnt]['module'] = $row['recipient_module'];
            $action_details[$cnt]['recipient_email_field'] = $row['recipient_email_field'];
            $action_details[$cnt]['survey'] = $row['survey_id'];
            $action_details[$cnt]['email_template_id'] = $row['email_template_id'];
            $action_details[$cnt]['filter_by'] = $row['filter_by'];
            $action_details[$cnt]['recipient_field'] = $row['recipient_field'];
            $action_details[$cnt]['recipient_operator'] = $row['recipient_operator'];
            $action_details[$cnt]['compare_value'] = $row['compare_value'];
            $action_details[$cnt]['id'] = $row['id'];
            $cnt += 1;
        }
        echo json_encode($action_details);
        exit;
    }

    function action_get_fields_list() {
        if (!empty($_REQUEST['flow_module']) && $_REQUEST['flow_module'] != '') {
            if (isset($_REQUEST['module_name']) && $_REQUEST['module_name'] != '') {
                $module = getModuleRelated($_REQUEST['flow_module'], $_REQUEST['module_name']);
            } else {
                $module = $_REQUEST['flow_module'];
            }
            $fieldlist = array();
            $fieldlist = getFieldsModule($module);
            $html = "";
            foreach ($fieldlist as $key => $value) {
                if (!(strstr($key, 'id')) && !(strstr($key, 'date_entered')) && $key != 'date_modified' && $key != 'deleted' && $key != 'opt_out' && $key != 'date_created' && $key != 'photo' && $key != 'archived' && $key != 'start_date' && $key != 'end_date' && $key != 'exp_date' && $key != 'active_date'  && $key != 'date_start' && $key != 'date_end' && $key != 'birthdate' && $key != 'do_not_call' && $key != 'sugar_login' && $key != 'is_admin' && $key != 'is_group') {
                    $html.="<option value='{$key}'>{$value}</option>";
                }
            }
            echo $html;
            exit;
        }
    }

    function action_getModuleFieldType() {
        $module_edit = strtolower($_REQUEST['module_name']);
        if (isset($module_edit) && $module_edit != '') {
            $rel_module = getModuleRelated($_REQUEST['flow_module'], $module_edit);
        } else {
            $rel_module = $_REQUEST['flow_module'];
        }
        $fieldname = $_REQUEST['field_name'];

        if (isset($_REQUEST['view'])) {
            $view = $_REQUEST['view'];
        } else {
            $view = 'EditView';
        }
        echo getModuleField($rel_module, $fieldname, "condition_value", $view, $value);
        die;
    }

    function action_get_related_fields() {
        $result_array = array();
        $dom_name = $_REQUEST['module_name'];
        $allowed_modules = array('Accounts', 'Contacts', 'Leads', 'Prospects');

        $dom_Obj = BeanFactory::getBean($dom_name);
        $rel_field = $dom_Obj->field_defs;

        foreach ($rel_field as $k => $field) {
            // get linked fields related module

            if ($field['type'] == 'parent') {
                // get label from module language file
                $lang = return_module_language('en_us', $dom_name);
                $result_array[$field['name']] = $lang[$field['vname']];
            }
            if ($field['type'] == 'relate') {
                // get label from module language file
                $lang = return_module_language('en_us', $dom_name);

                $rel_mod_name = ($field['name']);
                $dom_Obj->load_relationship($rel_mod_name);

                if (empty($field['link']) && in_array($field['module'], $allowed_modules)) {
                    $result_array[$field['name']] = $lang[$field['vname']];
                }
                if (!empty($field['link']) && $field['source'] == 'non-db' && in_array($field['module'], $allowed_modules) && !in_array($field['name'], $exclude_field)) {
                    $exclude_field[] = $field['id_name'];
                    $label = trim($lang[$field['vname']], ':');
                    if (!empty($label)) {
                        $result_array[$field['name']] = $label;
                    } else {
                        $result_array[$field['name']] = $field['vname'];
                    }
                }
            }
        }
        asort($result_array);
        if (in_array($_REQUEST['module_name'], $allowed_modules)) {
            $html .= "<option value='email1'>Email Address</option>";
        }
        foreach ($result_array as $k => $value) {
            $html .= '<option value="' . $k . '">' . $value . '</option>';
        }
        echo $html;
        exit;
    }

}
