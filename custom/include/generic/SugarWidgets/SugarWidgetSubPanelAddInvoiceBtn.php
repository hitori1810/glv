<?php
    //Create Delete all session button in Sessions subpanel - 24/07/2014 - by MTN
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class SugarWidgetSubPanelAddInvoiceBtn extends SugarWidgetSubPanelTopSelectButton
    {
        //This default function is used to create the HTML for a simple button
        function display(&$widget_data)
        {
            $button2 .= '<input type="button" id="btn_add_invoice" class="btn_add_invoice button primary" value="'.translate('LBL_ADD_INVOICE').'"/>';
            return $button2;
        }
    }
?>
