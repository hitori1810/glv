<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Professional Subscription
 * Agreement ("License") which can be viewed at
 * http://www.sugarcrm.com/crm/products/sugar-professional-eula.html
 * By installing or using this file, You have unconditionally agreed to the
 * terms and conditions of the License, and You may not use this file except in
 * compliance with the License.  Under the terms of the license, You shall not,
 * among other things: 1) sublicense, resell, rent, lease, redistribute, assign
 * or otherwise transfer Your rights to the Software, and 2) use the Software
 * for timesharing or service bureau purposes such as hosting the Software for
 * commercial gain and/or for the benefit of a third party.  Use of the Software
 * may be subject to applicable fees and any use of the Software without first
 * paying applicable fees is strictly prohibited.  You do not have the right to
 * remove SugarCRM copyrights from the source code or user interface.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *  (i) the "Powered by SugarCRM" logo and
 *  (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * Your Warranty, Limitations of liability and Indemnity are expressly stated
 * in the License.  Please refer to the License for the specific language
 * governing these rights and limitations under the License.  Portions created
 * by SugarCRM are Copyright (C) 2004-2010 SugarCRM, Inc.; All Rights Reserved.
 ********************************************************************************/
/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright(C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

$sugar_smarty = new Sugar_Smarty();
require_once('include/export_utils.php');
require_once('modules/Configurator/Configurator.php');
require_once('modules/Users/Forms.php');
require_once('modules/Users/UserSignature.php');
$focus = new User();


global $app_strings;
global $app_list_strings;
global $mod_strings;

$admin = new Administration();
$admin->retrieveSettings();


$is_current_admin=is_admin($current_user)
                ||is_admin_for_module($GLOBALS['current_user'],'Users');
$is_super_admin = is_admin($current_user);
if(!$is_current_admin && $_REQUEST['record'] != $current_user->id) sugar_die("Unauthorized access to administration.");

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}

if(!$is_super_admin && is_admin_for_module($GLOBALS['current_user'],'Users') && $focus->is_admin == 1) sugar_die("Unauthorized access to administrator.");


if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {
	$focus->id = "";
	$focus->user_name = "";
}else if(!isset($_REQUEST['record'])){
    define('SUGARPDF_USE_DEFAULT_SETTINGS', true);
}
	global $sugar_flavor;
	if((isset($sugar_flavor) && $sugar_flavor != null) &&
		($sugar_flavor=='CE' || isset($admin->settings['license_enforce_user_limit']) && $admin->settings['license_enforce_user_limit'] == 1)){
		if ($focus->id == "") {
		    $admin = new Administration();
		    $admin->retrieveSettings();
		    $license_users = $admin->settings['license_users'];
		    if ($license_users != ''){




		    $license_seats_needed = count( get_user_array(false, "Active", "", false, null, " AND deleted=0 AND is_group=0 AND portal_only=0 ", false) ) - $license_users;






		    }
		    else
		    	$license_seats_needed = -1;
		    if( $license_seats_needed >= 0 ){
		        displayAdminError( translate('WARN_LICENSE_SEATS_USER_CREATE', 'Administration') . translate('WARN_LICENSE_SEATS2', 'Administration')  );
				if( isset($_SESSION['license_seats_needed']))
			        unset($_SESSION['license_seats_needed']);
					//die();
				}
		}
	}

$the_query_string = 'module=Users&action=DetailView';
if(isset($_REQUEST['record'])) {
    $the_query_string .= '&record='.$_REQUEST['record'];
}
$buttons = "";
if (!$current_user->is_group){
    if ($focus->id == $current_user->id) {
        $reset_pref_warning = $mod_strings['LBL_RESET_PREFERENCES_WARNING'];
        $reset_home_warning = $mod_strings['LBL_RESET_HOMEPAGE_WARNING'];
    }
    else {
        $reset_pref_warning = $mod_strings['LBL_RESET_PREFERENCES_WARNING_USER'];
        $reset_home_warning = $mod_strings['LBL_RESET_HOMEPAGE_WARNING_USER'];
    }
	$buttons .="<input type='button' class='button' onclick='if(confirm(\"{$reset_pref_warning}\"))window.location=\"".$_SERVER['PHP_SELF'] .'?'.$the_query_string."&reset_preferences=true\";' value='".$mod_strings['LBL_RESET_PREFERENCES']."' />";
	$buttons .="&nbsp;<input type='button' class='button' onclick='if(confirm(\"{$reset_home_warning}\"))window.location=\"".$_SERVER['PHP_SELF'] .'?'.$the_query_string."&reset_homepage=true\";' value='".$mod_strings['LBL_RESET_HOMEPAGE']."' />";
}
if (isset($buttons)) $sugar_smarty->assign("BUTTONS", $buttons);

echo "\n<p>\n";
$params = array();
if(empty($focus->id)){
	$params[] = "<span class='pointer'>&raquo;</span>".$GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'];
}else{
	$params[] = "<span class='pointer'>&raquo;</span><a href='index.php?module=Users&action=DetailView&record={$focus->id}'>".$locale->getLocaleFormattedName($focus->first_name,$focus->last_name)."</a>";
	$params[] = $GLOBALS['app_strings']['LBL_EDIT_BUTTON_LABEL'];
}
echo getClassicModuleTitle("Users", $params, true);
//sugar_smarty

$sugar_smarty->assign('MOD', $mod_strings);
$sugar_smarty->assign('APP', $app_strings);

if(isset($_REQUEST['error_string'])) $sugar_smarty->assign('ERROR_STRING', '<span class="error">Error: '.$_REQUEST['error_string'].'</span>');
if(isset($_REQUEST['error_password'])) $sugar_smarty->assign('ERROR_PASSWORD', '<span id="error_pwd" class="error">Error: '.$_REQUEST['error_password'].'</span>');
if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') {

	$sugar_smarty->assign('RETURN_MODULE', $_REQUEST['return_module']);
	$sugar_smarty->assign('RETURN_ACTION', $_REQUEST['return_action']);
	$sugar_smarty->assign('RETURN_ID', $_REQUEST['record']);

} else {
	if(isset($_REQUEST['return_module'])) $sugar_smarty->assign('RETURN_MODULE', $_REQUEST['return_module']);
	else { $sugar_smarty->assign('RETURN_MODULE', $focus->module_dir);}
	if(isset($_REQUEST['return_id'])) $sugar_smarty->assign('RETURN_ID', $_REQUEST['return_id']);
	else { $sugar_smarty->assign('RETURN_ID', $focus->id); }
	if(isset($_REQUEST['return_action'])) $sugar_smarty->assign('RETURN_ACTION', $_REQUEST['return_action']);
	else { $sugar_smarty->assign('RETURN_ACTION', 'DetailView'); }
}

