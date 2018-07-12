<!--
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is not bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/

/*********************************************************************************

********************************************************************************/
-->
{sugar_getscript file="custom/include/javascripts/jquery.backstretch.min.js"}
{sugar_getscript file="custom/include/javascripts/CustomCheckboxLogin.js"}
<script type='text/javascript'>
    var LBL_LOGIN_SUBMIT = '{sugar_translate module="Users" label="LBL_LOGIN_SUBMIT"}';
    var LBL_REQUEST_SUBMIT = '{sugar_translate module="Users" label="LBL_REQUEST_SUBMIT"}';
    var LBL_SHOWOPTIONS = '{sugar_translate module="Users" label="LBL_SHOWOPTIONS"}';
    var LBL_HIDEOPTIONS = '{sugar_translate module="Users" label="LBL_HIDEOPTIONS"}';
</script>
<table cellpadding="0" align="center" width="100%" cellspacing="0" border="0" style="position: fixed; margin-left: 0px; margin-top: 0px; top: 25%;">
    <tr>
        <td align="center">
            <div class="loginBox" style="width: 575px; height: 250px">
                <div class="loginBoxTop">
                    <div class="cloud">
                        <a style="color: #4E69A2;" target="_blank" href="http://onlinecrm.vn"><img src="themes/OnlineCRM-Blue/images/logo.png" border="0" width="105" height="28"></a>
                    </div>
                </div>
                <div class="loginBoxMiddle">
                    <div class="loginBoxError">
                        <div id="loginDialogError">
                            <table cellpadding="0" cellspacing="2" border="0" align="center">
                                <tr>
                                    <td scope="row" colspan="2">
                                        <span class="error" id="browser_warning" style="display:none">
                                            {sugar_translate label="WARN_BROWSER_VERSION_WARNING"}
                                        </span>
                                        <span class="error" id="ie_compatibility_mode_warning" style="display:none">
                                            {sugar_translate label="WARN_BROWSER_IE_COMPATIBILITY_MODE_WARNING"}
                                        </span>
                                    </td>
                                </tr>
                                {if $LOGIN_ERROR !=''}
                                <tr>
                                    <td scope="row" colspan="2"><span class="error">{$LOGIN_ERROR}</span></td>
                                    {if $WAITING_ERROR !=''}
                                        <tr>
                                            <td scope="row" colspan="2"><span class="error">{$WAITING_ERROR}</span></td>
                                        </tr>
                                    {/if}
                                    </tr>
                                {else}
                                    <tr>
                                        <td scope="row"><span id='post_error' class="error"></span></td>
                                    </tr>
                                {/if}
                            </table>
                        </div>
                        <div id="forgotPasswordDialogError">
                            <table cellpadding="0" cellspacing="2" border="0" align="center">
                                <tr>
                                    <td colspan="2">
                                        <div id="generate_success" class='error' style="display:inline;"></div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div id="sessionDialogError">
                            <table cellpadding="0" cellspacing="2" border="0" align="center">
                                <tr>
                                    <td colspan="2"></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="loginBoxLeft">
                        {php}
                            $settings = new Administration();
                            $settings->retrieveSettings('system');
                            $this->assign('SYSTEM_NAME', $settings->settings['system_name']);
                        {/php}
                        <table cellpadding="0" cellspacing="2" border="0" align="center" width="100%">
                            <tr>
                                <td scope="row" width='1%'>
                                    <label id="lblBrandName" >{sugar_translate module="Users" label="LBL_LOGIN_WELCOME_TO"}<br>
                                    <span id="spnBrandName">{$SYSTEM_NAME}</span><label>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="loginBoxCenter">
                        <div class="spacerVertical right">
                            <div class="mark"></div>
                        </div>
                    </div>
                    <div class="loginBoxRight">
                        <div id="login_dialog">
                            <form action="index.php" method="post" name="DetailView" id="form" onsubmit="return document.getElementById('cant_login').value == ''">
                                <table cellpadding="0" cellspacing="2" border="0" align="center" width="90%">
                                    <tr>
                                        <td scope="row" colspan="2" width="100%"
                                            style="font-size: 12px; font-weight: normal; padding-bottom: 4px;">
                                            <input type="hidden" name="module" value="Users">
                                            <input type="hidden" name="action" value="Authenticate">
                                            <input type="hidden" name="return_module" value="Users">
                                            <input type="hidden" name="return_action" value="Login">
                                            <input type="hidden" id="cant_login" name="cant_login" value="">
                                            {foreach from=$LOGIN_VARS key=key item=var}
                                                <input type="hidden" name="{$key}" value="{$var}">
                                            {/foreach}
                                        </td>
                                    </tr>
                                    {if !empty($SELECT_LANGUAGE)}
                                        <tr>
                                            <td>
                                                <select id="login_language" name='login_language' onchange="switchLanguage(this.value)">{$SELECT_LANGUAGE}</select>
                                            </td>
                                        </tr>
                                    {/if}
                                    <tr>
                                        <td>
                                            <div style="position: relative;">
                                                <input type="text" tabindex="1" id="user_name" name="user_name" value='{$LOGIN_USER_NAME}' placeholder="{sugar_translate label="LBL_USER_NAME"}" class="login-field" autofocus="autofocus"/>
                                                <label class="login-field-icon fa-user"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>                                                                                                                          
                                        <td>
                                            <div style="position: relative;">
                                                <input type="password" tabindex="2" id="user_password" name="user_password" value='{$LOGIN_PASSWORD}' placeholder="{sugar_translate label="LBL_PASSWORD"}" class="login-field"/>
                                                <label class="login-field-icon fa-lock"></label>
                                            </div>
                                        </td>
                                    </tr> 
                                    <tr>    
                                        <td>
                                            <div style="margin-top: 5px;">
                                                <div style="float: left;">
                                                    <div id='wait_login_remember' style="{$WAIT_LOGIN_REMEMBER}">
                                                        <img src="themes/default/images/img_loading.gif">
                                                    </div>
                                                    <label style="font-size: 13px;{$CHECKED_REMEMBER_DISPLAY}"><input type="checkbox" {$CHECKED_REMEMBER} id="remember_me" name="remember_me"/>&nbsp;{sugar_translate module="Users" label="LBL_REMEMBER_ME"}</label>
                                                </div>
                                                <div style="float: right;">         
                                                    <label style="font-size: 13px;">{sugar_translate label="LBL_LOGIN_FORGOT_PASSWORD"}&nbsp;<input type="checkbox" id="forgot_pass" name="forgot_pass"/></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        {$MESSAGE_REMEMBER}
                                    </tr>
                                    <tr>                                                                                                                                                                                               
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <input title="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_TITLE"}" class="button primary" class="button primary" type="submit" tabindex="3" id="login_button" name="Login" value="{sugar_translate module="Users" label="LBL_LOGIN_BUTTON_LABEL"}"><br>&nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                        <div id="forgot_password_dialog" style="display:none;">
                            <form action="index.php" method="post" name="fp_form" id="fp_form">
                                <input type="hidden" name="entryPoint" value="GeneratePassword">
                                <table cellpadding="0" cellspacing="2" border="0" align="center" width="90%">
                                    <tr>
                                        <td>
                                            <div style="position: relative;">
                                                <input type="text" id="fp_user_name" name="fp_user_name" value='{$LOGIN_USER_NAME}' placeholder="{sugar_translate label="LBL_USER_NAME"}" class="login-field"/>
                                                <label class="login-field-icon fa-user"></label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>                                                                                  
                                            <div style="position: relative;">
                                                <input type="text" id="fp_user_mail" name="fp_user_mail" value='' placeholder="{sugar_translate label="LBL_EMAIL"}" class="login-field"/>
                                                <label class="login-field-icon fa-envelope"></label>
                                            </div>
                                        </td>
                                    </tr> 
                                    {$CAPTCHA}
                                    <tr>                 
                                        <td>
                                            <div style="margin-top: 5px;">
                                                <div style="float: left;">
                                                    <div id='wait_pwd_generation'></div>
                                                </div>
                                                <div style="float: right;">
                                                    <label style="font-size: 13px;">{sugar_translate label="LBL_LOGIN_FORGOT_PASSWORD"}&nbsp;<input type="checkbox" id="forgot_pass_2" name="forgot_pass_2"/></label>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            &nbsp;
                                        </td>
                                    </tr>                                                                                                                                                                                                                                          
                                    <tr>
                                        <td>
                                            <input title="Email Temp Password" class="button primary" type="button" style="display:inline" onclick="validateAndSubmit(); return document.getElementById('cant_login').value == ''" id="generate_pwd_button" name="fp_login" value="{sugar_translate label="LBL_SEND_EMAIL_BUTTON_LABEL"}">
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="loginBoxBottom">
                    <label style="font-size: small;">{sugar_translate label="LBL_DESIGN_BY_LABEL"} <a style="color: #4E69A2;" target="_blank" href="http://onlinecrm.vn">OnlineCRM</a></label>
                </div>
            </div>
        </td>
    </tr>
</table>
<br>
<br>