<?php

class OpportunitiesViewEdit extends ViewEdit {

    function OpportunitiesViewEdit(){
        parent::ViewEdit();
    }

    function preDisplay(){
        parent::preDisplay();

        $html .= '<div id="itemSelectorHolder"></div>';
        $this->ss->assign('DETAILS_HTML', $html);

        if(!empty($_GET['parent_type']) && !empty($_GET['lead_id'])){
            $lead = BeanFactory::getBean('Leads', $_GET['lead_id']);
            $this->bean->parent_type = $_GET['parent_type'];
            $this->bean->parent_id = $_GET['lead_id'];
            $this->bean->parent_name = $lead->name;
            $this->bean->lead_source = $lead->lead_source;
            $this->bean->team_id 			= $lead->team_id;
            $this->bean->team_set_id 		= $lead->team_id;
            $this->bean->assigned_user_id 	= $lead->assigned_user_id;
            $this->bean->assigned_user_name = $lead->assigned_user_name;
        }

        if(!empty($_GET['parent_type']) && !empty($_GET['contact_id'])){
            $contact = BeanFactory::getBean('Contacts', $_GET['contact_id']);
            $this->bean->parent_type = $_GET['parent_type'];
            $this->bean->parent_id = $_GET['contact_id'];
            $this->bean->parent_name = $contact->name;
            $this->bean->lead_source = $contact->lead_source;
            $this->bean->free_balance = $contact->free_balance;
            $this->bean->team_id 			= $contact->team_id;
            $this->bean->team_set_id 		= $contact->team_id;
            $this->bean->assigned_user_id 	= $contact->assigned_user_id;
            $this->bean->assigned_user_name = $contact->assigned_user_name;
        }

    }
    public function display(){
        global $beanFiles;
        //Get tax
        require_once($beanFiles['TaxRate']);
        $taxrate = new TaxRate();

        if(!isset($this->bean->id) || empty($this->bean->id)){

            //Get tax rate
            $this->ss->assign('TAX_RATE', $taxrate->get_default_taxrate_value() / 100);

            //Get none discount
            $this->bean->discount = '0';

            //Check NEWID
            $this->ss->assign('NEWID', '<span style="color:red;font-weight:bold"> New </span>');

            //                //Enroll For
            //                $enrollFor = (in_array($_GET['enroll'],array("Student", "Account"))) ? $_GET['enroll'] : 'Student';
            //                $this->ss->assign('ENROLL', '<input type="hidden" name="enroll" id="enroll" value="'.$enrollFor.'">');

            //User First Approach use to calculate commision
            $this->bean->username_approached = $this->bean->assigned_user_name;
            $this->bean->user_apprached_id = $this->bean->assigned_user_id;

        }else{
            if(!empty($this->bean->c_invoices_opportunities_1c_invoices_ida)){
                echo '
                <script type="text/javascript">
                alert("You do not have permission to edit");
                location.href=\'index.php?module=Opportunities&action=DetailView&record='.$this->bean->id.'\';
                </script>';
                die();
            }
            //Get tax rate
            $this->ss->assign('TAX_RATE', $this->bean->tax_rate);

            //Check NEWID
            $this->ss->assign('NEWID', '<span style="color:red;font-weight:bold"> '.$this->bean->oder_id.'</span>');

            //            $this->ss->assign('ENROLL', '<input type="hidden" name="enroll" id="enroll" value="'.$this->bean->enroll.'">');
        }

        $this->ss->assign('TAXRATE_OPTIONS', get_select_options_with_id($taxrate->get_taxrates(false), $this->bean->taxrate_id));
        $this->ss->assign('TAXRATE_JAVASCRIPT', $taxrate->get_taxrate_js());
        $this->ss->assign('NOW_DATE', $GLOBALS['timedate']->nowDate());
        $this->ss->assign('check_access', ACLController::checkAccess('Opportunities', 'import', true));
        parent::display();
    }
}

?>