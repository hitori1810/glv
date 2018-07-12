<?php
$module_name = 'J_Invoice';
$viewdefs[$module_name] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'buttons' =>
                array (
                    0 => 'EDIT',
                    1 => 'DUPLICATE',
                    2 => 'DELETE',
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
            'useTabs' => false,
            'tabDefs' =>
            array (
                'DEFAULT' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' =>
        array (
            'default' =>
            array (
                0 =>
                array (
                    0 => 'name',
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'serial_no',
                        'label' => 'LBL_SERIAL_NO',
                    ),
                    1 =>
                    array (
                        'name' => 'payment_name',
                        'label' => 'LBL_PAYMENT_NAME',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                    1 =>
                    array (
                        'name' => 'before_discount',
                        'label' => 'LBL_BEFORE_DISCOUNT',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'invoice_date',
                        'label' => 'LBL_INVOICE_DATE',
                    ),
                    1 =>
                    array (
                        'name' => 'total_discount_amount',
                        'label' => 'LBL_DISCOUNT_AMOUNT',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'content_vat_invoice',
                        'label' => 'LBL_CONTENT_VAT_INVOICE',
                    ),
                    1 =>
                    array (
                        'name' => 'invoice_amount',
                        'label' => 'LBL_INVOICE_AMOUNT',
                    ),
                ),
                5 =>
                array (
                    0 => 'description',
                ),
                6 => array (
                    0 => 'assigned_user_name',
                    1 => array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                7 => array (
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
            ),
        ),
    ),
);
