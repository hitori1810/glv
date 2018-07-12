{sugar_getscript file="custom/modules/J_Class/js/handleRemoveFromClass.js"}
<div id="remove_from_class_dialog" title="Remove From Class" style="display:none;">
        <input id="rfc_situation_id" type="hidden" value=""/>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span>THAO TÁC XÓA HỌC VIÊN RA KHỎI LỚP. Vui lòng làm theo từng bước bên dưới.<br>
        <br> Họ Tên: <span style="font-weight:bold;" id='rfc_student_name'></span><br>
        <br> Thời gian học trong lớp này: <span id='rfc_start' style="font-weight:bold;"></span> - <span style="font-weight:bold;" id='rfc_end'></span><br>
        <br> Tổng số giờ học:  <span id='rfc_total_hour' style="font-weight:bold;"></span><br>
        <br> Lịch học của lớp: <span id='rfc_schedule'>{$SCHEDULE}</span>
        <br><b>Bước 1:</b> Chọn khoảng thời gian xóa khỏi lớp<br><br>
        Remove từ: <span style="color:red;">*</span>
        <span class="dateTime" style="margin-right: 70px;margin-left: 10px;">
            <input disabled name="rfc_from_date" size="8" id="rfc_from_date" type="text" value="{$next_session_date}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="From Date" id="rfc_from_date_trigger" align="absmiddle"></span>
        Remove đến: <span style="color:red;">*</span>
        <span class="dateTime" style="margin-left: 10px;">
            <input disabled name="rfc_to_date" size="8" id="rfc_to_date" type="text" value="">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="rfc_to_date_trigger" align="absmiddle"></span>

        <br><br> <b>Bước 2:</b> Kiểm tra xác nhận thông tin<br><br>
        <table style="width: 520px !important;">
            <tbody>
                <tr>
                     <td width="40%"> <span>Số buổi sử dụng: </span></td>
                    <td width="30%"> <span id="rfc_studied_session" style="font-weight:bold;">0</span></td>
                     <td width="40%"> <span>Số giờ sử dụng: </span></td>
                     <td width="30%"> <span id="rfc_studied_hour" style="font-weight:bold;">0</span></td>


                </tr>
                <tr>
                    <td width="40%"><span>Số buổi xóa khỏi lớp: </span></td>
                     <td width="30%"><span id="rfc_remove_session" style="font-weight:bold;">0</span></td>
                    <td width="40%"><span>Số giờ xóa khỏi lớp: </span></td>
                    <td width="30%"><span id="rfc_remove_hour" style="font-weight:bold;">0</span> </td>

                </tr>
            </tbody>
        </table>
        <br>
        <b>Bước 4:</b><br>
        Click <b>Submit</b> :Học viên sẽ được xóa khỏi lớp.<br>
        Click <b>Cancel</b> :Để hủy bỏ thao tác<br><br>
</div>