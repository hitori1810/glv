<?php
/**
 * This file is used copy all file from package to instance file when the package is install
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class bc_surveyViewSurveyConfiguration extends SugarView
{
    function display()
    {
        global $sugar_version;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $LastValidation = $administrationObj->settings['SurveyPlugin_LastValidation'];
        $ModuleEnabled = $administrationObj->settings['SurveyPlugin_ModuleEnabled'];

        $licenseKey = (!empty($administrationObj->settings['SurveyPlugin_LicenseKey'])) ? $administrationObj->settings['SurveyPlugin_LicenseKey'] : "";

         $re_sugar_version= '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }
        //$LastValidation = 0;
        $html = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody><tr><td colspan="100"><h2><div class="moduleTitle">
                <h2>Survey Configuration </h2>
                <div class="clear"></div></div>
                </h2></td></tr>
                <tr><td colspan="100">
	            <div class="add_table" style="margin-bottom:5px">
		        <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			    <tr><th align="left" colspan="4" scope="row"><h4>Validate License</h4></th></tr>
			    <tr>
			    <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic"> License Key : </label></td>
            	<td nowrap="nowrap" style="width: 15%;"><input name="licence_key" id="licence_key" size="30" maxlength="255" value="'.$licenseKey.'" title="" accesskey="9" type="text"></td>
            	<td nowrap="nowrap" style="width: 20%;"><input title="Validate" id="Validate" class="button primary" onclick="validateLicence(this);" name="validate" value="Validate" type="button">&nbsp;<input title="Clear" id="clearkey" class="button primary" onclick="clearKey();" name="clear" value="Clear" type="button"></td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr></tbody></table></div>';
        $display_enable = "display:none;";
        $display_validate = "display:block;";
        if ($LastValidation == 1) {
            $display_enable = "display:block;";
            $display_validate = "display:none;";
        }
        $html .= '<table class="actionsContainer" style="' . $display_validate . '" border="0" cellpadding="1" cellspacing="1">
		        <tbody><tr><td>
				<input title="Back" accesskey="l" class="button" onclick="redirectToindex();" name="button" value="Back" type="button">
			    </td></tr>
            	</tbody></table>
                </td></tr></tbody></table>';
        $html .= '<div class="add_table" id="enableDiv" style="margin-bottom:5px;' . $display_enable . '">
		        <table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
			    <tbody>
			    <tr><th align="left" colspan="4" scope="row"><h4>Enable/Disable Module</h4></th></tr>
                <tr>
			    <td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic"> Enable/Disable </label></td>
            	<td nowrap="nowrap" style="width: 15%;">
            	<select name="enable" id="enable">';
        if ($ModuleEnabled == "1") {
            $html .= '<option value="1" selected="">Enable</option>
            	     <option value="0">Disable</option>';
        } else {
            $html .= '<option value="1">Enable</option>
            	     <option value="0" selected="">Disable</option>';
        }
        $html .= '</select>
            	</td>
            	<td nowrap="nowrap" style="width: 20%;">&nbsp;</td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr>
                </tbody></table>
	            </div>
	            <table class="actionsContainerEnableDiv" style="' . $display_enable . '" border="0" cellpadding="1" cellspacing="1">
		        <tbody><tr><td>
				<input title="Save" accesskey="a" class="button primary" onclick="enableSurveyPlugin();" name="button" value="Save" type="submit">
				<input title="Cancel" accesskey="l" class="button" onclick="redirectToindex();" name="button" value="Cancel" type="button">
			    </td></tr>
            	</tbody></table>
                </td></tr></tbody></table>';

        $html .= '<script type="text/javascript">
                    $("document").ready(function(){
                    $("#error_span").remove();
                    })
                    function clearKey(){
                    $("#error_span").remove();
                    $("#licence_key").val("");
                    }
                    function redirectToindex(){
                        location.href = "index.php?module=Administration&action=index";
                    }
                   function validateLicence(element){
                    $("#error_span").remove();
                    var key = $("#licence_key");
                        if(key.val().trim() == ""){
                            $("#clearkey").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter valid Licence key.</span>")
                            key.focus();
                            return false;
                        }else{
                             $.ajax({
                                url:"index.php?module=bc_survey&action=validateLicence",
                                type:"POST",
                                data:{"k": key.val()},
                                beforeSend : function(){
                                    $("#clearkey").after("<img style=\'color:red;padding-left: 10px;vertical-align: middle;\' id=\'survey_loader\' src= "+SUGAR.themes.loading_image+">");
                                    $(element).attr("disabled","disabled");
                                },
                                complete : function(){
                                    $("#survey_loader").remove();
                                    $(element).removeAttr("disabled");
                                },
                                success:function(result){
                                result = $.parseJSON(result);
                                $("#survey_loader").remove();
                                $("#enSelect option[value=0]").prop("selected", true);
                                        if(result[\'suc\'] == 1){
                                            $("#enableDiv").show();
                                            $(".actionsContainerEnableDiv").show();
                                            $(".actionsContainer").hide();
                                            $("#clearkey").after("<span style=\'color:green;padding-left: 10px;\' id=\'error_span\'>License validated successfully.</span>");
                                        }else{
                                            $("#clearkey").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>"+result[\'msg\']+"</span>");
                                            $("#enableDiv").hide();
                                            $(".actionsContainerEnableDiv").hide();
                                            $(".actionsContainer").show();
                                        }
                                    }
                                });
                            }
                        }
                    function enableSurveyPlugin(){
                            var enabled = $("#enable").val();
                            if($("#licence_key").val() != ""){
                             $.ajax({
                                url:"index.php?module=bc_survey&action=enableDisableSurvey",
                                data :{"enabled" : enabled},
                                type:"POST",
                                success:function(result){
                                    if(enabled == "1"){
                                    alert("Module enabled successfully.");
                                    }else{
                                    alert("Module disabled successfully.");
                                    }
                                    location.href = "index.php?module=Administration&action=index";
                                  }
                                });
                                }else{
                                    alert("Please enter valid license key.");
                        }
                        }
                  </script>';
        parent::display();
        echo $html;
    }
}

?>

