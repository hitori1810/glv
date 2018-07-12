<?php
    $viewdefs['Opportunities'] =
    array (
        'DetailView' =>
        array (
            'templateMeta' =>
            array (
                'form' =>
                array (
                    'buttons' =>
                    array (
                        //     0 => 'EDIT',
                        1 => array (
                            'customCode' => '{$DELETE_BT}',
                        ),
                        2 =>
                        array (
                            'customCode' => '{$CUSTOM_BUTTON}{$CUSTOM_CODE}{$PAYPOPUP}',
                        ),
                        3 => array(
                            'customCode' => '
                            {$ADDTOCLASS}
                            ',
                        ),
                        4 =>
                        array (
                            'customCode' => '{$BT_REFUND}',
                        ),
                        5 =>
                        array (
                            'customCode' => '{$BT_CLOST_ENR}',
                        ),
                        6 =>
                        array (
                            'customCode' => '{$BT_UNDO}',
                        ),
                        7 =>
                        array (
                            'customCode' => '{$convert_to_360} {include file="custom/modules/Opportunities/tpls/convert_payment.tpl"}',
                        ),
                    ),
                    'hideAudit' => true,
                    'hidden' =>
                    array (
                        1 => '<input type="hidden" name="descriptions" id="descriptions" value="">',
                        2 => '<link rel="stylesheet" href="{sugar_getjspath file="include/ytree/TreeView/css/folders/tree.css"}"/>',
                    ),
                ),
                'maxColumns' => '2',
                'widths' =>
                array (
                    0 =>
                    array (
                        'label' => '10',
                        'field' => '30',
                    ),
                    1 =>
                    array (
                        'label' => '10',
                        'field' => '30',
                    ),
                ),
                'javascript' => '
                {sugar_getscript file="custom/modules/Opportunities/js/detailview.js"}
                {sugar_getscript file="custom/include/javascripts/CustomDatePicker.js"}',
                'useTabs' => false,
                'tabDefs' =>
                array (
                    'DEFAULT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_PANEL_ASSIGNMENT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
            ),
            'panels' =>
            array (
                'default' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'oder_id',
                            'label' => 'LBL_ORDER_ID',
                            'customCode' => '
                            {if $fields.added_to_class.value == "0"}
                            <span class="textbg_blue">{$fields.oder_id.value}</span>
                            {else}
                            <span class="textbg_dream">{$fields.oder_id.value}</span>
                            {/if}',
                        ),
                         1 => 'opportunity_type',
                    ),
                    1 =>
                    array (
                        0 => 'name',
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'contact_name',
                            'label' => 'LBL_CONTACT_NAME',
                        ),
                        1 => 'total_hours'
                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'c_packages_opportunities_1_name',
                        ),
                        1 =>
                        array (
                            'name' => 'amount',
                            'label' => '{$MOD.LBL_PRICE} ({$CURRENCY})',
                        ),
                    ),
                    4 =>
                    array (

                        0 =>
                        array (
                            'name' => 'discount',
                            'label' => '{$MOD.LBL_DISCOUNT} (%)',
                        ),
                        1 => array(
                            'name' => 'discount_amount',
                            'label' => '{$MOD.LBL_DISCOUNT_AMOUNT} ({$CURRENCY})',
                        ),
                    ),
                    5 =>
                    array (
                        0 =>
                        array (
                            'name' => 'tax_rate',
                            'label' => '{$MOD.LBL_TAX_RATE} (%)',
                            'customCode' => '{$TAX_RATE}',
                        ),
                        1 =>  array(
                            'name' => 'tax_amount',
                            'label' => '{$MOD.LBL_TAX_AMOUNT} ({$CURRENCY})',
                        ),
                    ),
                    6 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_closed',
                            'customCode' => '{$FIELD_DATE}',
                        ),
                        1 =>
                        array (
                            'name' => 'total_in_invoice',
                            'label' => '{$MOD.LBL_TOTAL_IN_INVOICE} ({$CURRENCY})',
                        ),
                    ),
                    7 =>
                    array (
                        0 =>
                        array (
                            'name' => 'description',
                            'nl2br' => true,
                        ),
                        1 =>
                        array (
                            'name' => 'c_invoices_opportunities_1_name',
                            'label' => 'LBL_INVOICE',
                        ),
                    ),
                    8 =>
                    array (
                        0 =>
                        array (
                            'name' => 'sales_stage',
                            'customCode' => '<input type="hidden" class="sugar_field" id="sales_stage" value="{$fields.sales_stage.value}">
                            {if $fields.sales_stage.value == "Success"}
                            <span class="textbg_bluelight"><b>{$fields.sales_stage.value}<b></span>
                            {elseif $fields.sales_stage.value == "Deleted"}
                            <span class="textbg_black"><b>{$fields.sales_stage.value}<b></span>
                            {elseif $fields.sales_stage.value == "Draft"}
                            <span class="textbg_crimson"><b>{$fields.sales_stage.value}<b></span>
                            {else}

                            <span><b>{$fields.sales_stage.value}<b></span>
                            {/if}',
                        ),
                        1 =>
                        array (
                            'label' => '{$MOD.LBL_PAYMORE} ({$CURRENCY})',
                            'customCode' => '{$BUTTON_HTML} {$revenue_link}',
                        ),
                    ),
                ),
                'LBL_PANEL_ASSIGNMENT' =>
                array (
                    1 =>
                    array (
                        0 => 'assigned_user_name',
                        1 =>
                        array (
                            'name' => 'date_modified',
                            'label' => 'LBL_DATE_MODIFIED',
                            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        ),
                    ),
                    2 =>
                    array (
                        0 => 'team_name',
                        1 =>
                        array (
                            'name' => 'date_entered',
                            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        ),
                    ),
                ),
            ),
        ),
    );
