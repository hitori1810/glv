<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class CampaignsViewDetail extends ViewDetail {
                
    function _displaySubPanels(){
       
        //We want to display subset of available, panels, so we will call subpanel
        //object directly instead of using sugarview.  
        $GLOBALS['focus'] = $this->bean;
        require_once('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
        //get available list of subpanels

        if($this->bean->campaign_type == 'SMS'){
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['tracked_urls']);
        }
        $alltabs=$subpanel->subpanel_definitions->get_available_tabs();
        if (!empty($alltabs)) {
            //iterate through list, and filter out all but 3 subpanels
            $availabelTab = array(
                'prospectlists',
                'emailmarketing',
                'tracked_urls',   
                'leads',  
                'contacts',  
            );
            foreach ($alltabs as $key=>$name) {
                if(!in_array($name, $availabelTab)){
                    $subpanel->subpanel_definitions->exclude_tab($name);
                }
            }
            //only show email marketing subpanel for email/newsletter campaigns
            if ($this->bean->campaign_type != 'Email' && $this->bean->campaign_type != 'NewsLetter' && $this->bean->campaign_type != 'SMS' /* Add by Hai Nguyen For SMS*/) {
                //exclude emailmarketing subpanel if not on an email or newsletter campaign
                $subpanel->subpanel_definitions->exclude_tab('emailmarketing');
                // Bug #49893  - 20120120 - Captivea (ybi) - Remove trackers subpanels if not on an email/newsletter campaign (useless subpannl)
                $subpanel->subpanel_definitions->exclude_tab('tracked_urls');
            }                       
        }
        //show filtered subpanel list
        echo $subpanel->display();  
    }
    function display() {
        global $app_list_strings; 
        $this->ss->assign('APP_LIST', $app_list_strings);

        if (isset($_REQUEST['mode']) && $_REQUEST['mode']=='set_target'){
            require_once('modules/Campaigns/utils.php');
            //call function to create campaign logs
            $mess = track_campaign_prospects($this->bean);

            $confirm_msg = "var ajax_C_LOG_Status = new SUGAR.ajaxStatusClass(); 
            window.setTimeout(\"ajax_C_LOG_Status.showStatus('".$mess."')\",1000); 
            window.setTimeout('ajax_C_LOG_Status.hideStatus()', 1500); 
            window.setTimeout(\"ajax_C_LOG_Status.showStatus('".$mess."')\",2000); 
            window.setTimeout('ajax_C_LOG_Status.hideStatus()', 5000); ";
            $this->ss->assign("MSG_SCRIPT",$confirm_msg);

        }         

        if (($this->bean->campaign_type == 'Email') || ($this->bean->campaign_type == 'NewsLetter' )) {
            $this->ss->assign("ADD_BUTTON_STATE", "submit");
            $this->ss->assign("TARGET_BUTTON_STATE", "hidden");
        } else {
            $this->ss->assign("ADD_BUTTON_STATE", "hidden");
            $this->ss->assign("DISABLE_LINK", "display:none");
            $this->ss->assign("TARGET_BUTTON_STATE", "submit");
        }

        $currency = new Currency();
        if(isset($this->bean->currency_id) && !empty($this->bean->currency_id))
        {
            $currency->retrieve($this->bean->currency_id);
            if( $currency->deleted != 1){
                $this->ss->assign('CURRENCY', $currency->iso4217 .' '.$currency->symbol);
            }else {
                $this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());    
            }
        }else{
            $this->ss->assign('CURRENCY', $currency->getDefaultISO4217() .' '.$currency->getDefaultCurrencySymbol());
        }

        parent::display();
                            

    }
}
