<?php
    function html($obj){
        if($obj=='INV'){
            $html = '
            <link rel="stylesheet" href="modules/C_ConfigID/tpls/css/style_config.css"> 
            <script src="custom/include/javascripts/currency_word.js"></script> 
            <div class="entry-form">
            <form name="configinfo" action="index.php?module=Opportunities&action=save_detail&sugar_body_only=true" method="POST" id="configinfo"> 
            <table width="100%" border="0" cellpadding="4" cellspacing="0">
            <tr>
            <td colspan="2" align="right"><a href="#" id="close_ct">Close</a></td>
            </tr>
            <tr>
            <td>Invoice Date</td>
            <td><input class="date_input" autocomplete="off" type="text" name="invoice_date" id="invoice_date" value="" title="" tabindex="0" size="11" maxlength="10">
            <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="invoice_date_trigger">
            </span></td>
            <script type="text/javascript">
            Calendar.setup ({
            inputField : "invoice_date",
            ifFormat : cal_date_format,
            daFormat : cal_date_format,
            button : "invoice_date_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
            }
            );
            </script>
            </tr>
            <tr>
            <td style="padding:10px 0 10px 0;">For Company ?</td>
            <td>
            <input type="hidden" name="is_company" value="0"> 
            <input type="checkbox" id="is_company" name="is_company" value="1" style="width: 1.5em; height: 1.5em;" tabindex="0">
            </td>
            </tr>
            <tr>
            <td colspan="2">
            <fieldset id="vat-info">
            <table width="50%">
            <tbody>
            <tr>
            <td valign="top" width="12.5%" scope="col">Company Name:</td>                 
            <td valign="top" width="37.5%">
            <input type="text" name="company_name" id="company_name" size="30" value="" title="">
            </td>
            </tr>
            <tr>
            <td valign="top" width="12.5%" scope="col">Company Adress:</td>
            <td valign="top" width="37.5%">
            <input type="text" name="company_address" id="company_address" size="30" value="" title="">
            </td>
            </tr>
            <tr>
            <td valign="top" width="12.5%" scope="col">Tax Code:</td>
            <td valign="top" width="37.5%">
            <input type="text" name="tax_code" id="tax_code" size="30" value="" title="">
            </td>
            </tr>
            </tbody>
            </table>
            </fieldset>
            </td>
            </tr>
            <tr>
            <td>Notes</td>
            <td><textarea id="description" name="description" rows="3" cols="35" title="" tabindex="0"></textarea></td></tr>
            <tr>
            <td align="right">
            <input type="hidden" name="amount_in_words_invoice" id="amount_in_words_invoice" value="">
            <input type="hidden" name="action_save" id="action_save" value=""></td>
            <input type="hidden" name="record_use" id="record_use" value=""></td>
            </td>
            <td><div class="action_buttons">
            <input title="Save" accesskey="a" class="button primary" name="button" type="submit" value="Save" id="save_invoice">  <input  accesskey="l" class="button" type="button" name="button" value="Cancel" id="cancel_ct">  <div class="clear"></div></div></td>
            </tr>
            </table>
            </form>
            </div>';   
        }elseif($obj=='PAY'){
            $html = '
            <link rel="stylesheet" href="modules/C_ConfigID/tpls/css/style_config.css"> 
            <script src="custom/include/javascripts/currency_word.js"></script> 
            <div class="entry-payment-form">
            <form name="configinfo" action="index.php?module=Opportunities&action=save_detail&sugar_body_only=true" method="POST" id="configinfo" enctype="multipart/form-data"> 
            <table width="650px" border="0" cellpadding="4" cellspacing="0">
            <tbody>
            <tr>
            <td colspan="2" align="right"><a href="#" id="close_ct">Close</a></td>
            </tr>
            <tr>
            <td id="payment_method_label" scope="col" valign="top" width="12.5%">
            Payment Method:
            </td>
            <td colspan="3" width="37.5%">
            <label><input accesskey="" tabindex="0" name="payment_method" value="Cash" checked="checked" id="payment_method" title="" type="radio"><div style="width: 100px; display: inline; margin-right: 10px; margin-left: 5px; position: relative; top: 4px; padding: 5px; cursor: pointer;"><img src="custom/themes/default/images/cash-icon.png">&nbsp;<b>Cash</b></div></label>
            <label><input accesskey="" tabindex="0" name="payment_method" value="CreditDebitCard" id="payment_method" title="" type="radio"><div style="width: 100px; display: inline; margin-right: 10px; margin-left: 5px; position: relative; top: 4px; padding: 5px; cursor: pointer;"><img src="custom/themes/default/images/visa-icon.png">&nbsp;<b>Card</b></div></label>
            <label><input accesskey="" tabindex="0" name="payment_method" value="BankTranfer" id="payment_method" title="" type="radio"><div style="width: 100px; display: inline; margin-right: 10px; margin-left: 5px; position: relative; top: 4px; padding: 5px; cursor: pointer;"><img src="custom/themes/default/images/bank-icon.png">&nbsp;<b>Bank Transfer</b></div></label>
            <label><input accesskey="" tabindex="0" name="payment_method" value="Loan" id="payment_method" title="" type="radio"><div style="width: 100px; display: inline; margin-right: 10px; margin-left: 5px; position: relative; top: 4px; padding: 5px; cursor: pointer;"><img src="custom/themes/default/images/loan-icon.png">&nbsp;<b>Loan</b></div></label>
            <label><input accesskey="" tabindex="0" name="payment_method" value="Other" id="payment_method" title="" type="radio"><div style="width: 100px; display: inline; margin-right: 10px; margin-left: 5px; position: relative; top: 4px; padding: 5px; cursor: pointer;"><img src="custom/themes/default/images/other-icon.png">&nbsp;<b>Other</b></div></label>
            </td></tr>
            <tr>
            <td colspan="3" width="37.5%">
            <fieldset id="credit_info" style="min-height: 50px; display: none;">
            <legend><b> Credit Card Infomation </b></legend>
            <table>
            <tbody>
            <tr>                           
            <td id="remaining_label" scope="col" width="133px">
            Card type:    
            </td>
            <td valign="top">
            <select name="card_type" id="card_type">
            '.get_select_options($GLOBALS['app_list_strings']['card_type_payments_list'],'').'
            </select>
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" width="133px">
            Card Name:    
            </td>
            <td valign="top">
            <input accesskey="" tabindex="0" size="30" id="card_name" name="card_name" value="" type="text">
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" width="133px">
            Card number:    
            </td>
            <td valign="top">
            <input accesskey="" tabindex="0" size="30" id="card_number" name="card_number" value="" type="text">
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" width="133px">
            Expiration date:                      
            </td>
            <td valign="top">
            <select name="expiration_date" style="width: 47%; height: 25px;" id="expiration_date">
            '.get_select_options($GLOBALS['app_list_strings']['expiration_date_payment_list'],'').'
            </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <select name="expiration_year" style="width: 47%; height: 25px;" id="expiration_year">
            '.get_select_options($GLOBALS['app_list_strings']['year_list'],'').'
            </select>
            </td>
            </tr>
            <tr>
            <td id="remaining_label" scope="col" valign="top" width="133px">
            Card Rate / Amount:                      
            </td>
            <td valign="top">
            <input type="text" readonly="" style = "width: 45%; background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" name="card_rate" id="card_rate"> %
            <input type="text" readonly="" style = "width: 45%;background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" name="card_amount" id="card_amount">
            </td>
            </tr>
            </tbody>
            </table>
            </fieldset>
            <fieldset id="loan_info" style="min-height: 50px; display: none;">
            <legend><b> Loan Infomation </b></legend>
            <table>
            <tr>                           
            <td id="remaining_label" scope="col" valign="top" width="133px">
            Loan type:   
            </td>
            <td valign="top">
            <select name="loan_type" id="loan_type">
            '.get_select_options($GLOBALS['app_list_strings']['loan_type_list'],'').'
            </select>
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" valign="top" width="133px">
            Bank Name:    
            </td>
            <td valign="top">
            <select name="bank_name" id="bank_name">
            '.get_select_options($GLOBALS['app_list_strings']['bank_name_list'],'').'
            </select>
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" valign="top" width="133px">
            Bank Fee Rate:    
            </td>
            <td valign="top" nowrap>
            <input type="text" name="bank_fee_rate" class="currency" size ="3" id="bank_fee_rate"> %
            <input type="text" name="bank_fee_amount" class="currency" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" id="bank_fee_amount">
            </td>
            </tr>
            <tr>                           
            <td id="remaining_label" scope="col" valign="top" width="133px">
            Loan Fee Rate:    
            </td>
            <td valign="top" nowrap>
            <input type="text" name="loan_fee_rate" class="currency" size ="3" id="loan_fee_rate"> %
            <input type="text" name="loan_fee_amount" class="currency" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" id="loan_fee_amount">
            </td>
            </tr>
            </table>
            </fieldset>
            </td>
            </tr>
            <tr>
            <td id="payment_amount_label" scope="col" width="12.5%">
            Payment Amount:
            </td>
            <td colspan="3" valign="top" width="37.5%">
            <input name="payment_amount" readonly="readonly" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial; font-weight: bold; color: brown;" id="payment_amount" size="30" maxlength="26" value="" title="" tabindex="0" type="text">
            </td>
            </tr>
            <tr>
            <td id="payment_amount_label" scope="col" width="12.5%">
            Payment Date:
            </td>
            <td colspan="3" valign="top" width="37.5%">
            <span class="dateTime">
            <input class="date_input" readonly="readonly" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial; font-weight: bold; color: brown;" autocomplete="off" type="text" name="payment_date_text" id="payment_date_text" value="'.$GLOBALS['timedate']->nowDate().'" size="11" maxlength="10">
            <img src="themes/Sugar/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="payment_date_text_trigger">
            </span><script type="text/javascript">        
            Calendar.setup ({
            inputField : "payment_date_text",
            ifFormat : cal_date_format,
            daFormat : cal_date_format,
            button : "payment_date_text_trigger",
            singleClick : true,
            dateStr : "",
            startWeekday: 0,
            step : 1,
            weekNumbers:false
            }
            );</script>
            </td>
            </tr>
            <tr>
            <td id="payment_amount_label" scope="col" width="12.5%">
            Attachment:
            </td>
            <td colspan="3" valign="top" width="37.5%">
            <input id="uploadfile" name="uploadfile" type="file" title="" size="30" maxlength="255"></td>
            </tr>
            </tbody>
            <tr>
            <td align="right">
            <input type="hidden" name="amount_in_words_payment" id="amount_in_words_payment" value="">
            <input type="hidden" name="action_save" id="action_save" value="">
            <input type="hidden" name="payment_id" id="payment_id" value="">
            <input type="hidden" name="record_use" id="record_use" value="">
            </td>

            <td>
            <div class="action_buttons">
            <input title="Save" accesskey="a" class="button primary" name="button" type="submit" value="Save" id="save_payment">
            <input accesskey="l" class="button" type="button" name="button" value="Cancel" id="cancel_ct"> 
            </div>
            </td>
            </tr>
            </table>
            </form>
            </div>';
        }
        return $html;
    }
?>
