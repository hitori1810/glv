<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Original Author Biztech Co.
*/

require_once('modules/Contacts/views/view.detail.php');

class CustomContactsViewDetail extends ContactsViewDetail {

    /**
    * @see SugarView::display()
    *
    * We are overridding the display method to manipulate the portal information.
    * If portal is not enabled then don't show the portal fields.
    */
    function display() {
        global $current_user, $mod_strings, $app_strings;
        if(ACLController::checkAccess('bc_survey', 'view', true)){
            // Customize Survey
            echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/jquery.datetimepicker.css">';
            echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/survey.css">';
            echo '<script type="text/javascript" src="custom/include/js/survey_js/custom_code.js"></script>';
            require_once('custom/include/modules/Administration/plugin.php');
            $checkSurveySubscription = validateSurveySubscription();
            if (!$checkSurveySubscription['success']) {

            } else {
                $record_id = (!empty($_REQUEST['record'])) ? $_REQUEST['record'] : $this->bean->id;
                $module_name = (!empty($_REQUEST['module'])) ? $_REQUEST['module'] : $this->module;
                $send_survey = "<input  type='button' id='send_survey' onclick=\"create_SendSurveydiv('{$record_id}','{$module_name}');\" value='Send Survey'>";
                $send_poll   = "<input  type='button' id='send_poll' onclick=\"create_SendPolldiv('{$record_id}','{$module_name}');\" value='Send Poll'>";
                $this->ss->assign('send_survey', $send_survey);
                $this->ss->assign('send_poll', $send_poll);
            }
            //End Customize Survey
        }


        $this->ss->assign("PORTAL_ENABLED", true);

        $team_type = getTeamType($this->bean->team_id);
        $html_bt = "";

        // Add by Nguyen Tung 4-6-2018
        $html_bt .= '<input type="button" class="button" onclick="window.open(\'index.php?module=J_Payment&amp;action=EditView&amp;return_module=J_Payment&amp;return_action=DetailView&amp;payment_type=Enrollment&amp;student_id='.$this->bean->id.'\',\'_blank\')" value="'.$GLOBALS['mod_strings']['LNK_CREATE_ENROLLMENT'].'" id="juniorEnrollment">';

        // Get lisence info
        $lisence = getLisenceOnlineCRM();
        if($lisence['version'] != "Free" && $lisence['version'] != "Standard"){
            $html_bt .= '<input class="button" type="button" onClick="window.open(\'index.php?module=J_Payment&action=EditView&return_module=J_Payment&return_action=DetailView&payment_type=Moving%20Out&student_id='.$this->bean->id.'\',\'_blank\')" value="'.$GLOBALS['mod_strings']['LNK_NEW_MOVING'].'" id="juniorMoving">';    
        }

        if($this->bean->user_id && $this->bean->portal_active){
            $portalLoginPanel = '<input class="button" type="button" onClick="window.open(\'index.php?module=Contacts&action=LoginPortal&record='.$this->bean->id.'\',\'_blank\')" value="'.$GLOBALS['mod_strings']['LBL_LOGIN_PORTAL'].'" id="loginportal">';
            $portalResetPanel .= '<input type = "button" id = "reset_password" name = "reset_password" value = "'.$GLOBALS['mod_strings']['LBL_RESET_PASSWORD'].'" onClick = "ajaxResetPassword()">';
        }

        $html_bt .= '<input id="btn_view_change_log" title="View Change Log" class="button" onclick="open_popup(\'Audit\', \'600\', \'400\', \'&record='.$this->bean->id.'&module_name=Contacts\', true, false,  { \'call_back_function\':\'set_return\',\'form_name\':\'EditView\',\'field_to_name_array\':[] } ); return false;" type="button" value="'.$app_strings['LNK_VIEW_CHANGE_LOG'].'">';
        $this->ss->assign('custom_button',$html_bt);
        $this->ss->assign('portalLoginPanel',$portalLoginPanel);
        $this->ss->assign('portalResetPanel',$portalResetPanel);
        $this->ss->assign('team_type',$team_type);

        //display relationship
        $rs1 = $GLOBALS['db']->query("SELECT id, full_lead_name FROM leads WHERE contact_id = '{$this->bean->id}'");
        $row = $GLOBALS['db']->fetchByAssoc($rs1);

        $this->ss->assign('lead_convert_id',$row['id']);
        $this->ss->assign('lead_convert_name',$row['full_lead_name']);


        //Total Payment - Junior
        $sql1 = "SELECT DISTINCT
        COUNT(j_payment.id) j_payment__allcount
        FROM
        j_payment
        INNER JOIN
        contacts_j_payment_1_c l1_1 ON j_payment.id = l1_1.contacts_j_payment_1j_payment_idb
        AND l1_1.deleted = 0
        INNER JOIN
        contacts l1 ON l1.id = l1_1.contacts_j_payment_1contacts_ida
        AND l1.deleted = 0
        WHERE
        (((l1.id = '{$this->bean->id}')))
        AND j_payment.deleted = 0";
        $bt_delete = '<input title="Delete" accesskey="d" class="button" onclick="var _form = document.getElementById(\'formDetailView\'); _form.return_module.value=\'Contacts\'; _form.return_action.value=\'ListView\'; _form.action.value=\'Delete\'; if(confirm(\'Are you sure you want to delete this record?\')) SUGAR.ajaxUI.submitForm(_form);" type="submit" name="Delete" value="Delete" id="delete_button">';
        $this->ss->assign('DELETE',$bt_delete);

        //status color
        switch ($this->bean->status) {
            case "Waiting for class":
                $statusCss = "textbg_crimson";
                break;
            case "In Progress":     
                $statusCss = "textbg_blue";
                break;
            case "Delayed":    
                $statusCss = "textbg_black";
                break;
            case "OutStanding":     
                $statusCss = "textbg_orange";
                break;
            case "Finished":        
                $statusCss = "textbg_green";
                break;            
            default:              
                $statusCss = "textbg_green";
                break;
        } 
        $status = ' <span class="'.$statusCss.'"><b>'.$GLOBALS['app_list_strings']['contact_status_list'][$this->bean->contact_status].'<b></span>';
        $this->ss->assign('STATUS',$status);     

        //Generate School
        if(!empty($this->bean->j_school_contacts_1j_school_ida)){
            $school = BeanFactory::getBean('J_School',$this->bean->j_school_contacts_1j_school_ida);
            if(!empty($school->address_address_street)){
                $school->name .= " ({$school->address_address_street})";
            }
            $this->bean->j_school_contacts_1_name = $school->name;
        }
        parent::display();
    }

    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);

        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['c_classes_contacts_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['opportunities']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_payments_2']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_refunds_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_payments_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_payments_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_payments_2']);


        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_invoices_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['documents']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_contacts_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['quotes']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['cases']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);      
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_contacts_1']); 

        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][0]);//hiding create
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][1]);//hiding select

        //hide activities and history
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_invoices_1']);

        if(!ACLController::checkAccess('bc_survey', 'view', true)){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_contacts']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_submission_contacts']);

        }
        //Custom Atlantic
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_submission_contacts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['student_loyaltys']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contracts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contact_demo']);

        // Hide subpanel Leads
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);

        //Disable  transfer in Free/ Standard version
        $lisence = getLisenceOnlineCRM();
        if(in_array($lisence['version'], array("Free"))){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contact_pt']);
        }

        if(in_array($lisence['version'], array("Free","Standard"))){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_j_payment_1']['top_buttons'][1]);
        }


        // Order Subpanel
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['activities']['order'] = 1;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['history']['order'] = 2; 
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['campaigns']['order'] = 3;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['prospect_list_contacts']['order'] = 4;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contact_pt']['order'] = 5;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_j_payment_1']['order'] = 6;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_contacts_1']['order'] = 7;  
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contact_studentsituations']['order'] = 8;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['meetings_contacts']['order'] = 9;
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['student_j_gradebookdetail']['order'] = 10;  
        echo $subpanel->display();
    }

}
