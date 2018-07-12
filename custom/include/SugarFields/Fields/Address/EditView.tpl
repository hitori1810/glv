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
{sugar_getscript file='custom/include/SugarFields/Fields/Address/SugarFieldAddress.js'}
{{assign var="key" value=$displayParams.key|upper}}
{{assign var="street" value=$displayParams.key|cat:'_address_street'}}
{{assign var="city" value=$displayParams.key|cat:'_address_city'}}
{{assign var="state" value=$displayParams.key|cat:'_address_state'}}
{{assign var="country" value=$displayParams.key|cat:'_address_country'}}
{{assign var="postalcode" value=$displayParams.key|cat:'_address_postalcode'}}
{{assign var="latitude" value=$displayParams.key|cat:'_address_latitude'}}
{{assign var="longitude" value=$displayParams.key|cat:'_address_longitude'}}

<fieldset id='{{$key}}_address_fieldset'>
    <legend>{sugar_translate label='LBL_{{$key}}_ADDRESS' module='{{$module}}'}</legend>
    <table border="0" cellspacing="1" cellpadding="0" class="edit" width="100%">
        <tr>
            <td valign="top" id="{{$street}}_label" width='25%' scope='row' >
                <label for='{{$street}}'>{sugar_translate label='LBL_STREET' module='{{$module}}'}:</label>
                {if $fields.{{$street}}.required || {{if $street|lower|in_array:$displayParams.required}}true{{else}}false{{/if}}}
                <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span>
                {/if}
            </td>
            <td width="*">
                {{if $displayParams.maxlength}}
                <input type="text" id="{{$street}}" class="address_autocomplete" name="{{$street}}" maxlength="{{$displayParams.maxlength}}" size="30" {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}} {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}} value='{$fields.{{$street}}.value}' />
                {{else}}
                <input type="text" id="{{$street}}" class="address_autocomplete" name="{{$street}}" size="30"  {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}} {{if !empty($displayParams.accesskey)}} accesskey='{{$displayParams.accesskey}}' {{/if}}  value='{$fields.{{$street}}.value}' />
                {{/if}}
                <input type="hidden" class="longitude" name="{{$longitude}}" id="{{$longitude}}" value="{$fields.{{$longitude}}.value}">
                <input type="hidden" class="latitude" name="{{$latitude}}" id="{{$latitude}}" value="{$fields.{{$latitude}}.value}">
            </td>
        </tr>

        <tr>
            <td id="{{$state}}_label" width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope='row' >
                <label for='{{$state}}'>{sugar_translate label='LBL_STATE' module='{{$module}}'}:</label>
                {if $fields.{{$state}}.required || {{if $state|lower|in_array:$displayParams.required}}true{{else}}false{{/if}}}
                <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span>
                {/if}
            </td>
            <td>
                <input type="text" name="{{$state}}" id="{{$state}}" size="{{$displayParams.size|default:30}}" {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}} value='{$fields.{{$state}}.value}'  {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}>
            </td>
        </tr>

        <tr>
            <td id="{{$city}}_label" width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope='row' >
                <label for='{{$city}}'>{sugar_translate label='LBL_CITY' module='{{$module}}'}:</label>
                {if $fields.{{$city}}.required || {{if $city|lower|in_array:$displayParams.required}}true{{else}}false{{/if}}}
                <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span>
                {/if}
            </td>
            <td>
                <input type="text" name="{{$city}}" id="{{$city}}" size="{{$displayParams.size|default:30}}" {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}} value='{$fields.{{$city}}.value}'  {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}>
            </td>
        </tr>     

        <tr style="display:none;">
            <td id="{{$country}}_label" width='{{$def.templateMeta.widths[$smarty.foreach.colIteration.index].label}}%' scope='row' >

                <label for='{{$country}}'>{sugar_translate label='LBL_COUNTRY' module='{{$module}}'}:</label>
                {if $fields.{{$country}}.required || {{if $country|lower|in_array:$displayParams.required}}true{{else}}false{{/if}}}
                <span class="required">{$APP.LBL_REQUIRED_SYMBOL}</span>
                {/if}
            </td>
            <td>
                <input type="text" name="{{$country}}" id="{{$country}}" size="{{$displayParams.size|default:30}}" {{if !empty($vardef.len)}}maxlength='{{$vardef.len}}'{{/if}} value='{$fields.{{$country}}.value}'  {{if !empty($tabindex)}} tabindex="{{$tabindex}}" {{/if}}>
            </td>
        </tr>

        {{if $displayParams.copy}}
            <tr>
                <td scope='row' NOWRAP>
                    {sugar_translate label='LBL_COPY_ADDRESS_FROM_LEFT' module=''}:
                </td>
                <td>
                    <input id="{{$displayParams.key}}_checkbox" name="{{$displayParams.key}}_checkbox" type="checkbox" onclick="{{$displayParams.key}}_address.syncFields();">
                </td>
            </tr>
        {{else}}
            <tr>
                <td colspan='2' NOWRAP>&nbsp;</td>
            </tr>
        {{/if}}
    </table>
</fieldset>

<script type="text/javascript">
    SUGAR.util.doWhen("typeof(SUGAR.AddressField) != 'undefined'", function(){ldelim}
    {{$displayParams.key}}_address = new SUGAR.AddressField("{{$displayParams.key}}_checkbox",'{{$displayParams.copy}}', '{{$displayParams.key}}');
    {rdelim});
</script>
