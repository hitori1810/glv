<div id="add_to_class_dialog" title="Add Student To Class" style="display:none;">
        <input id="atc_class_id" type="hidden" value=""/>
        <input id="atc_sessionList" type="hidden" value=""/>
        <input id="atc_remain_practice" type="hidden" value=""/>
        <input id="atc_remain_skill" type="hidden" value=""/>
        <input id="atc_remain_connect" type="hidden" value=""/>
        <input id="atc_session" type="hidden" value=""/>
        <input id="atc_session_remain" type="hidden" value=""/>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 10px 0;"></span><b>Vui lòng làm theo từng bước bên dưới</b>.<br>
        <br> Class Code: <span id='atc_class_code'></span>
        <br> Class Name: <span id='atc_class_name'></span>
        <br> Class Type: <span id='atc_class_type'></span>
        <br> Start: <span id='atc_start_date'></span> - Finish: <span id='atc_end_date'></span>
        <br> Kind Of Course:  <span id='atc_koc'></span>  Level: <span id='atc_level'></span>
        <br> Schedule: <span id='atc_schedule'></span><br>
        <b>Bước 1:</b> Chọn khoảng thời gian học viên học trong lớp<br><br>
        Start: <span style="color:red;">*</span><span class="dateTime" style="margin-right: 70px;margin-left: 10px;">
            <input disabled size="10" id="atc_start_study" type="text" value="">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="From Date" id="atc_start_study_trigger" align="absmiddle"></span>
        End: <span style="color:red;">*</span><span class="dateTime" style="margin-left: 10px;">
            <input disabled size="10" id="atc_end_study" type="text" value="">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="atc_end_study_trigger" align="absmiddle"></span>
        <br><br> <b>Bước 2:</b> Kiểm tra xác nhận thông tin<br><br>
        <table style="width: 520px !important;">
            <tbody>
                <tr>
                	 <td width="25%"> <span>Số buổi học: </span></td>
                	 <td width="25%"> <span id="atc_cal_session" style="font-weight:bold;">0</span></td>
                	 <td width="25%"> <span>Số buổi còn lại: </span></td>
                	 <td width="25%"> <span id="atc_cal_session_remain" style="font-weight:bold;">0</span></td>
                </tr>
                <tr>
                     <td width="25%"> <span>Số giờ học: </span></td>
                     <td width="25%"> <span id="atc_cal_total_hours" style="font-weight:bold;">0</span></td>
                     <td width="25%"> <span></span></td>
                     <td width="25%"></span></td>
                </tr>
            </tbody>
        </table>
        <br>
        <b>Bước 3:</b>
        <br>Click <b>Submit</b>: Để đưa học viên vào lớp này.<br>
        Click <b>Cancel</b>: Để hủy bỏ thao tác<br>
        <span id = "atc_saving" style="display:none;">Saving.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>
<div id="add_to_class_notify" title="Kết quả" style="display:none;"></div>