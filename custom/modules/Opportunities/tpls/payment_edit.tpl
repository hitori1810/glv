{literal}
<style type="text/css">
    div.payment-item{
    width: 100px;
    display: inline;
    margin-right: 10px;
    margin-left: 5px;
    position: relative;
    top: 4px;
    padding: 5px;
    cursor: pointer;
    }
    td#payment_amount_holder *{
    vertical-align:middle;
    }
</style>
{/literal}
<table width="100%" border="0" cellspacing="1" cellpadding="0" id="LBL_EDITVIEW_PANEL1" class="yui3-skin-sam edit view panelContainer">
    <tbody>
        <tr>
            <td valign="top" id="payment_method_label" width="12.5%" scope="col">
                {$MOD.LBL_IS_COMPANY}:
            </td>
            <td valign="top" width="37.5%" colspan="1" nowrap="">
                <input type="hidden" name="is_company" value="0"> 
                <input type="checkbox" id="is_company" name="is_company" value="1" style="width: 1.5em; height: 1.5em;" tabindex="0">
            </td>
<!--            <td valign="top" id="remaining_label" width="12.5%" scope="col">
                {$MOD.LBL_PUBLISH_INVOICE_DATE}:
            </td>
            <td valign="top" width="37.5%" colspan="1">
                <span class="dateTime">
                    <input class="date_input" autocomplete="off" type="text" name="publish_invoice_date" id="publish_invoice_date" value="{$NOW_DATE}" size="11" maxlength="10">
                    <img src="themes/RacerX/images/jscalendar.png" alt="{$MOD.LBL_PUBLISH_INVOICE_DATE}" style="position:relative; top:6px" border="0" id="publish_invoice_date_trigger">
                </span>
                {literal}
                <script type="text/javascript">
                    Calendar.setup ({
                    inputField : "publish_invoice_date",
                    ifFormat : cal_date_format,
                    daFormat : cal_date_format,
                    button : "publish_invoice_date_trigger",
                    singleClick : true,
                    dateStr : "{/literal}{$NOW_DATE}{literal}",
                    startWeekday: 0,
                    step : 1,
                    weekNumbers:false
                    }
                    );
                </script>
                {/literal}
            </td> -->
        </tr>
        <tr>
            <td valign="top" id="payment_method_label" width="12.5%" scope="col">
            </td>
            <td valign="top" width="37.5%" colspan="3" nowrap="">
                <fieldset id="vat-info" style="width: 500px;">
                            <table width="50%">
                            <tbody>
                            <tr>
                            <td valign="top" width="12.5%" scope="col">{$MOD.LBL_COMPANY_NAME}</td>
                            <td valign="top" width="37.5%">
                            <input type="text" name="company_name_temp" class="sqsEnabled yui-ac-input" tabindex="0" id="company_name_temp" size="" value="" title="" autocomplete="off"><div id="EditView_account_name_results" class="yui-ac-container"><div class="yui-ac-content" style="display: none;"><div class="yui-ac-hd" style="display: none;"></div><div class="yui-ac-bd"><ul><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li></ul></div><div class="yui-ac-ft" style="display: none;"></div></div></div>
                            <input type="hidden" name="company_id_temp" id="company_id_temp" value="">
                            <span class="id-ff multiple">
                            <button type="button" name="btn_account_name" id="btn_account_name" tabindex="0" title="Select Account" class="button firstChild" value="Select Account"><img src="themes/default/images/id-ff-select.png"></button><button type="button" name="btn_clr_account_name" id="btn_clr_account_name" tabindex="0" title="Clear Account" class="button lastChild" onclick="SUGAR.clearRelateField(this.form, \'company_id_temp\', \'company_name_temp\');" value="Clear Account"><img src="themes/default/images/id-ff-clear.png"></button>
                            </span>
                            </td> 
                            <td valign="top" width="12.5%" scope="col">{$MOD.LBL_COMPANY_NAME_1}</td>                 
                            <td valign="top" width="37.5%">
                            <input type="text" name="company_name" id="company_name" size="30" value="{$fields.company_name.value}" title="">
                            </td>
                            </tr>
                            <tr>
                            <td valign="top" width="12.5%" scope="col">{$MOD.LBL_COMPANY_ADDRESS}:</td>
                            <td valign="top" width="37.5%">
                            <textarea cols="31" rows="4" name="company_address" id="company_address">{$fields.company_address.value}</textarea>
                            </td>
                            <td valign="top" width="12.5%" scope="col">{$MOD.LBL_TAX_CODE}:</td>
                            <td valign="top" width="37.5%">
                            <input type="text" name="tax_code" id="tax_code" size="30" value="{$fields.tax_code.value}" title="">
                            </td>
                            </tr>
                            </tbody>
                            </table>
                            </fieldset>
            </td>
        </tr>
        <tr>
        <td valign="top" width="100%" colspan="4" nowrap="">
