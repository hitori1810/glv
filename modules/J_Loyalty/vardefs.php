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

$dictionary['J_Loyalty'] = array(
    'table'=>'j_loyalty',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        'type'=> array (
            'required' => true,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
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
            'options' => 'loyalty_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'point' =>
        array (
            'required'  => true,
            'name'      => 'point',
            'vname'     => 'LBL_POINT',
            'type'      => 'varchar',
            'dbType'    => 'int',
            'no_default'=> false,
            'len'       => '100',
            'min'       => '1',
            'max'       => '99999999',
        ),
        'discount_amount' =>
        array (
            'required' => false,
            'name' => 'discount_amount',
            'vname' => 'LBL_DISCOUNT_AMOUNT',
            'type' => 'currency',
            'len' => 13,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
            'default' => '',
        ),
        'rate_in_out' =>
        array (
            'required' => false,
            'name' => 'rate_in_out',
            'vname' => 'LBL_RATE_IN_OUT',
            'type' => 'currency',
            'len' => 13,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
            'default' => '',
        ),
        'exp_date' =>
        array (
            'required' => true,
            'name' => 'exp_date',
            'vname' => 'LBL_EXP_DATE',
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
            'importable' => 'required',
        ),
        'input_date' =>
        array (
            'required' => true,
            'name' => 'input_date',
            'vname' => 'LBL_INPUT_DATE',
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
            'importable' => 'required',
            'display_default' => 'now',
        ),

        //Add Relationship Payment - Loyalty
        'payment_id' => array(
            'name' => 'payment_id',
            'vname' => 'LBL_PAYMENT_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'payment_name' => array(
            'name' => 'payment_name',
            'rname' => 'name',
            'id_name' => 'payment_id',
            'vname' => 'LBL_PAYMENT_NAME',
            'type' => 'relate',
            'link' => 'payment_link',
            'table' => 'j_payment',
            'isnull' => 'true',
            'module' => 'J_Payment',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'payment_link' => array(
            'name' => 'payment_link',
            'type' => 'link',
            'relationship' => 'payment_loyaltys',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PAYMENT_NAME',
        ),
        //END: Add Relationship Payment - Loyalty

        //Add Relationship Student - Loyalty
        'student_id' => array(
            'name' => 'student_id',
            'vname' => 'LBL_STUDENT_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'student_name' => array(
            'required' => true,
            'name' => 'student_name',
            'rname' => 'name',
            'id_name' => 'student_id',
            'vname' => 'LBL_STUDENT_NAME',
            'type' => 'relate',
            'link' => 'student_link',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'student_link' => array(
            'name' => 'student_link',
            'type' => 'link',
            'relationship' => 'student_loyaltys',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_STUDENT_NAME',
        ),
        //END: Add Relationship Student - Loyalty

        //Add Relationship Target Config - Loyalty
        'target_id' => array(
            'name' => 'target_id',
            'vname' => 'LBL_TARGET_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'target_name' => array(
            'required' => true,
            'name' => 'target_name',
            'rname' => 'name',
            'id_name' => 'target_id',
            'vname' => 'LBL_TARGET_NAME',
            'type' => 'relate',
            'link' => 'target_link',
            'table' => 'j_targetconfig',
            'isnull' => 'true',
            'module' => 'J_Targetconfig',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'target_link' => array(
            'name' => 'target_link',
            'type' => 'link',
            'relationship' => 'target_loyaltys',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_TARGET_NAME',
        ),
        //END: Add Relationship Student - Loyalty

        //Add Relationship Payment Detail - Loyalty
        'paymentdetail_id' => array(
            'name' => 'paymentdetail_id',
            'vname' => 'LBL_PAYMENT_DETAIL_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => ''
        ),
        'paymentdetail_name' => array(
            'name' => 'paymentdetail_name',
            'rname' => 'name',
            'id_name' => 'paymentdetail_id',
            'vname' => 'LBL_PAYMENT_DETAIL_NAME',
            'type' => 'relate',
            'link' => 'paymentdetail_link',
            'table' => 'j_paymentDetail',
            'isnull' => 'true',
            'module' => 'J_PaymentDetail',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>true,
            'source' => 'non-db',
        ),
        'paymentdetail_link' => array(
            'name' => 'paymentdetail_link',
            'type' => 'link',
            'relationship' => 'paymentdetail_loyaltys',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PAYMENT_DETAIL_NAME',
        ),
        //END: Add Relationship Payment Detail - Loyalty
    ),
    'relationships'=>array (
    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Loyalty','J_Loyalty', array('basic','team_security','assignable'));
