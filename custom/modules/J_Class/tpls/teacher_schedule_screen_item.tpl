<tr class="{$PRIORITY_LEVEL}">
    <td style="text-align: center;">
        <input type="radio" name="teacher_schedule_radio" value='{$TEACHER_ID}'>
    </td>
    <td>
        {$NAME}
        <input type="hidden" class="schedule_contract_id" value='{$CONTRACT_ID}'/>
    </td>
    <td style="text-align: center;">{$CONTRACT_TYPE}</td>
    <td style="text-align: center;">{$REQUIRED_HOURS}</td>
    <td style="text-align: center;">{$TAUGHT_HOURS}</td>
    <td style="text-align: center;">
        {$EXPIRE_DAY_SPAN}
        <input type="hidden" class="schedule_contract_until" value='{$EXPIRE_DAY}'/>
    </td>
    <td style="text-align: center;">{$DAY_OFF}</td>
    <td style="text-align: center;">{$HOLIDAYS}</td>
</tr>