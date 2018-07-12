<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');


class SugarWidgetSubPanelMeetingSelectStudent extends SugarWidgetField
{

    //button_properties is a collection of properties associated with the widget_class definition. layoutmanager
    function SugarWidgetSubPanelMeetingSelectStudent($button_properties=array())
    {
        $this->button_properties=$button_properties;
    }

    public function getWidgetId()
    {
        return parent::getWidgetId() . '_select_button';
    }

    //widget_data is the collection of attributes associated with the button in the layout_defs file.
    function display($layout_def)
    {
        global $current_user;
        $record_id = $layout_def['fields']['ID'];
        $bt_1 = '';
        if(ACLController::checkAccess('C_Classes', 'import', true) && $layout_def['focus']->type_of_class != "Junior")
            $bt_1 = '<input type="button" name="select_from_publ" id="select_from_publ" class="button" title="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_PUBL'].'" value="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_PUBL'].'">';

        if((is_admin($current_user)) && $layout_def['focus']->type_of_class == "Junior"){
            $bt_1 = '<input type="button" name="select_from_situa" class_name ="'.$layout_def['focus']->ju_class_name.'" class_id ="'.$layout_def['focus']->ju_class_id.'" id="select_from_situa" class="button" title="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_SITUA'].'" value="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_SITUA'].'">'; 
        }


        if(ACLController::checkAccess('C_Classes', 'import', true) && $layout_def['focus']->type_of_class != "Junior")
            $bt_2 = '<input type="button" name="select_from_corp" id="select_from_corp" class="button" title="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_CORP'].'" value="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_CORP'].'">';
        else
            $bt_2 = '';
        return $bt_1.$bt_2;
    }
}