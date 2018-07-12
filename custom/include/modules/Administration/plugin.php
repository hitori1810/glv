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

function validateSurveySubscription() {
//    require_once('modules/Administration/Administration.php');
//    $administrationObj = new Administration();
//    $administrationObj->retrieveSettings('SurveyPlugin');
//    $LastValidation = $administrationObj->settings['SurveyPlugin_LastValidation'];
//    $LastValidationDate = isset($administrationObj->settings['SurveyPlugin_LastValidationDate']) ? $administrationObj->settings['SurveyPlugin_LastValidationDate'] : "";
//    $LastValidationMsg = $administrationObj->settings['SurveyPlugin_LastValidationMsg'];
//    $LastValidationDate = strtotime($LastValidationDate);
//    $CurrentDate = strtotime(date("Y/m/d"));
//    $response = array();
//    if (($CurrentDate == $LastValidationDate) && ($LastValidation == 1)) {
        $response['success'] = true;
//        $response['message'] = html_entity_decode($LastValidationMsg);
//                } else {
//        $response['success'] = false;
//        $response['message'] = html_entity_decode($LastValidationMsg);
//    }
    return $response;
}