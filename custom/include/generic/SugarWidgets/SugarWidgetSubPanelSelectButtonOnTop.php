<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class SugarWidgetSubPanelSelectButtonOnTop extends SugarWidgetSubPanelTopSelectButton
    {
        //This default function is used to create the HTML for a simple button
        function display(&$layout_def)
        {
            $button='';
            if($layout_def['module']=='J_PTResult'||$layout_def['module']=='Meetings'){
                $name = $layout_def['subpanel_definition']->name;
                $on_click='handleSelect_'.$name;
                $on_click_1='handleCreate_'.$name;
                $lbl_select=strtoupper('LBL_ADD_TO_'.$layout_def['subpanel_definition']->_instance_properties['group']);
                $lbl_create=strtoupper('LBL_CREATE_'.$layout_def['subpanel_definition']->_instance_properties['group']);
                $button .= '<input style="margin-left: 15px;" type="button"  value="'.translate($lbl_select,$layout_def['module']).'" onclick="'.$on_click.'();"/>';
                $button .= '<input style="margin-left: 15px;" type="button"   value="'.translate($lbl_create,$layout_def['module']).'" onclick="'.$on_click_1.'();"/>';
            }
            else{
                $on_click = 'handleSelect_'.$layout_def['module'];
                $name = strtoupper('BTN_TOP_'.$layout_def['module']);
                $button .= '<input style="margin-left: 15px;" type="button" value="'.translate($name).'" onclick="'.$on_click.'();"/>';

            }
            return  $button;
        }
    }
?>
