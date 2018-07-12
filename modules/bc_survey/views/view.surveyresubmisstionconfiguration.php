<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');

class bc_surveyViewSurveyReSubmisstionConfiguration extends SugarView {

    function display() {
        global $sugar_version;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $reSubmitCount = (!empty($administrationObj->settings['SurveyPlugin_ReSubmitCount'])) ? $administrationObj->settings['SurveyPlugin_ReSubmitCount'] : 1;

        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }
        $html = '<table border="0" cellpadding="0" cellspacing="0" width="100%">
                <tbody>
                <tr>
                    <td colspan="100">
                        <div class="moduleTitle">
                            <h4>Survey Re-submission Configuration</h4>
                            <div class="clear"></div>
                        </div>
                    </td>
                </tr>
                <tr><td colspan="100">
	        <div class="add_table" style="margin-bottom:5px">
		<table id="ConfigureSurvey" class="themeSettings edit view" style="margin-bottom:0px;" border="0" cellpadding="0" cellspacing="0">
		<tbody>
		<tr><th align="left" colspan="4" scope="row"><h4>&nbsp;</h4></th></tr>
		<tr><td scope="row" nowrap="nowrap" style="width: 10%;"><label for="name_basic" style="position: relative;top: 5px;"> Re-submission Count : </label></td>
            	<td nowrap="nowrap" style="width: 15%;"><input name="resubmitcount" id="resubmitcount" size="30" maxlength="255" value="' . $reSubmitCount . '" title="" accesskey="9" type="text"></td>
            	<td nowrap="nowrap" style="width: 20%;"><input title="Submit" id="submit" class="button primary" onclick="submitReSubmitCount($(this));" name="submit" value="Submit" type="button">
                <input title="Cancel" id="cancel" class="button primary" onclick="redirectToindex();" name="cancel" value="Cancel" type="button"></td>
            	<td nowrap="nowrap" style="width: 55%;">&nbsp;</td>
            	</tr></tbody></table></div>
                <script>
                function submitReSubmitCount(element){
                 $("#error_span").remove();
                 var regX = /^\d+$/;  
                 var resubmitcount = $("#resubmitcount");
                    if(resubmitcount.val().trim() == ""){
                        $("#cancel").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter Count.</span>")
                        resubmitcount.focus();
                        return false;
                    }else if(regX.test(resubmitcount.val()) == false){
                        $("#cancel").after("<span style=\'color:red;padding-left: 10px;\' id=\'error_span\'>Please enter Valid Re-Submission Count.</span>")
                        resubmitcount.focus();
                        return false;
                    }else{
                        $.ajax({
                        url:"index.php?module=bc_survey&action=submitReSubmitCount",
                        type:"POST",
                        data:{"resubmitcount": $("#resubmitcount").val()},
                        beforeSend : function(){
                            $("#cancel").after("<img style=\'color:red;padding-left: 10px;vertical-align: middle;\' id=\'survey_loader\' src= "+SUGAR.themes.loading_image+">");
                            $(element).attr("disabled","disabled");
                        },
                        complete : function(){
                            $("#survey_loader").remove();
                            $(element).removeAttr("disabled");
                        },
                        success:function(result){
                          $("#cancel").after("<span style=\'color:green;padding-left: 10px;\' id=\'error_span\'> Successfully submitted.</span>")
                        }
                        });
                    }  
                }
                function redirectToindex(){
                        location.href = "index.php?module=Administration&action=index";
                }
                </script>';
        parent::display();
        echo $html;
    }

}
?>

