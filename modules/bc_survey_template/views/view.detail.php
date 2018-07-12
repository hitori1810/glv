<?php
/**
 * include js and css at detailview of survey template module
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once 'include/MVC/View/views/view.detail.php';
include_once 'custom/include/pagination.class.php';

class bc_survey_templateViewDetail extends ViewDetail {

    function __construct() {
        parent::ViewDetail();
    }

    function preDisplay() {
        parent::preDisplay();
        echo '<link href="custom/include/modules/bc_survey_template/css/survey.css" rel="stylesheet">';
        echo '<link href="custom/include/css/survey_css/pagination.css" rel="stylesheet">';
    }

    function display() {
        parent::display();
        echo '<link href="custom/include/modules/bc_survey_template/css/survey.css" rel="stylesheet">';
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
        }
    }
}

