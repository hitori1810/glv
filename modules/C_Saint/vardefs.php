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

$dictionary['C_Saint'] = array(
    'table'=>'c_saint',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (  
        'saint_month' => 
        array (
            'required' => false,
            'name' => 'saint_month',
            'vname' => 'LBL_SAINT_MONTH',
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
            'options' => 'month_options',
            'studio' => 'visible',
        ),
        'saint_day' => 
        array (
            'required' => false,
            'name' => 'saint_day',
            'vname' => 'LBL_SAINT_DAY',
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
            'options' => 'day_options',
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
VardefManager::createVardef('C_Saint','C_Saint', array('basic','assignable'));
