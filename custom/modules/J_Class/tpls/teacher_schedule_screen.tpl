<link rel="stylesheet" type="text/css" href="custom/modules/J_Class/tpls/css/teacher_schedule_screen.css">

<div id="dialog_teacher" title="Schedule Teacher" style="display:none;">
    <table style="border: 1px solid #F0F0F0;width:100%;">
        <tr>
            <td width="20%" style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_CLASS_CODE}: </td>
            <td width="30%" id="class_code_schedule" name = "class_code" style="color: blue;font-weight: bold;width: 170px;height: 50px;">{$CLASS_CODE}</td>
            <td width="20%" style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_START_DATE}: </td>
            <td width="30%" style="color: blue; font-weight: bold">{$fields.start_date.value}</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_NAME}: </td>
            <td id="class_name_schedule" style="color: blue;font-weight: bold">{$NAME}</td>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_END_DATE}: </td>
            <td style="color: blue;font-weight: bold">{$fields.end_date.value}</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_UPGRADE}: </td>
            <td colspan =2 id="upgrade_schedule" name = "upgrade_schedule" style="width: 170px;height: 50px;">{$CLASS_CODE}</td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_MAIN_SCHEDULE}: </td>
            <td colspan =2 id="dlg_class_schedule" name = "dlg_class_schedule" style="width: 170px;height: 50px;"></td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;">From Date:<span class="required">*</span></td>
            <td style="padding-left: 10px;">
                <span class="dateTime">
                    <input class="date_input" size="11" autocomplete="off" type="text"  maxlength="10" style="font-size: 1em" name="start_schedule" id="start_schedule"/>
                &nbsp<img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" id="start_schedule_trigger" style="vertical-align: middle"> </span>
                {literal}
                <script type="text/javascript">
                    Calendar.setup ({
                    inputField : "start_schedule",
                    ifFormat : cal_date_format,
                    daFormat : cal_date_format,
                    button : "start_schedule_trigger",
                    singleClick : true,
                    dateStr : "",
                    startWeekday: 0,
                    step : 1,
                    weekNumbers:false
                    });
                </script>
                {/literal}
            </td>
            <td style="text-align: right; font-weight: bold;">To Date:<span class="required">*</span></td>
            <td style="padding-left: 10px;height: 50px;">
                <span class="dateTime">
                    <input class="date_input" size="11" autocomplete="off" type="text"  maxlength="10" style="font-size: 1em" name="end_schedule" id="end_schedule"/>
                &nbsp<img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" id="end_schedule_trigger" style="vertical-align: middle"> </span>
                {literal}
                <script type="text/javascript">
                    Calendar.setup ({
                    inputField : "end_schedule",
                    ifFormat : cal_date_format,
                    daFormat : cal_date_format,
                    button : "end_schedule_trigger",
                    singleClick : true,
                    dateStr : "",
                    startWeekday: 0,
                    step : 1,
                    weekNumbers:false
                    });
                </script>
                {/literal}
                </span>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;height:50px;">{$MOD.LBL_DAY}: <span class="required">*</span></td>
            <td colspan="3">
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Monday' onclick='clearTeacherList();'/><label for="Monday" style="margin-right:10px; display: none" id="lbl_mon">{$MOD.LBL_MON}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Tuesday' onclick='clearTeacherList();'/><label for="Tuesday" style="margin-right:10px; display: none" id="lbl_tue">{$MOD.LBL_TUE}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Wednesday' onclick='clearTeacherList();'/><label for="Wednesday" style="margin-right:10px; display: none" id="lbl_wed">{$MOD.LBL_WED}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Thursday' onclick='clearTeacherList();'/><label for="Thursday" style="margin-right:10px; display: none" id="lbl_thu">{$MOD.LBL_THU}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Friday' onclick='clearTeacherList();'/><label for="Friday" style="margin-right:10px; display: none" id="lbl_fri">{$MOD.LBL_FRI}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Saturday' onclick='clearTeacherList();'/><label for="Saturday" style="margin-right:10px; display: none" id="lbl_sat">{$MOD.LBL_SAT}</label>
                </div>
                <div style="margin-bottom: 5px;">
                <input style="vertical-align: middle; margin-right: 5px; display: none;" type=checkbox class="day_of_week" id='Sunday' onclick='clearTeacherList();'/><label for="Sunday" style="margin-right:10px; display: none" id="lbl_sun">{$MOD.LBL_SUN}</label>
                </div>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_TEACHING_TYPE}: </td>
            <td colspan =2 style="width: 170px;height: 50px;">
                <select name = 'select_teaching_type' id = 'select_teaching_type' class="select_teaching_type">
                    {$teaching_type_options}
                </select>
            </td>
        </tr>
        <tr>
            <td style="text-align: right; font-weight: bold;padding-right: 10px;">{$MOD.LBL_CHANGE_TEACHER_REASON}: <span style="display: none;" class="required change_reason_required">*</span> </td>
            <td colspan =3 style="width: 170px;height: 50px;">
                <textarea id="change_teacher_reason" name="change_teacher_reason" rows="2" cols="60"></textarea>
            </td>
        </tr>
        <tr>

            <td colspan="4" style="text-align: center;">
                <input type="button" value="{$MOD.LBL_CHECK}" id="btn_check_schedule"></input>
                <input type="button" value="{$MOD.LBL_RESET}" id="btn_reset_input"></input>
            </td>
        </tr>
    </table>
    <div>
        <table id="list_teacher" class="list view" style="width: 100%;margin-top: 10px;">
            <thead>
                <tr>
                    <th></th>
                    <th>{$MOD.LBL_TEACHER_NAME}</th>
                    <th>{$MOD.LBL_TEACHER_CONTRACT_TYPE}</th>
                    <th>{$MOD.LBL_TEACHER_REQUIRED_HOURS}</th>
                    <th>{$MOD.LBL_TEACHER_TAUGHT_HOURS}</th>
                    <th>{$MOD.LBL_TEACHER_EXPIRE_DAY}</th>
                    <th>{$MOD.LBL_DAY_OFF}</th>
                    <th>{$MOD.LBL_HOLIDAYS}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div style="text-align: center;">
        <input type="hidden" id="teacher_schedule_start_date"></input>
        <input type="hidden" id="teacher_schedule_end_date"></input>
        <input type="hidden" id="teacher_schedule_day_of_week"></input>
        <img id="teacher_schedule_loading_icon" src="themes/default/images/loading.gif" align="absmiddle" style="width:16;display:none;">
        <input type="button" value="{$MOD.LBL_SAVE}" id="btn_teacher_schedule_save" style="display:none;"></input>
        <input type="button" value="{$MOD.LBL_CLOSED}" id="btn_teacher_schedule_close" ></input>
    </div>
</div>
