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
{capture name=getPhone assign=phoneValue}{sugar_fetch object=$parentFieldArray key=$col}{/capture}
<span style="white-space: nowrap;">
    {sugar_phone value=$phoneValue usa_format=$usa_format}
    {php}
    if($_REQUEST['action'] != 'Popup') :
    global $module, $current_user, $app_strings;
    $callableFields = array();
    $configFile = 'custom/Extension/modules/'. $module .'/CallableFields.php';
    if (file_exists($configFile)) {
    include($configFile);
    }

    $this->assign('CALLABLE_FIELDS', $callableFields);
    $this->assign('PHONE_EXTENSION', $current_user->phone_extension);
    $this->assign('APP', $app_strings);
    {/php}

    {if in_array($vardef.name|lower, $CALLABLE_FIELDS) && !empty($phoneValue)}
    <i data-number='sip:1:{$phoneValue}' title="{$APP.LBL_CLICK_TO_CALL_TO} {$phoneValue}" class="click-to-call"></i>
    {/if}

    {php}
    endif;
    {/php}
    {php}
    global $beanList, $module;
    $this->assign('module', $module);
    $smsEnableFields = array();
    $configFileSMSEnableFields = 'custom/Extension/modules/'. $module .'/SMSEnableFields.php';
    if (file_exists($configFileSMSEnableFields)) {
    include($configFileSMSEnableFields);
    }
    if($module == 'aCase'){
    $module = 'Cases';
    }
    $this->assign('SMSENABLE_FIELDS', $smsEnableFields); 
    $phoneValue = $this->get_template_vars('phoneValue');   

    {/php} 
    {if !empty($SMSENABLE_FIELDS)}                                    
    {if in_array($vardef.name|lower, $SMSENABLE_FIELDS) && !empty($phoneValue)} 
    <i title="{$APP.LBL_CLICK_TO_SEND_SMS_TO} {$phoneValue}" class="click-to-sms" onclick="openAjaxPopup('{$phoneValue}', '{$module}', '{$parentFieldArray.ID}', '{$parentFieldArray.NAME|escape}');"></i>
    {/if}
    {/if}
</span>