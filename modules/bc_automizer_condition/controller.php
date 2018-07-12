<?php

require_once('custom/include/utilsfunction.php');

/**
 * Survey Automizer Condition controller for save ,update ,delete condition records.
 *
 * @author     Original Author Biztech Consultancy
 */
class bc_automizer_conditionController extends SugarController {

    /**
     * to get the related module fields based on target module
     * 
     * @param $module - string
     * @param $fieldlist - array
     * @param $html - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_get_Related_Fields() {

        if (!empty($_REQUEST['flow_module'])) {
            
            $module = !empty($_REQUEST['module_name'])?getModuleRelated($_REQUEST['flow_module'], $_REQUEST['module_name']):$_REQUEST['flow_module'];
            $fieldlist = array();
            $fieldlist = getFieldsModule($module);
            $html = "";
            foreach ($fieldlist as $key => $value) {
                if (!(strstr($key, 'id')) && !(strstr($key, 'date_entered')) && $key != 'date_modified' && $key != 'deleted' && $key != 'opt_out' && $key != 'date_created' && $key != 'photo' && $key != 'archived' && $key != 'start_date' && $key != 'end_date' && $key != 'exp_date' && $key != 'active_date' && $key != 'date_start' && $key != 'date_end' && $key != 'birthdate' && $key != 'do_not_call' && $key != 'sugar_login' && $key != 'is_admin' && $key != 'is_group') {
                    $html.="<option value='{$key}'>{$value}</option>";
                }
            }
            echo $html;
            exit;
        }
    }

    /**
     * to get the related module list based on target module
     * 
     * @param $target_module - string
     * @param $select_options - array
     * @param $html - string
     * @param $result_array - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_get_module_list() {
        $select_options = array();
        $select_options = getRelationshipsModule($_REQUEST['module_name'], '');
        $target_module = $_REQUEST['module_name'];
        foreach ($select_options as $key => $module) {
            if (!(strstr($key, 'bc_survey')) && !(strstr($key, 'bc_survey_submission')) && $key != 'teams' && $key != 'favorite_link' && $key != 'following_link') {
                if ($target_module != $module) {
                    $result_array[$key] = $target_module . ' : ' . $module;
                } else {
                    $result_array[$key] = $target_module;
                }
            }
        }
        asort($result_array);

        $html = "";
        foreach ($result_array as $key => $value) {
            $html.="<option value='{$key}'>{$value}</option>";
        }
        echo $html;
        exit;
    }

    /**
     * to get the related fields list based on related module
     * 
     * @param $module - string
     * @param $fieldlist - array
     * @param $html - string
     * @param $row - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_get_Module_Related_Fields() {
        global $db;
        $query = "SELECT flow_module from bc_survey_automizer where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);

        if (!empty($row['flow_module']) && $row['flow_module'] != '') {
            if (isset($_REQUEST['module_name']) && $_REQUEST['module_name'] != '') {
                $module = getModuleRelated($row['flow_module'], $_REQUEST['module_name']);
            } else {
                $module = $row['flow_module'];
            }
            $fieldlist = array();
            $fieldlist = getFieldsModule($module);
            $html = "";
            foreach ($fieldlist as $key => $value) {
                if (!(strstr($key, 'id')) && !(strstr($key, 'date_entered')) && $key != 'date_modified' && $key != 'deleted' && $key != 'opt_out' && $key != 'date_created' && $key != 'photo' && $key != 'archived' && $key != 'start_date' && $key != 'end_date' && $key != 'exp_date' && $key != 'active_date' && $key != 'date_start' && $key != 'date_end' && $key != 'birthdate' && $key != 'do_not_call' && $key != 'sugar_login' && $key != 'is_admin' && $key != 'is_group') {
                    $html.="<option value='{$key}'>{$value}</option>";
                }
            }
            echo $html;
            exit;
        }
    }

    /**
     * to get the operator list dropdown
     * 
     * @param $module - string
     * @param $app_list_strings - array
     * @param $beanFiles - array
     * @param $row - array
     * @param $beanList - array
     * @param $valid_opp - array
     * @param $focus - object
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_getModuleOperatorField() {
        global $app_list_strings, $beanFiles, $beanList, $db;

        $query = "SELECT flow_module from bc_survey_automizer where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        $module_edit = strtolower($_REQUEST['module_name']);

        $module = !empty($module_edit) ? getModuleRelated($row['flow_module'], $module_edit) : $row['flow_module'];

        $view = !empty($_REQUEST['view']) ? $_REQUEST['view'] : 'EditView';

        require_once($beanFiles[$beanList[$module]]);
        $focus = new $beanList[$module];

        $vardef = $focus->getFieldDefinition($_REQUEST['field_name']);

        if ($vardef) {
            switch ($vardef['type']) {
                case 'double':
                case 'decimal':
                case 'float':
                case 'currency':
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'Greater_Than', 'Less_Than', 'Greater_Than_or_Equal_To', 'Less_Than_or_Equal_To', 'is_null', 'Any_change');
                    break;
                case 'uint':
                case 'ulong':
                case 'long':
                case 'short':
                case 'tinyint':
                case 'int':
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'Greater_Than', 'Less_Than', 'Greater_Than_or_Equal_To', 'Less_Than_or_Equal_To', 'is_null', 'Any_change');
                    break;
                case 'date':
                case 'datetime':
                case 'datetimecombo':
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'Greater_Than', 'Less_Than', 'Greater_Than_or_Equal_To', 'Less_Than_or_Equal_To', 'is_null', 'Any_change');
                    break;
                case 'enum':
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'is_null', 'Any_change');
                    break;
                case 'multienum':
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'is_null', 'Any_change');
                    break;
                default:
                    $valid_opp = array('Equal_To', 'Not_Equal_To', 'is_null', 'Any_change');
                    break;
            }

            foreach ($app_list_strings['operator_list'] as $key => $keyValue) {
                if (!in_array($key, $valid_opp)) {
                    unset($app_list_strings['operator_list'][$key]);
                }
            }

            if ($view == 'EditView') {
                $html = "<select type='text' style='' onchange='show_hide_fields(this)' name='operator_row' id='operator_row' title='' tabindex='116'>" . get_select_options_with_id($app_list_strings['operator_list'], $value) . "</select>";
            } else {
                echo $app_list_strings['operator_list'][$value];
            }
        }
        echo $html;
        exit;
    }

    /**
     * to get the field type list dropdown
     * 
     * @param $module - string
     * @param $app_list_strings - array
     * @param $beanFiles - array
     * @param $row - array
     * @param $beanList - array
     * @param $valid_opp - array
     * @param $focus - object
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_getFieldTypeOptions() {
        global $app_list_strings, $beanFiles, $beanList, $db;

        $query = "SELECT flow_module from bc_survey_automizer where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);

        if (isset($_REQUEST['module_name']) && $_REQUEST['module_name'] != '') {
            $module = getModuleRelated($row['flow_module'], $_REQUEST['module_name']);
        } else {
            $module = $row['flow_module'];
        }
        $fieldname = $_REQUEST['field_name'];

        if (isset($_REQUEST['view']))
            $view = $_REQUEST['view'];
        else
            $view = 'EditView';

        if (isset($_REQUEST['aow_value']))
            $value = $_REQUEST['aow_value'];
        else
            $value = '';


        require_once($beanFiles[$beanList[$module]]);
        $focus = new $beanList[$module];
        $vardef = $focus->getFieldDefinition($fieldname);

        switch ($vardef['type']) {
            case 'double':
            case 'decimal':
            case 'float':
            case 'currency':
                $valid_opp = array('Value', 'Field');
                break;
            case 'uint':
            case 'ulong':
            case 'long':
            case 'short':
            case 'tinyint':
            case 'int':
                $valid_opp = array('Value', 'Field');
                break;
            case 'date':
            case 'datetime':
            case 'datetimecombo':
                $valid_opp = array('Value', 'Field', 'Date');
                break;
            case 'enum':
            case 'dynamicenum':
            case 'multienum':
                $valid_opp = array('Value', 'Field', 'Multi');
                break;
            case 'relate':
            case 'id':
                $valid_opp = array('Value', 'Field', 'SecurityGroup');
                break;
            default:
                $valid_opp = array('Value', 'Field');
                break;
        }

        if (!file_exists('modules/SecurityGroups/SecurityGroup.php')) {
            unset($app_list_strings['condition_type_list']['SecurityGroup']);
        }
        foreach ($app_list_strings['condition_type_list'] as $key => $keyValue) {
            if (!in_array($key, $valid_opp)) {
                unset($app_list_strings['condition_type_list'][$key]);
            }
        }
        if ($view == 'EditView') {
            $html = "<select type='text' style='' name='option' id='option' onchange='getvalueFields()' title='' tabindex='116'>" . get_select_options_with_id($app_list_strings['condition_type_list'], $value) . "</select>";
        } else {
            echo $app_list_strings['condition_type_list'][$value];
        }
        //return $html;
        echo $html;
        exit;
    }

    /**
     * to get the value fields in condition popup
     * 
     * @param $module - string
     * @param $row - array
     * @param $valid_opp - array
     * @param $view - string
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_getModuleFieldType() {
        global $db;
        $query = "SELECT flow_module from bc_survey_automizer where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        $module_edit = strtolower($_REQUEST['module_name']);
        if (isset($module_edit) && $module_edit != '') {
            $rel_module = getModuleRelated($row['flow_module'], $module_edit);
        } else {
            $rel_module = $row['flow_module'];
        }
        $module = $row['flow_module'];
        $fieldname = $_REQUEST['field_name'];

        if (isset($_REQUEST['view']))
            $view = $_REQUEST['view'];
        else
            $view = 'EditView';

        if (isset($_REQUEST['aow_value']))
            $value = $_REQUEST['aow_value'];
        else
            $value = '';
        $html = '';
        switch ($_REQUEST['type_value']) {
            case 'Field':
                if (isset($_REQUEST['alt_module']) && $_REQUEST['alt_module'] != '')
                    $module = $_REQUEST['alt_module'];
                $fieldlist = getFieldsModule($module);
                $html .= "<select type='text' style='' name='condition_value' id='condition_value' title='' tabindex='116'>";
                foreach ($fieldlist as $key => $value) {
                    if (!(strstr($key, 'id')) && !(strstr($key, 'date_entered')) && $key != 'date_modified' && $key != 'deleted' && $key != 'opt_out' && $key != 'date_created' && $key != 'photo' && $key != 'archived' && $key != 'start_date' && $key != 'end_date' && $key != 'exp_date' && $key != 'active_date' && $key != 'date_start' && $key != 'date_end' && $key != 'birthdate' && $key != 'do_not_call' && $key != 'sugar_login' && $key != 'is_admin' && $key != 'is_group') {
                        $html.="<option value='{$key}'>{$value}</option>";
                    }
                }
                $html .= "</select>";
                break;
            case 'Any_Change';
                echo '';
                break;
            case 'Date':
                $html .= getDateField($module, "condition_value", $view, $value, false);
                break;
            case 'Multi':
                $html .= getModuleField($rel_module, $fieldname, "condition_value", $view, $value, 'multienum');
                break;
            case 'SecurityGroup':
                $fieldname = 'SecurityGroups';
            case 'Value':
            default:
                $html .= getModuleField($rel_module, $fieldname, "condition_value", $view, $value);
                break;
        }
        $html .= '&nbsp;&nbsp;<i class="fa fa-exclamation-circle" style="color:red; display:none;"></i>';
        echo $html;
        exit;
    }

    /**
     * Save Coondition From popup
     * 
     * @param $module - string
     * @param $target_module - string
     * @param $row - array
     * @param $valid_opp - array
     * @param $automizer - object
     * @param $condition - object
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_save() {
        global $db;
        $condition = new bc_automizer_condition();
        $automizer = new bc_survey_automizer();
        $automizer->retrieve($_REQUEST['record_id']);
        $target_module = $automizer->flow_module;
        if ($target_module != $_REQUEST['module_name']) {
            $condition->module = $target_module . ':' . $_REQUEST['module_name'];
        } else {
            $condition->module = $target_module;
        }
        $condition->bc_workflow_id = $_REQUEST['record_id'];
        $condition->value_type = $_REQUEST['type_value'];
        $condition->operator = $_REQUEST['operator_value'];
        $condition->field = $_REQUEST['field_value'];
        $condition->value = $_REQUEST['condition_value'];
        $condition->filter_by = $_REQUEST['filter_by'];
        $sql = "Select bc_workflow_id from bc_automizer_condition where bc_workflow_id = '{$_REQUEST['record_id']}'";
        $ans = $db->query($sql);
        $count_num_rows = 0;
        while ($anser_rows = $db->fetchByAssoc($ans)) {
            $count_num_rows++;
        }
        $cnt = $count_num_rows;
        $condition->condition_order = $cnt + 1;
        $condition->save();
        $condition->load_relationship("bc_survey_automizer_bc_automizer_condition");
        $condition->bc_survey_automizer_bc_automizer_condition->add($_REQUEST['record_id']);
        echo "done";
        exit;
    }

    /**
     * Retrive All Records to relate with automizer in detailview
     * 
     * @param $app_list_strings - array
     * @param $ans - array
     * @param $condition_details - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_getConditionRecords() {
        global $db, $app_list_strings;

        $oAtomizerCondition = new bc_automizer_condition();
        $aConditions = $oAtomizerCondition->get_list('module', "bc_workflow_id = '{$_REQUEST['record_id']}'");
        $condition_details = array();
        foreach ($aConditions['list'] as $cnt => $aCondition) {
            if ($aCondition->module != $_REQUEST['base_module']) {
                $mod = split(":", $aCondition->module);
		$GLOBALS['log']->fatal('Data'.print_r($mod,true));
                $module_name = getModuleRelated($_REQUEST['base_module'], $mod);
            } else {
                $module_name = $_REQUEST['base_module'];
                }
            $fieldlist_value = array();
            $fieldlist_value = getFieldsModule($module_name);
            if ($aCondition->module == $_REQUEST['base_module']) {
                $module_list = $aCondition->module;
            } else {
                $mod_list = array();
                $action_mod = split(":", $aCondition->module);
                $mod_list = getRelationshipsModule($_REQUEST['base_module'], '');
                $module_list = $_REQUEST['base_module'] . ":" . $mod_list[$action_mod];
            }
            $condition_details[$cnt]['con_order'] = $aCondition->condition_order;
            $condition_details[$cnt]['module'] = $module_list;
            $condition_details[$cnt]['fields'] = $fieldlist_value[$aCondition->field];
            $condition_details[$cnt]['operator'] = $app_list_strings['operator_list'][$aCondition->operator];
            if ($aCondition->value_type == "Field") {
                $fields = getFieldsModule($_REQUEST['base_module']);
                $value_type = $fields[$aCondition->value];
            } else {
                $value_type = $aCondition->value;
                }
            $condition_details[$cnt]['value_type'] = $aCondition->value_type;
            $condition_details[$cnt]['value'] = $value_type;
            $condition_details[$cnt]['id'] = $aCondition->id;
            }
        echo json_encode($condition_details);
        exit;
    }

    /**
     * Delete Codition From List at DetailView
     * 
     * @param $db - array
     * @param $result - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_delete_record() {
        global $db;
        $query = "Update bc_automizer_condition set deleted = 1 where id='{$_REQUEST['record_id']}'";
        $result = $db->query($query);
        echo "done";
        exit;
    }

    /**
     * Edit Time Retrive Perticular Record
     * 
     * @param $db - array
     * @param $result - array
     * @param $condition_details - array
     * @param $cnt - int
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_edit_record() {
        global $db;
        $sql = "Select * from bc_automizer_condition where id='{$_REQUEST['record_id']}' AND deleted=0";
        $result = $db->query($sql);
        $condition_details = array();
        $cnt = 1;
        while ($row = $db->fetchByAssoc($result)) {
            $condition_details[$cnt]['con_order'] = $row['condition_order'];
            $rel_module = split(':', $row['module']);
            if (!empty($rel_module[1])) {
                $condition_details[$cnt]['module'] = $rel_module[1];
            } else {
                $condition_details[$cnt]['module'] = $row['module'];
            }
            $condition_details[$cnt]['fields'] = $row['field'];
            $condition_details[$cnt]['operator'] = $row['operator'];
            $condition_details[$cnt]['value_type'] = $row['value_type'];
            $condition_details[$cnt]['value'] = $row['value'];
            $condition_details[$cnt]['filter_by'] = $row['filter_by'];
            $query = "select flow_module from bc_survey_automizer where id='{$row['bc_workflow_id']}'";
            $ans = $db->query($query);
            $answer = $db->fetchByAssoc($ans);
            $condition_details[$cnt]['flow_module'] = $answer['flow_module'];
            $cnt += 1;
        }
        echo json_encode($condition_details);
        exit;
    }

    /**
     * Update Record While Click On Update Condition
     * 
     * @param $db - array
     * @param $ans - array
     * 
     * @author   Original Author Biztech Consultancy  
     */
    function action_update_records() {
        global $db;
        if ($_REQUEST['module_name'] == $_REQUEST['base_module']) {
            $module_nm = $_REQUEST['base_module'];
        } else {
            $module_nm = $_REQUEST['base_module'] . ":" . $_REQUEST['module_name'];
        }
        $sql = "Update bc_automizer_condition set value_type='{$_REQUEST['type_value']}' , operator='{$_REQUEST['operator_value']}',value='{$_REQUEST['condition_value']}',field='{$_REQUEST['field_value']}',module='{$module_nm}',filter_by='{$_REQUEST['filter_by']}' where id='{$_REQUEST['record_id']}'";
        $ans = $db->query($sql);
        echo "done";
        exit;
    }

}
