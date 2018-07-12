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

* Description: TODO:  To be written.
* Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
* All Rights Reserved.
* Contributor(s): ______________________________________..
********************************************************************************/

global $current_user;
$workflow_modules = get_workflow_admin_modules_for_user($current_user);
if (!is_admin($current_user) && empty($workflow_modules))
{
    sugar_die("Unauthorized access to WorkFlow.");
}



///////////Show related config option
//Set to true - show related dynamic fields
//Set to false - do not show related dynamic fields
$show_related = true;

global $app_strings;
global $app_list_strings;
global $mod_strings;

$focus = new EmailTemplate();

if(isset($_REQUEST['record'])) {
    $focus->retrieve($_REQUEST['record']);
}
$old_id = '';

if(isset($_REQUEST['isDuplicate']) && $_REQUEST['isDuplicate'] == 'true') 
{
    if (! empty($focus->filename) )
    {	
        $old_id = $focus->id;
    }
    $focus->id = "";
}



//setting default flag value so due date and time not required
if (!isset($focus->id)) $focus->date_due_flag = 1;

//needed when creating a new case with default values passed in
if (isset($_REQUEST['contact_name']) && is_null($focus->contact_name)) {
    $focus->contact_name = $_REQUEST['contact_name'];
}
if (isset($_REQUEST['contact_id']) && is_null($focus->contact_id)) {
    $focus->contact_id = $_REQUEST['contact_id'];
}
if (isset($_REQUEST['parent_name']) && is_null($focus->parent_name)) {
    $focus->parent_name = $_REQUEST['parent_name'];
}
if (isset($_REQUEST['parent_id']) && is_null($focus->parent_id)) {
    $focus->parent_id = $_REQUEST['parent_id'];
}
if (isset($_REQUEST['parent_type'])) {
    $focus->parent_type = $_REQUEST['parent_type'];
}
elseif (!isset($focus->parent_type)) {
    $focus->parent_type = $app_list_strings['record_type_default_key'];
}

if (isset($_REQUEST['filename']) && $_REQUEST['isDuplicate'] != 'true') {
    $focus->filename = $_REQUEST['filename'];
}

echo getClassicModuleTitle($mod_strings['LBL_MODULE_ID'], array($mod_strings['LBL_ALERT_TEMPLATES'],$focus->name), true); 

$GLOBALS['log']->info("EmailTemplate detail view");

$xtpl=new XTemplate ('modules/WorkFlow/WorkFlowEditView.html');
$xtpl->assign("MOD", $mod_strings);
$xtpl->assign("APP", $app_strings);

//Edit by Bui Kim Tung - 29/09/2015
$typeOptions = get_select_options_with_id($mod_strings['LBL_EMAILTEMPLATES_TYPE_LIST_WORKFLOW'],'workflow');
$smsSelected = "";
if ($focus->type == "sms") $smsSelected = "selected";
$typeOptions .= "<option value='sms' ".$smsSelected.">SMS</option>";
$typeOptions = str_replace("\n","",$typeOptions);
$xtpl->assign("TYPEDROPDOWN", $typeOptions);

if (isset($_REQUEST['return_module'])) $xtpl->assign("RETURN_MODULE", $_REQUEST['return_module']);
if (isset($_REQUEST['return_action'])) $xtpl->assign("RETURN_ACTION", $_REQUEST['return_action']);
if (isset($_REQUEST['return_id'])) $xtpl->assign("RETURN_ID", $_REQUEST['return_id']);
$xtpl->assign("PRINT_URL", "index.php?".$GLOBALS['request_string']);
$xtpl->assign("JAVASCRIPT", get_set_focus_js());
$xtpl->assign("ID", $focus->id);
if (isset($focus->name)) $xtpl->assign("NAME", $focus->name); else $xtpl->assign("NAME", "");
if (isset($focus->description)) $xtpl->assign("DESCRIPTION", $focus->description); else $xtpl->assign("DESCRIPTION", "");
if (isset($focus->subject)) $xtpl->assign("SUBJECT", $focus->subject); else $xtpl->assign("SUBJECT", "");
$admin = new Administration();
$admin->retrieveSettings();
if ($focus->from_name != "") $xtpl->assign("FROM_NAME", $focus->from_name); else $xtpl->assign("FROM_NAME",$admin->settings['notify_fromname']);
if ($focus->from_address != "") $xtpl->assign("FROM_ADDRESS", $focus->from_address); else $xtpl->assign("FROM_ADDRESS",$admin->settings['notify_fromaddress']);
if ( $focus->published == 'on')
{
    $xtpl->assign("PUBLISHED","CHECKED");
}


/////////////////////////Workflow Area/////////////////////////////





if(!empty($_REQUEST['base_module']) && $_REQUEST['base_module']!=""){

    $focus->base_module = $_REQUEST['base_module'];
}
//
$xtpl->assign("BASE_MODULE", $focus->base_module);
$xtpl->assign("BASE_MODULE_TRANSLATED", $app_list_strings['moduleList'][$focus->base_module]);
$xtpl->assign("VALUE_TYPE", get_select_options_with_id($app_list_strings['wflow_array_type_dom'],""));

//////////////////////////End WorkFlow Area////////////////////////////

$xtpl->assign("LBL_CONTACT",$app_list_strings['moduleList']['Contacts']);
$xtpl->assign("LBL_ACCOUNT",$app_list_strings['moduleList']['Accounts']);

$xtpl->assign("OLD_ID", $old_id );

