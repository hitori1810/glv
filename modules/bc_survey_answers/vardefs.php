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

$dictionary['bc_survey_answers'] = array(
    'table' => 'bc_survey_answers',
    'audited' => true,
    'duplicate_merge' => true,
    'fields' => array(
        'description' =>
        array(
            'name' => 'description',
            'vname' => 'LBL_DESCRIPTION',
            'type' => 'text',
            'comment' => 'Full text of the note',
            'rows' => '6',
            'cols' => '80',
            'required' => false,
            'massupdate' => 0,
            'no_default' => false,
            'comments' => 'Full text of the note',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'size' => '20',
            'studio' => 'visible',
        ),
        'answer_sequence' =>
        array(
            'required' => false,
            'name' => 'answer_sequence',
            'vname' => 'LBL_ANSWER_SEQUENCE',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '11',
            'size' => '20',
        ),
        'score_weight' =>
        array(
            'required' => false,
            'name' => 'score_weight',
            'vname' => 'LBL_SCORE_WEIGHT',
            'type' => 'int',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => '11',
            'size' => '20',
        ),
        'logic_target' =>
        array(
            'required' => false,
            'name' => 'logic_target',
            'vname' => 'LBL_LOGIC_TARGET',
            'type' => 'text',
            'dbType' => 'varchar',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 1000,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
    ),
        'logic_action' =>
        array(
            'required' => false,
            'name' => 'logic_action',
            'vname' => 'LBL_LOGIC_ACTION',
            'type' => 'text',
            'dbType' => 'varchar',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'ans_type' =>
        array(
            'required' => false,
            'name' => 'ans_type',
            'vname' => 'LBL_ANSWER_TYPE',
            'type' => 'text',
            'dbType' => 'varchar',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'len' => 255,
            'size' => '20',
            'studio' => 'visible',
            'dependency' => false,
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
VardefManager::createVardef('bc_survey_answers', 'bc_survey_answers', array('basic', 'assignable'));
