<div id="diaglog_convert_to_360" title="Convert To 360" style="display:none;">
    <table width="100%"  class="edit view">
        <tbody>
            <tr>
                <td width="50%">Convert To Payment Type :</td>
                <td width="50%"><b>Delay & Cashholder</b></td>
            </tr>
            <tr>
                <td>Remain Amount :<span style="color:red;">*</span></td>
                <td><input name="ct_amount" class="currency" style="color: rgb(165, 42, 42);" size="20" id="ct_amount" type="text"></td>
            </tr>
            <tr>
                <td>Remain Days :<span style="color:red;">*</span></td>
                <td><input name="ct_days" class="currency" style="color: rgb(165, 42, 42);" size="10" id="ct_days" type="text"></td>
            </tr>
            <tr>
                <td>Unpaid Amount :<span style="color:red;">*</span></td>
                <td><input class="currency"  name="ct_unpaid_amount" style="color: rgb(165, 42, 42);" size="20" id="ct_unpaid_amount" type="text" value="{$ct_unpaid_amount}"></td>
            </tr>
            <tr>
                <td><b>Payment Date:</b> <span class="required">*</span></td>
                <td><span class="dateTime"><input disabled name="ct_date" size="10" id="ct_date" type="text" value="{$ct_date}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="ct_date_trigger" align="absmiddle"></span></td>
            </tr>
            <tr>
                <td style="colspan=2">
                    <input type="button" id="btn_submit_convert_360" value="Submit"/>
                    <span id = "submit_convert_loading" style="display:none;">Saving..</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>