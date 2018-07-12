<div id="diaglog_demo" title="{$MOD.LBL_ADD_DEMO}" style="display:none;">
    <table width="100%"  class="edit view">
        <tbody>
        <input type="hidden" id="dm_type_student" value="{$dm_student_type}">
        <input type="hidden" id="dm_student_id" value="{$dm_student_id}">
            <tr>
                <td> {$MOD.LBL_STUDENT_NAME}: </td>
                <td style="color: blue;font-weight: bold" colspan="3" id="dm_student_name">{$dm_student_name}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_CLASS_CODE}: </td>
            <td id="dm_class_code" style="color: blue;font-weight: bold">{$fields.class_code.value}</td>
            <td>{$MOD.LBL_START_DATE}: </td>
            <td id="dm_start_date">{$fields.start_date.value}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_NAME}: </td>
            <td id="dm_class_name_demo" style="color: blue;font-weight: bold">{$fields.name.value}</td>
            <td>{$MOD.LBL_END_DATE}: </td>
            <td id="dm_end_date">{$fields.end_date.value}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_MAIN_SCHEDULE}: </td>
            <td id="dm_schedule">{$SCHEDULE}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_LESSON_DATE}:</td>
            <td><span class="dateTime"><input disabled id="dm_lesson_date" name="dm_lesson_date" size="10" type="text" value="{$next_session_date}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="dm_lesson_date_trigger" align="absmiddle"></span></td>
            </tr>
            <tr>
                <td style="colspan=4;">
                    <input type="button" id="btn_add_demo" value="{$MOD.LBL_ADD}"/>
                    <span id = "add_demo_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>