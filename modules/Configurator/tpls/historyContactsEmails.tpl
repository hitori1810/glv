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
    <input type="hidden" name="action" value="historyContactsEmailsSave">
    <input type="hidden" name="module" value="Configurator">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" class="actionsContainer">
        <tr>
            <td width="100%" colspan="2">
                <input type="submit" id="configuratorHistoryContactsEmails_admin_save"  class="button primary" title="{$APP.LBL_SAVE_BUTTON_TITLE}" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" value="{$APP.LBL_SAVE_BUTTON_LABEL}">
                <input type="button" id="configuratorHistoryContactsEmails_admin_cancel" onclick="location.href='index.php?module=Administration&amp;action=index';" class="button" title="{$APP.LBL_CANCEL_BUTTON_TITLE}" accessKey="{$APP.LBL_CANCEL_BUTTON_KEY}" value="{$APP.LBL_CANCEL_BUTTON_LABEL}">
            </td>
        </tr>
    </table>

    <table width="100%" border="0" cellspacing="1" cellpadding="0" class="edit view">
        <tr>
            <td scope="row" align="right" valign="top" nowrap>{$MOD.LBL_ENABLE_HISTORY_CONTACTS_EMAILS}:</td>
            <td colspan="4" width="95%">
                <table id="sugarfeed_modulelist" cellspacing=3 border=0>
                    {foreach name=feedModuleList from=$modules item=entry}
                        {if ($smarty.foreach.feedModuleList.index % 2)==0}<tr>{/if}
                        <td scope="row" align="right">{$entry.label}:</td>
                        <td>
                            <input type="hidden" name="modules[{$entry.module}]" value="0">
                            <input type="checkbox" id="modules[{$entry.module}]" name="modules[{$entry.module}]" value="1" {if $entry.enabled==1}CHECKED{/if}>
                        </td>
                        {if ($i % 2)==1}</tr>{/if}
                    {/foreach}
                </table>
            </td></tr>
    </table>
</form>
