<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class SugarWidgetSubPanelCheckbox extends SugarWidgetField{
    function displayHeaderCell(&$layout_def){
        $module = $layout_def['module'];
        if(empty($layout_def['module']))
            $module = $layout_def['subpanel_module'];
        return '<input type="checkbox" class="checkall_custom_checkbox" module_name="'.$module.'" onclick="handleCheckBox($(this));"/>';
    }

    function displayList($layout_def){
        $field_value = $layout_def['fields'][$layout_def['field_value']];
        $module = $layout_def['module'];
        if(empty($layout_def['module']))
            $module = $layout_def['subpanel_module'];
        return '<input type="checkbox" class="custom_checkbox" module_name="'.$module.'" onclick="handleCheckBox($(this));" value="'.$field_value.'"/>';
    }
}
?>
