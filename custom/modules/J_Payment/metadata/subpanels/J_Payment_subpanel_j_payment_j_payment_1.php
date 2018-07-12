<?php
// created: 2015-11-13 15:53:46
$subpanel_layout['list_fields'] = array (
    'name' =>
    array (
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '10%',
        'default' => true,
    ),
    'payment_type' =>
    array (
        'vname' => 'LBL_PAYMENT_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'payment_date' =>
    array (
        'vname' => 'LBL_PAYMENT_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'sale_type' =>
    array (
        'vname' => 'LBL_SALE_TYPE',
        'width' => '10%',
        'default' => true,
    ),
    'sale_type_date' =>
    array (
        'vname' => 'LBL_SALE_TYPE_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'payment_amount' =>
    array (
        'vname' => 'LBL_PAYMENT_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'total_hours' =>
    array (
        'vname' =>($GLOBALS['current_user']->team_type == 'Adult') ? 'LBL_TOTAL_DAYS' :'LBL_TOTAL_HOURS',
        'width' => '10%',
        'default' => true,
    ),

    'remain_amount' =>
    array (
        'vname' => 'LBL_REMAIN_AMOUNT',
        'width' => '10%',
        'default' => true,
    ),
    'remain_hours' =>
    array (
        'vname' =>($GLOBALS['current_user']->team_type == 'Adult') ? 'LBL_REMAIN_DAYS' :'LBL_REMAIN_HOURS',
        'width' => '10%',
        'default' => true,
    ),
    'payment_expired' =>
    array (
        'vname' => 'LBL_PAYMENT_EXPIRED',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' =>
    array (
        'width' => '10%',
        'vname' => ($GLOBALS['current_user']->team_type == 'Adult') ? 'LBL_FIRST_SM' :'LBL_ASSIGNED_TO_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'default' => true,
    ),
    'team_name' =>
    array (
        'width' => '5%',
        'vname' => 'LBL_TEAM',
        'widget_class' => 'SubPanelDetailViewLink',
        'default' => true,
    ),
    'currency_id' =>
    array (
        'name' => 'currency_id',
        'usage' => 'query_only',
    ),
    'payment_use_payment' =>
    array (
        'name' => 'payment_use_payment',
        'usage' => 'query_only',
    ),
    'use_payment_id' =>
    array (
        'name' => 'use_payment_id',
        'usage' => 'query_only',
    ),
        'contract_id' =>
    array (
        'name' => 'contract_id',
        'usage' => 'query_only',
    ),
);
$subpanel_layout['order_by'] = 'j_payment.payment_date DESC';