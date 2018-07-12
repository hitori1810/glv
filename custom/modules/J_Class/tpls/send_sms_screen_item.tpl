<tr student_id="{$STUDENT_ID}" student_name_en="{$STUDENT_NAME_EN}" student_phone="{$STUDENT_PHONE}" attend_id = "{$ATTENDANCE_ID}" class="{$IN_CLASS_TYPE}">
    <td nowrap="" style="text-align: center;" >
        <input type="checkbox" class="custom_checkbox" module_name="J_Class" onclick="handleCheckBox($(this));" value="{$STUDENT_ID}">
    </td>
    <td nowrap="" style="text-align: left;" >
        <b><a href={if empty($ATTENDANCE_ID)} "index.php?module=Leads&amp;action=DetailView&amp;record={$STUDENT_ID}" {else} "index.php?module=Contacts&amp;action=DetailView&amp;record={$STUDENT_ID} {/if} " class="gs_link student_name">{$STUDENT_NAME}</a></b>
    </td>
    <td nowrap="" style="text-align: left;" >
        <span>{$BIRTHDATE}</span>
    </td>
    <td>
        {$PARENTNAME}
    </td>
    <td nowrap="" style="text-align: center;" >
        {if $ATTENDANCE_ID == "" }
        <input type="hidden" name="chk_attendance" class="chk_attendance" >
        {else}
        <input type="checkbox" {$ATTENDANCE_CHECKED} name="chk_attendance" class="chk_attendance" onclick="saveAttendance($(this));"/>
        {/if}
    </td>
    <td nowrap="" style="text-align: center;" >
        {if $ATTENDANCE_ID == ""|| !empty($IN_CLASS_TYPE) }
        {else}
        {if empty($ATTENDANCE_CHECKED)}
        <select disabled name="chk_absent_for_hour" onchange="saveAttendance($(this));" class="readonly chk_absent_for_hour">{$ABSENT_FOR_HOUR_OPTION}</select>
        {else}
        <select name="chk_absent_for_hour" onchange="saveAttendance($(this));" class="chk_absent_for_hour">{$ABSENT_FOR_HOUR_OPTION}</select>
        {/if}
        {/if}

    </td>
    <td nowrap="" style="text-align: center;" >
        {if $ATTENDANCE_ID == "" || !empty($IN_CLASS_TYPE) }
        <input type="hidden" name="chk_homework" class="chk_homework" >
        {else}
        <input type="checkbox" {$HOMEWORK_CHECKED} name="chk_homework" class="chk_homework" onclick="saveAttendance($(this));"/>
        {/if}
    </td>
    <td nowrap="">
        {if $ATTENDANCE_ID == ""|| !empty($IN_CLASS_TYPE) }
        <b> </b>
        {else}
        <textarea class="description" rows="3" cols="15" style="resize: vertical;vertical-align: middle;margin-bottom: 5px;" onchange="saveAttendance($(this));">{$DESCRIPTION}</textarea><br>
        {/if}
    </td>
    <td nowrap="">
        <textarea class="sms_content" name="sms_content" rows="2" cols="35" style="resize: vertical;vertical-align: middle;margin-bottom: 5px;" onkeyup="countSms($(this));"></textarea><br>
        <label class="message_counter"></label>
    </td>
    <td nowrap style="text-align: center;" >
        <input type="button" class="btn_expand" name="btn_expand" value="{$MOD.LBL_EXPAND}" style="margin-right: 10px;" onclick="expandContent($(this));"></input>
        <input type="button" class="btn_collapse" name="btn_collapse" value="{$MOD.LBL_COLLAPSE}" style="display:none;" onclick="collapseContent($(this));"></input>

        <input type="button" p_type = {if empty($ATTENDANCE_ID)} "Leads" {else} "Contacts" {/if} name="btn_send" value="{$MOD.LBL_SEND}" onclick="checkContent($(this));"></input>
        <img class="loading_icon" src="themes/default/images/loading.gif" align="absmiddle" style="width:16;display:none;">
    </td>
</tr>