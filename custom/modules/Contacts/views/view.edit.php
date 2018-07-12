<?php

class ContactsViewEdit extends ViewEdit {

    function ContactsViewEdit(){
        parent::ViewEdit();
    }

    function preDisplay(){
        // Check license when creating new
        if(isset($_POST['isDuplicate']) || empty($this->bean->id)) {
            checkStudentLimit(true);
        }
        parent::preDisplay();
    }
    public function display()
    {
        global $beanFiles, $current_user;

        //Handle Convert Lead to Student
        $is_lead_convert = false;
        if(!empty($_REQUEST['return_id']) && $_REQUEST['return_module'] == 'Leads'){
            $lead = BeanFactory::getBean('Leads', $_REQUEST['return_id']);
            foreach ($lead->field_defs as $keyField => $aFieldName)
                $this->bean->$keyField = $lead->$keyField;

            //fix by Trung 2016.01.12
            $this->bean->j_school_contacts_1_name = $lead->j_school_leads_1_name;
            $this->bean->j_school_contacts_1j_school_ida = $lead->j_school_leads_1j_school_ida;

            $this->bean->id = '';
            $this->bean->assigned_user_id = $lead->assigned_user_id;
            $this->bean->assigned_user_name = get_assigned_user_name($this->bean->assigned_user_id);   
            $is_lead_convert = true;
        }
        $this->ss->assign('is_lead_convert', $is_lead_convert); 
                                         
        if($_POST['isDuplicate'] == 'true' || empty($this->bean->id)){
            $this->bean->contact_id         = translate('LBL_AUTO_GENERATE','Accounts');
            $this->bean->contact_status= 'Waiting for class' ;
        }   
        
        //Status Color
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
        
        
        if(!empty($this->bean->campaign_id) && empty($this->bean->campaign_name))
            $this->bean->campaign_id = '';

        if(!empty($this->bean->j_school_contacts_1j_school_ida) && empty($this->bean->j_school_contacts_1_name))
            $this->bean->j_school_contacts_1j_school_ida = '';

        $this->ss->assign('assigned_user_id_2', $this->bean->assigned_user_id);

        $this->ss->assign('birthdate_2', $this->bean->birthdate);

        $this->ss->assign('first_name_2', $this->bean->first_name);

        $this->ss->assign('last_name_2', $this->bean->last_name);

        $this->ss->assign('phone_mobile_2', $this->bean->phone_mobile);

        $html = '';
        if($this->bean->nationality == 'Việt Nam' || $this->bean->id=='' || !isset($this->bean->id)){
            $html .= '<label style="display: inline-block;"><input name="radio_national" value="Việt Nam" id="vietnam_national" type="radio" checked = "checked"> Việt Nam</label> &nbsp';
            $html .= '<label style="display: inline-block;"><input value="" name="radio_national" id="other_national" type="radio" > Other</label> &nbsp;';
            $html .= '<input type="text" style="display:none" name="nationality" id="nationality" size="30" maxlength="255" value="'.$this->bean->nationality.'">';
        } else {
            $html .= '<label style="display: inline-block;"><input name="radio_national" value="Việt Nam" id="vietnam_national" type="radio"> Việt Nam</label> &nbsp';
            $html .= '<label style="display: inline-block;"><input value="" name="radio_national" id="other_national" type="radio" checked = "checked"> Other</label> &nbsp;';
            $html .= '<input type="text" name="nationality" id="nationality" size="30" maxlength="255" value="'.$this->bean->nationality.'">';
        }

        //contact parent
        //company
        $com = '';
        $com .= '<input name="company_name" id="company_name" type="text"  value="">
        <input name="company_id" id="company_id" type="hidden"  value="">
        <button type="button" name="choose_company" id="choose_company" ><img src="themes/default/images/id-ff-select.png"></button>
        <button type="button" name="clr_company" id="clr_company" class="button lastChild" onclick="SUGAR.clearRelateField(this.form, \'company_name\', \'company_id\');"><img src="themes/default/images/id-ff-clear.png"></button>';

        $team_id     = $this->bean->team_id;
        if(empty($team_id)) $team_id = $GLOBALS['current_user']->team_id;
        $_type 		= $GLOBALS['db']->getOne("SELECT team_type FROM teams WHERE id = '{$team_id}'");

        //generate Lead Source
        $html_source = '<select title="'.translate('LBL_LEAD_SOURCE').'" style="width: 40%;" name="lead_source" id="lead_source">';
        foreach($GLOBALS['app_list_strings']['lead_source_list'] as $key => $value){
            $sel = ($this->bean->lead_source == $key) ? 'selected' : '';
            $html_source .= "<option $sel value='$key' json='".json_encode($campArr[$key], JSON_UNESCAPED_UNICODE )."'>$value</option>";
        }
        $html_source .= '</select>';
                                    
        $this->ss->assign('lead_source', $html_source);


        $this->ss->assign('NATIONALITY', $html);
        if(ACLController::checkAccess('J_Marketingplan', 'import', true))
            $this->ss->assign('is_role_mkt', '1');
        else
            $this->ss->assign('is_role_mkt', '1');
        //End: Generate Lead Source

        $this->ss->assign('team_type',$_type);
        $this->ss->assign('html_koc',$html_koc);
        $this->ss->assign('NATIONALITY', $html);  
        $this->ss->assign('COMPANY', $com);
        $this->ss->assign('phone_mobile', $this->bean->phone_mobile);                              

        //Navigate Form Base convert Lead -> Student
        if (!empty($_REQUEST['return_id']) &&  $_REQUEST['return_module'] =='Leads'){
            $this->ss->assign('lead_id', $_REQUEST['return_id']);
            $_REQUEST['return_module'] 	= 'Contacts';
            $_REQUEST['return_id'] 		= '';
        }    
        
        $this->bean->guardian_name = $this->bean->guardian_name?$this->bean->guardian_name:"";
        $default_parent = array(
            'guardian_name' => $this->bean->c_contacts_contacts_1_name?$this->bean->c_contacts_contacts_1_name:$this->bean->guardian_name,
            'c_contacts_contacts_1c_contacts_ida' => $this->bean->c_contacts_contacts_1c_contacts_ida?$this->bean->c_contacts_contacts_1c_contacts_ida:"",
            'c_contacts_contacts_1_name' => $this->bean->c_contacts_contacts_1_name?$this->bean->c_contacts_contacts_1_name:$this->bean->guardian_name,
            'phone_mobile' => $this->bean->phone_mobile?$this->bean->phone_mobile:"",
            'primary_address_street' => $this->bean->primary_address_street?$this->bean->primary_address_street:"",
            'email1' => $this->bean->email1?$this->bean->email1:"",
        );
        echo '<script language="javascript">
        var default_parent = '.(json_encode($default_parent)).';

        </script>';    
        
        parent::display();
    }
}