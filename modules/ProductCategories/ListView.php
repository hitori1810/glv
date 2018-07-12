<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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

/*********************************************************************************

 * Description:  
 ********************************************************************************/


$header_text = '';
global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;
global $currentModule;

$GLOBALS['displayListView'] = true; 
$focus = new ProductCategory();
echo getClassicModuleTitle($focus->module_dir, array($mod_strings['LBL_MODULE_NAME']), true);
$is_edit = false;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
	if($result == null)
    {
    	sugar_die($app_strings['ERROR_NO_RECORD']);
    }
		$is_edit=true;
}
if(isset($_REQUEST['edit']) && $_REQUEST['edit']=='true') {
	$is_edit=true;
	//Only allow admins to enter this screen
	if (!is_admin($current_user) && !is_admin_for_module($GLOBALS['current_user'],'Products')) {
		$GLOBALS['log']->error("Non-admin user ($current_user->user_name) attempted to enter the ProductCategories edit screen");
		session_destroy();
		include('modules/Users/Logout.php');
	}
}

$GLOBALS['log']->info("ProductCategory list view");
global $theme;



$button  = "<form border='0' action='index.php' method='post' name='form'>\n";
$button .= "<input type='hidden' name='module' value='ProductCategories'>\n";
$button .= "<input type='hidden' name='action' value='EditView'>\n";
$button .= "<input type='hidden' name='edit' value='true'>\n";
$button .= "<input type='hidden' name='return_module' value='".$currentModule."'>\n";

$action = isset($_REQUEST['return_action']) ? $_REQUEST['return_action'] : 'EditView';
$button .= "<input type='hidden' name='return_action' value='".$action."'>\n";
$button .= "<input title='".$app_strings['LBL_NEW_BUTTON_TITLE']."' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']."' class='button primary' type='submit' name='New' value='".$app_strings['LBL_NEW_BUTTON_LABEL']."' id='btn_create'>\n";
$button .= "</form>\n";

$ListView = new ListView();
if(is_admin($current_user) && $_REQUEST['module'] != 'DynamicLayout' && !empty($_SESSION['editinplace'])){	
		$header_text = "&nbsp;<a href='index.php?action=index&module=DynamicLayout&from_action=ListView&from_module=".$_REQUEST['module'] ."'>".SugarThemeRegistry::current()->getImage("EditLayout","border='0' align='bottom'",null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";

}
$ListView->initNewXTemplate( 'modules/ProductCategories/ListView.html',$mod_strings);
$ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_DELETE']));

$ListView->setHeaderTitle($header_text.$button);

$ListView->show_export_button = false;
$ListView->show_mass_update = false;
$ListView->show_delete_button = false;
$ListView->show_select_menu = false;
$ListView->setQuery("", "", "list_order", "PRODUCTCATEGORY");
$ListView->processListView($focus, "main", "PRODUCTCATEGORY");
?>