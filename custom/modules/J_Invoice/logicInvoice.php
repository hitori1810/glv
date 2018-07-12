<?php
class logicInvoice{
    function deletedInvoice($bean, $event, $arguments){
        $GLOBALS['db']->query("UPDATE j_paymentdetail SET invoice_number='', serial_no='', numeric_vat_no=0, invoice_id=''  WHERE invoice_id = '{$bean->id}' AND deleted = 0");
    }
    function displayButton($bean, $event, $arguments) {
        if ($_REQUEST['module']=='J_Payment'){
            require_once('custom/include/_helper/class_utils.php');
            $bean->custom_button = '<div style="display: inline-flex;">';

            //Button get invoice no
            if(!empty($bean->name) && $bean->status == 'Paid'){
				//$bean->custom_button .= '<button class="button primary" style="width: 100px;height: 46px;" type="button" invoice_id="'.$bean->id.'" class="button" onclick="ex_invoice_2(this);"><img src="index.php?entryPoint=getImage&amp;themeName=OnlineCRM-Green&amp;imageName=Print_Email.gif" align="absmiddle" border="0"> '.$GLOBALS['mod_strings']['LBL_EXPORT_VAT_INV'].'</button>&nbsp;&nbsp;';
                if(checkDataLockDate($bean->invoice_date)){
                    $bean->custom_button .= '<button style="width: 70px;height: 46px;" id="btn_edit_invoice" invoice_id="'.$bean->id.'"><img src="index.php?entryPoint=getImage&themeName=OnlineCRM-Green&imageName=edit_inline.png" align="absmiddle" border="0"> '.$GLOBALS['mod_strings']['LBL_EDIT_BUTTON'].'</button>&nbsp;&nbsp;';
                    $bean->custom_button .= '<button style="width: 100px;height: 46px;" invoice_id="'.$bean->id.'" id="btn_cancel_invoice" onclick = \'ajaxCancelInvoice("'.$bean->id.'")\'><img src="index.php?entryPoint=getImage&themeName=OnlineCRM-Green&imageName=delete_inline.png" align="absmiddle" border="0"> '.$GLOBALS['mod_strings']['LBL_REMOVE'].'</button>';
                }
            }
        }
        $bean->custom_button .= '</div>';
    }
//
//    function handleBeforeSave($bean, $event, $arguments){
//
//    }
}
?>
