<?php
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is 3t bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/

$dictionary['C_Commission'] = array(
    'table'=>'c_commission',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array (
        'name' => 
        array (
            'name' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'link' => true,
            'dbType' => 'varchar',
            'len' => '50',
            'unified_search' => false,
            'full_text_search' => 
            array (
                'boost' => 3,
            ),
            'required' => true,
            'importable' => 'required',
            'duplicate_merge' => 'enabled',
            'merge_filter' => 'selected',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'duplicate_merge_dom_value' => '3',
            'audited' => false,
            'reportable' => true,
            'calculated' => false,
            'size' => '20',
        ),
        'date_input' => array(
            'required' => true,
            'name' => 'date_input',
            'vname' => 'LBL_DATE_INPUT',
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
            'enable_range_search' => false,
            'display_default' => 'now',
        ),
        'value_input' => 
        array (
            'required' => false,
            'name' => 'value_input',
            'vname' => 'LBL_VALUE_INPUT',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),

        'start_study' => 
        array (
            'name' => 'start_study',
            'vname' => 'Start Study',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'end_study' => 
        array (
            'name' => 'end_study',
            'vname' => 'End Study',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'class_aims_id' => 
        array (
            'name' => 'class_aims_id',
            'vname' => 'Class AIMS ID',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'payment_id' => 
        array (
            'name' => 'payment_id',
            'vname' => 'Payment ID',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'payment_date' => 
        array (
            'name' => 'payment_date',
            'vname' => 'Payment Date',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'amount' => 
        array (
            'name' => 'amount',
            'vname' => 'Amount',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),
        'student_id' => 
        array (
            'name' => 'student_id',
            'vname' => 'Student ID',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '36',
            'size' => '20',
        ),

        'value_input_2' => 
        array (
            'required' => false,
            'name' => 'value_input_2',
            'vname' => 'LBL_VALUE_INPUT_2',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),
        'value_input_3' => 
        array (
            'required' => false,
            'name' => 'value_input_3',
            'vname' => 'LBL_VALUE_INPUT_3',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),
        'value_input_4' => 
        array (
            'required' => false,
            'name' => 'value_input_4',
            'vname' => 'LBL_VALUE_INPUT_4',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),
        'value_input_5' => 
        array (
            'required' => false,
            'name' => 'value_input_5',
            'vname' => 'LBL_VALUE_INPUT_5',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),
        'value_input_6' => 
        array (
            'required' => false,
            'name' => 'value_input_6',
            'vname' => 'LBL_VALUE_INPUT_6',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
        ),
        'value_input_7' => 
        array (
            'required' => false,
            'name' => 'value_input_7',
            'vname' => 'LBL_VALUE_INPUT_7',
            'type' => 'decimal',
            'len' => '13',
            'size' => '20',
            'precision' => '2',
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
VardefManager::createVardef('C_Commission','C_Commission', array('basic','team_security','assignable'));