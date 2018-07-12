<?php
    //Create Delete all session button in Sessions subpanel - 24/07/2014 - by MTN
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
          
    class SugarWidgetSubPanelTeacherAllButton extends SugarWidgetSubPanelTopSelectButton
    {    
        //This default function is used to create the HTML for a simple button
        function display(&$widget_data)
        {   
            $bt_t = '
            <input type="submit" name="send_sms_teacher" id="send_sms_teacher" class="button" 
            onclick= ajaxSendSMSTeacher()            
            title="'.$GLOBALS['mod_strings']['LBL_SEND_SMS_FREETEXT'].'" value="'.$GLOBALS['mod_strings']['LBL_SEND_SMS_FREETEXT'].'">';
            return $bt_t;
        }
    }
?>
