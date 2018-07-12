<?php
class J_PaymentDetailLogicHook{
    function displayButton($bean, $event, $arguments) {
        if ($_REQUEST['module']=='J_Payment' || $_REQUEST['module']=='Contracts'){
            require_once('custom/include/_helper/class_utils.php');
            $bean->custom_button = '<div style="display: inline-flex;">';

            if($bean->status == 'Unpaid'){
                $bean->custom_button .= '<button style="width: 100px;height: 46px;" type="button" payment_detail_id="'.$bean->id.'" payment_detail_amount="'.format_number($bean->payment_amount,0,0).'" class="pay" onclick="pay(this);"><img src="custom/themes/default/images/cash_icon.png" align="absmiddle" border="0"> &nbsp;'.$GLOBALS['mod_strings']['LBL_PAY'].' </button> &nbsp;&nbsp;';
            }
            //Button get invoice no
            if($bean->status == 'Paid'){
                if(empty($bean->invoice_number)){
                    if($_REQUEST['module']=='J_Payment')
                        $bean->custom_button .= '<button class="button primary" style="width: 100px;height: 46px;" type="button" payment_detail_id="'.$bean->id.'" class="button" onclick="ex_invoice(this);"><img src="index.php?entryPoint=getImage&amp;themeName=OnlineCRM-Green&amp;imageName=Print_Email.gif" align="absmiddle" border="0">  In</button>&nbsp;&nbsp;';
                    $bean->custom_button .= '<button style="width: 70px;height: 46px;" payment_method="'.$bean->payment_method.'" payment_detail_amount="'.format_number($bean->payment_amount,0,0).'" payment_date="'.$bean->payment_date.'" card_type="'.$bean->card_type.'" bank_type="'.$bean->card_type.'" invoice_no="'.$bean->invoice_number.'" serial_no="'.$bean->serial_no.'" onclick = \'edit_invoice(this)\' payment_detail_id="'.$bean->id.'"><img src="index.php?entryPoint=getImage&themeName=OnlineCRM-Green&imageName=edit_inline.png" align="absmiddle" border="0">  Sửa</button>';
                }else{
                    if($bean->is_release != 1 && checkDataLockDate($bean->payment_date) && ACLController::checkAccess('J_Payment', 'edit')){
                        $bean->custom_button .= '<button style="width: 70px;height: 46px;" payment_method="'.$bean->payment_method.'" payment_detail_amount="'.format_number($bean->payment_amount,0,0).'" payment_date="'.$bean->payment_date.'" card_type="'.$bean->card_type.'" bank_type="'.$bean->card_type.'" invoice_no="'.$bean->invoice_number.'" serial_no="'.$bean->serial_no.'" onclick = \'edit_invoice(this)\' payment_detail_id="'.$bean->id.'"><img src="index.php?entryPoint=getImage&themeName=OnlineCRM-Green&imageName=edit_inline.png" align="absmiddle" border="0">  Sửa</button>&nbsp;&nbsp;';
                        $bean->custom_button .= '<button style="width: 100px;height: 46px;" payment_detail_id="'.$bean->id.'" class="cancel_invoice" onclick = \'cancel_invoice("'.$bean->id.'")\'><img src="index.php?entryPoint=getImage&themeName=OnlineCRM-Green&imageName=delete_inline.png" align="absmiddle" border="0">  Hủy thu lại</button>';
                    }
                }
            }
            $bean->custom_button .= '</div>';

            //Button edit invoice no
            if (!empty($bean->invoice_number)){
                //Truong hop da release
                if ($bean->is_release == 1){
                    $sqlConfInvoice = "SELECT id, release_list  FROM j_configinvoiceno WHERE deleted <> 1 AND team_id = '{$bean->team_id}'";
                    $res     = $GLOBALS['db']->query($sqlConfInvoice);
                    $r       = $GLOBALS['db']->fetchByAssoc($res);
                    if($r['release_list'] != ""){
                        $releaseList = json_decode(html_entity_decode($r['release_list']),true);
                    }
                    else $releaseList = array();
                    //$reloadImage = ' &nbsp<a onclick="reloadReleaseOptions();" id="btn_reload_invoice" title="Reload List"><i style="font-size: 20px;cursor: pointer;" class="icon icon-refresh"></i></a>';
                    $saveImage = ' &nbsp<a onclick="saveInvoiceNo($(this).closest(\'tr\'))" id="btn_save_invoice" title="Save"><i style="font-size: 20px;cursor: pointer;" class="icon icon-download-alt"></i></a>';

                    $invoiceOptions = "";
                    if (in_array($bean->invoice_number,$releaseList))
                        $invoiceOptions .= "<option selected value='{$bean->invoice_number}'>{$bean->invoice_number}</option>";
                    foreach($releaseList as $invoiceNo){
                        if ($invoiceNo == $bean->invoice_number) continue;
                        $invoiceOptions .= "<option value='{$invoiceNo}'>{$invoiceNo}</option>";
                    }
                    $bean->invoice_number = "<div style='display: inline-flex;'>";
                    $bean->invoice_number .= "<select class='select_invoice_no' pmd_id = '{$bean->id}'>".$invoiceOptions."</select>";

                    $bean->invoice_number .= "<input type='hidden' class='pay_dtl_id' value='".$bean->id."'/>";
                    $bean->invoice_number .= $saveImage;
                    $bean->invoice_number .= $reloadImage;
                    $bean->invoice_number .= "</div>";

                }else{ //Truong hop binh thuong
                    //                    if(checkDataLockDate($bean->payment_date) && ($_REQUEST['module'] == 'J_Payment')){
                    //                        $cancelImage = ' &nbsp<a onclick="releaseInvoiceNo($(this).closest(\'tr\')); setTimeout(function(){autoCheckInvoiceReleased();}, 10000);" id="btn_release_invoice" title="Release VAT No"><i style="font-size: 20px;cursor: pointer;" class="icon icon-edit"></i></a>';
                    //                        $bean->invoice_number = "<div style='display: inline-flex;'><span class='span_invoice_no'>".$bean->invoice_number."</span>";
                    //                        $bean->invoice_number .= "<input type='hidden' class='pay_dtl_id' value='".$bean->id."'/>";
                    //                        $bean->invoice_number .= $cancelImage;
                    //                        $bean->invoice_number .= "</div>";
                    //                    }
                }
            }
        }
    }

