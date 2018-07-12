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






global $app_strings;
global $currentModule;
global $theme;
global $focus;
global $action;
global $focus_list;

//we don't want the parent module's string file, but rather the string file specifc to this subpanel
global $current_language;

$current_module_strings = return_module_language($current_language, 'ProjectTask');
$project_module_strings = return_module_language($current_language, 'Project');




// focus_list is the means of passing data to a SubPanelView.

$button  = "<form action='index.php' method='post' name='form' id='form'>\n";
$button .= "<input type='hidden' name='module' value='ProjectTask' />\n";
$button .= "<input type='hidden' name='parent_id' value='{$focus->id}' />\n";
$button .= "<input type='hidden' name='parent_name' value='{$focus->name}' />\n";
$button .= "<input type='hidden' name='project_relation_id' value='{$focus->id}' />\n";
$button .= "<input type='hidden' name='project_relation_type' value='$currentModule' />\n";
$button .= "<input type='hidden' name='return_module' value='$currentModule' />\n";
$button .= "<input type='hidden' name='return_action' value='$action' />\n";
$button .= "<input type='hidden' name='return_id' value='{$focus->id}' />\n";
$button .= "<input type='hidden' name='action' />\n";

$button .= "<input title='"
	. $app_strings['LBL_NEW_BUTTON_TITLE']
	. "' accessyKey='".$app_strings['LBL_NEW_BUTTON_KEY']
	. "' class='button' onclick=\"this.form.action.value='EditView'\" type='submit' name='New' value='  "
	. $app_strings['LBL_NEW_BUTTON_LABEL']."  ' />\n";

$button .= "</form>\n";

$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/ProjectTask/SubPanelView.html',$current_module_strings);
$ListView->xTemplateAssign("EDIT_INLINE_PNG",
	SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));
$ListView->xTemplateAssign("RETURN_URL",
	"&return_module=".$currentModule."&return_action=DetailView&return_id=".$focus->id);

$header_text = '';
if(is_admin($current_user)
	&& $_REQUEST['module'] != 'DynamicLayout'
	&& !empty($_SESSION['editinplace']))
{
	$header_text = " <a href='index.php?action=index"
		. "&module=DynamicLayout"
		. "&from_action=SubPanelView"
		. "&from_module=ProjectTask"
		. "'>"
		.SugarThemeRegistry::current()->getImage("EditLayout", "border='0' align='bottom'"
,null,null,'.gif',$mod_strings['LBL_EDITLAYOUT'])."</a>";
}
$ListView->setHeaderTitle($project_module_strings['LBL_PROJECT_TASK_SUBPANEL_TITLE'] . $header_text);

$ListView->setHeaderText($button);
$ListView->setQuery('', '', 'order_number', 'project_task');
$ListView->processListView($focus_list, 'main', 'project_task');

?>