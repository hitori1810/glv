{*
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

*}
<script type="text/javascript" language="JavaScript">
{literal}
<!-- Begin
function set_focus() {
	if (document.DetailView.user_name.value != '') {
		document.DetailView.user_password.focus();
		document.DetailView.user_password.select();
	}
	else document.DetailView.user_name.focus();
}

//  End -->
</script>
<style type="text/css">
	.body, body {
		font-size: 12px;
		}


{/literal}
</style>
<br>
<br>
<div align='center'>
<!-- <img src="include/images/logo_sugarportal.gif" width="300" height="25" alt="Sugar"><br> -->
<table border="0" cellspacing="0" cellpadding="0" align="center">
<tr>
<td valign="top">
	<form action="index.php" method="post" name="DetailView" id="form" onsubmit="return document.getElementById('cant_login').value == ''">
			<table border="0" cellspacing="0" cellpadding="0" class="loginForm">
				<input type="hidden" name="module" value="Users">
				<input type="hidden" name="action" value="Authenticate">
				<input type="hidden" name="return_module" value="Users">
				<input type="hidden" name="return_action" value="Login">
				<input type="hidden" id="cant_login" name="cant_login" value="">
	{if $login_error}
			<tr>
				<td class="dataLabel" id="loginTop">{$current_module_strings.LBL_ERROR}</td>
				<td id="loginTop"><span class="error">{$login_error}</span></td>
			</tr>
	{elseif $sessionTimeout}
			<tr>
				<td colspan='2'><span class="error">{$current_module_strings.LBL_RELOGIN}</span></td>
			</tr>
	{else}
	{/if}
			<tr>
				<td class="dataLabel" nowrap id="loginTop">{$current_module_strings.LBL_USER_NAME}</td>
				<td id="loginTop">
					<input type="text" size='20' id="user_name" name="user_name" value="{$login_user_name}" /></td>
			</tr>
			<tr>
				<td class="dataLabel">{$current_module_strings.LBL_PASSWORD}</td>
				<td>
					<input type="password" size='20' id="user_password" name="user_password" value="{$login_password}" /></td>
			</tr>
			<tr>
				<td id="loginBottom">&nbsp;</td>
				<td id="loginBottom">
					<input title="{$current_module_strings.LBL_LOGIN_BUTTON_TITLE}"  class="button" type="submit" id="login_button" name="Login" value="  {$current_module_strings.LBL_LOGIN_BUTTON_LABEL}  ">
					<p class="loginRegister"><a href="registration.php">{$current_module_strings.LBL_REGISTER}</a></p>
				</td>

			</tr>
			</table>
		</form>
		</td>
</tr>
</table>
</div>
