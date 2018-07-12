<?php
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is not bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/

$dictionary['J_Coursefee'] = array(
    'table'=>'j_coursefee',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'code' =>
        array (
            'required' => false,
            'name' => 'code',
            'vname' => 'LBL_CODE',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'calculated' => false,
            'len' => '100',
            'size' => '20',
        ),
        'number_of_skill' =>
        array (
            'required' => false,
            'name' => 'number_of_skill',
            'vname' => 'LBL_NUMBER_OF_SKILL',
            'type' => 'int',
            'len' => 10,
            'size' => '5',
        ),
        'number_of_connect' =>
        array(
            'required' => false,
            'name' => 'number_of_connect',
            'vname' => 'LBL_NUMBER_OF_CONNECT',
            'type' => 'int',
            'len' => 10,
            'size' => '5',
        ),
        'number_of_practice' =>
        array(
            'required' => false,
            'name' => 'number_of_practice',
            'vname' => 'LBL_NUMBER_OF_PRACTICE',
            'type' => 'int',
            'len' => 10,
            'size' => '5',
        ),
        'type' =>
        array (
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => 'Hours',
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 20,
            'size' => '20',
            'options' => 'type_coursefee_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'type_of_course_fee' =>
        array (
            'required' => true,
            'name' => 'type_of_course_fee',
            'vname' => 'LBL_TYPE_OF_COURSE_FEE',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'type_of_course_fee_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'is_accumulate' =>
        array (
            'name' => 'is_accumulate',
            'vname' => 'Is Add Loyalty Reward', //Áp dụng chain discount
            'type' => 'bool',
            'default' => '0',
        ),
        'apply_for' => array (
            'required' => true,
            'name' => 'apply_for',
            'vname' => 'LBL_APPLY_FOR',
            'type' => 'multienum',
            'isMultiSelect' => true,
            'massupdate' => 0,
            'default' => '',
            'no_default' => false, 
            'importable' => false,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'kind_of_course_list',
            'studio' => 'visible',
            'dependency' => false,
//            'function'=> 'getAcademicProgramOptionsForVardefWithoutBlank',
        ),
    ),
    'relationships'=>array (
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Coursefee','J_Coursefee', array('basic','team_security','assignable'));