<?php
$viewdefs['Accounts'] =
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
                    1 => 'DELETE',
                ),
            ),
            'maxColumns' => '2',
            'useTabs' => false,
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
            'includes' =>
            array (
                0 =>
                array (
                    'file' => 'modules/Accounts/Account.js',
                ),
            ),
            'tabDefs' =>
            array (
                'LBL_ACCOUNT_INFORMATION' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_ADVANCED' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
            'syncDetailEditViews' => true,
        ),
        'panels' =>
        array (
            'lbl_account_information' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'account_id',
                        'label' => 'LBL_ACCOUNT_ID',
                        'customCode' => '<span class="textbg_blue">{$fields.account_id.value}</span>',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'comment' => 'Name of the Company',
                        'label' => 'LBL_NAME',
                        'displayParams' =>
                        array (
                            'enableConnectors' => true,
                            'module' => 'Accounts',
                            'connectors' =>
                            array (
                                0 => 'ext_rest_linkedin',
                                1 => 'ext_rest_twitter',
                            ),
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'picture',
                        'comment' => 'Picture file',
                        'label' => 'LBL_PICTURE',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'full_name',
                        'label' => 'LBL_FULL_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'phone_office',
                        'comment' => 'The office phone number',
                        'label' => 'LBL_PHONE_OFFICE',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'short_name',
                        'label' => 'LBL_SHORT_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'phone_alternate',
                        'comment' => 'An alternate phone number',
                        'label' => 'LBL_PHONE_ALT',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'website',
                        'type' => 'link',
                        'label' => 'LBL_WEBSITE',
                        'displayParams' =>
                        array (
                            'link_target' => '_blank',
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'phone_fax',
                        'comment' => 'The fax phone number of this company',
                        'label' => 'LBL_FAX',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'billing_address_street',
                        'label' => 'LBL_BILLING_ADDRESS',
                        'type' => 'address',
                        'displayParams' =>
                        array (
                            'key' => 'billing',
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'email1',
                        'studio' => 'false',
                        'label' => 'LBL_EMAIL',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
            'LBL_PANEL_ADVANCED' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'ceo_name',
                    ),
                    1 =>
                    array (
                        'name' => 'hr_manager_name',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'sale_manager_name',
                    ),
                    1 =>
                    array (
                        'name' => 'sale_contact_name',
                    ),
                ),

                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'bank_name',
                        'label' => 'LBL_BANK_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'tax_code',
                        'label' => 'LBL_TAX_CODE',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'type_of_account',
                        'comment' => 'The Company is of this type',
                        'label' => 'LBL_ACCOUNT_TYPE',
                    ),
                    1 =>
                    array (
                        'name' => 'bank_number',
                        'label' => 'LBL_BANK_NUMBER',
                    ),
                ),
                2 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                    ),
                ),
                3 =>
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
