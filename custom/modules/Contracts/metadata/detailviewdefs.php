<?php
$viewdefs['Contracts'] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '3',
            'widths' =>
            array (
                0 =>
                array (
                    'label' => '10',
                    'field' => '15',
                ),
                1 =>
                array (
                    'label' => '10',
                    'field' => '15',
                ),
                2 =>
                array (
                    'label' => '10',
                    'field' => '40',
                ),
            ),
            'includes' =>
            array (
                0 =>
                array (
                    'file' => 'custom/modules/Contracts/js/detailviews.js',
                ),
            ),
            'form' =>
            array (
                'hidden' =>
                array (
                    0 => '{include file="custom/modules/J_Payment/tpl/addToClassAdult.tpl"}',
                    1 => '{include file="custom/modules/J_Payment/tpl/paymentTemplate.tpl"}',
                ),

                'buttons' =>
                array (
                    0 => 'EDIT',
                    2 => 'DUPLICATE',
                    3 => 'DELETE',
                ),
            ),
            'useTabs' => false,
            'tabDefs' =>
            array (
                'LBL_CONTRACT_INFORMATION' =>
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
            'syncDetailEditViews' => true,
        ),
        'panels' =>
        array (
            'lbl_contract_information' =>
            array (
                0 =>
                array (  
                    0 =>
                    array (
                        'name' => 'contract_number',
                        'label' => 'LBL_CONTRACT_NUMBER',
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 => 'status',
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_CONTRACT_NAME',
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 => 'kind_of_course',
                ),
                3 =>
                array (
                    0 => 'customer_signed_date',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 => 'account_name',
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'total_contract_value',
                        'label' => '{$MOD.LBL_TOTAL_CONTRACT_VALUE}',
                    ),
                    1 =>  array (
                        'name' => 'vat_percent',
                    ),
                    2 =>
                    array (
                        'name' => 'account_address',
                        'studio' => 'visible',
                        'label' => 'LBL_ACCOUNT_ADDRESS',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'number_of_student',
                        'label' => 'LBL_NUMBER_OF_STUDENT',
                        'customCode' => '{$number_student}'
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'name' => 'account_phone',
                        'label' => 'LBL_ACCOUNT_PHONE',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'duration_session',
                        'label' => 'LBL_DURATION_SESSION',
                    ),
                    1 =>  array (
                        'name' => 'duration_hour',
                    ),
                    2 =>
                    array (
                        'name' => 'account_tax_code',
                        'label' => 'LBL_ACCOUNT_TAX_CODE',
                    ),
                ),
                7 =>
                array (
                    0 => 'start_date',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'name' => 'account_bank_name',
                        'label' => 'LBL_ACCOUNT_BANK_NAME',
                    ),
                ),
                8 =>
                array (
                    0 => 'end_date',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'name' => 'account_bank_number',
                        'label' => 'LBL_ACCOUNT_BANK_NUMBER',
                    ),
                ),
                9 =>
                array (
                    0 =>
                    array (
                        'name' => 'deadline_date',
                        'comment' => '',
                        'label' => 'LBL_DEADLINE_DATE',
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'name' => 'c_contacts_contracts_1_name',
                    ),
                ),
                10 =>
                array (
                    0 =>
                    array (
                        'customCode' => '{$PAID_AMOUNT}',
                        'label' => 'LBL_TOTAL_PAID',
                    ),
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
                    array (
                        'customCode' => '{$UNPAID_AMOUNT}',
                        'label' => 'LBL_TOTAL_UNPAID',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array (
                0 =>
                array (
                    0 => 'description',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>  array (
                        'hideLabel' => 'true',
                    ),
                ),
                1 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 => array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                2 => array (
                    0 => 'team_name',
                    1 =>  array (
                        'hideLabel' => 'true',
                    ),
                    2 =>
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
