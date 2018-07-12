<?php
$module_name = 'J_ConfigInvoiceNo';
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
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'active',
                        'label' => 'LBL_ACTIVE',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'customLabel' => '',
                        'customCode' => '<b>RANGE 1:</b>',
                    ),
                    1 =>
                    array (
                        'customLabel' => '',
                        'customCode' => '<b>RANGE 2:</b>',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'serial_no',
                        'label' => 'LBL_SERIAL_NO',
                    ),
                    1 =>
                    array (
                        'name' => 'serial_no_2',
                        'label' => 'LBL_SERIAL_NO',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'invoice_no_from',
                        'label' => 'LBL_INVOICE_NO_FORM',
                    ),
                    1 =>
                    array (
                        'name' => 'invoice_no_from_2',
                        'label' => 'LBL_INVOICE_NO_FORM',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'invoice_no_to',
                        'label' => 'LBL_INVOICE_NO_TO',
                    ),
                    1 =>
                    array (
                        'name' => 'invoice_no_to_2',
                        'label' => 'LBL_INVOICE_NO_TO',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'invoice_no_current',
                        'label' => 'LBL_INVOICE_NO_CURRENT',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'label' => 'History',
                    ),
                ),
            ),
        ),
    ),
);
