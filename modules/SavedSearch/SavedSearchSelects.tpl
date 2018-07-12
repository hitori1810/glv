{*

/*********************************************************************************
 * UnifiedCRM Community Edition is a customer relationship management program developed by
 * UnifiedCRM, Inc. Copyright (C) 2004-2013 UnifiedCRM Inc.
 * 
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 * 
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
 * details.
 * 
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 * 
 * You can contact UnifiedCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 * 
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 * 
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * UnifiedCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by UnifiedCRM".
 ********************************************************************************/




*}

{if $SAVED_SEARCHES_OPTIONS != null}
<select style="width: auto !important; min-width: 150px;" name='saved_search_select' id='saved_search_select' onChange='SUGAR.savedViews.shortcut_select(this, "{$SEARCH_MODULE}");'>
	{$SAVED_SEARCHES_OPTIONS}
</select>
<input type='checkbox' name='publish_saved_search_chk' id='publish_saved_search_chk' onclick="return CheckSavedSearchClick()" />
<input type='button' name='btnPublishSavedSearch' id='btnPublishSavedSearch' value='{$MOD.LBL_PUBLISH_TO}' title='Publish this search to all users' onclick='return PublishSavedSearch();' disabled />
<span id='availability_status_department' style='display:none;margin-left:10px'><img src='themes/default/images/loading.gif' align='absmiddle' width='16'></span>
<select multiple='multiple' name='department[]' id='department' onchange='return ChangeDepartment();' style='width:120px;margin-left:10px;display:none'></select>
<span id='availability_status_user_id' style='display:none;margin-left:10px'><img src='themes/default/images/loading.gif' align='absmiddle' width='16'></span>
<select multiple='multiple' name='user_id[]' id='user_id' onchange='return ChangeUser();' style='width:120px;display:none;margin-left:10px'></select>
<span id='availability_status_publish' style='margin-left:10px;display:none'><img src='themes/default/images/loading.gif' align='absmiddle' width='16'></span>
<span id='publish_saved_search_status' style='color:blue;margin-left:10px;display:none'></span>

 
<script>
{literal}
	//if the function exists, call the function that will populate the searchform
	//labels based on the value of the saved search dropdown
	if(typeof(fillInLabels)=='function'){
		fillInLabels();
	}
    
    
    
    
    
{/literal}	
</script>
{/if}

