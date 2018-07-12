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

$dictionary['C_Attendance'] = array(
    'table'=>'c_attendance',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'leaving_date' =>
        array (
            'required' => false,
            'name' => 'leaving_date',
            'vname' => 'LBL_LEAVING_DATE',
            'type' => 'date',
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
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'leaving_type' =>
        array (
            'required' => false,
            'name' => 'leaving_type',
            'vname' => 'LBL_LEAVING_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => 'A',
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
            'len' => 50,
            'size' => '20',
            'options' => 'leaving_type_student_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'absent_for_hour' =>
        array (
            'required' => false,
            'name' => 'absent_for_hour',
            'vname' => 'LBL_ABSENT_HOUR',
            'type' => 'currency',
            'len' => 10,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
            'default' => '',
        ),
        'in_class' =>
        array (
            'name' => 'in_class',
            'vname' => 'LBL_IN_CLASS',
            'type' => 'bool',
            'audited' => true,
            'default' => '1',
        ),
        'attended' =>
        array (
            'name' => 'attended',
            'vname' => 'LBL_ATTENDED',
            'type' => 'bool',
            'default' => '0',
        ),
        'homework' =>
        array (
            'name' => 'homework',
            'vname' => 'LBL_HOMEWORK',
            'type' => 'bool',
            'default' => '0',
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
VardefManager::createVardef('C_Attendance','C_Attendance', array('basic','team_security','assignable'));