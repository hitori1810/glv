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

$dictionary['J_Sponsor'] = array(
    'table'=>'j_sponsor',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (
        //Custom Relationship JUNIOR. Payment  - J_Sponsor (1-n)  By Lap Nguyen
        'payment_name' => array(
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'payment_name',
            'vname'     => 'LBL_PAYMENT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'payment_id',
            'join_name' => 'payment',
            'link'      => 'ju_payments',
            'table'     => 'j_payment',
            'isnull'    => 'true',
            'module'    => 'J_Payment',
        ),

        'payment_id' => array(
            'name'              => 'payment_id',
            'rname'             => 'id',
            'vname'             => 'LBL_PAYMENT_ID',
            'type'              => 'id',
            'table'             => 'j_payment',
            'isnull'            => 'true',
            'module'            => 'J_Payment',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'ju_payments' => array(
            'name'          => 'ju_payments',
            'type'          => 'link',
            'relationship'  => 'j_payment_j_sponsor',
            'module'        => 'J_Payment',
            'bean_name'     => 'J_Payment',
            'source'        => 'non-db',
            'vname'         => 'LBL_PAYMENT_NAME',
        ),

        //Custom Relationship JUNIOR. Sponsor  - Discount (1-n)  By Lap Nguyen


        //Custom Relationship. Voucher  - J_Sponsor (1-n)  By Lap Nguyen
        'voucher_code' => array(
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'voucher_code',
            'vname'     => 'LBL_VOUCHER_CODE',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'voucher_id',
            'join_name' => 'J_Voucher',
            'link'      => 'ju_voucher',
            'table'     => 'j_voucher',
            'isnull'    => 'true',
            'module'    => 'J_Voucher',
        ),

        'voucher_id' => array(
            'name'              => 'voucher_id',
            'rname'             => 'id',
            'vname'             => 'LBL_VOUCHER_ID',
            'type'              => 'id',
            'table'             => 'j_voucher',
            'isnull'            => 'true',
            'module'            => 'J_Voucher',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'ju_voucher' => array(
            'name'          => 'ju_voucher',
            'type'          => 'link',
            'relationship'  => 'j_sponsor_vouchers',
            'module'        => 'J_Voucher',
            'bean_name'     => 'J_Voucher',
            'source'        => 'non-db',
            'vname'         => 'LBL_VOUCHER_CODE',
        ),
        //Custom Relationship. Voucher  - J_Sponsor (1-n)  By Lap Nguyen


        'discount_name' => array(
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'discount_name',
            'vname'     => 'LBL_DISCOUNT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'discount_id',
            'join_name' => 'J_Discount',
            'link'      => 'j_discounts',
            'table'     => 'j_discount',
            'isnull'    => 'true',
            'module'    => 'J_Discount',
        ),

        'discount_id' => array(
            'name'              => 'discount_id',
            'rname'             => 'id',
            'vname'             => 'LBL_DISCOUNT_ID',
            'type'              => 'id',
            'table'             => 'j_discount',
            'isnull'            => 'true',
            'module'            => 'J_Discount',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'j_discounts' => array(
            'name'          => 'j_discounts',
            'type'          => 'link',
            'relationship'  => 'j_sponsor_j_discounts',
            'module'        => 'J_Discount',
            'bean_name'     => 'J_Discount',
            'source'        => 'non-db',
            'vname'         => 'LBL_DISCOUNT_NAME',
        ),
        'total_down' =>
        array (
            'required' => false,
            'name' => 'total_down',
            'vname' => 'LBL_DISCOUNT_SPONSOR_DOWN',
            'type' => 'currency',
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
            'len' => 13,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
        ),
        'type' =>
        array (
            'required' => false,
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => 'FOC Type',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 30,
            'size' => '20',
            'options' => 'type_j_sponsor_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'foc_type' =>
        array (
            'required' => false,
            'name' => 'foc_type',
            'vname' => 'LBL_FOC_TYPE',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => 'FOC Type',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 30,
            'size' => '20',
            'options' => 'foc_type_payment_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'is_owner' =>
        array (
            'name' => 'is_owner',
            'vname' => 'LBL_IS_OWNER',
            'type' => 'bool',
            'default' => '0',
        ),
        'percent' =>
        array (
            'required' => false,
            'name' => 'percent',
            'vname' => 'LBL_PERCENT',
            'type' => 'decimal',
            'len' => 7,
            'precision' => 2,
        ),
        'amount' =>
        array (
            'required' => false,
            'name' => 'amount',
            'vname' => 'LBL_AMOUNT',
            'type' => 'currency',
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
            'len' => 13,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
        ),

        'sponsor_number' =>
        array (
            'required' => false,
            'name' => 'sponsor_number',
            'vname' => 'LBL_SPONSOR_NUMBER',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Sponsor Number',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '50',
            'size' => '20',
        ),
        'campaign_code' =>
        array (
            'required' => false,
            'name' => 'campaign_code',
            'vname' => 'LBL_CAMPAIGN_CODE',
            'type' => 'varchar',
            'len' => '50',
            'size' => '20',
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
VardefManager::createVardef('J_Sponsor','J_Sponsor', array('basic','team_security','assignable'));