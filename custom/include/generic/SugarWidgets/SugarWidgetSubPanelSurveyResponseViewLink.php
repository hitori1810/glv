<?php
/**
 * In module subpanel we can see the response of customers.
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

class SugarWidgetSubPanelSurveyResponseViewLink extends SugarWidgetField {

    function displayList(&$layout_def) {
        include_once 'custom/include/pagination.class.php';
        global $focus, $current_user;
        $current_theme = $current_user->getPreference('user_theme');
        $random_num = mt_rand();
        $file = "custom/include/css/survey_css/survey-popup.css";

        $record = '';
        if (isset($layout_def['varname'])) {
            $key = strtoupper($layout_def['varname']);
        } else {
            $key = $this->_get_column_alias($layout_def);
            $key = strtoupper($key);
        }
        if (empty($layout_def['fields'][$key])) {
            return "";
        } else {
            $value = $layout_def['fields'][$key];
        }

        if (empty($layout_def['target_record_key'])) {
            $record = $layout_def['fields']['ID'];
        } else {
            $record_key = strtoupper($layout_def['target_record_key']);
            $record = $layout_def['fields'][$record_key];
        }

        $cssFile = '';
        if (file_exists($file)) {
            $cssFile = "<link href='{$file}?{$random_num}' rel='stylesheet'>";
        }
        $oSurveySubmission = new bc_survey_submission();
        $oSurveySubmission->retrieve($record);
        $oSurveySubmission->load_relationship('bc_survey_submission_bc_survey');
        $survey_id = $oSurveySubmission->bc_survey_submission_bc_surveybc_survey_ida;
        $value = $layout_def['fields'][$key];
        $html .= '<link href="custom/include/css/survey_css/pagination.css" rel="stylesheet">' . $cssFile;
        if (!empty($record) &&
                ($layout_def['DetailView'] && !$layout_def['owner_module'] || $layout_def['DetailView'] && !ACLController::moduleSupportsACL($layout_def['owner_module']) || ACLController::checkAccess($layout_def['owner_module'], 'view', $layout_def['owner_id'] == $current_user->id))) {
            $link = "javascript:getReports('{$survey_id}',1,'{$focus->id}','{$record}' );";
            return '<a href="' . $link . '" >' . "$value</a>" . $html;
        } else {
            return $value;
        }
    }

}
?>