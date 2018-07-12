<?php
/**
 * include js and css at editview of survey template module
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once 'include/MVC/View/views/view.edit.php';
require_once("include/Sugar_Smarty.php");

class bc_survey_templateViewEdit extends ViewEdit {

    function __construct() {
        parent::ViewEdit();
    }

    function preDisplay() {
        parent::preDisplay();
        echo '<link href="custom/include/modules/bc_survey_template/css/survey.css" rel="stylesheet">';
    }

    function display() {
        echo '<link type="text/css" rel="stylesheet" href="custom/include/css/survey_css/multiple-select.css"></link>';
        global $sugar_config, $current_user;
        $re_suite_version = '/(7\.[0-9].[0-9])/';
        if ($sugar_config['suitecrm_version'] != '' && preg_match($re_suite_version, $sugar_config['suitecrm_version'])) {
            $current_theme = $current_user->getPreference('user_theme');
            $random_num = mt_rand();
            $file = "custom/include/css/survey_css/general_drag_drop.css";
            if (file_exists($file) && $current_theme == 'SuiteR') {
                echo "<link href='{$file}?{$random_num}' rel='stylesheet'>";
            } else {
                echo "<link href='custom/include/css/survey_css/suite7_drag_drop.css?{$random_num}' rel='stylesheet'>";
            }
        } else {
            echo "<link rel='stylesheet' href='custom/include/css/survey_css/general_drag_drop.css' type='text/css'>";
        }
        require_once('custom/include/modules/Administration/plugin.php');
        $checkSurveySubscription = validateSurveySubscription();
        if (!$checkSurveySubscription['success']) {
            if (!empty($checkSurveySubscription['message'])) {
                echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">' . $checkSurveySubscription['message'] . '</div>';
            }
        } else {
            if (!empty($checkSurveySubscription['message'])) {
                echo '<div style="color: #f11147;font-size: 14px;left: 0;text-align: center;top: 50%;">' . $checkSurveySubscription['message'] . '</div>';
            }
            parent::display();
        }
    }

}

?>
