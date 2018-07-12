<div id="dialog_sponsor" title="{$MOD.LBL_ADD_SPONSOR}" style="display:none;">
    <table id="table_sponsor" width="100%" class="list view">
        <thead>
            <tr><td colspan="5"><button style="float: right;" class="button primary" type="button" id="btnAddSponsor">{$MOD.LBL_ADD_CODE}</button></td></tr>
            <tr>
                <th width="20%" style="text-align:center">{$MOD.LBL_VOUCHER_CODE}</th>
                <th width="20%" style="text-align:center">{$MOD.LBL_TYPE}</th>    
                <th width="20%" style="text-align:center">{$MOD.LBL_AMOUNT}</th>
                <th width="20%" style="text-align:center">{$MOD.LBL_SPONSOR_PERCENT}</th>
                <th width="20%" style="text-align:center"></th>
                </tr>
                </thead>
<tbody id="tbodysponsor" style="height: 350px; width:100%; overflow:auto;">
        {$html_tpl_spon}
        {$html_spon}
        </tbody>
    </table><br>
    <table width="100%">
        <tr>
            <td width="45%" align="right">1. {$MOD.LBL_AMOUNT_BEFORE_DISCOUNT}:</td>
            <td width="10%" align="right" class="sponsor_amount_bef_discount"></td>
            <td width="35%" align="left" scope="col" id="sponsor_ratio"></td>
        </tr>
        <tr>
            <td width="45%" align="right">2. {$MOD.LBL_SPONSOR_AMOUNT}:</td>
            <td width="10%" align="right" class="total_sponsor_amount"></td>
            <td width="35%" align="left"></td>
        </tr>

        <tr>
            <td width="45%" align="right">3. {$MOD.LBL_SPONSOR_PERCENT}:</td>
            <td width="10%" align="right" class="total_sponsor_percent"></td>
            <input type="hidden" class="total_sponsor_percent_to_amount" value="">
            <td width="35%" align="left"></td>
        </tr>

        <tr>
            <td width="45%" align="right">4. {$MOD.LBL_TOTAL_SPONSOR} = (2) + (1 - 2)x(3):</td>
            <td width="10%" align="right" class="final_sponsor"></td>
            <input type="hidden" class="final_sponsor_percent" value="">
        </tr>
    </table>
</div>
<!--ADD SOME CSS -->
{literal}
<style type="text/css" id="jstree-stylesheet">
.multiselect-search{
    text-transform: uppercase;
}
::-webkit-input-placeholder { /* WebKit browsers */
    text-transform: none;
}
:-moz-placeholder { /* Mozilla Firefox 4 to 18 */
    text-transform: none;
}
::-moz-placeholder { /* Mozilla Firefox 19+ */
    text-transform: none;
}
:-ms-input-placeholder { /* Internet Explorer 10+ */
    text-transform: none;
}
</style>
{/literal}