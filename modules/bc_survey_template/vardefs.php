<?php
/**
 * table structure of survey template module
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */

$dictionary['bc_survey_template'] = array(
    'table' => 'bc_survey_template',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'name' =>
        array(
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'link' => true,
            'dbType' => 'varchar',
            'len' => '255',
            'unified_search' => false,
            'full_text_search' =>
            array(
                'boost' => 3,
            ),
            'required' => true,
            'importable' => 'required',
            'duplicate_merge' => 'disabled',
            'merge_filter' => 'disabled',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'size' => '20',
            'inline_edit' => false,
        ),
        'survey_page' =>
        array(
            'name' => 'survey_page',
            'vname' => 'LBL_SURVEYPAGE',
            'source' => 'non-db',
            'type' => 'AddSurveyPagefield',
            'inline_edit' => false,
        ),
          'create_survey' =>
        array(
            'name' => 'create_survey',
	    'labelValue' => 'Create Survey', 
            'vname' => 'LBL_CREATE_SURVEY',
            'type' => 'html',
            'source' => 'non-db',
            'inline_edit' => false,
        ),

    ),
    'relationships' => array(
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);
if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('bc_survey_template','bc_survey_template', array('basic','assignable'));
$dictionary["bc_survey_template"]["fields"]["description"] ["inline_edit"]= false;