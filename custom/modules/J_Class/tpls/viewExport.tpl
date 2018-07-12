<link rel="stylesheet" type="text/css" href="{sugar_getjspath file='custom/modules/J_Class/css/showExport.css'}">
{sugar_getscript file='custom/modules/J_Class/js/showExport.js'}
<div id="div_export_form" title="{$MOD.BTN_EXPORT}" style="display: none;" >       
    <input type="hidden" value="{$CLASS_ID}" id="classID">
    <input type="hidden" value="{$TEAMTYPE}" id="team_type">
    <div id ="change_template">
        <label for="template" class="span5">{$MOD.LBL_CHOOSE_TEMPLATE}:</label>
        <select name='template' id='template'>      
            <option value="student_list">{$MOD.LBL_STUDENT_LIST}</option>      
        </select>
    </div>
    <table class="studentList" id="tbl_student_list_export">
        <tbody>
                <tr style="vertical-align: top;">
                    <th>{$MOD.LBL_EXPORT_CHECK_ALL}<br><input type="checkbox" id="checkAll"></th>
                    <th>{$MOD.LBL_EXPORT_STUDENT_NO}</th>
                    <th>{$MOD.LBL_EXPORT_STUDENT_ID}</th>
                    <th>{$MOD.LBL_EXPORT_STUDENT_NAME}</th>
                </tr>
                <tr>
                    <td colspan="4"><hr></td>

                </tr>

                {$STUDENT_LIST}
        </tbody>
    </table>
</div>
