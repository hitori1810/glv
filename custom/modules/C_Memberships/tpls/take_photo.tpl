<script type="text/javascript" src="custom/include/javascripts/ScriptCam/scriptcam.js"></script>
<script type="text/javascript" src="custom/modules/C_Memberships/js/editview.js"></script>
<link rel='stylesheet' type='text/css' href='custom/include/javascripts/Bootstrap/css/view_custom.css'>
<table id="photo_config" width="100%" name="photo_config">
    <tr>
        <td width="50%" rowspan="8">
            <div id="webcam" style="width:470px; height:300px"></div>
            <div id="gallery">{$IMAGE_URL}</div>
            <input type="hidden" name="image" id="image" value="{$IMAGE}">
        </td>
        <td valign="top" id="name_label" width="12.5%" scope="col">
            ID Card:
            <span class="required">*</span>
        </td> 
        <td valign="top" width="37.5%" colspan="2">
            
            <span><input type="text" name="name" id="name" size="30" maxlength="150" value="{$fields.name.value}">&nbsp;<label style="display:none;" id="valid_code"><img src="custom/include/images/checked.gif" align="absmiddle" width="16"></label><label style="display:none;" id="invalid_valid_code"><img src="custom/include/images/unchecked.gif" align="absmiddle" width="16"></label> <input name="checkDuplicate" id="checkDuplicate" type="text" style="display:none;"/></span>
        
        </td>    
    </tr>

    <tr>


        <td valign="top" id="c_memberships_contacts_2_name_label" width="12.5%" scope="col">
            Card Type:
        </td>
        <td valign="top" width="37.5%" colspan="2">
               {html_options name="type" id="type" options=$fields.type.options selected=$fields.type.value}
        </td>


    </tr>


    <tr id = "student" {if $fields.type.value eq 'visitor'} style="display:none;" {/if}>
        <td valign="top" id="c_memberships_contacts_2_name_label" width="12.5%" scope="col">
            Student:
        </td>
        <td valign="top" width="37.5%" colspan="2">
            <input type="text" name="c_memberships_contacts_2_name" class="sqsEnabled yui-ac-input" tabindex="0" id="c_memberships_contacts_2_name" size="" value="{$fields.c_memberships_contacts_2_name.value}" title="" autocomplete="off"><div id="EditView_c_memberships_contacts_2_name_results" class="yui-ac-container"><div class="yui-ac-content" style="display: none;"><div class="yui-ac-hd" style="display: none;"></div><div class="yui-ac-bd"><ul><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li></ul></div><div class="yui-ac-ft" style="display: none;"></div></div></div>
            <input type="hidden" name="c_memberships_contacts_2contacts_idb" id="c_memberships_contacts_2contacts_idb" value="{$fields.c_memberships_contacts_2contacts_idb.value}">
            <span class="id-ff multiple">
                <button type="button" name="btn_c_memberships_contacts_2_name" id="btn_c_memberships_contacts_2_name" tabindex="0" title="Select Contact" class="button firstChild" value="Select Contact" onclick=""><img src="themes/default/images/id-ff-select.png"></button><button type="button" name="btn_clr_c_memberships_contacts_2_name" id="btn_clr_c_memberships_contacts_2_name" tabindex="0" title="Clear Contact" class="button lastChild" onclick="" value="Clear Contact"><img src="themes/default/images/id-ff-clear.png"></button>
            </span>
        </td>
    </tr>
    
    
    <tr id = "visitor" {if $fields.type.value neq 'visitor'} style="display:none;" {/if}>
        <td valign="top" id="c_memberships_leads_1_name_label" width="12.5%" scope="col">
            Lead:
        </td>
        <td valign="top" width="37.5%" colspan="2">
            <input type="text" name="c_memberships_leads_1_name" id='c_memberships_leads_1_name' value="{$fields.c_memberships_leads_1_name.value}"><div id="EditView_c_memberships_leads_1_name_results" class="yui-ac-container"><div class="yui-ac-content" style="display: none;"><div class="yui-ac-hd" style="display: none;"></div><div class="yui-ac-bd"><ul><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li><li style="display: none;"></li></ul></div><div class="yui-ac-ft" style="display: none;"></div></div></div>
            <input type="hidden" name="c_memberships_leads_1leads_idb" id="c_memberships_leads_1leads_idb" value="{$fields.c_memberships_leads_1leads_idb.value}">
            <span class="id-ff multiple">
                <button type="button" name="btn_c_memberships_leads_1_name" id="btn_c_memberships_leads_1_name" tabindex="0" title="Select Contact" class="button firstChild" value="Select Contact" onclick=""><img src="themes/default/images/id-ff-select.png"></button><button type="button" name="btn_clr_c_memberships_leads_1_name" id="btn_clr_c_memberships_leads_1_name" tabindex="0" title="Clear Contact" class="button lastChild" onclick="" value="Clear Contact"><img src="themes/default/images/id-ff-clear.png"></button>
            </span>
        </td>
    </tr>
    
    
    
    
    <tr>
        <td valign="top" width="12.5%" colspan="2">
            Mobile:
        </td>
        <td valign="top" width="37.5%" colspan="1">
            <label width="12.5%" class="balance_label" style="color: rgb(68, 68, 68); padding: 0.5em; display: inline; background-color: rgb(238, 238, 238);"> <span id="phone_mobile_text" style="font-weight:bold;"></span><input type="hidden" name="phone_mobile" id="phone_mobile" value="{$MOBILE}"></label>
        </td>
    </tr>
    <tr>
        <td valign="top" width="12.5%" colspan="2">
            Email:
        </td>
        <td valign="top" width="37.5%" colspan="1">
            <label width="12.5%" class="balance_label" style="color: rgb(68, 68, 68); padding: 0.5em; display: inline; background-color: rgb(238, 238, 238);"> <span id="email1_text" style="font-weight:bold;"></span><input type="hidden" name="email1" id="email1" value="{$EMAIL1}"></label>
        </td>
    </tr>
    <tr>
        <td valign="top" width="12.5%" colspan="2">
            Birthday:
        </td>
        <td valign="top" width="37.5%" colspan="1">
            <label width="12.5%" class="balance_label" style="color: rgb(68, 68, 68); padding: 0.5em; display: inline; background-color: rgb(238, 238, 238);"> <span id="birthdate_text" style="font-weight:bold;"></span><input type="hidden" name="birthdate" id="birthdate" value="{$BIRTHDATE}"></label>
        </td>
    </tr>
    <tr>
        <td valign="top" width="12.5%" colspan="2">
            Description:
        </td>
        <td valign="top" width="37.5%" colspan="1">
        <textarea id="description" name="description" rows="4" cols="60" title="" tabindex="0">{$fields.description.value}</textarea>
    </tr>
</table>


