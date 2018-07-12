<div>
    <fieldset id="split_payment_info" class="fieldset-border" style="width: 85%;">
    <table id="tbl_split_payment_1">
        <tbody>
            <tr><td id="payment_label_1" colspan="2"><b>{$MOD.LBL_PAY_DETAIL_1}:</b></td></tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_AMOUNT}: <span class="required">*</span> </td>
                <td nowrap>
                    <input class="currency" type="text" name="pay_dtl_amount[]" id="payment_amount_1" size="20" maxlength="26" value="{sugar_number_format var=$PAY_DTL_AMOUNT_1}" title="{$MOD.LBL_PAYMENT_AMOUNT}" tabindex="0"  style="font-weight: bold;">
                    </td>
            </tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_EXPECTED_DATE}: </td>
                <td nowrap>
            <span class="dateTime">
            <input readonly name="pay_dtl_invoice_date[]" size="10" id="pay_dtl_invoice_date_1" type="text" value="{$PAY_DTL_INVOICE_DATE_1}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="pay_dtl_invoice_date_1_trigger" align="absmiddle"></span>
                </td>
            </tr>
        </tbody>
    </table>
    <table id="tbl_split_payment_2">
        <tbody>
            <tr><td id="payment_label_2"  colspan="2"><b>{$MOD.LBL_PAY_DETAIL_2}:</b></td></tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_AMOUNT}: <span class="required">*</span> </td>
                <td nowrap>
                    <input class="currency" type="text" name="pay_dtl_amount[]" id="payment_amount_2" size="20" maxlength="26" value="{sugar_number_format var=$PAY_DTL_AMOUNT_2}" title="{$MOD.LBL_PAYMENT_AMOUNT}" tabindex="0"  style="font-weight: bold;"/>
                </td>

            </tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_EXPECTED_DATE}: </td>
                <td nowrap>
            <span class="dateTime">
            <input readonly name="pay_dtl_invoice_date[]" size="10" id="pay_dtl_invoice_date_2" type="text" value="{$PAY_DTL_INVOICE_DATE_2}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="pay_dtl_invoice_date_2_trigger" align="absmiddle"></span>
                </td>
            </tr>
        </tbody>
    </table>
    <table id="tbl_split_payment_3">
        <tbody>
            <tr><td id="payment_label_3" colspan="2"><b>{$MOD.LBL_PAY_DETAIL_3}:</b></td></tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_AMOUNT}: <span class="required">*</span> </td>
                <td nowrap>
                    <input class="currency" type="text" name="pay_dtl_amount[]" id="payment_amount_3" size="20" maxlength="26" value="{sugar_number_format var=$PAY_DTL_AMOUNT_3}" title="{$MOD.LBL_PAYMENT_AMOUNT}" tabindex="0"  style="font-weight: bold;">
                </td>
            </tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_EXPECTED_DATE}: </td>
                <td nowrap>
            <span class="dateTime">
            <input readonly name="pay_dtl_invoice_date[]" size="10" id="pay_dtl_invoice_date_3" type="text" value="{$PAY_DTL_INVOICE_DATE_3}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="pay_dtl_invoice_date_3_trigger" align="absmiddle"></span>
                </td>
            </tr>
        </tbody>
    </table>
        <table id="tbl_split_payment_4">
        <tbody>
            <tr><td id="payment_label_4" colspan="2"><b>{$MOD.LBL_PAY_DETAIL_4}:</b></td></tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_AMOUNT}: <span class="required">*</span> </td>
                <td nowrap>
                    <input class="currency" type="text" name="pay_dtl_amount[]" id="payment_amount_4" size="20" maxlength="26" value="{sugar_number_format var=$PAY_DTL_AMOUNT_4}" title="{$MOD.LBL_PAYMENT_AMOUNT}" tabindex="0"  style="font-weight: bold;">
                </td>
            </tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_EXPECTED_DATE}: </td>
                <td nowrap>
            <span class="dateTime">
            <input readonly name="pay_dtl_invoice_date[]" size="10" id="pay_dtl_invoice_date_4" type="text" value="{$PAY_DTL_INVOICE_DATE_4}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="pay_dtl_invoice_date_4_trigger" align="absmiddle"></span>
                </td>
            </tr>
        </tbody>
    </table>
        <table id="tbl_split_payment_5">
        <tbody>
            <tr><td id="payment_label_5" colspan="2"><b>{$MOD.LBL_PAY_DETAIL_5}:</b></td></tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_AMOUNT}: <span class="required">*</span> </td>
                <td nowrap>
                    <input class="currency" type="text" name="pay_dtl_amount[]" id="payment_amount_5" size="20" maxlength="26" value="{sugar_number_format var=$PAY_DTL_AMOUNT_5}" title="{$MOD.LBL_PAYMENT_AMOUNT}" tabindex="0"  style="font-weight: bold;">
                </td>
            </tr>
            <tr>
                <td valign="top" width="12.5%" scope="col">{$MOD.LBL_EXPECTED_DATE}: </td>
                <td nowrap>
            <span class="dateTime">
            <input readonly name="pay_dtl_invoice_date[]" size="10" id="pay_dtl_invoice_date_5" type="text" value="{$PAY_DTL_INVOICE_DATE_5}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="pay_dtl_invoice_date_5_trigger" align="absmiddle"></span>
                </td>
            </tr>
        </tbody>
    </table>
    </fieldset>
</div>
