<div id="diaglog_export_attendance" title="Export Attendance List" style="display: none;">
    <table width="100%"  class="edit view">
        <tbody>
            <tr>
                <td style="width:25%;">{$MOD.LBL_CLASS_CODE}: </td>
                <td style="width:30%;" id="ot_class_code" style="color: blue;font-weight: bold">{$fields.class_code.value}</td>
                <td style="width:20%;">{$MOD.LBL_START_DATE}: </td>
                <td style="width:30%;" id="ot_start_class">{$fields.start_date.value}</td>
            </tr>
            <tr>
                <td>{$MOD.LBL_NAME}: </td>
                <td id="ot_class_name" style="color: blue;font-weight: bold">{$fields.name.value}</td>

                <td>{$MOD.LBL_END_DATE}: </td>
                <td id="ot_end_class">{$fields.end_date.value}</td>
            </tr>
            <tr>
            <td>{$MOD.LBL_MAIN_SCHEDULE}: </td>
            <td colspan="3" id="ot_schedule">{$SCHEDULE}</td>
            </tr>
            <tr>
                <td>{$MOD.BTN_EXPORT_FROM_LESSON}:<span style="color:red;">*</span></td></td>
                <td>
                    <select id="export_from_lesson">{$LESSON_LIST}</select>
                </td>

                <td>{$MOD.BTN_EXPORT_TO_LESSON}: </td>
                <td id="total_sessions_text">
                    <select id="export_to_lesson">{$LESSON_LIST}</select>
                </td>
            </tr>
        </tbody>
    </table>
</div>