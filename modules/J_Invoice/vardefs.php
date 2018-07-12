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

$dictionary['J_Invoice'] = array(
    'table'=>'j_invoice',
    'audited'=>true,
    'duplicate_merge'=>true,
    'fields'=>array (

        'invoice_amount' =>
        array (
            'required' => false,
            'name' => 'invoice_amount',
            'vname' => 'LBL_INVOICE_AMOUNT',
            'type' => 'currency',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Payment Amount',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 13,
            'min' => 1,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'precision' => 2,
            'default' => '',
        ),

        'invoice_date' =>
        array (
            'name' => 'invoice_date',
            'vname' => 'LBL_INVOICE_DATE',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'before_discount' =>
        array (
            'name' => 'before_discount',
            'vname' => 'LBL_BEFORE_DISCOUNT',
            'type' => 'currency',
            'len' => 13,
            'precision' => 2,
        ),

        'total_discount_amount' =>
        array (
            'name' => 'total_discount_amount',
            'vname' => 'LBL_DISCOUNT_AMOUNT',
            'type' => 'currency',
            'len' => 13,
            'precision' => 2,
        ),
        'content_vat_invoice' =>
        array (
            'name' => 'content_vat_invoice',
            'vname' => 'LBL_CONTENT_VAT_INVOICE',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Method Note',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '255',
            'size' => '20',
        ),
        'serial_no' =>
        array (
            'required' => false,
            'name' => 'serial_no',
            'vname' => 'LBL_SERIAL_NO',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'Serial No',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => true,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '20',
            'size' => '20',
        ),
        'custom_button' =>
        array (
            'name' => 'custom_button',
            'vname' => 'Button',
            'type' => 'varchar',
            'len' => '1',
            'studio' => 'visible',
            'source' => 'non-db',
        ),
        'status' =>
        array (
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => 'Paid',
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
            'len' => 10,
            'size' => '20',
            'options' => 'status_paymentdetail_list',
            'studio' => 'visible',
            'dependency' => false,
        ),

        //Add Relationship Payment - Invoice
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
            'relationship' => 'payment_invoices',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_PAYMENT_NAME',
        ),
        // Payment - Invoice
        //Add Relationship Invoice - Payment Detail
        'paymentdetail_link'=>array(
            'name' => 'paymentdetail_link',
            'type' => 'link',
            'relationship' => 'invoice_paymentdetail',
            'module' => 'J_PaymentDetail',
            'bean_name' => 'J_PaymentDetail',
            'source' => 'non-db',
            'vname' => 'LBL_PAYMENT_DETAIL',
        ),
    ),
    'relationships'=>array (
        //Add Relationship Invoice - Payment Detail
        'invoice_paymentdetail' => array(
            'lhs_module' => 'J_Invoice',
            'lhs_table' => 'j_invoice',
            'lhs_key' => 'id',
            'rhs_module' => 'J_PaymentDetail',
            'rhs_table' => 'j_paymentdetail',
            'rhs_key' => 'invoice_id',
            'relationship_type' => 'one-to-many'
        ),

    ),
    'optimistic_locking'=>true,
    'unified_search'=>true,
);
if (!class_exists('VardefManager')){
    require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('J_Invoice','J_Invoice', array('basic','team_security','assignable'));
