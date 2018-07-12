<?php
// created: 2016-07-21 13:16:07
$searchFields['J_Payment'] = array (
    'name' =>
    array (
        'query_type' => 'default',
    ),
    'current_user_only' =>
    array (
        'query_type' => 'default',
        'db_field' =>
        array (
            0 => 'assigned_user_id',
        ),
        'my_items' => true,
        'vname' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
    ),
    'assigned_user_id' =>
    array (
        'query_type' => 'default',
    ),
    'favorites_only' =>
    array (
        'query_type' => 'format',
        'operator' => 'subquery',
        'subquery' => 'SELECT sugarfavorites.record_id FROM sugarfavorites
        WHERE sugarfavorites.deleted=0
        and sugarfavorites.module = \'J_Payment\'
        and sugarfavorites.assigned_user_id = \'{0}\'',
        'db_field' =>
        array (
            0 => 'id',
        ),
    ),
    'range_date_entered' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_entered' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_entered' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_date_modified' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_date_modified' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_date_modified' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_payment_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_payment_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_payment_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_start_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_start_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_start_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_end_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_end_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_end_study' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_payment_expired' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_payment_expired' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_payment_expired' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_moving_tran_out_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_moving_tran_out_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_moving_tran_out_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_moving_tran_in_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_moving_tran_in_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_moving_tran_in_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_class_start' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_class_start' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_class_start' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_class_end' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_class_end' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_class_end' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_sale_type_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_sale_type_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_sale_type_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_number_class' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_number_class' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_number_class' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_paid_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_paid_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_paid_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_amount_bef_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_amount_bef_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_amount_bef_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_discount_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_discount_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_discount_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_remaining_freebalace' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_remaining_freebalace' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_remaining_freebalace' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_total_after_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_total_after_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_total_after_discount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_deposit_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_deposit_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_deposit_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_sponsor_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_sponsor_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_sponsor_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_class_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_class_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_class_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_student_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_student_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_student_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_transfer_in_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_transfer_in_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_transfer_in_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_from_payment_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'start_range_from_payment_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'end_range_from_payment_aims_id' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
    ),
    'range_invoice_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'start_range_invoice_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'end_range_invoice_date' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true,
        'is_date_field' => true,
    ),
    'range_remain_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'start_range_remain_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'end_range_remain_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true
    ),

        'range_payment_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'start_range_payment_amount' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'end_range_payment_amount' => array(
        'query_type' => 'default',
        'enable_range_search' => true
    ),

        'range_remain_hours' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'start_range_remain_hours' =>
    array (
        'query_type' => 'default',
        'enable_range_search' => true
    ),
    'end_range_remain_hours' => array(
        'query_type' => 'default',
        'enable_range_search' => true
    ),
);