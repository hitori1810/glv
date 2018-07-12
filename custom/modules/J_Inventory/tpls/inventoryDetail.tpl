{literal}
<style>

    .tr_template{
    text-align: center;
    }

</style>
{/literal}
<div id="example" class="content">
    <div class="row" style="margin-left: 5px;margin-top: 10px;">
        <div ><input type="button" id="btnAdd" class="btn btn-primary" value = 'Add Row' ></div>
    </div>
    <table id = 'tableBookLine'>
        <thead>
            <th align="center" style="width: 190px;"><b>{$MOD.LBL_PART}</b></th>
            <th align="center" style="width: 90px;"><b>{$MOD.LBL_QUANTITY}</b></th>
            <th align="center" style="width: 90px;"><b>{$MOD.LBL_PART_NO}</b></th>
            <th align="center" style="width: 80px;"><b>{$MOD.LBL_UNIT}</b></th>
            <th align="center" style="width: 190px;"><b>{$MOD.LBL_PRICE}</b></th>
            <th align="center" style="width: 190px;"><b>{$MOD.LBL_AMOUNT}</b></th>
            <th align="center" style="width: 190px;"><b>{$MOD.LBL_REMARK}</b></th>
            <td align="center" style="width: 100px;"></td>
        </thead>
        <tbody id = 'tbodyPT'>
            {$html_tpl}
            {$html}
        </tbody> 
    </table>

</div>
<table style="width: 1000px;">
    <tr><td colspan="7"><hr/><td></td></tr>
    <tr>
        <td align="left"><b>{$MOD.LBL_TOTAL_QUANTITY}</b></td>
        <td align="left">
            <input type="text" readonly="true" style="text-align: right;width: 60px;margin-left: 45px;" 
                name="total_quantity" id="total_quantity" class = 'input_readonly' value="{$fields.total_quantity.value}"/>
        </td>
        <td colspan="2" align="right"><b>{$MOD.LBL_TOTAL_AMOUNT}</b> </td>
        <td colspan="2" align="right">                    
            <input type="text" style="text-align: right;" class = 'input_readonly curency_readonly' readonly="true" id="total_amount" name="total_amount" value=" {sugar_currency_format var=$fields.total_amount.value round=0 decimals=0 }"/>
        </td> 
    </tr>
</table>