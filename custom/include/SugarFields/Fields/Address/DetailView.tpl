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

{sugar_getscript file='include/SugarFields/Fields/Address/DisplayMap.js'}

<table border='0' cellpadding='0' cellspacing='0' width='100%'>
    <tr>
        <td width='99%' >
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_street" value="{$fields.{{$displayParams.key}}_address_street.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_state" value="{$fields.{{$displayParams.key}}_address_state.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_city" value="{$fields.{{$displayParams.key}}_address_city.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">  
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_country" value="{$fields.{{$displayParams.key}}_address_country.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_postalcode" value="{$fields.{{$displayParams.key}}_address_postalcode.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_latitude" value="{$fields.{{$displayParams.key}}_address_latitude.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <input type="hidden" class="sugar_field" id="{{$displayParams.key}}_address_longitude" value="{$fields.{{$displayParams.key}}_address_longitude.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}">
            <!--Custom code by Bui Kim Tung -- Hide View_map button when address street is empty-->
            
            <!--End custom code by Bui Kim Tung-->
            {$fields.{{$displayParams.key}}_address_street.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}<br/>
            <!--{$fields.{{$displayParams.key}}_address_state.value|escape:'htmlentitydecode'|strip_tags|url2html|nl2br}<br/>
            {$fields.{{$displayParams.key}}_address_city.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}<br/> 
            {$fields.{{$displayParams.key}}_address_postalcode.value|escape:'htmlentitydecode'|strip_tags|url2html|nl2br}
            {$fields.{{$displayParams.key}}_address_country.value|escape:'htmlentitydecode'|escape:'html'|url2html|nl2br}-->
            {if ! empty($fields.{{$displayParams.key}}_address_street.value)}
                <br/><input type="button"  id="view_map_{{$displayParams.key}}" onclick="displayMap(this)" value="{$APP.LBL_VIEW_MAP}">  
            {/if}
        </td>
        <td class='dataField' width='1%'>
            {{* 
            This is custom code that you may set to show on the second column of the address
            table.  An example would be the "Copy" button present from the Accounts detailview.
            See modules/Accounts/views/view.detail.php to see the value being set 
            *}}
            {$custom_code_{{$displayParams.key}}}
        </td>
    </tr>
</table>