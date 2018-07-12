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
<form name="AdminSettings" method="POST">
<input type="hidden" name="action" value="AdminSettings">
<input type="hidden" name="module" value="SugarFeed">
<input type="hidden" name="process" value="">

<table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
<tr>
<td width="100%" colspan="2">
<input type="button" id="activityStream_admin_save" onclick="document.AdminSettings.process.value='true'; if(check_form('AdminSettings')) {ldelim} document.AdminSettings.submit(); {rdelim}" class="button primary" title="{$app.LBL_SAVE_BUTTON_TITLE}" accessKey="{$app.LBL_SAVE_BUTTON_KEY}" value="{$app.LBL_SAVE_BUTTON_LABEL}">
<input type="button" id="activityStream_admin_cancel" onclick="document.AdminSettings.process.value='false'; document.AdminSettings.submit();" class="button" title="{$app.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$app.LBL_CANCEL_BUTTON_KEY}" value="{$app.LBL_CANCEL_BUTTON_LABEL}">
<input type="button" id="activityStream_admin_delete" onclick="document.AdminSettings.process.value='deleteRecords'; if(confirm('{$mod.LBL_CONFIRM_DELETE_RECORDS}')) document.AdminSettings.submit();" class="button" title="{$mod.LBL_FLUSH_RECORDS}" value="{$mod.LBL_FLUSH_RECORDS}">
</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
<tr>
<td scope="row" align="right" nowrap>{$mod.LBL_ENABLE_FEED}:</td>
<td align="left" width="25%" colspan='4'>
<input type="hidden" id="feed_enable_hidden" name="feed_enable" value="0">
<input type="checkbox" id="feed_enable" name="feed_enable" value="1" {$enabled_checkbox} onClick="SugarFeedDisableCheckboxes()">
{$mod.LBL_ENABLE_EXTERNAL_CONNECTORS}
</td>
</tr>
<tr>
<td scope="row" align="right" valign="top" nowrap>{$mod.LBL_ENABLE_MODULE_LIST}:</td>
<td colspan="4" width="95%">
<table id="sugarfeed_modulelist" cellspacing=3 border=0>
{foreach name=feedModuleList from=$module_list key=i item=entry}
{if ($i % 2)==0}<tr>{/if}
<td scope="row" align="right">{$entry.label}:</td>
<td>
<input type="hidden" name="modules[module_{$entry.module}]" value="0">
<input type="checkbox" id="modules[module_{$entry.module}]" name="modules[module_{$entry.module}]" value="1" {if $entry.enabled==1}CHECKED{/if}>
</td>
{if ($i % 2)==1}</tr>{/if}
{/foreach}
</table>
</td></tr>
<tr>
<td scope="row" align="right" nowrap>{$mod.LBL_ENABLE_USER_FEED}:</td>
<td align="left" width="25%">
<input type="hidden" id="modules[module_UserFeed]" name="modules[module_UserFeed]" value="0">
<input type="checkbox" id="modules[module_UserFeed]" name="modules[module_UserFeed]" value="1" {if $user_feed_enabled==1}CHECKED{/if}>
</td>
<td colspan="3" width="70%">&nbsp;</td>
</tr>
</table>
</form>


<script type="text/javascript">
var SugarFeedCheckboxList = new Object();
SugarFeedCheckboxList['module_UserFeed'] = 'modules[module_UserFeed]';
{foreach name=feedModuleList from=$module_list key=i item=entry}
SugarFeedCheckboxList['{$i}'] = 'modules[module_{$entry.module}]';
{/foreach}
{literal}
addToValidate('AdminSettings', 'tracker_prune_interval', 'int', true, "{$mod.LBL_TRACKER_PRUNE_RANGE}");
addToValidateRange('AdminSettings', 'tracker_prune_interval', 'range', true, '{$mod.LBL_TRACKER_PRUNE_RANGE}', 1, 180);
function SugarFeedDisableCheckboxes(is_init) {
        var setDisabled = false;

        if ( document.getElementsByName('feed_enable')[1].checked == true ) {
           setDisabled = false;
           if ( is_init != true ) {
               document.getElementsByName('modules[module_UserFeed]')[1].checked = true;
           }
        } else {
           setDisabled = true;
        }
        
        var currElem = null;
        for ( var i in SugarFeedCheckboxList ) {
            currElem = document.getElementsByName(SugarFeedCheckboxList[i])[1];
            if ( typeof(currElem) != 'object' ) { continue; }
            if ( currElem.type == 'checkbox' ) {
               currElem.disabled = setDisabled;
               currElem.readonly = setDisabled;
            }
        }
}
SugarFeedDisableCheckboxes(true);
{/literal}
</script>
