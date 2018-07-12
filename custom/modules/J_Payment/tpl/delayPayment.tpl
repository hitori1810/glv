{sugar_getscript file="custom/modules/J_Payment/js/dropPayment.js"}
<div id="drop_payment_dialog" title="Drop Payment" style="display:none;">
        <input id="dl_student_id" type="hidden" value=""/>
        <input id="drop_amount_raw" type="hidden" value=""/>
        <input id="drop_hour_raw" type="hidden" value=""/>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>THAO TÁC DROP PAYMENT.<br><br>
        <br><b>Bước 1:</b> Chọn ngày drop: <span class="required">*</span>
        <span class="dateTime" style="margin-right: 70px;margin-left: 10px;">
            <input disabled name="dl_date" size="10" id="dl_date" type="text" value="{$today}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Drop Date" id="dl_date_trigger" align="absmiddle"></span>

        <br><br> <b>Bước 2:</b> Tính toán số dư của học viên<br>
        <table>
            <tbody>
                <tr>
                	 <td width="20%"> <span>Tổng số tiền đã đóng: </span></td>
                	 <td width="30%"> <span id="dl_total_amount" style="font-weight:bold;">0</span></td>
                </tr>
                <tr>
                    <td width="20%"><span>Tổng số tiền đã sử dụng: </span></td>
                    <td width="30%"><span id="dl_used_amount" style="font-weight:bold;">0</span> </td>
                </tr>
                <tr>
                	<td width="20%"><span>Tổng số tiền drop: </span></td>
                	<td width="30%"><span id="dl_drop_amount" style="font-weight:bold;">0</span>
                    <span style="font-weight:bold;"> &asymp; <span id="dl_drop_hour" style="font-weight:bold;">0</span> hours </span> </td>
                </tr>
            </tbody>
        </table>
        <br>
        <b>Bước 3: Nêu lý do Drop Payment <span class="required">*</span></b><br>
        <textarea cols="50" rows="2" style="margin-top: 5px;" id="dl_reason"></textarea><br><br>
        <b>Bước 4:</b>
        <span class="btn_drop_to_delay"><br>Click <b>Drop to Payment Delay</b> :Khoảng tiền drop sẽ chuyển vào Payment Delay của học viên. </span><br>
        <span class="btn_drop_to_revenue"><br>Click <b>Drop to Revenue</b> :Khoảng tiền drop sẽ chuyển vào doanh thu của center.<br></span>
        <br>Click <b>Cancel</b> :Để hủy bỏ thao tác.
        <br><br> <b>Chú ý:</b> Payment Delay này chỉ sử dụng số tiền<br><br>
        <span id = "save_drop_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>