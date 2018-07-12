<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

require_once('modules/Accounts/views/view.list.php');

class CustomAccountsViewList extends AccountsViewList {

    public function preDisplay() {
        parent::preDisplay();
        $this->lv->targetList = true;
        require_once('custom/include/modules/Administration/plugin.php');
        $checkSurveySubscription = validateSurveySubscription();
        if (!$checkSurveySubscription['success']) {

        } else {
            $this->lv->actionsMenuExtraItems[] = $this->buildMyMenuItem();
            $this->lv->actionsMenuExtraItems[] = $this->pollMenuItem();
        }
    }

    protected function buildMyMenuItem() {
        global $app_strings, $current_user,$sugar_version;
        echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/survey.css">';
        echo '<link rel="stylesheet" type="text/css" href="custom/include/css/survey_css/jquery.datetimepicker.css">';
        echo '<script type="text/javascript" src="custom/include/js/survey_js/custom_code.js">';
        $module_name = (!empty($_REQUEST['module'])) ? $_REQUEST['module'] : $this->module;
        $type = 'survey';
        $re_sugar_version = '/(6\.4\.[0-9])/';
        if (preg_match($re_sugar_version, $sugar_version)) {
            return "<a id='send_survey' onclick=\"getListRecords('{$module_name}','{$type}');\" class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='javascript:void(0);' style='width:150px'>Send Survey</a>";
        } else {
            return "<a id='send_survey' onclick=\"getListRecords('{$module_name}','{$type}');\">Send Survey</a>";
        }
    }

    protected function pollMenuItem() {
        global $app_strings, $current_user,$sugar_version;
        $module_name = (!empty($_REQUEST['module'])) ? $_REQUEST['module'] : $this->module;
        $re_sugar_version = '/(6\.4\.[0-9])/';
        $type = 'poll';
        if (preg_match($re_sugar_version, $sugar_version)) {
            return "<a id='send_poll' onclick=\"getListRecords('{$module_name}','{$type}');\" class='menuItem' onmouseover='hiliteItem(this,\"yes\");' onmouseout='unhiliteItem(this);' href='javascript:void(0);' style='width:150px'>Send Poll</a>";
        } else {
            return "<a id='send_poll' onclick=\"getListRecords('{$module_name}','{$type}');\">Send Poll</a>";
}
    }

}
