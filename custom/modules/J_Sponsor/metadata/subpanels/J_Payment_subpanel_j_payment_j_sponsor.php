<?php
// created: 2016-03-30 07:19:21
global $current_user;
if(!$current_user->isAdmin())
    $subpanel_layout['where'] = "(j_sponsor.type = 'Sponsor')";
$subpanel_layout['list_fields'] = array (
    'name' =>
    array (
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '10%',
        'default' => true,
    ),
    'campaign_code' =>
    array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_CAMPAIGN_CODE',
        'width' => '10%',
    ),
    'foc_type' =>
    array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'vname' => 'LBL_FOC_TYPE',
        'width' => '10%',
    ),
    'sponsor_number' =>
    array (
        'type' => 'varchar',
        'vname' => 'LBL_SPONSOR_NUMBER',
        'width' => '10%',
        'default' => true,
    ),
    'amount' =>
    array (
        'type' => 'currency',
        'vname' => 'LBL_AMOUNT',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
    ),
    'percent' =>
    array (
        'type' => 'decimal',
        'vname' => 'LBL_PERCENT',
        'width' => '10%',
        'default' => true,
    ),
    'total_down' =>
    array (
        'type' => 'currency',
        'vname' => 'LBL_DISCOUNT_SPONSOR_DOWN',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
    ),
    'date_entered' =>
    array (
        'type' => 'datetime',
        'studio' =>
        array (
            'portaleditview' => false,
        ),
        'vname' => 'LBL_DATE_ENTERED',
        'width' => '3%',
        'default' => true,
    ),
);