$sugar_smarty->assign('JAVASCRIPT',user_get_validate_record_js().user_get_chooser_js().user_get_confsettings_js().'<script type="text/javascript" language="Javascript" src="modules/Users/User.js"></script>');
$sugar_smarty->assign('PRINT_URL', 'index.php?'.$GLOBALS['request_string']);
$sugar_smarty->assign('ID', $focus->id);

$sugar_smarty->assign('USER_NAME', $focus->user_name);
$sugar_smarty->assign('FIRST_NAME', $focus->first_name);
$sugar_smarty->assign('LAST_NAME', $focus->last_name);
$sugar_smarty->assign('TITLE', $focus->title);
$sugar_smarty->assign('DEPARTMENT', $focus->department);
$sugar_smarty->assign('REPORTS_TO_ID', $focus->reports_to_id);
$sugar_smarty->assign('REPORTS_TO_NAME', get_assigned_user_name($focus->reports_to_id));
$sugar_smarty->assign('PHONE_HOME', $focus->phone_home);
$sugar_smarty->assign('PHONE_MOBILE', $focus->phone_mobile);
$sugar_smarty->assign('PHONE_WORK', $focus->phone_work);
$sugar_smarty->assign('PHONE_OTHER', $focus->phone_other);
$sugar_smarty->assign('PHONE_FAX', $focus->phone_fax);
$sugar_smarty->assign('EMAIL1', $focus->email1);
$sugar_smarty->assign('EMAIL2', $focus->email2);
$sugar_smarty->assign('ADDRESS_STREET', $focus->address_street);
$sugar_smarty->assign('ADDRESS_CITY', $focus->address_city);
$sugar_smarty->assign('ADDRESS_STATE', $focus->address_state);
$sugar_smarty->assign('ADDRESS_POSTALCODE', $focus->address_postalcode);
$sugar_smarty->assign('ADDRESS_COUNTRY', $focus->address_country);
$sugar_smarty->assign('DESCRIPTION', $focus->description);
$sugar_smarty->assign('EXPORT_DELIMITER', $focus->getPreference('export_delimiter'));
$sugar_smarty->assign('PWDSETTINGS', isset($GLOBALS['sugar_config']['passwordsetting']) ? $GLOBALS['sugar_config']['passwordsetting'] : array());
if ( isset($GLOBALS['sugar_config']['passwordsetting']) && isset($GLOBALS['sugar_config']['passwordsetting']['customregex']) ) {
    $pwd_regex=str_replace( "\\","\\\\",$GLOBALS['sugar_config']['passwordsetting']['customregex']);
    $sugar_smarty->assign("REGEX",$pwd_regex);     
}

if(!empty($GLOBALS['sugar_config']['authenticationClass'])){
		$sugar_smarty->assign('EXTERNAL_AUTH_CLASS_1', $GLOBALS['sugar_config']['authenticationClass']);
		$sugar_smarty->assign('EXTERNAL_AUTH_CLASS', $GLOBALS['sugar_config']['authenticationClass']);
}else{
	if(!empty($GLOBALS['system_config']->settings['system_ldap_enabled'])){
		$sugar_smarty->assign('EXTERNAL_AUTH_CLASS_1', $mod_strings['LBL_LDAP']);
		$sugar_smarty->assign('EXTERNAL_AUTH_CLASS', $mod_strings['LBL_LDAP_AUTHENTICATION']);
	}
}
if(!empty($focus->external_auth_only))$sugar_smarty->assign('EXTERNAL_AUTH_ONLY_CHECKED', 'CHECKED');
if ($is_current_admin)
	$sugar_smarty->assign('IS_ADMIN','1');
else
	$sugar_smarty->assign('IS_ADMIN', '0');

if ($is_super_admin)
    $sugar_smarty->assign('IS_SUPER_ADMIN','1');
else
    $sugar_smarty->assign('IS_SUPER_ADMIN', '0');


//jc:12293 - modifying to use the accessor method which will translate the
//available character sets using the translation files
$sugar_smarty->assign('EXPORT_CHARSET', get_select_options_with_id($locale->getCharsetSelect(), $locale->getExportCharset('', $focus)));
//end:12293

