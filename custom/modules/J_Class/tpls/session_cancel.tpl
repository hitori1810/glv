<div style="display: inline-flex;">
    <div id="cancel_dialog" style="display:none;">
        <div>
            <form name = "CancelView" id = "CancelView" method="post" action="cancelSession">
            <input type="hidden" value="" id= "before_ss">
            <input type="hidden" value="" id= "after_ss">
            <input type="hidden" value="" id= "cancel_session_id">

                <table width="100%">
                    <tr>
                        <td style="width:15%; text-align: right; font-weight: bold; ">{$MOD.LBL_CLASS_CODE}: </td>
                        <td  id="cs_class_code" style="width:35%; color: blue;font-weight: bold">{$fields.class_code.value}</td>
                        <td style="width:15%; text-align: right; font-weight: bold;">{$MOD.LBL_START_DATE}: </td>
                        <td  id="cs_start_class">{$fields.start_date.value}</td>
                    </tr>
                    <tr>
                        <td width="15%" style="width:15%; text-align: right; font-weight: bold; height: 35px;">{$MOD.LBL_NAME}: </td>
                        <td width="35%" id="cs_class_name" style="color: blue;font-weight: bold">{$fields.name.value}</td>

                        <td width="15%" style="width:15%; text-align: right; font-weight: bold; ">{$MOD.LBL_END_DATE}: </td>
                        <td width="35%" id="cs_end_class">{$fields.end_date.value}</td>
                    </tr>
                    <tr>
                        <td style="width:15%; text-align: right; font-weight: bold; height: 40px;">{$MOD.LBL_MAIN_SCHEDULE}: </td>
                        <td width="85%" colspan="3" id="cs_schedule"><ul>{$SCHEDULE}</ul></td>
                    </tr>
                    <tr>
                        <td colspan="4" style="height: 0; padding:0"><hr style="padding:0; margin:0;"></td>
                    </tr>
                    <tr>
                        <td style="width:20%; text-align: right; font-weight: bold; height: 40px;">{$MOD.LBL_CANCEL_SESSION_DATE}:</td>
                        <td style="width:30%; padding-left: 10px;"><label id="cs_cancel_date" style="font-weight: bold; color: red;"></label>: <label id="cs_cancel_time" style="font-weight: bold; color: red;"></label></td>
                        <td style="width:15%; text-align: right; font-weight: bold;">{$MOD.LBL_CANCEL_BY}:</td>
                        <td style="width:35%; padding-left: 10px;">
                            <select id='cs_cancel_by'>
                                {$session_cancel_reason_options}
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td style="width:20%; text-align: right; font-weight: bold;vertical-align: top;height: 40px;">{$MOD.LBL_REASON_DES}: <span class="required">*</span></td>
                        <td style="padding-left: 10px;" colspan="3"><textarea rows="3" cols="60" style="resize: none;" id="cs_cancel_reason" name="cs_cancel_reason"></textarea></td>
                    </tr>
                    </tr>
                    <tr>
                        <td style="width:20%; text-align: right; font-weight: bold;vertical-align: top;height: 30px;"></td>
                        <td style="padding-left: 10px;" colspan="3">
                            <label style="vertical-align: inherit;"><input type="radio" name="cs_makeup_type" id="cs_this_schedule" value="this_schedule" checked >  {$MOD.LBL_MAKE_UP_IN_THIS_SCHEDULE}</label>
                            <br>
                            <label style="vertical-align: inherit;"><input type="radio" name="cs_makeup_type" id="cs_other_schedule" value="other_schedule">  {$MOD.LBL_MAKE_UP_IN_OTHER_SCHEDULE}</label>
                            <br>
                        </td>
                    </tr>
                </table>
                <div>
                    <fieldset>
                        <table style="width:100%">
                            <tr>
                                <td style="width:20%; text-align: right; font-weight: bold;height: 30px;">{$MOD.LBL_MAKE_UP_DATE}:<span class="required">*</span></td>
                                <td style="width:30%;padding-left: 10px;">
                                    <span class="dateTime">
                                        <input class="date_input datePicker" value="" oldval = "" size="11" readonly type="text" name="cs_date_makeup" id="cs_date_makeup" title="Make up date" maxlength="10" style="vertical-align: top;">
                                    </span>

                                </td>
                                <td style="width:50%; height: 40px;" colspan="2">
                                    <span class="timeOnly">
                                        <input class="time start" value="" oldval="" type="text" style="width: 70px; text-align: center;" readonly name = 'cs_start'>
                                        - {$MOD.LBL_TO} -
                                        <input class="time end input_readonly" value="" oldval="" type="text" style="width: 70px; text-align: center;" readonly name = 'cs_end'>
                                    </span>
                                    <input type="hidden" value="" oldval="" name='cs_duration_hour' id='cs_duration_hour' class="duration_hour input_readonly" readonly style="width: 70px; text-align: center;">
                                    {if $lisence <> "Free" && $lisence <> "Standard"}
                                    <input type="button" value="{$MOD.LBL_CHECK}" class="btn_check_cancel_session" style="margin-left: 50px;"></input>
                                    {/if}
                                </td>
                            </tr>     
                            {if $lisence <> "Free" && $lisence <> "Standard"}
                            <tr>
                                <td style="width:20%; text-align: right; font-weight: bold;height: 40px;">{$MOD.LBL_TEACHER_COVER}: </td>
                                <td style="width:30%;padding-left: 10px;">
                                    <select name = 'cs_teacher' id='cs_teacher'>
                                        <option value = ''> --{$MOD.LBL_NONE}--</option>
                                    </select>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td style="width:20%; text-align: right; font-weight: bold;height: 40px;">{$MOD.LBL_TEACHER_TYPE}:</td>
                                <td style="width:30%;padding-left: 10px;">
                                    <select name='cs_teaching_type' id='cs_teaching_type'>
                                        {$teaching_type_options}
                                    </select>
                                </td>
                                <td style="width:15%; text-align: right; font-weight: bold;height: 40px;">{$MOD.LBL_CHANGE_TEACHER_REASON}: <span style="display: none;" class="required cancel_change_required">*</span></td>
                                <td style="width:35%;padding-left: 10px;">
                                    <textarea rows="3" cols="25" style="resize: none;" id = "cs_change_teacher_reason" name="cs_change_teacher_reason"></textarea>
                                </td>
                            </tr>
                            {/if}
                        </table>
                    </fieldset>
                </div>
            </form>
        </div>
    </div>
</div>