<hr id="group_hr1">
</td>
        </tr>
        <tr>
            <td valign="top" id="payment_method_label" width="12.5%" scope="col">
                {$MOD.LBL_PAYMENT_METHOD}:
            </td>
            <td valign="top" width="37.5%" colspan="3" nowrap="">
                <label><input type="radio" name="payment_method" value="Cash" checked="checked" id="payment_method" title=""><div class="payment-item"><img src="custom/themes/default/images/cash-icon.png">&nbsp;<b>Cash</b></div></label>
                <label><input type="radio" name="payment_method" value="CreditDebitCard" id="payment_method" title=""><div class="payment-item"><img src="custom/themes/default/images/visa-icon.png">&nbsp;<b>Card</b></div></label>
                <label><input type="radio" name="payment_method" value="BankTranfer" id="payment_method" title=""><div class="payment-item"><img src="custom/themes/default/images/bank-icon.png">&nbsp;<b>Bank Transfer</b></div></label>
                <label><input type="radio" name="payment_method" value="Loan" id="payment_method" title=""><div class="payment-item"><img src="custom/themes/default/images/loan-icon.png">&nbsp;<b>Loan</b></div></label>
                <label><input type="radio" name="payment_method" value="Other" id="payment_method" title=""><div class="payment-item"><img src="custom/themes/default/images/other-icon.png">&nbsp;<b>Other</b></div></label>
            </td>
        </tr>
        <tr>
            <td valign="top" width="37.5%" colspan="3">
                <fieldset id="credit_info" style="min-height:50px;">
                    <legend><b> {$MOD.LBL_CREDIT_INFO} </b></legend>
                    <table>
                        <tbody><tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_CARD_TYPE}:    
                                </td>
                                <td valign="top">
                                    {html_options name="card_type" style = "width: 100%; height: 25px;" id="card_type" options=$fields.card_type.options selected=$fields.card_type.value}
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_CARD_NAME}:    
                                </td>
                                <td valign="top">
                                    <input accesskey="" tabindex="0" type="text" size="30" id="card_name" name="card_name" >
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_CARD_NUMBER}:    
                                </td>
                                <td valign="top">
                                    <input accesskey="" tabindex="0" type="text" size="30" id="card_number" name="card_number" >
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_EXPIRATION_DATE}:                      
                                </td>
                                <td valign="top">
                                    {html_options name="expiration_date" style = "width: 47%; height: 25px;" id="expiration_date" options=$fields.expiration_date.options selected=$fields.expiration_date.value}&nbsp;&nbsp;&nbsp;&nbsp;{html_options name="expiration_year" style = "width: 46%; height: 25px;" id="expiration_year" options=$fields.expiration_year.options selected=$fields.expiration_year.value}
                                </td>
                            </tr>
                            <tr>
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_CARD_AMOUNT}:                      
                                </td>
                                <td valign="top">
                                    <input class="currency" type="text" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" name="card_rate" id="card_rate"> %
                                    <input class="currency" type="text" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" name="card_amount" id="card_amount">
                                </td>
                            </tr>
                        </tbody></table>
                </fieldset>
                <fieldset id="loan_info" style="min-height:50px;">
                    <legend><b> {$MOD.LBL_LOAN_INFO} </b></legend>
                    <table>
                        <tbody>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_LOAN_TYPE}:    
                                </td>
                                <td valign="top">
                                    {html_options name="loan_type" style = "height: 25px;" id="loan_type" options=$fields.loan_type.options selected=$fields.loan_type.value}
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_BANK_NAME}:    
                                </td>
                                <td valign="top">
                                    {html_options name="bank_name" style = "height: 25px;" id="bank_name" options=$fields.bank_name.options selected=$fields.bank_name.value}
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_BANK_FEE_RATE}:    
                                </td>
                                <td valign="top" nowrap>
                                    <input type="text" name="bank_fee_rate" class="currency" id="bank_fee_rate"> %
                                    <input type="text" name="bank_fee_amount" class="currency" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" id="bank_fee_amount">
                                </td>
                            </tr>
                            <tr>                           
                                <td id="remaining_label" scope="col" valign="top" width="133px">
                                    {$MOD.LBL_LOAN_FEE_RATE}:    
                                </td>
                                <td valign="top" nowrap>
                                    <input type="text" name="loan_fee_rate" class="currency" id="loan_fee_rate"> %
                                    <input type="text" name="loan_fee_amount" class="currency" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial;" id="loan_fee_amount">
                                </td>
                            </tr>
                        </tbody></table>
                </fieldset>
            </td>
        </tr>
        <tr>
            <td valign="top" id="remaining_label" width="12.5%" scope="col">
                {$MOD.LBL_PAYMENT_DATE}:
            </td>
            <td valign="top" width="37.5%" colspan="3">
                <span class="dateTime">
                    <input class="date_input" autocomplete="off" type="text" name="payment_date" id="payment_date" value="{$NOW_DATE}" size="11" maxlength="10">
                    <img src="themes/RacerX/images/jscalendar.png" alt="Payment Date" style="position:relative; top:6px" border="0" id="payment_date_trigger">
                </span>
                {literal}
                <script type="text/javascript">
                    Calendar.setup ({
                    inputField : "payment_date",
                    ifFormat : cal_date_format,
                    daFormat : cal_date_format,
                    button : "payment_date_trigger",
                    singleClick : true,
                    dateStr : "{/literal}{$NOW_DATE}{literal}",
                    startWeekday: 0,
                    step : 1,
                    weekNumbers:false
                    }
                    );
                </script>
                {/literal}
            </td></tr>
        <tr>
        <tr>
            <td valign="top" id="remaining_label" width="12.5%" scope="col">
                {$MOD.LBL_REMAINING}:
            </td>
            <td valign="top" width="37.5%" colspan="3">
                <input accesskey="" tabindex="0" type="text" size="30" class="currency" name="remaining" id="remaining" readonly="" style="background-color: #DDDDDD; font-weight: bold; color: brown;">
            </td></tr>
        <tr>
            <td valign="top" id="payment_amount_label" width="12.5%" scope="col">
                {$MOD.LBL_PAYMENT_AMOUNT}:
            </td>
            <td id="payment_amount_holder" width="80%" colspan="4">
                <input accesskey="" tabindex="0"  type="text" size="30" class="currency" name="payment_amount" id="payment_amount" readonly="" style="background-color: #DDDDDD; font-weight: bold; color: brown;">
                <input accesskey="" tabindex="0"  type="text" size="30" class="currency" name="payment_deposit" id="payment_deposit" style="display: none; font-weight: bold; color: brown;">
                <input accesskey="" tabindex="0"  type="text" size="30" class="currency" name="payment_move" id="payment_move" readonly style="background-color: #DDDDDD; font-weight: bold; color: brown;">
                <label>
                    <label width="12.5%" style="background-color:#eee; color: #444; padding:.6em">{$MOD.LBL_PAYMENT_TYPE}: </label>
                    {html_options name="payment_type" style = "height: 25px;" id="payment_type" options=$fields.payment_type.options selected=$fields.payment_type.value}
                    <label width="30%" id="free_balance_label" style="background-color:#eee; color: #444; padding:.6em">{$MOD.LBL_FREE_BALANCE}: &nbsp;&nbsp;</label><span id="free_balance"></span>
                </label>
            </td>
        </tr>
        <tr>
            <td valign="top" id="payment_balance_label" width="12.5%" scope="col">
                {$MOD.LBL_PAYMENT_BALANCE}:
            </td>
            <td valign="top" width="37.5%" colspan="3">
                <input accesskey="" tabindex="0" type="text" size="30" class="currency" name="payment_balance" id="payment_balance" readonly="" style="background-color: #DDDDDD; font-weight: bold; color: brown;">
            </td>
        </tr>
        <tr>
            <td valign="top" id="payment_balance_label" width="12.5%" scope="col">
                {$MOD.LBL_ATTACHMENT}:
            </td>
            <td valign="top" width="37.5%" colspan="3">
                <input id="uploadfile" name="uploadfile" type="file" title="" size="30" maxlength="255"></td>
            </td>
        </tr>
    </tbody></table>