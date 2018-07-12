<div id="diaglog_close_class" title="Are you sure you want to Submit Close this class ?" style="display:none;">
    <table width="100%"  class="edit view">
        <tbody>
            <tr>
                <td width="40%">{$MOD.LBL_CLOSED_DATE}:<span style="color:red;">*</span></td>
                <td width="60%">
                <span class="dateTime">
                <input disabled name="cc_closed_date" size="10" id="cc_closed_date" type="text" value="{$fields.closed_date.value}">  <img border="0" src="custom/themes/default/images/jscalendar.png" alt="Enter Date" id="cc_closed_date_trigger" align="absmiddle"></span>
                </td>
            </tr>
            <tr>
            <td colspan="2;">
             <span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 25px 0;"></span> Please make sure you have delayed all students out of class. Teachers will be removed from class after <b>Closed Date</b>. 
            </td>
             </tr>
            <tr>
                <td style="colspan=2">
                    <input type="button" id="btn_close_class" value="Submit"/>
                    <span id = "close_class_loading" style="display:none;">Loading.. <img src="custom/include/images/loader.gif" align="absmiddle" width="16"></span>
                </td>
            </tr>
        </tbody>
    </table>
</div>