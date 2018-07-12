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

$dictionary['J_Kindofcourse'] = array(
    'table'=>'j_kindofcourse',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'is_upgrade' =>
        array (
            'name' => 'is_upgrade',
            'vname' => 'LBL_IS_UPGRADE',
            'type' => 'bool',
            'source' => 'non-db',
            'audited' => false,
            'reportable' => true,
        ),
        'is_set_hour' =>
        array (
            'name' => 'is_set_hour',
            'vname' => 'LBL_IS_SET_HOUR',
            'type' => 'bool',
            'source' => 'non-db',
            'audited' => false,
            'reportable' => true,
        ),
        'kind_of_course' => array (
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
        ),
        'kind_of_course_adult' => array (
            'required' => true,
            'name' => 'kind_of_course_adult',
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
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'kind_of_course_360_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'year' =>
        array (
            'name' => 'year',
            'vname' => 'LBL_YEAR',
            'type' => 'enum',
            'default' => '2016',
            'len' => 20,
            'size' => '20',
            'options' => 'year_list',
            'studio' => 'visible',
            'massupdate' => 0,
        ),
		'short_course_name' =>
        array (
            'required' => false,
            'name' => 'short_course_name',
            'vname' => 'LBL_SHORT_COURSE_NAME',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'calculated' => false,
            'len' => '50',
            'size' => '20',
        ),
        'status' => array (
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => 1,
            'default' => 'Active',
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
            'options' => 'status_kindofcourse_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'content' => array (
            'name' => 'content',
            'vname' => 'LBL_CONTENT',
            'type' => 'text',
        ),
        'syllabus' => array (
            'name' => 'syllabus',
            'vname' => 'LBL_SYLLABUS',
            'type' => 'text',
        ),
        //Custom Relationship JUNIOR. Kind Of Course - Class   By Lap Nguyen
        'kindofcourse_class'=>array(
            'name' => 'kindofcourse_class',
            'type' => 'link',
            'relationship' => 'kindofcourse_class',
            'module' => 'J_Class',
            'bean_name' => 'J_Class',
            'source' => 'non-db',
            'vname' => 'LBL_J_CLASS_LINK',
        ),

        'kindofcourse_gradeconfig'=>array(
            'name' => 'kindofcourse_gradeconfig',
            'type' => 'link',
            'relationship' => 'kindofcourse_gradeconfig',
            'module' => 'J_GradebookConfig',
            'bean_name' => 'J_GradebookConfig',
            'source' => 'non-db',
            'vname' => 'LBL_J_GRADEBOOKCONFIG_LINK',
        ),
    ),
    'relationships'=>array (
        //Custom Relationship JUNIOR. Kind Of Course - Class  By Lap Nguyen
        'kindofcourse_class' => array(
            'lhs_module'        => 'J_Kindofcourse',
            'lhs_table'            => 'j_kindofcourse',
            'lhs_key'            => 'id',
            'rhs_module'        => 'J_Class',
            'rhs_table'            => 'j_class',
            'rhs_key'            => 'koc_id',
            'relationship_type'    => 'one-to-many',
        ),

        'kindofcourse_gradeconfig' => array(
            'lhs_module'        => 'J_Kindofcourse',
            'lhs_table'            => 'j_kindofcourse',
            'lhs_key'            => 'id',
            'rhs_module'        => 'J_GradebookConfig',
            'rhs_table'            => 'j_gradebookconfig',
            'rhs_key'            => 'koc_id',
            'relationship_type'    => 'one-to-many',
        ),
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Kindofcourse','J_Kindofcourse', array('basic','team_security','assignable'));