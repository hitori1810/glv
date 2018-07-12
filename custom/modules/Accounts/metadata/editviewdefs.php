<?php
$viewdefs['Accounts'] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'enctype' => 'multipart/form-data',
                'buttons' =>
                array (
                    0 => 'SAVE',
                    1 => 'CANCEL',
                ),
            ),
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/include/javascripts/CKEditor/ckeditor.js"}
            {sugar_getscript file="custom/modules/Accounts/js/editview.js"}',
            'useTabs' => true,
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
            'syncDetailEditViews' => false,
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
                        'customCode' => '<input type="text" class="input_readonly" name="account_idd" id="account_id" maxlength="255" value="{$fields.account_id.value}" title="{$MOD.LBL_ACCOUNT_ID}" size="30" readonly>',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'picture',
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
                        'label' => 'LBL_PHONE_OFFICE',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'short_name',
                        'label' => 'LBL_SHORT_NAME',
                        'customCode' => '<input type="text" name="short_name" id="short_name" value="{$fields.short_name.value}" size="5" style="text-transform: uppercase;" maxlength="10">',
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
                    ),
                    1 =>
                    array (
                        'name' => 'phone_fax',
                        'label' => 'LBL_FAX',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'billing_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' =>
                        array (
                            'key' => 'billing',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
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
                    0 => 'type_of_account',
                    1 =>
                    array (
                        'name' => 'bank_number',
                        'label' => 'LBL_BANK_NUMBER',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                        'label' => 'LBL_ASSIGNED_TO',
                    ),
                    1 =>
                    array (
                        'name' => 'team_name',
                        'displayParams' =>
                        array (
                            'display' => true,
                        ),
                    ),
                ),
            ),
                    ),
                ),
);
