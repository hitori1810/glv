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

<form name='form_{$id}' id='form_{$id}'>
<div class="dashletNonTable" style='white-space:nowrap;'>
  <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td nowrap="nowrap"><span id='more_img_{$id}'>{$more_img}</span><span id='less_img_{$id}' style="display:none;">{$less_img}</span> <b>{$user_name}</b>&nbsp;</td>
      <td style="padding-right: 5px;"><input id="text" name="text" type="text" size='25' maxlength='100' value="" title="{sugar_translate label='LBL_POST_TITLE' module='SugarFeed'} {$user_name} "/></td>
      <td nowrap="nowrap">
      <input type="submit" value="{$LBL_POST}" class="button" style="vertical-align:top" onclick="SugarFeed.pushUserFeed('{$id}'); return false;"></td>
    </tr>
</table>
<div id='more_{$id}' style='display:none;padding-top:5px'>
<table>
<tr>
    <td>{html_options name='link_type' options=$link_types}</td>
    <td><input type='text' name='link_url' title="{sugar_translate label='LBL_URL_LINK_TITLE' module='SugarFeed'}"  size='30'/></td>
</tr>
<tr>
    <td><b>{$LBL_TO}</b></td>
    <td nowrap="nowrap">
        <input type="text" name="team_name" id="team_name_{$id}" class="sqsEnabled" value="{$team_name}" size="15"  title="{sugar_translate label='LBL_TEAM_VISIBILITY_TITLE' module='SugarFeed'}" />
        <input type="hidden" name="team_id" id="team_id_{$id}" value="{$team_id}"/>
        <input type="button" value="{$LBL_SELECT}" class='button' type="button" style="vertical-align:top" onclick='open_popup("Teams", 600, 400, "", true, false, {ldelim}"call_back_function":"set_return","form_name":"form_{$id}","field_to_name_array":{ldelim}"id":"team_id","name":"team_name"{rdelim}{rdelim}, "single", true);' />
    </td>
</tr>
</table>
</div>
</div>

</form>

<form name='SugarFeedReplyForm_{$id}' id='SugarFeedReplyForm_{$id}'>
<input type='hidden' name='parentFeed' value=''>
<div style='white-space:nowrap; display: none;'>
 <table border=0 cellspacing=0 cellpadding=2>
    <tr>
      <td nowrap="nowrap"><b>{$user_name}</b>&nbsp;</td>
      <td style="padding-right: 5px;"><input id="text" name="text" type="text" size='25' maxlength='100' value="" /></td>
      <td nowrap="nowrap">
      <input type="submit" value="{$LBL_POST}" class="button" style="vertical-align:top" onclick="SugarFeed.replyToFeed('{$id}'); return false;"></td>
    </tr>
</table>
</div>
</form>

