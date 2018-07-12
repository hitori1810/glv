<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/generic/SugarWidgets/SugarWidget.php');

class SugarWidgetSubPanelMovingClass extends SugarWidgetSubPanelTopButton
{
    var $module;
    var $title;
    var $access_key;
    var $form_value;
    var $additional_form_fields;
    var $acl;

    //TODO rename defines to layout defs and make it a member variable instead of passing it multiple layers with extra copying.

    /** Take the keys for the strings and look them up.  Module is literal, the rest are label keys
    */
    function SugarWidgetSubPanelMovingClass($module='', $title='', $access_key='', $form_value='')
    {
        global $app_strings;

        if(is_array($module)) {
            // it is really the class details from the mapping
            $class_details = $module;

            // If keys were passed into the constructor, translate them from keys to values.
            if(!empty($class_details['module']))
                $this->module = $class_details['module'];
            if(!empty($class_details['title']))
                $this->title = $app_strings[$class_details['title']];
            if(!empty($class_details['access_key']))
                $this->access_key = $app_strings[$class_details['access_key']];
            if(!empty($class_details['form_value']))
                $this->form_value = translate($class_details['form_value'], $this->module);
            if(!empty($class_details['additional_form_fields']))
                $this->additional_form_fields = $class_details['additional_form_fields'];
            if(!empty($class_details['ACL'])){
                $this->acl = $class_details['ACL'];
            }
        } else {
            $this->module = $module;

            // If keys were passed into the constructor, translate them from keys to values.
            if(!empty($title))
                $this->title = $app_strings[$title];
            if(!empty($access_key))
                $this->access_key = $app_strings[$access_key];
            if(!empty($form_value))
                $this->form_value = translate($form_value, $module);
        }
    }

    function &_get_form($defines, $additionalFormFields = null) {
        global $app_strings;
        global $currentModule;

        return true;

    }

    /** This default function is used to create the HTML for a simple button */
    function display($defines, $additionalFormFields = null) {
        global $currentModule, $beanList;
        $button = "";
        if($currentModule == 'Contacts'){
            $button = '<input class="button" type="button" onClick="window.open(\'index.php?module=J_StudentSituations&action=EditView&return_module=J_StudentSituations&return_action=DetailView&type=Moving%20Out&student_id='.$defines['focus']->id.'\',\'_blank\')" value="'.$GLOBALS['mod_strings']['LBL_MOVING_CLASS'].'" id="juniorMovingClass">';
        }
        return $button;
    }

    /**
    * get_subpanel_relationship_name
    * Get the relationship name based on the subapnel definition
    * @param mixed $defines The subpanel definition
    */
    function get_subpanel_relationship_name($defines) {
        $relationship_name = '';
        if(!empty($defines)) {
            $relationship_name = isset($defines['module']) ? $defines['module'] : '';
            $dataSource = $defines['subpanel_definition']->get_data_source_name(true);
            if (!empty($dataSource)) {
                $relationship_name = $dataSource;
                //Try to set the relationship name to the real relationship, not the link.
                if (!empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource])
                && !empty($defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship']))
                {
                    $relationship_name = $defines['subpanel_definition']->parent_bean->field_defs[$dataSource]['relationship'];
                }
            }
        }
        return $relationship_name;
    }

}
?>
