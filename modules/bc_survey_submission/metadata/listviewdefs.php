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
$listViewDefs [$module_name] = array(
            'BC_SURVEY_SUBMISSION_BC_SURVEY_NAME' =>
            array(
                'type' => 'relate',
                'link' => true,
                'label' => 'LBL_BC_SURVEY_SUBMISSION_BC_SURVEY_FROM_BC_SURVEY_TITLE',
                'id' => 'BC_SURVEY_SUBMISSION_BC_SURVEYBC_SURVEY_IDA',
                'width' => '10%',
                'default' => true,
            ),
            'NAME' =>
            array(
                'width' => '32%',
		'label' => 'LBL_NAME', 
                'default' => false,
                'link' => true,
                'usage' => 'query_only',
            ),
            'MODULE_NAME' =>
            array(
                'type' => 'varchar',
                'label' => 'LBL_MODULE_NAME',
                'width' => '10%',
		'default' => true,
            ),
            'CUSTOMER_NAME' =>
            array(
                'type' => 'varchar',
                'label' => 'LBL_CUSTOMER_NAME',
                'width' => '10%',
                'default' => true,
            ),
            'SCHEDULE_ON' =>
            array(
                'type' => 'datetimecombo',
                'label' => 'LBL_SCHEDULE_ON',
                'width' => '10%',
                'default' => true,
            ),
            'SUBMISSION_DATE' =>
            array(
                'type' => 'datetimecombo',
                'label' => 'LBL_SUBMISSION_DATE',
                'width' => '10%',
                'default' => true,
            ),
            'SURVEY_SEND' =>
            array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_SURVEY_SEND',
                'width' => '10%',
            ),
            'EMAIL_OPENED' =>
            array(
                'type' => 'bool',
                'default' => true,
                'label' => 'LBL_EMAIL_OPENED',
                'width' => '10%',
            ),
            'STATUS' =>
            array(
                'type' => 'enum',
                'default' => true,
                'studio' => 'visible',
                'label' => 'LBL_STATUS',
                'width' => '10%',
            ),
            'BASE_SCORE' =>
            array(
                'type' => 'int',
                'label' => 'LBL_BASE_SCORE',
                'width' => '10%',
                'default' => true,
            ),
            'OBTAIN_SCORE' =>
            array(
                'type' => 'int',
                'label' => 'LBL_OBTAIN_SCORE',
                'width' => '10%',
                'default' => true,
            ),
            'SCORE_PERCENTAGE' =>
            array(
                'type' => 'int',
                'default' => true,
                'label' => 'LBL_SCORE_PERCENTAGE',
                'width' => '10%',
            ),
            'ASSIGNED_USER_NAME' =>
            array(
                'width' => '9%',
		'label' => 'LBL_ASSIGNED_TO_NAME',
		'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
                'default' => false,
            ),
);
