<?php

/**
 * Send All submission Data To report.tpl file
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once('include/MVC/View/SugarView.php');
require_once("include/Sugar_Smarty.php");
//require_once("include/SugarSmarty/Sugar_Smarty.php");
require_once 'custom/include/utilsfunction.php';

/**
 * generate report data and pass to report.tpl file
 *
 * @author     Original Author Biztech Co
 */
class ViewSurveysmtp extends SugarView {

    function __construct() {
        parent::SugarView();
    }

    function display() {

        global $app_strings, $mod_strings, $current_user, $sugar_config;
/*        $administrationObj = new Administration();
        $administrationObj->retrieveSettings('SurveyPlugin');
        $LastValidation = $administrationObj->settings['SurveyPlugin_LastValidation'];

        if (!$LastValidation) {
            echo '<div style="color: #F11147;text-align: center;background: #FAD7EC;padding: 10px;margin: 3% auto;width: 70%;top: 50%;left: 0;right: 0;border: 1px solid #F8B3CC;font-size : 14px;">Please validate your licence.</div>';
        } else {     */
            $custom_Smtp_Settings = new Administration();
            $custom_Smtp_Settings->retrieveSettings('SurveySmtp');
            $custom_smtp_css_path =  "custom/include/css/survey_css/survey.css";
            $sugarSmarty = new Sugar_Smarty();
            $survey_smtp_email_provider = (empty($custom_Smtp_Settings->settings['SurveySmtp_survey_smtp_email_provider'])) ? 'other' : $custom_Smtp_Settings->settings['SurveySmtp_survey_smtp_email_provider'];
            $sugarSmarty->assign("mail_sendtype", $custom_Smtp_Settings->settings['mail_sendtype']);
            $sugarSmarty->assign("survey_notify_fromname", $custom_Smtp_Settings->settings['SurveySmtp_survey_notify_fromname']);
            $sugarSmarty->assign("survey_notify_fromaddress", $custom_Smtp_Settings->settings['SurveySmtp_survey_notify_fromaddress']);
            $sugarSmarty->assign("survey_smtp_email_provider", $survey_smtp_email_provider);
            $sugarSmarty->assign("survey_mail_smtp_host", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtp_host']);
            $sugarSmarty->assign("survey_mail_smtpport", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtpport']);
            $sugarSmarty->assign("survey_mail_smtpssl", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtpssl']);
            $sugarSmarty->assign("survey_mail_smtp_username", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtp_username']);
            $sugarSmarty->assign("survey_mail_smtp_password", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtp_password']);
            $sugarSmarty->assign("survey_mail_smtp_retype_password", $custom_Smtp_Settings->settings['SurveySmtp_survey_mail_smtp_retype_password']);
            $sugarSmarty->assign("APP", $app_strings);
            $sugarSmarty->assign('custom_smtp_css_path', $custom_smtp_css_path);
            $sugarSmarty->assign("MOD", return_module_language('en_us', 'EmailMan'));
            $sugarSmarty->display('custom/modules/Administration/tpl/survey_smtp.tpl');

            parent::display();
/*        }       */
    }

}
