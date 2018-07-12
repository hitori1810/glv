<fieldset id="vat-corp-info" class="fieldset-border" style="width: 90%; display: none;">
<table>
    <tbody>
        <tr>
            <td valign="top" width="35%">{$MOD.LBL_ACCOUNT_NAME}:<span class="required">*</span></td>
            <td valign="top" width="65%" nowrap>
                <input type="text" size="15" name="account_name" id="account_name" class="yui-ac-input" value="{$fields.company_name.value}">
                <input type="hidden" name="account_id" id="account_id" value="{$fields.account_id.value}">
                <span class="id-ff multiple">
                <button type="button" name="btn_account_name" id="btn_account_name" class="button" class="button firstChild"><img src="themes/default/images/id-ff-select.png"></button>
                <button type="button" name="btn_clr_account_name" style="margin-left: 0px;" id="btn_clr_account_name" class="button lastChild"><img src="themes/default/images/id-ff-clear.png"></button>
                </span>
            </td>  
        </tr>
        <tr>      
            <td valign="top">
                {$MOD.LBL_ACCOUNT_NAME_VAT}: <span class="required">*</span>
            </td>                
            <td valign="top">
                <input type="text" name="company_name" id="company_name" size="25" value="{$fields.company_name.value}">
            </td>
        </tr>  
        <tr>     
            <td valign="top">{$MOD.LBL_ACCOUNT_TAX_CODE}: <span class="required">*</span></td>
            <td valign="top">
                <input type="text" name="tax_code" id="tax_code" size="25" value="{$fields.tax_code.value}">
            </td>
        </tr>
        <tr>
            <td valign="top">{$MOD.LBL_ACCOUNT_ADDRESS}: <span class="required">*</span></td>
            <td valign="top">
                <textarea cols="24" rows="2" name="company_address" id="company_address">{$fields.company_address.value}</textarea>
            </td>  
        </tr>   
    </tbody>
</table>
</fieldset>