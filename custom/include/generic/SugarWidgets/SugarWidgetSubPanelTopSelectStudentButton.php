<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    /*********************************************************************************
    * By installing or using this file, you are confirming on behalf of the entity
    * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
    * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
    * http://www.sugarcrm.com/master-subscription-agreement
    *
    * If Company is not bound by the MSA, then by installing or using this file
    * you are agreeing unconditionally that Company will be bound by the MSA and
    * certifying that you have authority to bind Company accordingly.
    *
    * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
    ********************************************************************************/


    class SugarWidgetSubPanelTopSelectStudentButton extends SugarWidgetSubPanelTopSelectButton
    {

        //button_properties is a collection of properties associated with the widget_class definition. layoutmanager
        function SugarWidgetSubPanelTopSelectStudentButton($button_properties=array())
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
            $record_id = $layout_def['focus']->id;
            $class_type = $GLOBALS['db']->getOne("SELECT type FROM c_classes WHERE id = '$record_id'");
            if(ACLController::checkAccess('C_Classes', 'import', true))
                $bt_1 = '<input type="button" name="select_from_publ" id="select_from_publ" class="button" title="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_PUBL'].'" value="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_PUBL'].'">';
            if(ACLController::checkAccess('C_Classes', 'import', true))
                $bt_2 = '<input type="button" name="select_from_corp" id="select_from_corp" class="button" title="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_CORP'].'" value="'.$GLOBALS['mod_strings']['LBL_SELECT_FROM_CORP'].'">';
            if(ACLController::checkAccess('C_Classes', 'import', true) && $class_type=='Practice' )
                $bt_3 = '<input type="button" name="move_to_class" id="move_to_class" class="button" title="'.$GLOBALS['mod_strings']['LBL_MOVE_TO_CLASS'].'" value="'.$GLOBALS['mod_strings']['LBL_MOVE_TO_CLASS'].'">';
            $bt_4 = '<input type="submit" name="send_email" id="send_email" class="button" title="'.$GLOBALS['mod_strings']['LBL_SEND_EMAIL'].'" value="'.$GLOBALS['mod_strings']['LBL_SEND_EMAIL'].'">';
            $bt_5 = '<input type="submit" name="send_sms" id="send_sms" class="button" title="'.$GLOBALS['mod_strings']['LBL_SEND_SMS'].'" value="'.$GLOBALS['mod_strings']['LBL_SEND_SMS'].'">';
            $bt_6 = '
            <input type="submit" name="send_sms_student" id="send_sms_student" class="button" 
            onclick= ajaxSendSMSStudent()            
            title="'.$GLOBALS['mod_strings']['LBL_SEND_SMS_FREETEXT'].'" value="'.$GLOBALS['mod_strings']['LBL_SEND_SMS_FREETEXT'].'">';
            return $bt_1.$bt_2.$bt_3.$bt_4.$bt_5.$bt_6;
        }
    }
?>
