<div id="delay_class_dialog" title="{$MOD.LBL_DELAY_CLASS}" style="display:none;">
        <input id="dl_student_id" type="hidden" value=""/>
        <input id="dl_situation_id" type="hidden" value=""/>
        <input id="dl_payment_date_date" type="hidden" value=""/>
        <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>{$MOD.LBL_STEP_BY_STEP_DELAY_STUDENT}<br>
        <br> {$MOD.LBL_STUDENT_NAME}: <span id='dl_student_name'></span><br>
        <br> {$MOD.LBL_LEARNING_TIME_IN_THIS_CLASS}: <span id='dl_start'></span> - <span id='dl_end'></span><br>
        <br> {$MOD.LBL_TOTAL_HOURS}:  <span id='dl_total_hour' style="font-weight:bold;"></span>  {$MOD.LBL_TOTAL_AMOUNT}: <span id='dl_total_amount' style="font-weight:bold;"></span><br>
        <br> {$MOD.LBL_SCHEDULE_CLASS}: <span id='dl_schedule'>{$SCHEDULE}</span><br>
        <b>{$MOD.LBL_STEP_1_DELAY}<br><br>
        {$MOD.LBL_DELAY_FROM}:<span style="color:red;">*</span>
        <span class="dateTime" style="margin-right: 70px;margin-left: 10px;">
            <input disabled name="dl_from_date" size="10" id="dl_from_date" type="text" value="{$next_session_date}">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="From Date" id="dl_from_date_trigger" align="absmiddle"></span>
        {$MOD.LBL_DELAY_TO}:<span style="color:red;">*</span>
        <span class="dateTime" style="margin-left: 10px;">
            <input disabled name="dl_to_date" size="10" id="dl_to_date" type="text" value="">
            <img border="0" src="custom/themes/default/images/jscalendar.png" alt="To Date" id="dl_to_date_trigger" align="absmiddle"></span>

        <br><br> <b>{$MOD.LBL_STEP2_DELAY}<br><br>
        <table style="width: 520px !important;">
            <tbody>
                <tr>
                     <td width="30%"> <span>{$MOD.LBL_HOURS_LEARNED}: </span></td>
                     <td width="20%"> <span id="dl_studied_hour" style="font-weight:bold;">0</span></td>
                     <td width="30%"> <span>{$MOD.LBL_AMOUNT_LEARNED}: </span></td>
                     <td width="20%"> <span id="dl_studied_amount" style="font-weight:bold;">0</span></td>
                </tr>
                <tr>
                    <td width="30%"><span>{$MOD.LBL_HOURS_DELAY}: </span></td>
                    <td width="20%"><span id="dl_delay_hour" style="font-weight:bold;">0</span> </td>
                    <td width="30%"><span>{$MOD.LBL_AMOUNT_DELAY}: </span></td>
                    <td width="20%"><span id="dl_delay_amount" style="font-weight:bold;">0</span></td>
                </tr>
                <tr>
                <td colspan="2" align="left">{$MOD.LBL_DATE_DELAY}:<span style="color:red;">*</span> <span class="dateTime" style="margin-right: 70px;margin-left: 10px;"></td>
                <td colspan="2"><input disabled name="dl_payment_date" size="10" id="dl_payment_date" type="text" value="">
<!--                    <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Delay Payment Date" id="dl_payment_date_trigger" align="absmiddle"></span>-->
                </td>
                </tr>
            </tbody>
        </table>
        <br>
        <b>{$MOD.LBL_REASON_DELAY} <span style="color:red;">*</span></b><br>
        <textarea cols="50" rows="2" style="margin-top: 5px;" id="dl_reason"></textarea><br>
        <b>{$MOD.LBL_STEP4}:</b>
       {$MOD.LBL_DES_STEP4}
        <span id = "save_loading" style="display:none;">{$MOD.LBL_LOADING}.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
</div>