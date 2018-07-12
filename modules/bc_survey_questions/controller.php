<?php
/**
 * remove image type questions
 * 
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once('include/MVC/Controller/SugarController.php');
require_once('custom/include/utilsfunction.php');

class bc_survey_questionsController extends SugarController
{
    function action_EditView(){
        $this->view = 'noaccess';
    }
    function action_DetailView(){
        $this->view = 'noaccess';
    }
    function action_ListView(){
        $this->view = 'noaccess';
    }
    /**
     * remove image question
     *
     * @author     Original Author Biztech Co.
     * @return     string
     */
    function action_removeImageQuestionFromEdit(){
        global $db;
        $que_id=$_REQUEST['record'];
        $adv_type=$_REQUEST['adv_type'];
        $db->query("update bc_survey_questions set advance_type = '' where id = '{$que_id}'");
        echo 'done';
        exit;
    }

    function action_getfieldstype() {
        global $app_list_strings;
        $sync_field = $_REQUEST['sync_field'];
        $sync_module = $_REQUEST['module_name'];
        $result = array();
        // Retrieve Module Object
        $dom_Obj = BeanFactory::getBean($sync_module);
        // Retrieve Sync Field type
        $sync_field_type = $dom_Obj->field_defs[$sync_field]['type'];
        $options = $dom_Obj->field_defs[$sync_field]['options'];
        if($sync_field == "email1" || $sync_field == "email2" || $sync_field == "email_addresses_non_primary"){
            $sync_field_type = "email";
}
        if ($sync_field_type == 'name' || $sync_field_type == 'varchar' || $sync_field_type == 'phone') {
            $result = array('correct_que_type' => 'Textbox', 'correct_data_type' => 'Char');
        } else if ($sync_field_type == 'int') {
            $result = array('correct_que_type' => 'Textbox', 'correct_data_type' => 'Integer');
        } else if ($sync_field_type == 'email1' || $sync_field_type == 'email') {
            $result = array('correct_que_type' => 'Textbox', 'correct_data_type' => 'Email');
        } else if ($sync_field_type == 'text') {
            $result = array('correct_que_type' => 'CommentTextbox');
        } else if ($sync_field_type == 'enum') {
            $result = array('correct_que_type' => 'DrodownList', 'options' => $app_list_strings[$options]);
        } else if ($sync_field_type == 'multienum') {
            $result = array('correct_que_type' => 'MultiSelectList', 'options' => $app_list_strings[$options]);
        } else if ($sync_field_type == 'radioenum') {
            $result = array('correct_que_type' => 'RadioButton', 'options' => $app_list_strings[$options]);
        }
        echo json_encode($result);
        exit;
    }

    function action_get_requiredfields() {
        
        $sync_module = $_REQUEST['module_name'];
        $fields = getFieldsModule($sync_module);
        $allowed_field_type = array('name', 'text', 'int', 'varchar', 'enum', 'multienum', 'radioenum', 'phone', 'email');
        $dom_Obj = BeanFactory::getBean($sync_module);
        foreach ($dom_Obj->field_defs as $key => $field) {
            // check field is required or not
            if (in_array($field['type'], $allowed_field_type)) {
                $is_required_sync_field = $field['required'];
                $field_label = $fields[$field['name']];
                
                // check current question type matches to sync field type or not
                if ($is_required_sync_field == true) {
                    $result[$field['name']] = array('is_required' => $is_required_sync_field, 'label' => $field_label);
                }
            }
        }
        echo json_encode($result);
        exit;
    }

}
