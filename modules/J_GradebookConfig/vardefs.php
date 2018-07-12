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

$dictionary['J_GradebookConfig'] = array(
    'table'=>'j_gradebookconfig',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'kind_of_course' =>
        array (
            'required' => true,
            'name' => 'kind_of_course',
            'vname' => 'LBL_KIND_OF_COURSE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
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
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'kind_of_course_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        //Bổ sung quan hệ  Kind of Course - Class
        'koc_name' =>
        array(
            'required'  => true,
            'source'    => 'non-db',
            'name'      => 'koc_name',
            'vname'     => 'LBL_KOC_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'koc_id',
            'join_name' => 'j_kindofcourse',
            'link'      => 'kindofcourse_config',
            'table'     => 'j_kindofcourse',
            'isnull'    => 'true',
            'module'    => 'J_Kindofcourse',
            'additionalFields' => array('id' => 'koc_id'),
        ),
        'koc_id' =>
        array(
            'name'              => 'koc_id',
            'rname'             => 'id',
            'vname'             => 'LBL_KOC_ID',
            'type'              => 'id',
            'table'             => 'j_kindofcourse',
            'isnull'            => 'true',
            'module'            => 'J_Kindofcourse',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),
        'kindofcourse_config' =>
        array(
            'name'          => 'kindofcourse_config',
            'type'          => 'link',
            'relationship'  => 'kindofcourse_gradeconfig',
            'module'        => 'J_Kindofcourse',
            'bean_name'     => 'J_Kindofcourse',
            'source'        => 'non-db',
            'vname'         => 'LBL_KOC_NAME',
        ),
        'level' =>
        array (
            'required' => true,
            'name' => 'level',
            'vname' => 'LBL_LEVEL',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
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
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'level_program_list',
            'studio' => 'visible',
            'dependency' => false,
        ),

        'type' => array(
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'len' => '20',
            'options' => 'gradeconfig_type_options',
        ),
        'minitest' => array(
            'name' => 'minitest',
            'vname' => 'LBL_MINITEST',
            'type' => 'enum',
            'len' => '20',
            'options' => 'gradeconfig_minitest_options',
        ),

        'weight' => array(
            'required' => false,
            'name' => 'weight',
            'vname' => 'LBL_WEIGHT',
            'type' => 'decimal',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '6',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
            'default' => '',
        ),

        'content' => array(
            'name' => 'content',
            'vname' => 'LBL_CONTENT',
            'type' => 'text',
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
VardefManager::createVardef('J_GradebookConfig','J_GradebookConfig', array('basic','team_security','assignable'));