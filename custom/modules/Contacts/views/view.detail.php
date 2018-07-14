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

        $saintOptions = getSaintListOptions();
        
        $this->bean->full_student_name = $saintOptions[$this->bean->saint]." ".$this->bean->full_student_name;
        
        
        
        $this->ss->assign("GUARDIAN_NAME", $saintOptions[$this->bean->guardian_saint_1]." ".$this->bean->guardian_name);
        $this->ss->assign("GUARDIAN_NAME_2", $saintOptions[$this->bean->guardian_saint_2]." ".$this->bean->guardian_name_2);
        
        
        parent::display();
    }

    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);
                                                                                                       
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['opportunities']);            

                                                                                                         
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['documents']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['campaigns']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contracts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_contacts_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['quotes']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['cases']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);      
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads_contacts_1']); 

        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][0]);//hiding create
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']['top_buttons'][1]);//hiding select

        //hide activities and history
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts_c_invoices_1']);

        // Hide subpanel Leads
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);

        // Order Subpanel
        $subpanel->subpanel_definitions->layout_defs['subpanel_setup']['activities']['order'] = 1;             
        echo $subpanel->display();
    }

}
