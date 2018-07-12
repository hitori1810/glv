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

require_once('modules/Leads/views/view.detail.php');

class CustomLeadsViewDetail extends LeadsViewDetail {
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);                                              

        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_c_payments_1']);            
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_contacts_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_leads_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_submission_leads']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['lead_demo']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['lead_studentsituations']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['j_class_leads_1']);
        if($this->bean->status == 'Converted' && !empty($this->bean->contact_id)){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['lead_payments']);   
        }

        if(!ACLController::checkAccess('bc_survey', 'view', true)){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_submission_leads']);
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_leads']);
        }

        //Add by Tung Bui - Check lisence     
        $lisence = getLisenceOnlineCRM();  
        if($lisence['version'] == "Free"){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['lead_pt']);
        }


        echo $subpanel->display();
    }

    function display() {
        //Custom Survey
        global $current_user, $app_list_strings;
        if(ACLController::checkAccess('bc_survey', 'view', true)){
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
        }
        //END Custom Survey


        //get student name
        if(isset($this->bean->contact_id) && !empty($this->bean->contact_id)){
            $contacts = new Contact();
            $contacts->retrieve($this->bean->contact_id);
            $this->ss->assign('Contacts', $contacts);
        }                                                                      
        //Display parent
        $this->bean->load_relationship('c_contacts_leads_1');
        $parent = reset($this->bean->c_contacts_leads_1->getBeans());
        if(!empty($parent)){
            $par = '<a href="index.php?module=C_Contacts&offset=2&stamp=1443079476077587300&return_module=C_Contacts&action=DetailView&record='.$parent->id.'" TARGET=_blank>'.$parent->name.'</a>';
            $par .= ' ('.$this->bean->contact_rela.')';
            $this->ss->assign('PARENT',$par);
        }

        //Display company
        $this->bean->load_relationship('accounts');
        $corporates = reset($this->bean->accounts->getBeans());
        $com = '<a href="index.php?module=Accounts&offset=3&stamp=1445239126024798600&return_module=Accounts&action=DetailView&record='.$corporates->id.'" TARGET=_blank>'.$corporates->name.'</a>';
        $this->ss->assign('COMPANY',$com);

        $lead_type = "Junior";
        $this->ss->assign('team_type',$lead_type);

        //Button Convert To Student
        if(empty($this->bean->contact_id))
            $btn_convert_2 = '<input class="button" name="CONVERT_STUDENT_BTN" id="convert_student_button" title="'.translate('LBL_CONVERTLEAD','Leads').'" onclick="var _form = document.getElementById(\'formDetailView\');_form.return_module.value=\'Leads\'; _form.return_action.value=\'DetailView\'; _form.return_id.value=\''.$this->bean->id.'\';_form.module.value=\'Contacts\';_form.action.value=\'EditView\';_form.submit();" type="button" value="'.translate('LBL_CONVERTLEAD','Leads').'">';
        else $btn_convert_2 = '';
        $this->ss->assign('btn_convert_2',$btn_convert_2);

        //Generate School
        if(!empty($this->bean->j_school_leads_1j_school_ida)){
            $school = BeanFactory::getBean('J_School',$this->bean->j_school_leads_1j_school_ida);
            if(!empty($school->address_address_street)){
                $school->name .= " ({$school->address_address_street})";
            }
            $this->bean->j_school_leads_1_name = $school->name;
        }

        //Custom Quickedit
        if(ACLController::checkAccess('J_Payment', 'import', true)){
            $user_list = $GLOBALS['db']->fetchArray("SELECT DISTINCT IFNULL(users.id,'') primaryid ,CONCAT(IFNULL(users.last_name,''),' ',IFNULL(users.first_name,'')) users_full_name FROM users INNER JOIN teams l1 ON users.default_team=l1.id AND l1.deleted=0 WHERE (((l1.id='{$this->bean->team_id}' ) AND ((users.is_admin is null OR users.is_admin='0') ) AND (users.status = 'Active' ) AND (users.for_portal_only = '0' ) AND (users.portal_only = '0' ) )) AND users.deleted=0 ORDER BY users.last_name, users.first_name ASC");
            $user_arr = array($current_user->id => $current_user->name);
            foreach($user_list as $key => $user)
                $user_arr[$user['primaryid']] = $user['users_full_name'];

            $assigned_user_idQ = '<img id="loading_assigned_user_id" src=\'custom/include/images/fb_loading.gif\' style=\'width:15px; height:15px; display:none;\'/>
            <div id="panel_1_assigned_user_id"><label id="label_assigned_user_id">'.$this->bean->assigned_user_name.'</label>&nbsp&nbsp<a id="btnedit_assigned_user_id" title="Edit" title="Admin Edit"><i style="font-size: 20px;cursor: pointer;" class="icon icon-edit"></i></a></div>
            <div id="panel_2_assigned_user_id" style="display: none;"><select id="value_assigned_user_id">'.get_select_options($user_arr, $this->bean->assigned_user_id).'</select>
            &nbsp&nbsp<a title="Save" id="btnsave_assigned_user_id"><i style="font-size: 20px;cursor: pointer;" class="icon icon-download-alt"></i></a> <a title="Cancel" id="btncancel_assigned_user_id"><i style="font-size: 20px;cursor: pointer;" class="icon icon-remove"></i></a></div>';
        }else
        $assigned_user_idQ    = '<label id="label_assigned_user_id">'.$this->bean->assigned_user_name.'</label>';


        $potentialQ = '<img id="loading_potential" src=\'custom/include/images/fb_loading.gif\' style=\'width:15px; height:15px; display:none;\'/>
        <div id="panel_1_potential"><label id="label_potential">'.$app_list_strings['level_lead_list'][$this->bean->potential].'</label>&nbsp&nbsp<a id="btnedit_potential" title="Edit" title="Admin Edit"><i style="font-size: 20px;cursor: pointer;" class="icon icon-edit"></i></a></div>
        <div id="panel_2_potential" style="display: none;"><select id="value_potential">'.get_select_options($GLOBALS['app_list_strings']['level_lead_list'], $this->bean->potential).'</select>
        &nbsp&nbsp<a title="Save" id="btnsave_potential"><i style="font-size: 20px;cursor: pointer;" class="icon icon-download-alt"></i></a> <a title="Cancel" id="btncancel_potential"><i style="font-size: 20px;cursor: pointer;" class="icon icon-remove"></i></a></div>';

        $this->ss->assign('potentialQ',$potentialQ);
        $this->ss->assign('assigned_user_idQ',$assigned_user_idQ);

        //Display status
        $status = $GLOBALS['app_list_strings']['lead_status_dom'][$this->bean->status];
        switch ($status) {
            case "New":
                $statusCss = "textbg_green";
                break;
            case "Assigned":     
                $statusCss = "textbg_bluelight";
                break;
            case "In Process":    
                $statusCss = "textbg_blue";
                break;
            case "Converted":     
                $statusCss = "textbg_red";
                break;
            case "PT/Demo":       
                $statusCss = "textbg_violet";
                break;
            case "Recycled":      
                $statusCss = "textbg_orange";
                break;
            case "Dead":          
                $statusCss = "textbg_black";
                break;
            default:              
                $statusCss = "textbg_green";
                break;
        }  

        $this->ss->assign('STATUS', $status);
        $this->ss->assign('STATUS_CSS', $statusCss);

        parent::display();
    }    
}
