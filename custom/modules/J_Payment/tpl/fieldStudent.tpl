<table style="padding:0px!important;">
    <tr>
        <td style="padding: 0px !important;">
            {if empty($fields.contacts_j_payment_1contacts_ida.value)}
            <div>
                <select id="student_picker" data-abs-request-delay="0" data-live-search="true" >{$STUDENT_OPTION}</select>
            </div>
            {else}
            <input accesskey="7" tabindex="0" type="text" class="input_readonly" name="contacts_j_payment_1_name" id="contacts_j_payment_1_name" maxlength="255" value="{$fields.contacts_j_payment_1_name.value}" size="25" readonly="">
            {/if}
        </td>
        <td>
            <input type="text" name="contacts_j_payment_1contacts_ida" id="contacts_j_payment_1contacts_ida" value="{$fields.contacts_j_payment_1contacts_ida.value}" style="display:none;">
            <input type="hidden" name="json_student_info" id="json_student_info" value="{$json_student_info}">
            {if empty($fields.contacts_j_payment_1contacts_ida.value)}
            <span class="id-ff multiple">
                <button type="button" id="btn_select_student" style="margin-right: -4px;" tabindex="0" title="Select Student" class="button firstChild" value="Select Student"><img src="themes/default/images/id-ff-select.png"></button>
                <button type="button" id="btn_clr_select_student" style="margin-right: -4px;" tabindex="0" title="Clear Student" class="button lastChild" value="Clear Student"><img src="themes/default/images/id-ff-clear.png"></button>
            </span>
            {else}
            <span class="id-ff multiple">
                <button type="button" disabled id="btn_select_student" style="margin-right: -4px;" tabindex="0" title="Select Student" class="button firstChild" value="Select Student"><img src="themes/default/images/id-ff-select.png"></button>
                <button type="button" disabled id="btn_clr_select_student" style="margin-right: -4px;" tabindex="0" title="Clear Student" class="button lastChild" value="Clear Student"><img src="themes/default/images/id-ff-clear.png"></button>
            </span>
            {/if}
            <a id="eye_dialog_123" title="View" style="cursor:pointer;"><img border="0" src="themes/RacerX/images/view_inline.png" style="margin-left:10px;margin-right:10px;"></a>
<!--            {if $team_type == "Junior"}Membership Level: <span class="loy_loyalty_mem_level"><label><span class="textbg_nocolor">N/A</span></label></span>{/if} -->
            <div id="dialog_student_info"></div>
        </td>
    </tr>
</table>
