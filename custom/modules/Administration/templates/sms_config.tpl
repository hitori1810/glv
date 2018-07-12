{literal}
<script type="text/javascript">
function myFunction(){
    $('input[name=load_again]').val('1');
    var _form = document.getElementById('EditView');
    SUGAR.ajaxUI.submitForm(_form);
    ajaxStatus.showStatus('Loading <img src="custom/include/images/loader32.gif" align="absmiddle" width="32">');
}
</script>
{/literal}
<html>
    <head>
        <title></title>
    </head>
    <body>
        <form name="ConfigureSettings" id="EditView" method="POST" >
            <input type="hidden" name="module" value="Administration">
            <input type="hidden" name="return_module" value="Administration">
            <input type="hidden" name="action" value="smsConfig">
            <input type="hidden" name="return_action" value="index">
            <input type="hidden" name="save_config" value="">
            <input type="hidden" name="load_again" value="0">

            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td style="padding-bottom: 5px;">
                        <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button"  type="submit" name="btn_submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                        <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
                    </td>
                    <td align="right" nowrap>
                        <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span> {$APP.NTC_REQUIRED}
                    </td>
                </tr>
            </table>
            <fieldset>
                <legend><h3>{$MOD.LBL_WS_INFO}</h3></legend>
                <table width="100%" cellpadding="0" cellspacing="3" border="0" class="tabForm">
                    <tr heigth="20">
                        <td class="dataLabel"> Center </td>
                        <td class="dataField">{$team_options}</td>
                        <td class="dataLabel">Supplier</td>
                        <td class="dataField">{html_options name='sms_ws_supplier' id="sms_ws_supplier" options=$supplier_options selected=$sms_ws_supplier}</td>
                    </tr>
                    <tr heigth="20">
                        <td class="dataLabel">{$MOD.LBL_LINK_WS}</td>
                        <td class="dataField"><input type="text" name="sms_ws_link" id="sms_ws_link" value="{$sms_ws_link}" size="50"></td>
                        <td class="dataLabel">{$MOD.LBL_WS_ACCOUNT}</td>
                        <td class="dataField"><input type="text" name="sms_ws_account" id="sms_ws_account" value="{$sms_ws_account}" size="30"></td>
                    </tr>
                    <tr heigth="20">
                        <td class="dataLabel">{$MOD.LBL_WS_PASS}</td>
                        <td class="dataField"><input type="text" name="sms_ws_pass" id="sms_ws_pass" value="{$sms_ws_pass}" size="30"></td>
                        <td class="dataLabel">{$MOD.LBL_WS_BRANDNAME}</td>
                        <td class="dataField"><input type="text" name="sms_ws_brandname" id="sms_ws_brandname" value="{$sms_ws_brandname}" size="30"></td>
                    </tr>
                    <tr heigth="20">
                        <td class="dataLabel">{$MOD.LBL_WS_GROUPID}</td>
                        <td class="dataField"><input type="text" name="sms_ws_groupid" id="sms_ws_groupid" value="{$sms_ws_groupid}" size="30"></td>
                        <td class="dataLabel">{$MOD.LBL_DEPTID}</td>
                        <td class="dataField"><input type="text" name="sms_ws_deptid" id="sms_ws_deptid" value="{$sms_ws_deptid}" size="30"></td>
                    </tr>
                </table>
            </fieldset>
            <!-- <fieldset>
            <legend><h3>{$MOD.LBL_SMS_CONTENT}</h3></legend>
            <table width="100%" cellpadding="0" cellspacing="3" border="0" class="tabForm">
            <tr>
            <td class="dataLabel">{$MOD.LBL_COUNTRY_CODE}</td>
            <td class="dataField"><input type="text" name="country_code" value="{$country_code}"></td>
            <td class="dataLabel">&nbsp;</td>
            <td class="dataField">&nbsp;</td>
            </tr>
            <tr>
            <td class="dataLabel">{$MOD.LBL_VIETTEL_PREFIX}</td>
            <td class="dataField"><input type="text" name="viettel_prefix" value="{$viettel_prefix}"></td>
            <td class="dataLabel">{$MOD.LBL_VIETTEL_PRICE}</td>
            <td class="dataField"><input type="text" name="viettel_price" value="{$viettel_price}"></td>
            </tr>
            <tr>
            <td class="dataLabel">{$MOD.LBL_VINA_PREFIX}</td>
            <td class="dataField"><input type="text" name="vina_prefix" value="{$vina_prefix}"></td>
            <td class="dataLabel">{$MOD.LBL_VINA_PRICE}</td>
            <td class="dataField"><input type="text" name="vina_price" value="{$vina_price}"></td>
            </tr>
            <tr>
            <td class="dataLabel">{$MOD.LBL_MOBI_PREFIX}</td>
            <td class="dataField"><input type="text" name="mobi_prefix" value="{$mobi_prefix}"></td>
            <td class="dataLabel">{$MOD.LBL_MOBI_PRICE}</td>
            <td class="dataField"><input type="text" name="mobi_price" value="{$mobi_price}"></td>
            </tr>
            </table>
            </fieldset>-->
            <div style="padding-top: 10px;">
                <input title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button"  type="submit" name="btn_submit" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" class="button" onclick="this.form.action.value='{$RETURN_ACTION}'; this.form.module.value='{$RETURN_MODULE}';" type="submit" name="button" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </div>
        </form>
    </body>
</html>