<?php
// created: 2017-12-03 14:58:10
$subpanel_layout['list_fields'] = array (
    'name' =>
    array (
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '15%',
        'default' => true,
    ),
    'serial_no' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_SERIAL_NO',
        'width' => '10%',
        'default' => true,
    ),
    'invoice_date' =>
    array (
        'type' => 'date',
        'vname' => 'LBL_INVOICE_DATE',
        'width' => '10%',
        'default' => true,
    ),
    'status' =>
    array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_STATUS',
        'width' => '7%',
    ),
    'before_discount' =>
    array (
        'type' => 'currency',
        'vname' => 'LBL_BEFORE_DISCOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
    ),
    'total_discount_amount' =>
    array (
        'type' => 'currency',
        'vname' => 'LBL_DISCOUNT_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
    ),
    'invoice_amount' =>
    array (
        'type' => 'currency',
        'default' => true,
        'vname' => 'LBL_INVOICE_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
    ),
    'content_vat_invoice' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_CONTENT_VAT_INVOICE',
        'width' => '10%',
        'default' => true,
    ),
    'assigned_user_name' =>
    array (
        'link' => true,
        'type' => 'relate',
        'vname' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
        'widget_class' => 'SubPanelDetailViewLink',
        'target_module' => 'Users',
        'target_record_key' => 'assigned_user_id',
    ),
    'custom_button' =>
    array (
        'type' => 'varchar',
        'width' => '20%',
        'default' => true,
    ),
);