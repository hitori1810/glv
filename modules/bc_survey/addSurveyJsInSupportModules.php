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
require_once('custom/include/modules/Administration/plugin.php');

class addSurveyJsInSupportModules {

    function getSurveyScripts(&$bean, $event, $arguments = array()) {
        global $current_user, $sugar_version;

        $dateformat = $current_user->getPreference('datef');
        $timeformat = $current_user->getPreference('timef');
        $supportModulesArray = array('Accounts', 'Contacts', 'Leads', 'ProspectLists', 'Prospects', 'Users');
        $excludeAction = array(
            'DynamicAction',
            'modulelistmenu',
            'favorites',
            'EmailUIAjax',
            'wizard'
        );
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }
        if (!in_array($_REQUEST['action'], $excludeAction) && empty($_REQUEST['to_pdf']) && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && isset($current_user->id) && (in_array($_REQUEST['module'], $supportModulesArray) && ($_REQUEST['action'] == 'index' || $_REQUEST['action'] == 'DetailView'))) {
            echo "<script type='text/javascript' src='custom/include/js/survey_js/custom_code.js'></script>";
            $dateformat = $current_user->getPreference('datef');
            $timeformat = $current_user->getPreference('timef');
            echo '<script>
              var curr_user_date_format = "' . $dateformat . '" ;
              var curr_user_time_format = "' . $timeformat . '" ;
              localStorage["curr_user_date_formatVal"] = curr_user_date_format;
              localStorage["curr_user_time_formatVal"] = curr_user_time_format;
              </script>';
             echo "<script type='text/javascript' src='custom/include/js/survey_js/jquery.datetimepicker.js'></script>";
            echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/survey.css">';
            echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/jquery.datetimepicker.css">';
        }
    }

    function checkSurveySubscription(&$bean, $event, $arguments) {
        require_once('custom/include/modules/Administration/plugin.php');
        require_once('modules/Administration/Administration.php');
        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $LicenceKey = $administrationObj->settings['SurveyPlugin_LicenceKey'];
        $LastValidation = $administrationObj->settings['SurveyPlugin_LastValidation'];
        $LastValidationDate = $administrationObj->settings['SurveyPlugin_LastValidationDate'];
        $LastValidationDate = strtotime($LastValidationDate);
        $CurrentDate = strtotime(date("Y/m/d"));
        if ((($CurrentDate > $LastValidationDate) || ($LastValidation != 1)) && !empty($LicenceKey)) {
            $CheckResult = checkPluginLicence($LicenceKey);
        }
    }

    function getSurveyScriptsForSurvey(&$bean, $event, $arguments = array()) {
        global $current_user, $sugar_version;

        $dateformat = $current_user->getPreference('datef');
        $timeformat = $current_user->getPreference('timef');
        $excludeAction = array(
            'DynamicAction',
            'modulelistmenu',
            'favorites',
            'EmailUIAjax',
            'wizard'
        );
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }
        if (!in_array($_REQUEST['action'], $excludeAction) && empty($_REQUEST['to_pdf']) && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && isset($current_user->id) && ($_REQUEST['module'] == "bc_survey" && $_REQUEST['action'] == 'EditView')) {
            $dateformat = $current_user->getPreference('datef');
            $timeformat = $current_user->getPreference('timef');
            echo '<script>
              var curr_user_date_format = "' . $dateformat . '" ;
              var curr_user_time_format = "' . $timeformat . '" ;
              localStorage["curr_user_date_formatVal"] = curr_user_date_format;
              localStorage["curr_user_time_formatVal"] = curr_user_time_format;
              </script>';
            echo '<script type="text/javascript" src="custom/include/js/survey_js/jquery.datetimepicker.js"></script>';
            echo '<script type="text/javascript" src="custom/include/modules/bc_survey_template/js/custom_setting.js"></script>';
            echo '<script type="text/javascript" src="custom/include/modules/bc_survey/js/custom_survey_setting.js"></script>';
            echo '<script type="text/javascript" src="custom/include/js/survey_js/moment.js"></script>';
        } else if (!in_array($_REQUEST['action'], $excludeAction) && empty($_REQUEST['to_pdf']) && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && isset($current_user->id) && ($_REQUEST['module'] == "bc_survey" && $_REQUEST['action'] == 'DetailView')) {
            echo '<script type="text/javascript" src="custom/include/modules/bc_survey/js/custom_survey_setting.js"></script>';
        } else if ($_REQUEST['module'] == "bc_survey" && $_REQUEST['action'] == 'index') { // Remove ajax url while using Quick create for support datetime picker.
            echo '<script>
                var generalUrl = "index.php?module=bc_survey&action=EditView&return_module=bc_survey&return_action=DetailView";
                document.getElementById("create_link").setAttribute("href",generalUrl);
              </script>';
        }
    }

    function getSurveyScriptsForSurveyTemplate(&$bean, $event, $arguments = array()) {
        global $current_user, $sugar_version;

        $dateformat = $current_user->getPreference('datef');
        $timeformat = $current_user->getPreference('timef');
        $excludeAction = array(
            'DynamicAction',
            'modulelistmenu',
            'favorites',
            'EmailUIAjax',
            'wizard'
        );
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.11.1.min.js"></script>';
            echo '<script type="text/javascript" src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>';
            echo '<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css" />';
        }

        if (!in_array($_REQUEST['action'], $excludeAction) && empty($_REQUEST['to_pdf']) && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && isset($current_user->id) && ($_REQUEST['module'] == "bc_survey_template" && $_REQUEST['action'] == 'EditView')) {
            $dateformat = $current_user->getPreference('datef');
            $timeformat = $current_user->getPreference('timef');
            echo '<script>
              var curr_user_date_format = "' . $dateformat . '" ;
              var curr_user_time_format = "' . $timeformat . '" ;
              localStorage["curr_user_date_formatVal"] = curr_user_date_format;
              localStorage["curr_user_time_formatVal"] = curr_user_time_format;
              </script>';
            echo '<script type="text/javascript" src="custom/include/js/survey_js/jquery.datetimepicker.js"></script>';
            echo '<script type="text/javascript" src="custom/include/modules/bc_survey_template/js/custom_setting.js"></script>';
        } else if (!in_array($_REQUEST['action'], $excludeAction) && empty($_REQUEST['to_pdf']) && (!empty($_REQUEST['module']) && $_REQUEST['module'] != 'ModuleBuilder') && empty($_REQUEST['to_csv']) && isset($current_user->id) && ($_REQUEST['module'] == "bc_survey_template" && $_REQUEST['action'] == 'DetailView')) {
            echo '<script type="text/javascript" src="custom/include/modules/bc_survey_template/js/custom_setting.js"></script>';
        }
    }
    function create_survey_button($bean, $event, $arguments)
        {
           $bean->create_survey='<input type="button" name="create_survey" id="create_survey" value="Create Survey" onclick="window.open(\'index.php?module=bc_survey&action=EditView&template_id='.$bean->id.'&return_module=bc_survey_template&return_action=index\',\'_blank\')">';
                
        }

}
