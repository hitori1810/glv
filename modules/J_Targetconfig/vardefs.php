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

$dictionary['J_Targetconfig'] = array(
    'table'=>'j_targetconfig',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'type' =>
        array (
            'required' => false,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'massupdate' => 1,
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
            'options' => 'type_targetconfig_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'year' =>
        array (
            'required' => false,
            'name' => 'year',
            'vname' => 'LBL_YEAR',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'massupdate' => 1,
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 10,
            'size' => '20',
            'options' => 'year_targetconfig_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'frequency' =>
        array (
            'required' => false,
            'name' => 'frequency',
            'vname' => 'LBL_FREQUENCY',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'massupdate' => 1,
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
            'options' => 'frequency_targetconfig_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'time_unit' =>
        array (
            'required' => false,
            'name' => 'time_unit',
            'vname' => 'LBL_TIME_UNIT',
            'type' => 'varchar',
            'audited' => false,
            'len' => '50',
            //            'dbType' => 'integer',
        ),
        'value' =>
        array (
            'required' => false,
            'name' => 'value',
            'vname' => 'LBL_VALUE',
            'type' => 'decimal',
            'precision' => '2',
            'audited' => false,
            'len' => '10',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
            'massupdate' => 1,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 20,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
        ),
        //Add Relationship Payment - Loyalty
        'loyalty_link'=>array(
            'name' => 'loyalty_link',
            'type' => 'link',
            'relationship' => 'target_loyaltys',
            'module' => 'J_Loyalty',
            'bean_name' => 'J_Loyalty',
            'source' => 'non-db',
            'vname' => 'LBL_LOYALTY',
        ),
    ),
    'relationships'=>array (
        'target_loyaltys' => array(
            'lhs_module' => 'J_Targetconfig',
            'lhs_table' => 'j_targetconfig',
            'lhs_key' => 'id',
            'rhs_module' => 'J_Loyalty',
            'rhs_table' => 'j_loyalty',
            'rhs_key' => 'target_id',
            'relationship_type' => 'one-to-many'
        ),
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Targetconfig','J_Targetconfig', array('basic','team_security','assignable'));