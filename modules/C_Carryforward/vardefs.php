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

$dictionary['C_Carryforward'] = array(
    'table'=>'c_carryforward',
    'audited'=>false,
    'duplicate_merge'=>true,
    'fields'=>array (
        'type' =>
        array (
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
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 20,
            'size' => '20',
            'options' => 'data_type_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'collected' =>
        array (
            'required' => false,
            'name' => 'collected',
            'vname' => 'LBL_COLLECTED',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'moving_in' =>
        array (
            'required' => false,
            'name' => 'moving_in',
            'vname' => 'LBL_MOVING_IN',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'moving_out' =>
        array (
            'required' => false,
            'name' => 'moving_out',
            'vname' => 'LBL_MOVING_OUT',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'tranfer_in' =>
        array (
            'required' => false,
            'name' => 'tranfer_in',
            'vname' => 'LBL_TRANFER_IN',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'tranfer_out' =>
        array (
            'required' => false,
            'name' => 'tranfer_out',
            'vname' => 'LBL_TRANFER_OUT',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'delivery' =>
        array (
            'required' => false,
            'name' => 'delivery',
            'vname' => 'LBL_DELIVERY',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'refund' =>
        array (
            'required' => false,
            'name' => 'refund',
            'vname' => 'LBL_REFUND',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'last_stock' =>
        array (
            'required' => false,
            'name' => 'last_stock',
            'vname' => 'LBL_LAST_STOCK',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'this_stock' =>
        array (
            'required' => false,
            'name' => 'this_stock',
            'vname' => 'LBL_THIS_STOCK',
            'type' => 'decimal',
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
            'len' => '26',
            'size' => '20',
            'enable_range_search' => false,
            'precision' => '2',
        ),
        'month' =>
        array (
            'name' => 'month',
            'vname' => 'LBL_MONTH',
            'type' => 'enum',
            'default' => '',
            'len' => 30,
            'size' => '20',
            'options' => 'month_list_view',
            'studio' => 'visible',
            'massupdate' => 0,
        ),
        'year' =>
        array (
            'name' => 'year',
            'vname' => 'LBL_YEAR',
            'type' => 'enum',
            'default' => '2014',
            'len' => 20,
            'size' => '20',
            'options' => 'year_list',
            'studio' => 'visible',
            'massupdate' => 0,
        ),
        'passed' =>
        array (
            'required' => false,
            'name' => 'passed',
            'vname' => 'LBL_PASSED',
            'type' => 'bool',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
        ),

        // Relationship Student ( 1 - n ) Carry Forward - Lap Nguyen
        'student_name' => array (
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'student_name',
            'vname'     => 'LBL_STUDENT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'student_id',
            'join_name' => 'contacts',
            'link'      => 'student_forward',
            'table'     => 'contacts',
            'isnull'    => 'true',
            'module'    => 'Contacts',
        ),

        'student_id' => array (
            'name'              => 'student_id',
            'rname'             => 'id',
            'vname'             => 'LBL_STUDENT_ID',
            'type'              => 'id',
            'table'             => 'contacts',
            'isnull'            => 'true',
            'module'            => 'Contacts',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'student_forward' => array (
            'name'          => 'student_forward',
            'type'          => 'link',
            'relationship'  => 'student_forward',
            'module'        => 'Contacts',
            'bean_name'     => 'Contacts',
            'source'        => 'non-db',
            'vname'         => 'LBL_STUDENT_NAME',
        ),
        // Relationship Student ( 1 - n ) Carry Forward

        // Relationship Lead ( 1 - n ) Carry Forward - Lap Nguyen
        'lead_name' => array (
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'lead_name',
            'vname'     => 'LBL_LEAD_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'lead_id',
            'join_name' => 'leads',
            'link'      => 'lead_forward',
            'table'     => 'leads',
            'isnull'    => 'true',
            'module'    => 'Leads',
        ),

        'lead_id' => array (
            'name'              => 'lead_id',
            'rname'             => 'id',
            'vname'             => 'LBL_LEAD_ID',
            'type'              => 'id',
            'table'             => 'leads',
            'isnull'            => 'true',
            'module'            => 'Leads',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),

        'lead_forward' => array (
            'name'          => 'lead_forward',
            'type'          => 'link',
            'relationship'  => 'lead_forward',
            'module'        => 'Leads',
            'bean_name'     => 'Leads',
            'source'        => 'non-db',
            'vname'         => 'LBL_LEAD_NAME',
        ),
        // Relationship Lead ( 1 - n ) Carry Forward
        'date_input' =>
        array (
            'required' => false,
            'name' => 'date_input',
            'vname' => 'LBL_DATE_INPUT',
            'type' => 'date',
            'massupdate' => 0,
            'no_default' => false,
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
        ),

        //Bo sung Turn Over Report
        'beginning_hours' =>
        array (
            'required' => false,
            'name' => 'beginning_hours',
            'vname' => 'LBL_BEGINING_HOUR',
            'type' => 'decimal',
            'len' => '7',
            'precision' => '2',
        ),
        'beginning_delivery' =>
        array (
            'required' => false,
            'name' => 'beginning_delivery',
            'vname' => 'LBL_BEGINING_DELIVERY',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'total_amount_after_discount' =>
        array (
            'required' => false,
            'name' => 'total_amount_after_discount',
            'vname' => 'LBL_TOTAL_AMOUNT_AFTER_DISCOUNT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'total_hour_studied' =>
        array (
            'required' => false,
            'name' => 'total_hour_studied',
            'vname' => 'LBL_TOTAL_HOUR_STUDIED',
            'type' => 'decimal',
            'len' => '7',
            'precision' => '2',
        ),
        'total_allocated_hours' =>
        array (
            'required' => false,
            'name' => 'total_allocated_hours',
            'vname' => 'LBL_TOTAL_ALLOCATED_HOUR',
            'type' => 'decimal',
            'len' => '7',
            'precision' => '2',
        ),
        'total_allocated_balance' =>
        array (
            'required' => false,
            'name' => 'total_allocated_balance',
            'vname' => 'LBL_TOTAL_ALLOCATED_BALANCE',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'mv_tf_rf_out' =>
        array (
            'required' => false,
            'name' => 'mv_tf_rf_out',
            'vname' => 'LBL_MTR_OUT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'mv_tf_rf_in' =>
        array (
            'required' => false,
            'name' => 'mv_tf_rf_in',
            'vname' => 'LBL_MTR_IN',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'mv_tf_in_allocated' =>
        array (
            'required' => false,
            'name' => 'mv_tf_in_allocated',
            'vname' => 'LBL_MV_TF_IN_ALLOCATED',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'mv_tf_out_allocated' =>
        array (
            'required' => false,
            'name' => 'mv_tf_out_allocated',
            'vname' => 'LBL_MV_TF_OUT_ALLOCATED',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'refund_allocated' =>
        array (
            'required' => false,
            'name' => 'refund_allocated',
            'vname' => 'LBL_REFUND_ALLOCATED',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'total_hour_left' =>
        array (
            'required' => false,
            'name' => 'total_hour_left',
            'vname' => 'LBL_TOTAL_HOUR_LEFT',
            'type' => 'decimal',
            'len' => '7',
            'precision' => '2',
        ),
        //END
        //Some Junior Field
        'amount_before_discount' =>
        array (
            'required' => false,
            'name' => 'amount_before_discount',
            'vname' => 'LBL_AMOUNT_BEFORE_DISCOUNT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'discount_amount' =>
        array (
            'required' => false,
            'name' => 'discount_amount',
            'vname' => 'LBL_DISCOUNT_AMOUNT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'sponsor_amount' =>
        array (
            'required' => false,
            'name' => 'sponsor_amount',
            'vname' => 'LBL_SPONSOR_AMOUNT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'cashdelay_amount_in' =>
        array (
            'required' => false,
            'name' => 'cashdelay_amount_in',
            'vname' => 'LBL_CASHDELAY_AMOUNT_IN',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'cashdelay_hour_in' =>
        array (
            'required' => false,
            'name' => 'cashdelay_hour_in',
            'vname' => 'LBL_CASHDELAY_HOUR_IN',
            'type' => 'decimal',
            'len' => '13',
            'precision' => '2',
        ),
        'collected_hour' =>
        array (
            'required' => false,
            'name' => 'collected_hour',
            'vname' => 'LBL_COLLECTED_HOUR',
            'type' => 'decimal',
            'len' => '13',
            'precision' => '2',
        ),
        'cashdelay_amount_out' =>
        array (
            'required' => false,
            'name' => 'cashdelay_amount_out',
            'vname' => 'LBL_CASHDELAY_AMOUNT_OUT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'cashdelay_hour_out' =>
        array (
            'required' => false,
            'name' => 'cashdelay_hour_out',
            'vname' => 'LBL_CASHDELAY_HOUR_OUT',
            'type' => 'decimal',
            'len' => '13',
            'precision' => '2',
        ),
        'settle_amount' =>
        array (
            'required' => false,
            'name' => 'settle_amount',
            'vname' => 'LBL_SETTLE_AMOUNT',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'settle_hours' =>
        array (
            'required' => false,
            'name' => 'settle_hours',
            'vname' => 'LBL_SETTLE_HOURS',
            'type' => 'decimal',
            'len' => '13',
            'precision' => '2',
        ),
        'level' =>
        array (
            'name' => 'level',
            'vname' => 'LBL_LEVEL',
            'type' => 'varchar',
            'len' => 30,
        ),
        'class_code' =>
        array (
            'name' => 'class_code',
            'vname' => 'LBL_CLASS_CODE',
            'type' => 'varchar',
            'len' => 100,
        ),
        'invoice_no' =>
        array (
            'name' => 'invoice_no',
            'vname' => 'LBL_INVOICE_NO',
            'type' => 'varchar',
            'len' => 200,
        ),
        //END:
        'payment_id' =>
        array (
            'name'              => 'payment_id',
            'rname'             => 'id',
            'vname'             => 'LBL_PAYMENT_ID',
            'type'              => 'id',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
            'studio' => 'visible',
        ),


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
        'ju_payments' => array(
            'name'          => 'ju_payments',
            'type'          => 'link',
            'relationship'  => 'j_payment_carryforward',
            'module'        => 'J_Payment',
            'bean_name'     => 'J_Payment',
            'source'        => 'non-db',
            'vname'         => 'LBL_PAYMENT_NAME',
        ),
        //Chưa phải là field quan hệ
        'contract_id' =>
        array (
            'name'              => 'contract_id',
            'rname'             => 'id',
            'vname'             => 'LBL_CONTRACT_ID',
            'type'              => 'id',
        ),

        //Relationship Enrollment (1 - n) Carryforward
        'enrollment_id' =>
        array (
            'name'              => 'enrollment_id',
            'rname'             => 'id',
            'vname'             => 'LBL_ENROLLMENT_NAME',
            'type'              => 'id',
            'table'             => 'opportunities',
            'isnull'            => 'true',
            'module'            => 'Opportunities',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),
        'enrollment_name' =>
        array (
            'required'  => false,
            'source'    => 'non-db',
            'name'      => 'enrollment_name',
            'vname'     => 'LBL_ENROLLMENT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'enrollment_id',
            'link'      => 'enrollment_carry',
            'table'     => 'opportunities',
            'isnull'    => 'true',
            'module'    => 'Opportunities',
        ),
        'enrollments' =>
        array (
            'name'          => 'enrollments',
            'type'          => 'link',
            'relationship'  => 'enrollment_carry',
            'module'        => 'Opportunities',
            'bean_name'     => 'Opportunity',
            'source'        => 'non-db',
            'vname'         => 'LBL_ENROLLMENT_NAME',
        ),

        //Add Field Kind Of Course - Show Corporate student
        'kind_of_course' =>
        array (
            'name' => 'kind_of_course',
            'vname' => 'LBL_KIND_OF_COURSE',
            'type' => 'varchar',
            'len' => 150,
        ),
        //END: Field Kind of cource

        'out_standing' =>
        array (
            'required' => false,
            'name' => 'out_standing',
            'vname' => 'LBL_OUT_STANDING',
            'type' => 'decimal',
            'len' => '30',
            'precision' => '2',
        ),
        'carry_amount_temp' =>
        array (
            'required' => false,
            'name' => 'carry_amount_temp',
            'vname' => 'LBL_CARRY_AMOUNT_TEMP',
            'type' => 'decimal',
            'len' => '20',
            'precision' => '2',
        ),
        'out_standing_hours' =>
        array (
            'required' => false,
            'name' => 'out_standing_hours',
            'vname' => 'LBL_OUT_STANDING_HOURS',
            'type' => 'decimal',
            'len' => '7',
            'precision' => '2',
        ),
        'carry_hours_temp' =>
        array (
            'required' => false,
            'name' => 'carry_hours_temp',
            'vname' => 'LBL_CARRY_HOURS_TEMP',
            'type' => 'decimal',
            'len' => '7',
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
VardefManager::createVardef('C_Carryforward','C_Carryforward', array('basic','team_security','assignable'));