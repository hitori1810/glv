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

$dictionary['J_Gradebook'] = array(
    'table'=>'j_gradebook',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
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
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => '20',
            'default' => 'Not Approval',
            'options' => 'gradebook_status_options',
        ),
        'date_input' => array(
            'name' => 'date_input',
            'vname' => 'LBL_DATE_INPUT',
            'type' => 'date',
            'display_default' => 'now',
        ),
        'j_gradebook_j_gradebookdetail' => array(
            'name' => 'j_gradebook_j_gradebookdetail',
            'type' => 'link',
            'relationship' => 'j_gradebook_j_gradebookdetail',
            'module' => 'J_GradebookDetail',
            'bean_name' => 'J_GradebookDetail',
            'source' => 'non-db',
            'vname' => 'LBL_GRADEBOOK_DETAIL',
        ), 

        'weight' => array(
            'name' => 'weight',
            'vname' => 'LBL_WEIGHT',
            'type' => 'int',
            'default' => 0,
        ),

        'grade_config' => array(
            'name' => 'grade_config',
            'vname' => 'LBL_CONFIG',
            'type' => 'text',                
        ),
        'date_confirm' => array(
            'name' => 'date_confirm',
            'vname' => 'LBL_DATE_CONFIRM',
            'type' => 'date',
        ),
        'gradebook_config_id' =>  array (
            'required' => false,
            'name' => 'gradebook_config_id',
            'vname' => 'Config ID',
            'type' => 'id',
            'len' => 36,
        ),  
    ),
    'relationships'=>array (
        'j_gradebook_j_gradebookdetail' => array(
            'lhs_module'        => 'J_Gradebook',
            'lhs_table'            => 'j_gradebook',
            'lhs_key'            => 'id',
            'rhs_module'        => 'J_GradebookDetail',
            'rhs_table'            => 'j_gradebookdetail',
            'rhs_key'            => 'gradebook_id',
            'relationship_type'    => 'one-to-many',
        ),
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Gradebook','J_Gradebook', array('basic','team_security','assignable'));