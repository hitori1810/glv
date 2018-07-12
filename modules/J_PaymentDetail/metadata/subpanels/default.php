<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
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


$module_name='J_PaymentDetail';
$subpanel_layout = array(
    'top_buttons' => array(
        array('widget_class' => 'SubPanelTopCreateButton'),
        array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => $module_name),
    ),

    'where' => '',

    'list_fields' => array(
        'payment_no' =>
        array (
            'vname' => 'LBL_PAYMENT_NO',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '5%',
            'default' => true,
            'link' => true,
        ),
        'payment_method' =>
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_PAYMENT_METHOD',
            'width' => '6%',
            'link' => true,
        ),
        'before_discount' =>
        array (
            'type' => 'currency',
            'vname' => 'LBL_BEFORE_DISCOUNT',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
        ),
        'discount_amount' =>
        array (
            'type' => 'currency',
            'vname' => 'LBL_DISCOUNT_AMOUNT',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
        ),
        'sponsor_amount' =>
        array (
            'type' => 'currency',
            'vname' => 'LBL_SPONSOR_AMOUNT',
            'currency_format' => true,
            'width' => '10%',
            'default' => true,
        ),
        'payment_amount' =>
        array (
            'type' => 'currency',
            'default' => true,
            'vname' => 'LBL_PAYMENT_AMOUNT',
            'currency_format' => true,
            'width' => '10%',
        ),
        'invoice_number' =>
        array (
            'type' => 'varchar',
            'vname' => 'LBL_INVOICE_NUMBER',
            'width' => '10%',
            'default' => true,
        ),
        'payment_date' =>
        array (
            'type' => 'date',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_PAYMENT_DATE',
            'width' => '10%',
        ),
        //    'sale_type' =>
        //    array (
        //        'type' => 'enum',
        //        'default' => true,
        //        'studio' => 'visible',
        //        'vname' => 'LBL_SALE_TYPE',
        //        'width' => '9%',
        //    ),
        //    'sale_type_date' =>
        //    array (
        //        'type' => 'date',
        //        'default' => true,
        //        'studio' => 'visible',
        //        'vname' => 'LBL_SALE_TYPE_DATE',
        //        'width' => '9%',
        //    ),
        'status' =>
        array (
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '7%',
        ),
        'assigned_user_name' =>
        array (
            'type' => 'relate',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'width' => '9%',
        ),
        'custom_button' =>
        array (
            'type' => 'varchar',
            'width' => '20%',
            'default' => true,
        ),
        'team_id' =>
        array (
            'usage'=>'query_only',
        ),
        'card_type' =>
        array (
            'usage'=>'query_only',
        ),
        'bank_type' =>
        array (
            'usage'=>'query_only',
        ),
        'is_release' =>
        array (
            'usage'=>'query_only',
        ),
        'serial_no' =>
        array (
            'usage'=>'query_only',
        ),
    ),
);

?>