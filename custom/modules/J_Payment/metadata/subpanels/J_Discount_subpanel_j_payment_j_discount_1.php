<?php
// created: 2015-12-28 10:05:55
global $current_user;
$subpanel_layout['list_fields'] = array (
    'name' =>
    array (
        'vname' => 'LBL_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'width' => '10%',
        'default' => true,
        'link' => true,
    ),
    'payment_type' =>
    array (
        'vname' => 'LBL_PAYMENT_TYPE',
        'width' => '7%',
        'default' => true,
    ),
    'class_string' =>
    array (
        'vname' => 'LBL_CLASSES_NAME',
        'width' => '10%',
        'default' => true,
        'sortable' => false,
    ),
    'payment_date' =>
    array (
        'vname' => 'LBL_PAYMENT_DATE',
        'width' => '7%',
        'default' => true,
    ),
    'total_amount' =>
    array (
        'vname' => 'LBL_TOTAL_AMOUNT',
        'width' => '7%',
        'sortable' => false,
        'default' => true,
    ),
    'tuition_hours' =>
    array (
        'vname' => ($current_user->team_type == 'Adult') ? 'LBL_TUITION_DAYS' :'LBL_TUITION_HOURS',
        'width' => '5%',
        'default' => true,
        'sortable' => false,
    ),
    'number_of_payment' =>
    array (
        'vname' => '<table width="100%"><tbody>
        <tr><th colspan="4" style="text-align: center;">Payment Detail</th></tr>
        <tr>
        <td style="width: 20%;">VAT No</td>
        <td style="width: 30%;">Invoice Date</td>
        <td style="width: 30%;">Amount</td>
        <td style="width: 20%;">Status</td>
        </tbody></table>',
        'width' => '30%',
        'default' => true,
        'sortable' => false,
    ),
    'related_payment_detail' =>
    array (

        'vname' => ($current_user->team_type == 'Adult') ? '<table><tbody>
        <tr><th colspan="3" style="text-align: center;">Related Payment</th></tr>
        <tr>
        <td style="width: 30%;">Pay ID</td>
        <td style="width: 40%;">Used Amount</td>
        <td style="width: 30%;">Used Day</td></tr></tbody></table>' : '<table><tbody>
        <tr><th colspan="3" style="text-align: center;">Related Payment</th></tr>
        <tr>
        <td style="width: 40%;">Pay ID</td>
        <td style="width: 40%;">Used Amount</td>
        <td style="width: 20%;">Used Hour</td></tr></tbody></table>',
        'width' => '20%',
        'default' => true,
        'sortable' => false,
    ),

    'remain_amount' =>
    array (
        'vname' => 'LBL_REMAIN_AMOUNT',
        'width' => '7%',
        'sortable' => false,
        'default' => true,
        'align' => 'left',
    ),
    'remain_hours' =>
    array (
        'vname' =>($current_user->team_type == 'Adult') ? 'LBL_REMAIN_DAYS' :'LBL_REMAIN_HOURS',
        'width' => '5%',
        'default' => true,
        'sortable' => false,
    ),
    'assigned_user_name' =>
    array (
        'width' => '10%',
        'vname' => ($current_user->team_type == 'Adult') ? 'LBL_FIRST_SM' :'LBL_ASSIGNED_TO_NAME',
        'widget_class' => 'SubPanelDetailViewLink',
        'default' => true,
        'sortable' => false,
    ),
    'team_name' =>
    array (
        'width' => '10%',
        'vname' => 'LBL_TEAM',
        'widget_class' => 'SubPanelDetailViewLink',
        'default' => true,
        'sortable' => false,
    ),
    'currency_id' =>
    array (
        'name' => 'currency_id',
        'usage' => 'query_only',
    ),
    'aims_id' =>
    array (
        'name' => 'aims_id',
        'usage' => 'query_only',
    ),
    'payment_amount' =>
    array (
        'name' => 'payment_amount',
        'usage' => 'query_only',
    ),
    'paid_amount' =>
    array (
        'name' => 'paid_amount',
        'usage' => 'query_only',
    ),
    'deposit_amount' =>
    array (
        'name' => 'deposit_amount',
        'usage' => 'query_only',
    ),
    'contract_id' =>
    array (
        'name' => 'contract_id',
        'usage' => 'query_only',
    ),
);
if (($current_user->team_type == 'Adult')){ //Fix tạm
    unset($subpanel_layout['list_fields']['remain_amount']);
    unset($subpanel_layout['list_fields']['remain_hours']);
    unset($subpanel_layout['list_fields']['class_string']);
}
