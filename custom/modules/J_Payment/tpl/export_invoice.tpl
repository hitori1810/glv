{literal}
<style type="text/css">
.table-border td, .table-border th{
    border: 1px solid cadetblue;
    border-collapse:collapse;
    line-height: 18px;
    padding: 6px;
    vertical-align: middle;
    text-align: center;
}
 .table-border {
     border-collapse:collapse;
 }
.no-bottom-border {
     border-bottom: 0px solid cadetblue !important;
}
.no-top-border {
     border-top: 0px solid cadetblue !important;
}
</style>
{/literal}
{sugar_getscript file="custom/modules/J_Payment/js/export_invoice.js"}
<div class="diaglog_invoice" title="{$MOD.LBL_EXPORT_INVOICE}" style="display:none;">
<input type="hidden" id="eid_invoice_id" value="">
    <table width="100%">
    <thead>
            <tr style="height: 50px;">
                <td colspan="6"><b>{$MOD.LBL_PAYMENT_DATE_FROM}: </b> <span class="required"> *  </span> <span class="dateTime"><input disabled name="eid_from" size="10" id="eid_from" type="text" value="{$fields.payment_date.value}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="eid_from_trigger" align="absmiddle"></span> <b> {$MOD.LBL_TO} </b> <span class="dateTime"><input disabled name="eid_to" size="10" id="eid_to" type="text" value="{$today}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="eid_to_trigger" align="absmiddle"></span> </td>
                <td colspan="1"><input style="margin-left: 30px;" type="button" id="btn_eid_load_payment" value="{$MOD.LBL_LOAD_PAYMENT}"></input></td>
            </tr>
    </thead>
    </table>
    <table id="export_invoice_detail" class="export_invoice_detail table-border" width="100%" cellpadding="0" cellspacing="0" style="max-height: 350px; display: inline-block; width: 100%; overflow: auto;">
    <thead>
            <tr>
                <th width="3%" style="text-align: center;"><input type="checkbox" class="checkall_custom_checkbox" module_name="J_PaymentDetail" onclick="handleCheckBox($(this));calculateInvoice();"></th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_RECEIPT_DATE} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_PAYMENT_ID} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_PAYMENT_TYPE} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_BEFORE_DISCOUNT} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_DISCOUNT_SPONSOR} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_PAYMENT_AMOUNT} </th>
                <th width="10%" style="text-align: center;">{$MOD.LBL_STATUS_PAYMENT} </th>
            </tr>
    </thead>
        <tbody id="tbody_eid_detail">

        </tbody>
        <tfoot id="tfoot_eid_detail">
            <tr>
                <td colspan="8"><div style="float:left; margin-left: 20px;" class="selectedTopSupanel"><input type="hidden" id="J_PaymentDetail_checked_str" value=""></div></td>
            </tr>
        </tfoot>
    </table>
    <table width="100%">
        <input type="hidden" class="eid_total_before_discount" value="0">
        <input type="hidden" class="eid_total_discount_amount" value="0">
        <input type="hidden" class="eid_total_after_discount" value="0">
        <tr>
            <td width="25%" align="right">{$MOD.LBL_AMOUNT_BEFORE_DISCOUNT}:</td>
            <td width="15%" align="right" class="eid_total_before_discount">0</td>
            <td width="25%" align="right">{$MOD.LBL_VAT_INV_NO}: <span class="required"> * </span> </td>
            <td width="30%"><input style="text-transform: uppercase;" type="text" name="eid_invoice_no" id="eid_invoice_no" size="10" maxlength="100" value=""></td>
        </tr>
        <tr>
            <td width="25%" align="right">{$MOD.LBL_DISCOUNT_AMOUNT}:</td>
            <td width="15%" align="right" class="eid_total_discount_amount">0</td>
            <td width="25%" align="right">{$MOD.LBL_SERIAL}: <span class="required">  </span></td>
            <td width="30%"><input style="text-transform: uppercase;" type="text" name="eid_invoice_serial" id="eid_invoice_serial" size="10" maxlength="100" value=""></td>
        </tr>
        <tr>
            <td width="25%" align="right">{$MOD.LBL_FINAL_DISCOUNT}:</td>
            <td width="15%" align="right" class="eid_total_after_discount">0</td>
            <td width="25%" align="right">{$MOD.LBL_VAT_INV_DATE}: <span class="required"> * </span> </td>
            <td width="30%"><span class="dateTime"><input disabled name="eid_invoice_date" size="10" id="eid_invoice_date" type="text" value="{$today}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="eid_invoice_date_trigger" align="absmiddle"></span></td>
        </tr>
        <tr><td style="text-align: center" colspan="4"></td></tr>
        <tr>
                <td style="text-align: center" colspan="4">
                    <input type="button" class="button primary" action="save" id="btn_eid_save" value="{$MOD.LBL_SAVE}"></input>
                    <input type="button" id="btn_eid_cancel" value="{$MOD.LBL_CANCEL}"></input>
                    <span id = "eid_save_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
                </td>
        </tr>

    </table>
</div>