if (isset($focus->parent_type) && $focus->parent_type != "") {
    $change_parent_button = "<input title='".$app_strings['LBL_SELECT_BUTTON_TITLE']."' tabindex='3' type='button' class='button' value='".$app_strings['LBL_SELECT_BUTTON_LABEL']."' name='button' LANGUAGE=javascript onclick='return window.open(\"index.php?module=\"+ document.EditView.parent_type.value + \"&action=Popup&html=Popup_picker&form=TasksEditView\",\"test\",\"width=600,height=400,resizable=1,scrollbars=1\");'>";
    $xtpl->assign("CHANGE_PARENT_BUTTON", $change_parent_button);
}
if ($focus->parent_type == "Account") $xtpl->assign("DEFAULT_SEARCH", "&query=true&account_id=$focus->parent_id&account_name=".urlencode($focus->parent_name));

$xtpl->assign("DESCRIPTION", $focus->description);
$xtpl->assign("TYPE_OPTIONS", get_select_options_with_id($app_list_strings['record_type_display'], $focus->parent_type));

if(!empty($focus->body)){
    $xtpl->assign("ALT_CHECKED", 'checked');
}

if (isset($focus->body)) $xtpl->assign("BODY", $focus->body); else $xtpl->assign("BODY", "");
if (isset($focus->body_html)) $xtpl->assign("BODY_HTML", $focus->body_html); else $xtpl->assign("BODY_HTML", "");

//////////////////////////////////
require_once('include/SugarTinyMCE.php');
$tiny = new SugarTinyMCE();
$tiny->defaultConfig['apply_source_formatting']=true;
$tiny->defaultConfig['cleanup_on_startup']=true;
$tiny->defaultConfig['relative_urls']=false;
$tiny->defaultConfig['convert_urls']=false;
$ed = $tiny->getInstance('body_html');
$xtpl->assign("tiny", $ed);
$edValue = $focus->body_html ;
$xtpl->assign("HTMLAREA",$edValue);
$xtpl->parse("main.htmlarea");
//////////////////////////////////

echo <<<EOQ
	  <script>
      // Edit by Bui Kim Tung - customize function insert_variable_html for SMS type
	  function insert_variable_html(text) {
        if($("select[name='type']").val() == "sms"){ // insert varible to poision of pointer
            var poisionStart = $('#alt_text').prop("selectionStart");
            var poisionEnd = $('#alt_text').prop("selectionEnd");
            var content = $('#alt_text').val();
            
            contentPart1 = content.substring(0,poisionStart);
            contentPart2 = content.substring(poisionEnd,content.length);
            content = contentPart1 + $("#variable_text").val() + contentPart2;
            
            $('#alt_text').val(content);
        }     
        else{
            var inst = tinyMCE.getInstanceById('body_html');
            if (inst) inst.getWin().focus();
            inst.execCommand('mceInsertContent', false, text);
        }             
      }
	  </script>
EOQ;

//Add Custom Fields
require_once('modules/DynamicFields/templates/Files/EditView.php');

/*
Only allowed the related module dynamic fields 
if the config setting "show_related" in this file is set to true
*/

if($show_related==true){
    $xtpl->parse("main.special");
    $xtpl->parse("main.special2");
}

if($show_related==false){
    $xtpl->parse("main.normal");
}

$xtpl->parse("main");

$xtpl->out("main");

$javascript = new javascript();
$javascript->setFormName('EditView');
$javascript->setSugarBean($focus);
$javascript->addAllFields('');
echo $javascript->getScript(); 

echo <<<EOQ
      <script>
      //Generate variable for SMS template
      function copy_text(target_iframe, target_field){ 
        var iframe_object = window.frames[target_iframe].document;
        var inner_text = iframe_object.getElementById('target_dropdown').value;
        var front_text = iframe_object.getElementById('ext1').value;
        var evaluated = false;
        if (inner_text == "href_link") {
            var value_type = "href_link";
            var evaluated = true;
        }
        if (inner_text == "invite_link") {
            var value_type = "invite_link";
            var evaluated = true;
        }
        if (evaluated == false) {
            if (iframe_object.getElementById('ext2').value == 'base_fields') {
                var value_type = get_value('value_type');
            } else {
                var value_type = "future";
            }
        }
        if ($("select[name='type']").val() == "sms") var total_text = '$' + inner_text;
        else var total_text = '{::' + value_type + '::' + front_text + '::' + inner_text + '::}';
        this.document.getElementById(target_field).value = total_text;
      }
      // Add by Bui Kim Tung - show/hide tiny text area when change alert template type
      function displayTinymce(){
        if($("select[name='type']").val() == "sms"){
            //tinymce.EditorManager.execCommand('mceRemoveControl', true, "body_html");
            $("#body_html_tbl").hide();
            $("#alt_text_check").hide();
            $("#tr_from_name").hide();
            $("#tr_from_address").hide();
            $("#tr_subject").hide();
            $('#value_type').hide();
            $('#text_div').show();
        } 
        else{
            //tinymce.EditorManager.execCommand('mceAddControl', true, "body_html");
            $("#body_html_tbl").show();
            $("#alt_text_check").show();
            $("#tr_from_name").show();
            $("#tr_from_address").show();
            $("#tr_subject").show();
            $('#value_type').show();
        }
      }
      
      SUGAR.util.doWhen("typeof $('#body_html_tbl').val() != 'undefined'", function(){
            displayTinymce();
        });
      </script>
EOQ;
?>
