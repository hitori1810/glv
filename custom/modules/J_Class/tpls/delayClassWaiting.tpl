<div id="delay_class_waiting" title="Delay Class" style="display:none;">
        <input id="dl_student_id" type="hidden" value=""/>
        <input id="dl_situation_id" type="hidden" value=""/>
        <input class="dl_total_hour" id="dl_total_hour" type="hidden" value=""/>
        <input class="dl_total_amount" id="dl_total_amount" type="hidden" value=""/>

        <input class="dl_delay_hour" id="dl_delay_hour" type="hidden" value=""/>
        <input class="dl_delay_amount" id="dl_delay_amount" type="hidden" value=""/>

        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 1px 0;"></span>THAO TÁC DELAY HỌC VIÊN TRONG LỚP WAITING. Vui lòng làm theo từng bước bên dưới.
        <br> Họ Tên: <span style="font-weight:bold;" id='dl_student_name'></span><br>
        <br> Lớp waiting: <span style="font-weight:bold;" id='dl_class_code'></span><br>
        <br> Tổng số giờ:  <span class='dl_total_hour' style="font-weight:bold;"></span>  Tổng số tiền: <span class='dl_total_amount' style="font-weight:bold;"></span><br><br>
        <b>Bước 1:</b> Nhập số giờ muốn delay, số giờ từ 1 -> <span class='dl_total_hour'></span><br><br>
        <input id="dl_hour" class="form-control" type="number" size="3" value="1" min="1" max="10" />
        <br> <b>Bước 2:</b> Tính toán số dư của học viên<br><br>
        <table>
            <tbody>
                <tr>
                	<td width="20%"><span>Số giờ Delay: </span></td>
                	<td width="30%"><span class="dl_delay_hour" style="font-weight:bold;">0</span> </td>
                	<td width="20%"><span>Số tiền Delay: </span></td>
                	<td width="30%"><span class="dl_delay_amount" style="font-weight:bold;">0</span></td>
                </tr>
            </tbody>
        </table>
        <br>
        <b>Bước 3:</b> Nêu chọn ngày delay và nêu lý do Delay học viên<br>
       Ngày Payment delay:<span style="color:red;">*</span> <span class="dateTime" style="margin-right: 70px;margin-left: 10px;">
       <input disabled name="dl_from_date" size="10" id="dl_from_date" type="text" value="{$today}">
       <img border="0" src="custom/themes/default/images/jscalendar.png" alt="From Date" id="dl_from_date_trigger" align="absmiddle"></span> <br>
       Lý do:<span style="color:red;">*</span> <textarea cols="50" rows="2" style="margin-top: 5px;" id="dl_reason"></textarea><br><br>
        <b>Bước 4:</b>
        <br>Click <b>Save</b> : Số dư được chuyển vào Payment của học viên. Delay Payment có thể sử dụng để đăng ký một khóa học khác, refund hoặc transfer.<br>
        Click <b>Cancel</b> :Để hủy bỏ thao tác<br><br>
        <span id = "delay_save_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>