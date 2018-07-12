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

require_once('modules/Accounts/views/view.detail.php');

class CustomAccountsViewDetail extends AccountsViewDetail {

    function CustomAccountsViewDetail() {
        parent::AccountsViewDetail();
    }

    function display() {
        global $current_user;
        parent::display();
    }
    function _displaySubPanels(){
        require_once ('include/SubPanel/SubPanelTiles.php');
        $subpanel = new SubPanelTiles($this->bean, $this->module);

        /*Sub-Panel buttons hiding code*/
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']['top_buttons'][1]);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['accounts_c_payments_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['accounts_c_invoices_1']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['cases']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['accounts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['opportunities']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['leads']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['contacts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['campaigns']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['account_payments']);


        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_submission_accounts']);
        unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['bc_survey_accounts']);

        echo $subpanel->display();
    }

}

?>