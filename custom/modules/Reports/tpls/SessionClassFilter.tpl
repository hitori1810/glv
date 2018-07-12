
<link rel="stylesheet" type="text/css" href="{sugar_getjspath file= 'custom/modules/Reports/css/SessionClass.css'}">
{sugar_getscript file = 'custom/modules/Reports/js/SessionClass.js'}

<form action="#" method="post" name ="form_filter" id="form_filter">
    <div id="filters_tab" style="">
        <div scope="row" style="padding-bottom: 50px; text-align: center;"><h2>{$MOD.LBL_SESSION_REPORT}</h2>
        </div>
        <div scope="row" style="padding-bottom: 50px;"><h3>{$MOD.LBL_FILTER} :</h3>
            <div class="row" style="float: left; margin-left: 50px; width:45%">
                <div>
                    <label for="center" class="span5">{$MOD.LBL_CENTER}:</label>            
                    <input type='text' name='center' id='center' size='20' value='{$CENTER}' readonly="">
                    <input type='hidden' name='center_id' id='center_id' value='{$CENTER_ID}'>
                    <input type="button" id="popup" class="btnPopup" /><img style="margin-left: -30px;margin-bottom: -5px;" src="themes/default/images/id-ff-select.png?v=JoX4Ng3vRx3g9l0PalZ9nw" alt=""></img>
                </div>                  
            </div>
        </div>
        
        <div scope="row" style="padding-bottom: 50px;">
            <div class="row" style="float: left; margin-left: 50px; width:45%">
                <div>
                    <label for="date_report_from" class="span5">{$MOD.LBL_DATE_REPORT_FROM}:</label>            
                    <input type='text' name='date_report_from' id='date_report_from' size='20' value='{$DATE_FROM}' maxlength="10">
                    <img name="date_report_from_trigger" src="themes/OnlineCRM-Green/images/jscalendar.png?v=jaHGMYsp_kJXLUFKsCws_g" alt="Enter Date" style="position:relative; top:2px" id="date_report_from_trigger" border="0"> 
                </div>
            </div>
            <div class="row" style="float: left; margin-left: 50px; width:45%">
                <div>
                    <label for="session_closed" class="span5">{$MOD.LBL_SESSION_CLOSED}:</label>            
                    <input type='text' name='session_closed' id='session_closed' size='20' value='{$SESSION_CLOSED}' maxlength="10"> 
                </div>
            </div>
            
        </div>
        
        <div scope="row" style="padding-bottom: 50px;">
            <div class="row" style="float: left; margin-left: 50px; width:45%">
                <div>
                    <label for="date_report_to" class="span5">{$MOD.LBL_DATE_REPORT_TO}:</label>            
                    <input type='text' name='date_report_to' id='date_report_to' size='20' value='{$DATE_TO}' maxlength="10">
                    <img name="date_report_to_trigger" src="themes/OnlineCRM-Green/images/jscalendar.png?v=jaHGMYsp_kJXLUFKsCws_g" alt="Enter Date" style="position:relative; top:2px" id="date_report_to_trigger" border="0"> 
                </div>
            </div>
            <div class="row" style="float: left; margin-left: 50px; width:45%">
                <div>
                    <label for="session_not_start" class="span5">{$MOD.LBL_SESSION_NOT_START}:</label>            
                    <input type='text' name='session_not_start' id='session_not_start' size='20' value='{$SESSION_NOT_START}' maxlength="10"> 
                </div>
            </div>
        </div>
        <div scope="row" style="padding-bottom: 50px;">
            <div class="row" style="float: left; margin-left: 50px; width:45%">
            </div>
            <div class="row" style="float:left; margin-left: 50px; width:25%">           
                <input type="submit" id="btnReport" name="btnReport" value="{$MOD.BTN_FILTER}" class="btnReport">
            </div>            
            <!--<div class="row" style="float:left; margin-left: 50px;">           
                <input db-data="Export File" class="btnExport" name="exportToTxt" id="btnExport" value="{$MOD.BTN_EXPORT}" title="Export session report"  type="button" onclick="location.href = 'index.php?module=C_Shops&action=exportfile&date_report={$DATERP}&shop_code={$SHOPCODE}';">
            </div>-->
            
        </div>
    </div>
</form>