if( $focus->getPreference('use_real_names') == 'on' || ( empty($focus->id) && isset($GLOBALS['sugar_config']['use_real_names'])
       && $GLOBALS['sugar_config']['use_real_names'] && $focus->getPreference('use_real_names') != 'off') )
{

	$sugar_smarty->assign('USE_REAL_NAMES', 'CHECKED');
}
if($focus->getPreference('no_opps') == 'on') {
    $sugar_smarty->assign('NO_OPPS', 'CHECKED');
}

// REASSIGNMENT SCRIPT CODE
$confirmReassignJs = "
	function confirmReassignRecords() {
		var status = document.getElementsByName('status');
		if(verify_data(document.EditView)) {
			if(status[0] && status[0].value == 'Inactive'){
				var handleYes = function() {
		    		document.EditView.return_action.value = 'reassignUserRecords';
		    		document.EditView.return_module.value = 'Users';
		    		document.EditView.submit();
					};

				var handleNo = function() {
		   			 document.EditView.submit();
					};
						YAHOO.namespace(\"example.container\");
						YAHOO.example.container.simpledialog1 =
		    			new YAHOO.widget.SimpleDialog(\"simpledialog1\",
		             { width: \"300px\",
		               fixedcenter: true,
		               visible: true,
		               draggable: false,
		               close: true,
		               text: \"{$mod_strings['LBL_REASS_CONFIRM_REASSIGN']}\",
		               constraintoviewport: true,
		               buttons: [ { text:\"Yes\", handler:handleYes, isDefault:true },
		                          { text:\"No\",  handler:handleNo } ]
		             } );
		             YAHOO.example.container.simpledialog1.setHeader(\"Re-Assign\");
		             YAHOO.example.container.simpledialog1.render(\"popup_window\");
					 YAHOO.util.Event.addListener(\"Save\", \"click\", YAHOO.example.container.simpledialog1.show, YAHOO.example.container.simpledialog1, true);
			}
			else{
				document.EditView.submit();
			}
		}
		else{
			return false;
		}
	}
";

// check if the user has access to the User Management
$sugar_smarty->assign('USER_ADMIN',is_admin_for_module($current_user,'Users')&& !is_admin($current_user));


///////////////////////////////////////////////////////////////////////////////
////	NEW USER CREATION ONLY
if(empty($focus->id)) {
	$sugar_smarty->assign('SHOW_ADMIN_CHECKBOX','height="30"');
	$sugar_smarty->assign('NEW_USER','1');
}else{
	$sugar_smarty->assign('NEW_USER','0');
	$sugar_smarty->assign('NEW_USER_TYPE','DISABLED');
	$sugar_smarty->assign('confirmReassignJs', $confirmReassignJs);
	$sugar_smarty->assign('REASSIGN_JS', "return confirmReassignRecords();");
}

////	END NEW USER CREATION ONLY
///////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////
////	REDIRECTS FROM COMPOSE EMAIL SCREEN
if(isset($_REQUEST['type']) && (isset($_REQUEST['return_module']) && $_REQUEST['return_module'] == 'Emails')) {
	$sugar_smarty->assign('REDIRECT_EMAILS_TYPE', $_REQUEST['type']);
}
////	END REDIRECTS FROM COMPOSE EMAIL SCREEN
///////////////////////////////////////////////////////////////////////////////


///////////////////////////////////////////////////////////////////////////////
////	LOCALE SETTINGS
////	Date/time format
$dformat = $locale->getPrecedentPreference($focus->id?'datef':'default_date_format', $focus);
$tformat = $locale->getPrecedentPreference($focus->id?'timef':'default_time_format', $focus);
$timeOptions = get_select_options_with_id($sugar_config['time_formats'], $tformat);
$dateOptions = get_select_options_with_id($sugar_config['date_formats'], $dformat);
$sugar_smarty->assign('TIMEOPTIONS', $timeOptions);
$sugar_smarty->assign('DATEOPTIONS', $dateOptions);
///////////////////////////////////////////////////////////////////////////////
/////////  PDF SETTINGS
global $focus_user;
$focus_user = $focus;
define('SUGARPDF_USE_FOCUS', true);
include('include/Sugarpdf/sugarpdf_config.php');
$sugar_smarty->assign('PDF_CLASS',PDF_CLASS);
$sugar_smarty->assign('PDF_UNIT',PDF_UNIT);
$sugar_smarty->assign('PDF_PAGE_FORMAT_LIST',get_select_options_with_id(array_combine(explode(",",PDF_PAGE_FORMAT_LIST), explode(",",PDF_PAGE_FORMAT_LIST)), PDF_PAGE_FORMAT));
$sugar_smarty->assign('PDF_PAGE_ORIENTATION_LIST',get_select_options_with_id(array("P"=>$mod_strings["LBL_PDF_PAGE_ORIENTATION_P"],"L"=>$mod_strings["LBL_PDF_PAGE_ORIENTATION_L"]),PDF_PAGE_ORIENTATION));
$sugar_smarty->assign('PDF_MARGIN_HEADER',PDF_MARGIN_HEADER);
$sugar_smarty->assign('PDF_MARGIN_FOOTER',PDF_MARGIN_FOOTER);
$sugar_smarty->assign('PDF_MARGIN_TOP',PDF_MARGIN_TOP);
$sugar_smarty->assign('PDF_MARGIN_BOTTOM',PDF_MARGIN_BOTTOM);
$sugar_smarty->assign('PDF_MARGIN_LEFT',PDF_MARGIN_LEFT);
$sugar_smarty->assign('PDF_MARGIN_RIGHT',PDF_MARGIN_RIGHT);

require_once('include/Sugarpdf/FontManager.php');
$fontManager = new FontManager();
$fontlist = $fontManager->getSelectFontList();
$sugar_smarty->assign('PDF_FONT_NAME_MAIN',get_select_options_with_id($fontlist, PDF_FONT_NAME_MAIN));
$sugar_smarty->assign('PDF_FONT_SIZE_MAIN',PDF_FONT_SIZE_MAIN);
$sugar_smarty->assign('PDF_FONT_NAME_DATA',get_select_options_with_id($fontlist, PDF_FONT_NAME_DATA));
$sugar_smarty->assign('PDF_FONT_SIZE_DATA',PDF_FONT_SIZE_DATA);
///////// END PDF SETTINGS
////////////////////////////////////////////////////////////////////////////////
//// Timezone
if(empty($focus->id)) { // remove default timezone for new users(set later)
    $focus->user_preferences['timezone'] = '';
}
require_once('include/timezone/timezones.php');
global $timezones;

$userTZ = $focus->getPreference('timezone');
if(empty($userTZ) && !$focus->is_group && !$focus->portal_only) {
	$focus->setPreference('timezone', date('T'));
}

if(empty($userTZ) && !$focus->is_group && !$focus->portal_only)
	$userTZ = lookupTimezone();

if(!$focus->getPreference('ut')) {
	$sugar_smarty->assign('PROMPTTZ', ' checked');
}

$timezoneOptions = '';
ksort($timezones);
foreach($timezones as $key => $value) {
	$selected =($userTZ == $key) ? ' SELECTED="true"' : '';
	$dst = !empty($value['dstOffset']) ? '(+DST)' : '';
	$gmtOffset =($value['gmtOffset'] / 60);

	if(!strstr($gmtOffset,'-')) {
		$gmtOffset = '+'.$gmtOffset;
	}
  $timezoneOptions .= "<option value='$key'".$selected.">".str_replace(array('_','North'), array(' ', 'N.'),translate('timezone_dom','',$key)). "(GMT".$gmtOffset.") ".$dst."</option>";
}
$sugar_smarty->assign('TIMEZONEOPTIONS', $timezoneOptions);

//// Numbers and Currency display
require_once('modules/Currencies/ListCurrency.php');
$currency = new ListCurrency();

// 10/13/2006 Collin - Changed to use Localization.getConfigPreference
// This was the problem- Previously, the "-99" currency id always assumed
// to be defaulted to US Dollars.  However, if someone set their install to use
// Euro or other type of currency then this setting would not apply as the
// default because it was being overridden by US Dollars.
$cur_id = $locale->getPrecedentPreference('currency', $focus);
if($cur_id) {
	$selectCurrency = $currency->getSelectOptions($cur_id);
	$sugar_smarty->assign("CURRENCY", $selectCurrency);
} else {
	$selectCurrency = $currency->getSelectOptions();
	$sugar_smarty->assign("CURRENCY", $selectCurrency);
}

$currenciesVars = "";
foreach($locale->currencies as $id => $arrVal) {
	$currenciesVars .= "currencies['{$id}'] = '{$arrVal['symbol']}';\n";
}
$currencySymbolsJs = <<<eoq
var currencies = new Object;
{$currenciesVars}
function setSymbolValue(id) {
	document.getElementById('symbol').value = currencies[id];
}
eoq;
$sugar_smarty->assign('currencySymbolJs', $currencySymbolsJs);


// fill significant digits dropdown
$significantDigits = $locale->getPrecedentPreference('default_currency_significant_digits', $focus);
$sigDigits = '';
for($i=0; $i<=6; $i++) {
	if($significantDigits == $i) {
	   $sigDigits .= "<option value=\"$i\" selected=\"true\">$i</option>";
	} else {
	   $sigDigits .= "<option value=\"$i\">{$i}</option>";
	}
}

$sugar_smarty->assign('sigDigits', $sigDigits);

$num_grp_sep = $focus->getPreference('num_grp_sep');
$dec_sep = $focus->getPreference('dec_sep');
$sugar_smarty->assign("NUM_GRP_SEP",(empty($num_grp_sep) ? $sugar_config['default_number_grouping_seperator'] : $num_grp_sep));
$sugar_smarty->assign("DEC_SEP",(empty($dec_sep) ? $sugar_config['default_decimal_seperator'] : $dec_sep));
$sugar_smarty->assign('getNumberJs', $locale->getNumberJs());

//// Name display format
$sugar_smarty->assign('default_locale_name_format', $locale->getLocaleFormatMacro($focus));
$sugar_smarty->assign('getNameJs', $locale->getNameJs());
////	END LOCALE SETTINGS
///////////////////////////////////////////////////////////////////////////////

//require_once($theme_path.'config.php');


// Grouped tabs?
$useGroupTabs = $current_user->getPreference('navigation_paradigm');
if ( ! isset($useGroupTabs) ) {
    if ( ! isset($GLOBALS['sugar_config']['default_navigation_paradigm']) ) {
        $GLOBALS['sugar_config']['default_navigation_paradigm'] = 'gm';
    }
    $useGroupTabs = $GLOBALS['sugar_config']['default_navigation_paradigm'];
}
$sugar_smarty->assign("USE_GROUP_TABS",($useGroupTabs=='gm')?'checked':'');

$user_max_tabs = $focus->getPreference('max_tabs');
if(isset($user_max_tabs) && $user_max_tabs > 0) {
	$sugar_smarty->assign("MAX_TAB", $user_max_tabs);
} elseif(SugarThemeRegistry::current()->maxTabs > 0) {
    $sugar_smarty->assign("MAX_TAB", SugarThemeRegistry::current()->maxTabs);
} else {
    $sugar_smarty->assign("MAX_TAB", $GLOBALS['sugar_config']['default_max_tabs']);
}
$sugar_smarty->assign("MAX_TAB_OPTIONS", range(1, 10));

$user_subpanel_tabs = $focus->getPreference('subpanel_tabs');
if(isset($user_subpanel_tabs)) {
    $sugar_smarty->assign("SUBPANEL_TABS", $user_subpanel_tabs?'checked':'');
} else {
    $sugar_smarty->assign("SUBPANEL_TABS", $GLOBALS['sugar_config']['default_subpanel_tabs']?'checked':'');
}

$user_theme = $focus->getPreference('user_theme');
if(isset($user_theme)) {
    $sugar_smarty->assign("THEMES", get_select_options_with_id(SugarThemeRegistry::availableThemes(), $user_theme));
} else {
    $sugar_smarty->assign("THEMES", get_select_options_with_id(SugarThemeRegistry::availableThemes(), $GLOBALS['sugar_config']['default_theme']));
}
$sugar_smarty->assign("SHOW_THEMES",count(SugarThemeRegistry::availableThemes()) > 1);
$sugar_smarty->assign("USER_THEME_COLOR", $focus->getPreference('user_theme_color'));
$sugar_smarty->assign("USER_THEME_FONT", $focus->getPreference('user_theme_font'));
$sugar_smarty->assign("USER_THEME", $user_theme);

// Build a list of themes that support group modules
$sugar_smarty->assign("DISPLAY_GROUP_TAB", 'none');

$selectedTheme = $user_theme;
if(!isset($user_theme)) {
    $selectedTheme = $GLOBALS['sugar_config']['default_theme'];
}

$themeList = SugarThemeRegistry::availableThemes();
$themeGroupList = array();

foreach ( $themeList as $themeId => $themeName ) {
    $currThemeObj = SugarThemeRegistry::get($themeId);
    if ( isset($currThemeObj->group_tabs) && $currThemeObj->group_tabs == 1 ) {
        $themeGroupList[$themeId] = true;
        if ( $themeId == $selectedTheme ) {
            $sugar_smarty->assign("DISPLAY_GROUP_TAB", '');
        }
    } else {
        $themeGroupList[$themeId] = false;
    }
}
$sugar_smarty->assign("themeGroupListJSON",json_encode($themeGroupList));

$sugar_smarty->assign("MAIL_SENDTYPE", get_select_options_with_id($app_list_strings['notifymail_sendtype'], $focus->getPreference('mail_sendtype')));
$reminder_time = $focus->getPreference('reminder_time');
if(empty($reminder_time)){
	$reminder_time = -1;
}
$sugar_smarty->assign("REMINDER_TIME_OPTIONS", get_select_options_with_id($app_list_strings['reminder_time_options'],$reminder_time));
if($reminder_time > -1){
	$sugar_smarty->assign("REMINDER_TIME_DISPLAY", 'inline');
	$sugar_smarty->assign("REMINDER_CHECKED", 'checked');
}else{
	$sugar_smarty->assign("REMINDER_TIME_DISPLAY", 'none');
}
//Add Custom Fields
$xtpl = $sugar_smarty;
require_once('modules/DynamicFields/templates/Files/EditView.php');

$edit_self = $current_user->id == $focus->id;
$admin_edit_self = is_admin($current_user) && $edit_self;

if($is_current_admin) {
	$status  = "<td scope='row'><slot>".$mod_strings['LBL_STATUS'].": <span class='required'>".$app_strings['LBL_REQUIRED_SYMBOL']."</span></slot></td>\n";
	$status .= "<td><select name='status' tabindex='1'";
	if((!empty($sugar_config['default_user_name']) &&
		$sugar_config['default_user_name']== $focus->user_name &&
		isset($sugar_config['lock_default_user_name']) &&
		$sugar_config['lock_default_user_name']) || $admin_edit_self)
	{
		$status .= ' disabled="disabled" ';
	}
	$status .= ">";
	$status .= get_select_options_with_id($app_list_strings['user_status_dom'], $focus->status);
	$status .= "</select></td>\n";
	$sugar_smarty->assign("USER_STATUS_OPTIONS", $status);
}
if($is_current_admin && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){
	$record = '';
	if(!empty($_REQUEST['record'])){
		$record = 	$_REQUEST['record'];
	}
	$sugar_smarty->assign("ADMIN_EDIT","<a href='index.php?action=index&module=DynamicLayout&from_action=".$_REQUEST['action'] ."&from_module=".$_REQUEST['module'] ."&record=".$record. "'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' alt='Edit Layout' align='bottom'")."</a>");
}

if(!empty($sugar_config['default_user_name']) &&
	$sugar_config['default_user_name'] == $focus->user_name &&
	isset($sugar_config['lock_default_user_name']) &&
	$sugar_config['lock_default_user_name'])
{
	$status .= ' disabled ';
	$sugar_smarty->assign('FIRST_NAME_DISABLED', 'disabled="disabled"');
	$sugar_smarty->assign('USER_NAME_DISABLED', 'disabled="disabled"');
	$sugar_smarty->assign('LAST_NAME_DISABLED', 'disabled="disabled"');
	$sugar_smarty->assign('IS_ADMIN_DISABLED', 'disabled="disabled"');
	$sugar_smarty->assign('IS_PORTAL_ONLY_DISABLED', 'disabled="disabled"');
	$sugar_smarty->assign('IS_GROUP_DISABLED', 'disabled="disabled"');
}

if($focus->receive_notifications ||(!isset($focus->id) && $admin->settings['notify_send_by_default'])) $sugar_smarty->assign("RECEIVE_NOTIFICATIONS", "checked");
if($focus->getPreference('mailmerge_on') == 'on') {
	$sugar_smarty->assign('MAILMERGE_ON', 'checked');
}
$usertype='REGULAR';
if(!empty($focus->is_admin) && $focus->is_admin){
   $usertype='ADMIN';
}

$sugar_smarty->assign('SHOW_TEAM_SELECTION', !empty($focus->id));
$sugar_smarty->assign('IS_PORTALONLY', '0');

if (isset($sugar_config['enable_web_services_user_creation']) && $sugar_config['enable_web_services_user_creation'] &&
	(!empty($focus->portal_only) && $focus->portal_only) || (isset($_REQUEST['usertype']) && $_REQUEST['usertype']=='portal')) {
	$sugar_smarty->assign('IS_PORTALONLY', '1');
	$usertype='PORTAL_ONLY';
}


if((!empty($focus->is_group) && $focus->is_group)  || (isset($_REQUEST['usertype']) && $_REQUEST['usertype']=='group')){
	$sugar_smarty->assign('IS_GROUP', '1');
	$usertype='GROUP';
} else
	$sugar_smarty->assign('IS_GROUP', '0');

$sugar_smarty->assign("USER_TYPE_DESC", $mod_strings['LBL_'.$usertype.'_DESC']);
$sugar_smarty->assign("USER_TYPE_LABEL", $mod_strings['LBL_'.$usertype.'_USER']);
$sugar_smarty->assign('USER_TYPE',$usertype);

$enable_syst_generate_pwd=false;
if(isset($sugar_config['passwordsetting']) && isset($sugar_config['passwordsetting']['SystemGeneratedPasswordON'])){
	$enable_syst_generate_pwd=$sugar_config['passwordsetting']['SystemGeneratedPasswordON'];
}

// If new regular user without system generated password or new portal user
if(((isset($enable_syst_generate_pwd) && !$enable_syst_generate_pwd && $usertype!='GROUP') || $usertype =='PORTAL_ONLY') && empty($focus->id))
	$sugar_smarty->assign('REQUIRED_PASSWORD','1');
else
    $sugar_smarty->assign('REQUIRED_PASSWORD','0');

// If my account page or portal only user or regular user without system generated password or a duplicate user
if((($current_user->id == $focus->id) || $usertype=='PORTAL_ONLY' || (($usertype=='REGULAR' || (isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true' && $usertype!='GROUP')) && !$enable_syst_generate_pwd)) && !$focus->external_auth_only )
   $sugar_smarty->assign('CHANGE_PWD', '1');
else
   $sugar_smarty->assign('CHANGE_PWD', '0');

// Make sure group users don't get a password change prompt
if ( $usertype == 'GROUP' ) {
    $sugar_smarty->assign('CHANGE_PWD', '0');
}

$configurator = new Configurator();
if ( isset($configurator->config['passwordsetting']) && ($configurator->config['passwordsetting']['SystemGeneratedPasswordON'] || $configurator->config['passwordsetting']['forgotpasswordON'])
        && $usertype != 'GROUP' && $usertype != 'PORTAL_ONLY' )
	$sugar_smarty->assign('REQUIRED_EMAIL_ADDRESS','1');
else
	$sugar_smarty->assign('REQUIRED_EMAIL_ADDRESS','0');
if($usertype=='GROUP' || $usertype=='PORTAL_ONLY'){
	$sugar_smarty->assign('HIDE_FOR_GROUP_AND_PORTAL', 'none');
	$sugar_smarty->assign('HIDE_CHANGE_USERTYPE','none');}
else{
	$sugar_smarty->assign('HIDE_FOR_NORMAL_AND_ADMIN','none');
	if (!$is_current_admin)
		$sugar_smarty->assign('HIDE_CHANGE_USERTYPE','none');
	else
		$sugar_smarty->assign('HIDE_STATIC_USERTYPE','none');
	}

$sugar_smarty->assign('IS_FOCUS_ADMIN', is_admin($focus));

$disable_download_tab = !isset($sugar_config['disable_download_tab']) ? false : $sugar_config['disable_download_tab'];

if($edit_self && !$disable_download_tab) {
	$sugar_smarty->assign('EDIT_SELF','1');
}
if($admin_edit_self) {
	$sugar_smarty->assign('ADMIN_EDIT_SELF','1');
}



/////////////////////////////////////////////
/// Handle email account selections for users
/////////////////////////////////////////////
 $hide_if_can_use_default = true;
if( !($usertype=='GROUP' || $usertype=='PORTAL_ONLY') )
{
    // email smtp
    $systemOutboundEmail = new OutboundEmail();
    $systemOutboundEmail = $systemOutboundEmail->getSystemMailerSettings();
    $mail_smtpserver = $systemOutboundEmail->mail_smtpserver;
    $mail_smtptype = $systemOutboundEmail->mail_smtptype;
    $mail_smtpport = $systemOutboundEmail->mail_smtpport;
    $mail_smtpssl = $systemOutboundEmail->mail_smtpssl;
    $mail_smtpuser = "";
    $mail_smtppass = "";
    $mail_smtpdisplay = $systemOutboundEmail->mail_smtpdisplay;
    $hide_if_can_use_default = true;
    if( !$systemOutboundEmail->isAllowUserAccessToSystemDefaultOutbound() )
    {
        $userOverrideOE = $systemOutboundEmail->getUsersMailerForSystemOverride($current_user->id);
        if($userOverrideOE != null) {
            $mail_smtpuser = $userOverrideOE->mail_smtpuser;
            $mail_smtppass = $userOverrideOE->mail_smtppass;
        }

        if(empty($systemOutboundEmail->mail_smtpserver) || empty($systemOutboundEmail->mail_smtpuser) || empty($systemOutboundEmail->mail_smtppass)){
            $hide_if_can_use_default = true;
        }
        else{
            $hide_if_can_use_default = false;
        }
    }
    $sugar_smarty->assign("mail_smtpdisplay", $mail_smtpdisplay);
    $sugar_smarty->assign("mail_smtpserver", $mail_smtpserver);
    $sugar_smarty->assign("mail_smtpuser", $mail_smtpuser);
    $sugar_smarty->assign("mail_smtppass", $mail_smtppass);
    $sugar_smarty->assign('MAIL_SMTPPORT',$mail_smtpport);
    $sugar_smarty->assign('MAIL_SMTPSSL',$mail_smtpssl);
}
$sugar_smarty->assign('HIDE_IF_CAN_USE_DEFAULT_OUTBOUND',$hide_if_can_use_default);

$reports_to_change_button_html = '';

if($is_current_admin) {
	//////////////////////////////////////
	///
	/// SETUP USER POPUP

	$reportsDisplayName = showFullName() ? 'name' : 'user_name';
	$popup_request_data = array(
		'call_back_function' => 'set_return',
		'form_name' => 'EditView',
		'field_to_name_array' => array(
			'id' => 'reports_to_id',
			"$reportsDisplayName" => 'reports_to_name',
			),
		);

	$json = getJSONobj();
	$encoded_popup_request_data = $json->encode($popup_request_data);
	$sugar_smarty->assign('encoded_popup_request_data', $encoded_popup_request_data);

	//
	///////////////////////////////////////

	$reports_to_change_button_html = '<input type="button"'
	. " title=\"{$app_strings['LBL_SELECT_BUTTON_TITLE']}\""
	. " accesskey=\"{$app_strings['LBL_SELECT_BUTTON_KEY']}\""
	. " value=\"{$app_strings['LBL_SELECT_BUTTON_LABEL']}\""
	. ' tabindex="5" class="button" name="btn1"'
	. " onclick='open_popup(\"Users\", 600, 400, \"\", true, false, {$encoded_popup_request_data});'"
	. "' />";
} else {
	$sugar_smarty->assign('IS_ADMIN_DISABLED', 'disabled="disabled"');
}
$sugar_smarty->assign('REPORTS_TO_CHANGE_BUTTON', $reports_to_change_button_html);

/* Module Tab Chooser */
require_once('include/templates/TemplateGroupChooser.php');
require_once('modules/MySettings/TabController.php');
$chooser = new TemplateGroupChooser();
$controller = new TabController();


if($is_current_admin || $controller->get_users_can_edit()) {
	$chooser->display_hide_tabs = true;
} else {
	$chooser->display_hide_tabs = false;
}

$chooser->args['id'] = 'edit_tabs';
$chooser->args['values_array'] = $controller->get_tabs($focus);
foreach($chooser->args['values_array'][0] as $key=>$value) {
    $chooser->args['values_array'][0][$key] = $app_list_strings['moduleList'][$key];
}

foreach($chooser->args['values_array'][1] as $key=>$value) {
    $chooser->args['values_array'][1][$key] = $app_list_strings['moduleList'][$key];
}

foreach($chooser->args['values_array'][2] as $key=>$value) {
    $chooser->args['values_array'][2][$key] = $app_list_strings['moduleList'][$key];
}

$chooser->args['left_name'] = 'display_tabs';
$chooser->args['right_name'] = 'hide_tabs';

$chooser->args['left_label'] =  $mod_strings['LBL_DISPLAY_TABS'];
$chooser->args['right_label'] =  $mod_strings['LBL_HIDE_TABS'];
$chooser->args['title'] =  $mod_strings['LBL_EDIT_TABS'].' <img border="0" src="themes/default/images/helpInline.gif" onmouseover="return overlib(\'Choose which tabs are displayed.\', FGCLASS, \'olFgClass\', CGCLASS, \'olCgClass\', BGCLASS, \'olBgClass\', TEXTFONTCLASS, \'olFontClass\', CAPTIONFONTCLASS, \'olCapFontClass\', CLOSEFONTCLASS, \'olCloseFontClass\', WIDTH, -1, NOFOLLOW, \'ol_nofollow\' );" onmouseout="return nd();"/>';
$sugar_smarty->assign('TAB_CHOOSER', $chooser->display());
$sugar_smarty->assign('CHOOSER_SCRIPT','set_chooser();');
$sugar_smarty->assign('CHOOSE_WHICH', $mod_strings['LBL_CHOOSE_WHICH']);
///////////////////////////////////////////////////////////////////////////////
////	EMAIL OPTIONS
// We need to turn off the requiredness of emails if it is a group or portal user
if ($usertype == 'GROUP' || $usertype == 'PORTAL_ONLY' ) {
    global $dictionary;
    $dictionary['User']['fields']['email1']['required'] = false;
}
// hack to disable email field being required if it shouldn't be required
if ( $sugar_smarty->get_template_vars("REQUIRED_EMAIL_ADDRESS") == '0' )
    $GLOBALS['dictionary']['User']['fields']['email1']['required'] = false;
$sugar_smarty->assign("NEW_EMAIL", getEmailAddressWidget($focus, "email1", $focus->email1, "EditView"));
// hack to undo that previous hack
if ( $sugar_smarty->get_template_vars("REQUIRED_EMAIL_ADDRESS") == '0' )
    $GLOBALS['dictionary']['User']['fields']['email1']['required'] = true;
$sugar_smarty->assign('EMAIL_LINK_TYPE', get_select_options_with_id($app_list_strings['dom_email_link_type'], $focus->getPreference('email_link_type')));
/////	END EMAIL OPTIONS
///////////////////////////////////////////////////////////////////////////////


if ($is_current_admin) {
$employee_status = '<select tabindex="5" name="employee_status">';
$employee_status .= get_select_options_with_id($app_list_strings['employee_status_dom'], $focus->employee_status);
$employee_status .= '</select>';
} else {
	$employee_status = $focus->employee_status;
}
$sugar_smarty->assign('EMPLOYEE_STATUS_OPTIONS', $employee_status);
$sugar_smarty->assign('EMPLOYEE_STATUS_OPTIONS', $employee_status);

$messenger_type = '<select tabindex="5" name="messenger_type">';
$messenger_type .= get_select_options_with_id($app_list_strings['messenger_type_dom'], $focus->messenger_type);
$messenger_type .= '</select>';
$sugar_smarty->assign('MESSENGER_TYPE_OPTIONS', $messenger_type);
$sugar_smarty->assign('MESSENGER_ID', $focus->messenger_id);

$sugar_smarty->assign('CALENDAR_PUBLISH_KEY', $focus->getPreference('calendar_publish_key' ));
//$sugar_smarty->parse('main.freebusy');


//sugar_smarty


$xtpl->assign("ENJAY_EXT_C", $focus->enjay_phoneextension_c);
//$xtpl->assign('ENJAY_HOST_C', $focus->enjay_host_c);
//$xtpl->assign('ENJAY_MODULE_MODE1_C',  $focus->enjay_module_mode1_c);
//$xtpl->assign('ENJAY_MODULE_MODE_C',  $focus->enjay_module_mode_c);
//$xtpl->assign('ENJAY_INCALL_C',  $focus->enjay_incall_c);
//$xtpl->assign('ENJAY_OUTCALL_C',  $focus->enjay_outcall_c);

$xtpl->assign("ENJAY_HOST_C", $focus->enjay_synapsename_c);
$xtpl->assign("ENJAY_DIAL_PLAN_C", $focus->enjay_dial_plan_c);
$xtpl->assign("ENJAY_DIALOUT_C", $focus->enjay_dialout_prefix_c);



if ($focus->enjay_showclicktocall_c)
{
$xtpl->assign("ENJAY_SHOWCLICKTOCALL_C", 'checked');
}
else
{
$xtpl->assign("ENJAY_SHOWCLICKTOCALL_C", '');
}


if ($focus->enjay_call_notification_c)
{
    $xtpl->assign("ENJAY_CALLNOTIFICATION_C", 'checked');
}
else
{
    $xtpl->assign("ENJAY_CALLNOTIFICATION_C", '');
}
if ($focus->enjay_relate_contact_c)
{
    $xtpl->assign("ENJAY_RELATECONTACT_C", 'checked');
}
else
{
    $xtpl->assign("ENJAY_RELATECONTACT_C", '');
}
// </abcona>
if ($focus->enjay_relate_account_c)
{
    $sugar_smarty->assign("ENJAY_RELATEACCOUNT_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_RELATEACCOUNT_C", '');
}
if ($focus->enjay_create_account_c)
{
    $sugar_smarty->assign("ENJAY_CREATEACCOUNT_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_CREATEACCOUNT_C", '');
}

if ($focus->enjay_create_lead_c)
{
    $sugar_smarty->assign("ENJAY_CREATELEAD_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_CREATELEAD_C", '');
}
if ($focus->enjay_create_contact_c)
{
    $sugar_smarty->assign("ENJAY_CREATECONTACT_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_CREATECONTACT_C", '');
}

if ($focus->enjay_call_transfer_c)
{
    $sugar_smarty->assign("ENJAY_CALLTRANSFER_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_CALLTRANSFER_C", '');
}

if ($focus->enjay_call_hangup_c)
{
    $sugar_smarty->assign("ENJAY_CALLHANGUP_C", 'checked');
}
else
{
    $sugar_smarty->assign("ENJAY_CALLHANGUP_C", '');
}
if ($focus->enjay_lastcall_c)
{
$xtpl->assign("ENJAY_LASTCALL_C", 'checked');
}
else
{
$xtpl->assign("ENJAY_LASTCALL_C", '');
}








/////////////////////////
$sugar_smarty->display('custom/modules/Users/EditView.tpl');
$json = getJSONobj();

require_once('include/QuickSearchDefaults.php');
$qsd = new QuickSearchDefaults();
$sqs_objects = array('EditView_reports_to_name' => $qsd->getQSUser());
$sqs_objects['EditView_reports_to_name']['populate_list'] = array('reports_to_name', 'reports_to_id');

if(!empty($focus->id)) {
	$sqs_objects = array_merge($sqs_objects, $teamsWidget->createQuickSearchCode(false));
}

$quicksearch_js = '<script type="text/javascript" language="javascript">
                    sqs_objects = ' . $json->encode($sqs_objects) . '; enableQS();</script>';
echo $quicksearch_js;

$savedSearch = new SavedSearch();
$savedSearchSelects = $json->encode(array($GLOBALS['app_strings']['LBL_SAVED_SEARCH_SHORTCUT'] . '<br>' . $savedSearch->getSelect('Users')));
$str = "<script>
YAHOO.util.Event.addListener(window, 'load', SUGAR.util.fillShortcuts, $savedSearchSelects);
</script>";
//echo $str;
//BUG #16298
?>
