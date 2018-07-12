<div id="dialog_loyalty" title="Loyalty Point" style="display:none;">
    <input type="hidden" id="loy_total_points" name="loy_total_points" value="">
    <input type="hidden" id="loy_points_to_spend" name="loy_points_to_spend" value="">
    <input type="hidden" id="loy_amount_to_spend" name="loy_amount_to_spend" value="">
    <input type="hidden" id="loy_loyalty_rate_out_value" name="loy_loyalty_rate_out_value" value="">

    <input type="hidden" id="loy_loyalty_rate_out_id" name="loy_loyalty_rate_out_id" value="">
    <input type="hidden" id="loy_loyalty_mem_level" name="loy_loyalty_mem_level" value="">
    <input type="hidden" id="loy_net_amount" name="loy_net_amount" value="">
    <table id="table_loyalty" width="100%">
        <tr>
            <td colspan="2" align="left" style="font-weight: bold;">
            Student: <span class="loy_student_name"></span> | Membership Level: <span class="loy_loyalty_mem_level"></span> have <span class="loy_total_points" style="font-weight: bold; color: #b90000;">0 points</span>.
            Enter how many points would student like to use:
            </td>
        </tr>
        <tr>
            <td align="right" style="font-weight: bold; font-size: 16px;">Loyalty Points:   </td>
            <td align="left"> <input type="text" id="loy_loyalty_points" name="loy_loyalty_points" size="6" maxlength="13" value="{$loy_loyalty_points}" title="Loyalty Points" style="font-weight: bold; text-align: center;"> <span style="font-weight: bold;">points (X </span><span class="loy_loyalty_rate_out_value" style="font-weight: bold;"></span><span style="font-weight: bold;">) </span></td>
        </tr>
        <tr>
            <td colspan="2" align="center" style="font-size: 13px;" class="error" id="loy_error"></td>
        </tr>
        <tr>
            <td colspan="2" align="left" style="font-weight: bold;">
            Max Points: <span class="loy_total_points" style="font-weight: bold; color: #b90000;" >0 points</span> / Points to Spend: <span class="loy_points_to_spend" style="font-weight: bold; color: #b90000;"></span>.
            </td>
        </tr>
    </table>
</div>