{sugar_getscript file="custom/modules/J_Payment/js/dropPaymentAdult.js"}
<div id="drop_payment_dialog" title="Delay Payment" style="display:none;">
        <input id="drop_amount_raw" type="hidden" value="{sugar_number_format var=$fields.remain_amount.value}"/>
        <input id="drop_day_raw" type="hidden" value="{sugar_number_format var=$fields.remain_hours.value}"/>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>THAO TÁC DELAY PAYMENT.<br><br>
        <br><b>Bước 1:</b>Chọn ngày kết thúc học:<span class="required">*</span>
        <span class="dateTime">
            <input disabled name="dl_date" size="10" id="dl_date" type="text" value="{$today}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Delay Date" id="dl_date_trigger" align="absmiddle"></span>

        <br><br> <b>Bước 2:</b> Tính toán số dư của học viên<br>
        <table>
            <tbody>
                <tr>
                     <td width="20%"> <span>Tổng số tiền đã đóng: </span></td>
                     <td width="30%"> <span id="dl_total_amount" style="font-weight:bold;">{sugar_number_format var=$total_amount}</span></td>
                </tr>
                <tr>
                    <td width="20%"><span>Tổng số tiền đã sử dụng: </span></td>
                    <td width="30%"><span id="dl_used_amount" style="font-weight:bold;">{sugar_number_format var=$total_used}</span> </td>
                </tr>
                <tr>
                    <td width="20%"><span>Tổng số tiền delay: </span></td>
                    <td width="30%"><span id="dl_drop_amount" style="font-weight:bold;">{sugar_number_format var=$fields.remain_amount.value}</span>
                    <span style="font-weight:bold;"> &asymp; <span id="dl_drop_day" style="font-weight:bold;">{sugar_number_format var=$fields.remain_hours.value} Days </span> </td>
                </tr>
            </tbody>
        </table>
        <br>
        <b>Bước 3: Nêu lý do Drop Payment <span class="required">*</span></b><br>
        <textarea cols="50" rows="2" style="margin-top: 5px;" id="dl_reason"></textarea><br><br>
        <b>Bước 4:</b>
        <span class="btn_drop_to_delay"><br>Click <b>Delay to Payment</b> :Khoảng tiền drop sẽ chuyển vào Payment Delay của học viên. </span><br>
        <br>Click <b>Cancel</b> :Để hủy bỏ thao tác.
        <span id = "save_drop_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>

<div id="enable_delay_fee" title="Enable Fee" style="display:none;">
        <br><b></b> Return Date: <span class="required">*</span>
        <span class="dateTime" style="margin-left: 10px;">
            <input disabled name="edf_return_date" size="10" id="edf_return_date" type="text" value="{$today}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Return Date" id="edf_return_date_trigger" align="absmiddle"></span>

        <br><br>Click <b>Submit</b>: Để kích hoạt lại phí.<br>
        <br>Click <b>Cancel</b>: Để hủy bỏ thao tác.<br>
        <span id = "save_edf_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>