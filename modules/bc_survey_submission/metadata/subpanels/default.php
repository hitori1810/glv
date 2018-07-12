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


$module_name = 'bc_survey_submission';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),
    'where' => '',
    'list_fields' => array(
        'survey_name' =>
        array(
            'name' => 'survey_name',
            'vname' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_TITLE',
            'module' => 'bc_survey',
            'widget_class' => 'SubPanelSurveyResponseViewLink',
            'target_record_key' => 'id',
            'target_module' => 'bc_survey',
            'width' => '10%',
        ),
        'email_opened' =>
        array(
            'type' => 'bool',
            'default' => true,
            'vname' => 'LBL_EMAIL_OPENED',
            'width' => '10%',
        ),
        'survey_send' =>
        array(
            'type' => 'bool',
            'default' => true,
            'vname' => 'LBL_SURVEY_SEND',
            'width' => '10%',
        ),
        'schedule_on' =>
        array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_SCHEDULE_ON',
            'width' => '10%',
            'default' => true,
        ),
        'status' =>
        array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
        ),
          'resubmit' =>
        array(
            'type' => 'bool',
            'default' => true,
            'vname' => 'LBL_RESUBMIT',
            'width' => '10%',
        ),
        'submitted_by' =>
        array(
            'type' => 'bool',
            'default' => true,
            'vname' => 'LBL_SUBMITTED_BY',
            'width' => '10%',
        ),
        
       'score_percentage' =>
        array(
            'type' => 'int',
            'default' => true,
            'vname' => 'LBL_SCORE_PERCENTAGE',
            'width' => '10%',
        ), 
        'attend_survey' =>
        array(
            'vname' => 'LBL_ATTEND_SURVEY',
            'module' => 'bc_survey',
            'widget_class' => 'SubPanelAttendSurvey',
            'target_record_key' => 'id',
            'target_module' => 'bc_survey',
            'width' => '2%',
    ),
    ),
);
?>