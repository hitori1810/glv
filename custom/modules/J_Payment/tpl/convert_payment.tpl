<div id="diaglog_convert_payment" title="Convert Payment" style="display:none;">
    <table width="100%"  class="edit view">
        <tbody>
            <tr>
                <td width="50%">Convert :</td>
                <td width="50%">{html_options name="cp_convert_type" id="cp_convert_type" options=$convertTypeList selected=$convertType}
                </td>
            </tr>
            <tr>
                <td>Total Amount :</td>
                <td>{sugar_number_format var=$fields.payment_amount.value}</td>
            </tr>
            <tr>
                <td>Tuition Hours :<span style="color:red;">*</span></td>
                <td><input name="cp_tuition_hours" style="color: rgb(165, 42, 42);" size="10" id="cp_tuition_hours" type="text" value="{sugar_number_format var=$fields.tuition_hours.value}"></td>
            </tr>
            <tr>
                <td>Remain Amount :</td>
                <td>{sugar_number_format var=$fields.remain_amount.value}</td>
            </tr>
            <tr>
                <td>Remain Hours :<span style="color:red;">*</span></td>
                <td><input name="cp_remain_hours" style="color: rgb(165, 42, 42);" size="10" id="cp_remain_hours" type="text" value="{sugar_number_format var=$fields.remain_hours.value}"></td>
            </tr>
            <tr>
                <td style="colspan=2">
                    <input type="button" id="btn_submit_convert" value="Submit"/>
                    <span id = "submit_convert_loading" style="display:none;">Saving..</span>
                </td>
            </tr>
        </tbody>
    </table>
</div>