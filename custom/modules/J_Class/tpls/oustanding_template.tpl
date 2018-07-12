<div id="diaglog_outstanding" title="Add OutStanding" style="display: none;">
    <table width="100%"  class="edit view">
        <tbody>
            <tr>
                <td width="20%">{$MOD.LBL_STUDENT_NAME}: </td>
                <td width="70%" style="color: blue;font-weight: bold" colspan="3" name="ot_student_name" id="ot_student_name">{$ot_student_name}</td><input type = "hidden" name="ot_student_id" id="ot_student_id" value="{$ot_student_id}"/><input type = "hidden" name="ot_add_type" id="ot_add_type" value="Create"/><input type = "hidden" id="ot_situation_id" value=""/>
            </tr>
            <tr>
                <td width="20%">{$MOD.LBL_CLASS_CODE}: </td>
                <td width="30%" id="ot_class_code" style="color: blue;font-weight: bold">{$fields.class_code.value}</td>
                <td width="20%">{$MOD.LBL_START_DATE}: </td>
                <td width="30%" id="ot_start_class">{$fields.start_date.value}</td>
            </tr>
            <tr>
                <td width="20%">{$MOD.LBL_NAME}: </td>
                <td width="30%" id="ot_class_name" style="color: blue;font-weight: bold">{$fields.name.value}</td>
                
                <td width="20%">{$MOD.LBL_END_DATE}: </td>
                <td width="30%" id="ot_end_class">{$fields.end_date.value}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_MAIN_SCHEDULE}: </td>
            <td colspan="3" id="ot_schedule">{$SCHEDULE}</td>
            </tr>
            <tr>
                <td width="20%">{$MOD.LBL_START_OUT_STANDING}:<span style="color:red;">*</span></td></td>
                <td width="30%"><span class="dateTime"><input disabled name="start_outstanding" size="10" id="start_outstanding" type="text" value="{$fields.start_date.value}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="start_outstanding_trigger" align="absmiddle"></span></td>
 
                <td width="20%">{$MOD.LBL_TOTAL_SESSIONS}: </td>
                <td width="30%" id="ot_total_sessions_text"></td><input type = "hidden" id="ot_total_sessions" value=""/>
            </tr>
            <tr>
                <td width="20%">{$MOD.LBL_END_OUT_STANDING}:<span style="color:red;">*</span></td>
                <td width="30%"><span class="dateTime"><input disabled name="end_outstanding" size="10" id="end_outstanding" type="text" value="{$fields.end_date.value}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="end_outstanding_trigger" align="absmiddle"></span></td>

                <td width="20%">{$MOD.LBL_TOTAL_HOURS}: </td>
                <td width="30%" id="ot_total_hours_text"></td><input type = "hidden" id="ot_total_hours" value=""/>
            </tr>
        </tbody>
    </table>
</div>