    function handleBeforeSave($bean, $event, $arguments){
        if(($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save') || ($_POST['module'] == 'Import')){
            $_fee = 0;
            if($bean->payment_method == 'Card')
                $_fee  = floatval($GLOBALS['app_list_strings']['card_rate'][$bean->card_type]) * $bean->payment_amount / 100;
            elseif($bean->payment_method == 'Bank Transfer'){
                $_fee  = floatval($GLOBALS['app_list_strings']['bank_rate'][$bean->card_type]) * $bean->payment_amount / 100;
            }
            $bean->payment_method_fee = $_fee;

            $bean->numeric_vat_no  = $bean->invoice_number;
            if(empty($bean->invoice_number)){
                $bean->invoice_id     = '';
                $bean->numeric_vat_no = 0;
                $bean->serial_no      = '';
            }
            if(empty($bean->invoice_id)){
                $bean->invoice_number = '';
                $bean->numeric_vat_no = 0;
                $bean->serial_no      = '';
            }

            if($bean->is_release){
                $bean->is_release = 0;
                $res = $GLOBALS['db']->query("SELECT DISTINCT
                    IFNULL(j_paymentdetail.id,'') id,
                    IFNULL(j_paymentdetail.invoice_number,'') invoice_number,
                    IFNULL(j_paymentdetail.is_release,0) is_release,
                    IFNULL(l2.id,'') configinvoiceno_id,
                    IFNULL(l2.release_list,'') release_list
                    FROM j_paymentdetail
                    INNER JOIN
                    j_configinvoiceno l2 ON j_paymentdetail.team_id = l2.team_id
                    AND l2.deleted = 0
                    WHERE j_paymentdetail.id='{$bean->id}'");
                $r              = $GLOBALS['db']->fetchByAssoc($res);
                $releaseList    = json_decode(html_entity_decode($r['release_list']),true);
                if(array_key_exists($bean->id ,$releaseList)){
                    unset($releaseList[$bean->id]);
                    $wq1 = "UPDATE j_configinvoiceno SET release_list='" . json_encode($releaseList) . "' WHERE id='{$r['configinvoiceno_id']}'";
                    $GLOBALS['db']->query($wq1);
                }
            }

            //Update Student ID
            if (empty($bean->student_id) || (!empty($bean->student_id) && ($bean->fetched_row['payment_id'] != $bean->payment_id))) {
                $payment = BeanFactory::getBean('J_Payment', $bean->payment_id);
                $bean->student_id = $payment->contacts_j_payment_1contacts_ida;
            }

        }
    }

    function handleAfterSave($bean, $event, $arguments){
        require_once("custom/include/_helper/junior_revenue_utils.php");
        if(($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save')){
            //Update Sale Type
            $q1 = "SELECT IFNULL(SUM(j_paymentdetail.payment_amount), 0) payment_amount_paid,
            l1.payment_amount payment_amount,
            l1.sale_type sale_type
            FROM j_paymentdetail
            INNER JOIN
            j_payment l1 ON j_paymentdetail.payment_id = l1.id
            AND l1.deleted = 0
            WHERE j_paymentdetail.payment_id = '{$bean->payment_id}'
            AND j_paymentdetail.status = 'Paid' AND ((COALESCE(LENGTH(j_paymentdetail.invoice_number),0) > 0))
            AND j_paymentdetail.deleted = 0";
            $rs1 = $GLOBALS['db']->query($q1);
            $row = $GLOBALS['db']->fetchByAssoc($rs1);
            //Fix tạm bug này
            if(!empty($bean->invoice_number) && $bean->status == 'Paid' && $row['sale_type'] == 'Not set'){
                $sale_type = 'Not set';
                if($row['payment_amount_paid'] > 0)
                    $sale_type = checkSaleType($bean->payment_id, $bean->payment_date);

                $GLOBALS['db']->query("UPDATE j_payment SET sale_type = '$sale_type', sale_type_date='{$bean->payment_date}' WHERE id = '{$bean->payment_id}'");
            }
            //Update finish printing - bug edit bằng tay
            $GLOBALS['db']->query("UPDATE j_configinvoiceno SET finish_printing = 1 WHERE deleted = 0 AND pmd_id_printing='{$bean->id}'");
        }
        $student_id = $GLOBALS['db']->getOne("SELECT contacts_j_payment_1contacts_ida
            update_remain_last_date($bean->student_id);
            FROM contacts_j_payment_1_c WHERE contacts_j_payment_1j_payment_idb = '{$bean->payment_id}'");
    }
}
?>
