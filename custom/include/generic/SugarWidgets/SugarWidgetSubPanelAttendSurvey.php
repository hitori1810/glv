<?php
/**
 * When Customer not submit survey at that time admin can submit survey
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
include_once('include/generic/SugarWidgets/SugarWidgetField.php');

//custom to get rid of create button on events

class SugarWidgetSubPanelAttendSurvey extends SugarWidgetField {

    function displayHeaderCell($layout_def)
    {
            return '';
    }
    function displayList(&$layout_def) {

        //getting survey url 
        global $sugar_config;
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $survey_url_for_email = $administrationObj->settings['SurveyPlugin_survey_url_for_email'];
        $sugar_survey_Url = $sugar_config['site_url'] . $survey_url_for_email; //create survey submission url

        $html = '';
        //current module name whom we send the survey
        $linked_field = explode('_', $layout_def['linked_field']);
        $module = ucfirst($linked_field[3]);
        //survey related information
        $survey = array();
        $osurvey = $layout_def['fields'];
        foreach ($osurvey as $key => $value) {
            $survey[$key] = $value;
        }
        $oSurveySubmission = BeanFactory::getBean('bc_survey_submission',$survey['ID']);
        $oSurveyList = $oSurveySubmission->get_linked_beans('bc_survey_submission_bc_survey','bc_survey');
        foreach($oSurveyList as $objSurvey)
        {
            $Survey_id = $objSurvey->id;
        }
        //if survey is send anf recipient has not submitted the survey so sender can submit survey using Attend Survey button
        
        if ($survey['RESUBMIT'] != 0 || ($survey['STATUS'] == 'Pending' && $survey['SURVEY_SEND'] != 0)) {
             $html .= "<script type='text/javascript'>
                        function attend_survey_inBehalf_of_customer(surveyUrl){
                            if(confirm('Are you sure want to submit survey in behalf of client?'))
                            {
                              window.open(surveyUrl);
                            }
                        }
                    </script>";
             $file_name=str_replace("survey_id","q",$survey_url_for_email);
             $pure_data = $Survey_id . '&ctype=' . $module . '&cid=' . $_REQUEST['record'];
             $encoded_data = base64_encode($pure_data);
             $new_url=$sugar_config['site_url'] . $file_name . $encoded_data;
            $html.='<input type="button" name="attend_survey" value="Attend Survey" title="Attend Survey In behalf of Customer" onclick="attend_survey_inBehalf_of_customer(\'' . $new_url . '\')" />';
        } else {
            $html = '';
        }
        return $html;
    }

}

?>
