<script type="text/javascript"  src="custom/modules/J_Class/js/sendSMS.js"></script>
<script type="text/javascript"  src="custom/include/javascripts/Autogrow/jquery.autogrowtextarea.min.js"></script>
<link rel='stylesheet' href='{sugar_getjspath file="custom/modules/J_Class/tpls/css/send_sms_screen.css"}'/>
{literal}
<style type="text/css">
.table-border td, .table-border th{
    border: 1px solid #ddd;
    border-collapse:collapse;
    line-height: 18px;
    padding: 6px;
    vertical-align: middle;
    text-align: center;
}
 .table-border {
     border-collapse:collapse;
 }
.no-bottom-border {
     border-bottom: 0px solid #ddd !important;
}
.no-top-border {
     border-top: 0px solid #ddd !important;
}
</style>
{/literal}
<form action="" method="POST" name="SendSMSForm" id="SendSMSForm">
    <div class="container">
        <div class="page-header">
            <h1>{$MOD.LBL_CLASS_SEND_SMS_TITLE}</h1>
            <input type="hidden" name="current_user_id" id="current_user_id" value="{$CURRENT_USER_ID}">
            <input type="hidden" name="session_id" id="session_id" value="{$SESSION_ID}">
        </div>
        <table class="tbl">
            <tbody>
                <tr>
                    <td width="15%" nowrap>
                        <b>{$MOD.LBL_CLASS}: <span class="required">*</span> </b>
                    </td>
                    <td width="35%"  nowrap >
                         <input type="text" name="class_name" id="class_name" value="{$CLASS_NAME}">
                         <input type="hidden" name="class_id" id="class_id" value="{$CLASS_ID}">
                         <button type="button" name="btn_select_class" id="btn_select_class" title="Select" class="button firstChild" value="Select"><img src="themes/default/images/id-ff-select.png?v=JoX4Ng3vRx3g9l0PalZ9nw"></button>
                         <button type="button" name="btn_clr_class" id="btn_clr_class" title="Clear Selection" class="button lastChild"  value="Clear Selection"><img src="themes/default/images/id-ff-clear.png?v=JoX4Ng3vRx3g9l0PalZ9nw"></button>
                    </td>
                    <td rowspan="3" style="width:50%;vertical-align: top;" id="td_class_info">

                    </td>
                </tr>
                <tr>
                    <td  nowrap>
                        <span><b>{$MOD.LBL_TEMPLATE}:</b></span>
                    </td>
                    <td  nowrap>
                        <select id="template" name="template" style="width: 200px;" onchange="load_template();">{$TEMPLATE_OPTIONS}</select>
                    </td>
                </tr>
                <tr>
                    <td  nowrap>
                        <b>{$MOD.LBL_DATE_IN_CONTENT}: <span class="required">*</span></b>
                    </td>
                    <td  nowrap>
                        <!-- Edit By Nguyen Tung -->
                        <select name="date_in_content" id="date_in_content" style="width: 200px;" >
                            {$SESSION_LIST}
                        </select>
<!--                         <span class="dateTime">
                            <input class="date_input" size="11" autocomplete="off" type="text" name="date_in_content" id="date_in_content" value="{$TODAY}" title="{$MOD.LBL_DATE_IN_CONTENT}" tabindex="0" maxlength="10" style="vertical-align: top;">
                            <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:0px" border="0" id="date_in_content_trigger">
                        </span> -->
                        <!-- End by Nguyen Tung -->
                    </td>
                </tr>
                <tr>
                    <td  nowrap>
                        <b>{$MOD.LBL_CONTENT}: </b>
                    </td>
                    <td  nowrap colspan="2">
                        <textarea id="template_content" name="template_content" rows="3" cols="50" style="resize: vertical;"></textarea>
                    </td>
                </tr>
                <tr>
                    <td  nowrap>
                        <b>{$MOD.LBL_DESCRIPTION}: </b>
                    </td>
                    <td  nowrap colspan="2">
                        <textarea id="session_description" name="session_description" rows="3" cols="50" style="resize: vertical;" onchange="saveSessionDescription();">{$SESSION_SCRIPTION}</textarea>
                    </td>
                </tr>
                <tr>
                    <td colspan="3" nowrap align='center'>
                        <input type="button" class="button primary" id="btn_generate" value="{$MOD.LBL_GENERATE}"></input>
                        <input type="button" class="button" id="refresh_sms" value="Refresh" onclick="load_student();" style="margin-left: 20px;">
                        <input type="button" class="button" id="recent_sms" value="Recent SMS" onclick="showRecentSMS();" style="margin-left: 20px;">
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="tbl student_list table-border" id="tbl_student" width="100%" >
            <thead>
                <tr>
                    <th width="5%" style="text-align: center;">
                        <div class="selectedTopSupanel"></div>
                        <input type="checkbox" class="checkall_custom_checkbox" module_name="J_Class" onclick="handleCheckBox($(this));">
                    </th>
                    <th width="15%" style="text-align: center;padding-left: 15px;">{$MOD.LBL_STUDENT}</th>
                    <th width="7%" style="text-align: center;">{$MOD.LBL_BIRTHDATE}</th>
                    <th width="10%" style="text-align: center;">{$MOD.LBL_PARENTNAME}</th>
                    <th width="5%" style="text-align: center;">{$MOD.LBL_ATTENDANCE}</th>
                    <th width="5%" style="text-align: center;">{$MOD.LBL_ABSENT_FOR_HOUR}</th>
                    <th width="5%" style="text-align: center;">{$MOD.LBL_HOMEWORK}</th>
                    <th width="10%" style="text-align: center;">{$MOD.LBL_DESCRIPTION}</th>
                    <th width="30%" style="">Content</th>
                    <th width="20%" style="padding-top: 10px;padding-bottom: 10px;">
                        <input type="button" class="button primary"  id="btn_send_all" value="{$MOD.LBL_SEND_ALL}"></input>
                    </th>
                </tr>
            </thead>
            <tbody>
                {$STUDENT_LIST}
            </tbody>
        </table>
    </div>
</form>


