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

$dictionary['C_Grade'] = array(
    'table'=>'c_grade',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'status' => 
        array (
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => 0,
            'comments' => '',
            'help' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => 0,
            'reportable' => 0,
            'len' => 100,
            'options' => 'c_grade_status_options',
            'studio' => 'visible',
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
VardefManager::createVardef('C_Grade','C_Grade', array('basic','assignable'));
