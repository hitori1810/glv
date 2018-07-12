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
<link rel="stylesheet" href="sidecar/lib/chosen/chosen.css"/>
<script src="sidecar/lib/chosen/chosen.jquery.js"></script>
<form id='0' name='0'>
    <table class='tabform' width='100%' cellpadding=4>

        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_CONFIGURE}:
            </td>
            <td colspan='1' nowrap>
                <input type="checkbox" name="appStatus" {if $appStatus eq 'online'}checked{/if} class='portalField' id="appStatus" value="online"/>
                {$mod.LBL_PORTAL_ENABLE}
            </td>
        </tr>
        {if $appStatus eq 'online'}
        <tr>
            <td>&nbsp;</td>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_SITE_URL} <a href="{$siteURL}/portal/index.html" target="_blank">{$siteURL}/portal/index.html</a>
            </td>
        </tr>
        {/if}
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_LOGO_URL}: {sugar_help text=$mod.LBL_CONFIG_PORTAL_URL}
            </td>
            <td colspan='1' nowrap>
                <input class='portalProperty portalField' id='logoURL' name='logoURL' value='{$logoURL}' size=60>
            </td>
        </tr>
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_LIST_NUMBER}:<span class="required">*</span>
            </td>
            <td colspan='1' nowrap>
                <input class='portalProperty portalField' id='maxQueryResult' name='maxQueryResult' value='{$maxQueryResult}' size=4>
            </td>
        </tr>
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_DETAIL_NUMBER}:<span class="required">*</span>
            </td>
            <td colspan='1' nowrap>
                <input class='portalProperty portalField' id='fieldsToDisplay' name='fieldsToDisplay' value='{$fieldsToDisplay}' size=4>
            </td>
        </tr>
        <tr>
            <td colspan='1' nowrap>
                {$mod.LBL_PORTAL_DEFAULT_ASSIGN_USER}:
            </td>
            <td colspan='1' nowrap class="defaultUser">
                <select data-placeholder="{$mod.LBL_USER_SELECT}" class="chzn-select portalProperty portalField" id='defaultUser' name='defaultUser' >
                {foreach from=$userList item=user key=userId}
                    <option value="{$userId}" {if $userId == $defaultUser}selected{/if}>{$user}</option>
                {/foreach}
                </select>
            </td>
        </tr>
        <tr>
            <td colspan='2' nowrap>
                <input type='button' class='button' id='gobutton' value='Save'>
            </td>
        </tr>

    </table>
</form>
<div>

    {if $disabledDisplayModules}
    <br>
    <p>
        {$mod.LBL_PORTAL_DISABLED_MODULES}
    <ul>
        {foreach from=$disabledDisplayModulesList item=modName}
            <li>{$modName}</li>
        {/foreach}
    </ul>
    </p>
    <p>
        {$mod.LBL_PORTAL_ENABLE_MODULES}
    </p>
    {/if}

</div>
{literal}

<script language='javascript'>
    $('.chzn-select').chosen({allow_single_deselect: true});
    addToValidateRange(0, "maxQueryResult", "int", true,{/literal}"{$mod.LBL_PORTAL_LIST_NUMBER}"{literal},1,100);
    addToValidateRange(0, "fieldsToDisplay", "int", true,{/literal}"{$mod.LBL_PORTAL_LIST_NUMBER}"{literal},1,100);
    $('#gobutton').click(function(event){
        var field;
        var fields = $('.portalField');
        var props = {};
        var fName; var i;
        for(i=0; i<fields.length; i++) {
            field = $(fields[i]);
            props[field.attr('name')] = field.val();
            if (field.is(':checked')) {
                props[field.attr('name')] = 'true';
            }
        }
        retrieve_portal_page($.param(props))
    });
    function retrieve_portal_page(props) {
        if (validate_form(0,'')){
        ModuleBuilder.getContent("module=ModuleBuilder&action=portalconfigsave&" + props);
        }
    }
</script>
{/literal}
