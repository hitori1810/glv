<?php
$viewdefs['Contracts'] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'hidden' =>
                array (
                    0 => '<input type="hidden" name="pmd_id[]" id="pmd_id_1" value="{$pmd.1.pmd_id}">',
                    1 => '<input type="hidden" name="pmd_id[]" id="pmd_id_2" value="{$pmd.2.pmd_id}">',
                    2 => '<input type="hidden" name="pmd_id[]" id="pmd_id_3" value="{$pmd.3.pmd_id}">',
                    3 => '<input type="hidden" name="pmd_id[]" id="pmd_id_4" value="{$pmd.5.pmd_id}">',
                    4 => '<input type="hidden" name="pmd_id[]" id="pmd_id_5" value="{$pmd.6.pmd_id}">',
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
            'javascript' => '{$PROBABILITY_SCRIPT}
            {sugar_getscript file="custom/modules/Contracts/js/editview.js"}
            {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
            <link rel="stylesheet" href="{sugar_getjspath file=\'custom/include/javascripts/Select2/select2.css\'}"/>',
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
                    1 =>
                    array (
                        'name' => 'status',
                        'customCode' => '{html_options name="status" id="status" options=$fields.status.options selected=$fields.status.value}',
                    ),
                ),  
                2 =>
                array (
                    0 => 'name',
                    1 =>
                    array (
                        'name' => 'kind_of_course',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'customer_signed_date',
                        'displayParams' =>
                        array (
                            'showFormats' => true,
                            'required' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'account_name',
                        'displayParams' =>
                        array (
                            'field_to_name_array' =>
                            array (
                                'id' => 'account_id',
                                'name' => 'account_name',
                                'phone_office' => 'account_phone',
                                'tax_code' => 'account_tax_code',
                                'phone_fax' => 'account_fax',
                                'bank_name' => 'account_bank_name',
                                'bank_number' => 'account_bank_number',
                                'billing_address_street' => 'account_address',
                            ),
                            'required' => true,
                        ),
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'total_contract_value',
                        'label' => 'LBL_TOTAL_CONTRACT_VALUE',
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important;" width = "40%"><input class="currency" type="text" name="total_contract_value" id="total_contract_value" size="20" maxlength="26" value="{sugar_number_format var=$fields.total_contract_value.value}" title="{$MOD.LBL_TOTAL_CONTRACT_VALUE}" tabindex="0"  style="font-weight: bold;color: rgb(165, 42, 42);"></td>
                        <td width="25%" scope="col"><label>
                        {$MOD.LBL_VAT_PERCENT}: </label></td>
                        <td width="35%"><input class="" autocomplete="off" type="text" name="vat_percent" id="vat_percent" value="{sugar_number_format var=$fields.vat_percent.value precision=2}" tabindex="0" size="4" maxlength="10" style="color: rgb(165, 42, 42);"></td>
                        </tr></tbody>
                        </table>',
                    ),
                    1 =>
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
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr>
                        <td style="padding: 0px !important;" width = "40%"><input type="text" name="number_of_student" id="number_of_student" size="4" value="{sugar_number_format var=$fields.number_of_student.value}" title="{$MOD.LBL_NUMBER_OF_STUDENT}" tabindex="0"  style="color: rgb(165, 42, 42);"></td>
                        </tr></tbody>
                        </table>',
                    ),
                    1 =>
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
                        'customCode' => '<table width="100%" style="padding:0px!important;">
                        <tbody><tr colspan="3">
                        <td style="padding: 0px !important;" width = "40%"><input type="text" name="duration_session" id="duration_session" size="4" value="{sugar_number_format var=$fields.duration_session.value}" title="{$MOD.LBL_DURATION_SESSION}" tabindex="0"  style="color: rgb(165, 42, 42);"></td>
                        <td width="25%" scope="col"><label>
                        {$MOD.LBL_DURATION_HOUR}: <span class="required">*</span></label></td>
                        <td width="35%"><input class="" autocomplete="off" type="text" name="duration_hour" id="duration_hour" value="{sugar_number_format var=$fields.duration_hour.value precision=2}" tabindex="0" size="4" maxlength="10" title="{$MOD.LBL_DURATION_HOUR}" style="color: rgb(165, 42, 42);"></td>
                        </tr></tbody>
                        </table>',
                    ),
                    1 =>
                    array (
                        'name' => 'account_tax_code',
                        'label' => 'LBL_ACCOUNT_TAX_CODE',
                    ),
                ),
                7 =>
                array (
                    0 =>
                    array (
                        'name' => 'start_date',
                        'displayParams' =>
                        array (
                            'showFormats' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'account_bank_name',
                        'label' => 'LBL_ACCOUNT_BANK_NAME',
                    ),
                ),
                8 =>
                array (
                    0 =>
                    array (
                        'name' => 'end_date',
                        'displayParams' =>
                        array (
                            'showFormats' => true,
                        ),
                    ),
                    1 =>
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
                        'label' => 'LBL_DEADLINE_DATE',
                        'displayParams' =>
                        array (
                            'showFormats' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'c_contacts_contracts_1_name',
                    ),
                ),
                10 =>
                array (
                    0 =>
                    array (
                        'name' => 'number_of_payment',
                        'customLabel' => '{$MOD.LBL_SPLIT_PAYMENT}:',
                        'customCode' => '
                        {html_options name="number_of_payment" id="number_of_payment" options=$fields.number_of_payment.options selected=$fields.number_of_payment.value}',
                    ),
                ),
                11 =>
                array (
                    0 =>
                    array (
                        'hideLabel' => true,
                        'customCode' => '{include file="custom/modules/Contracts/tpl/payment_detail.tpl"}',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'team_name',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
