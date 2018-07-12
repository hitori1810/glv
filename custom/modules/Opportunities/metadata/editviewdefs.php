<?php
    $viewdefs['Opportunities'] =
    array (
        'EditView' =>
        array (
            'templateMeta' =>
            array (
                'form' =>
                array (
                    'enctype' => 'multipart/form-data',
                    'hidden' =>
                    array (
                        1 => '<input type="hidden" name="amount_in_words_payment" id="amount_in_words_payment" value="">',
                        2 => '<input type="hidden" name="amount_in_words_invoice" id="amount_in_words_invoice" value="">',
                        3 => '<input type="hidden" name="c_promotions_opportunities_1c_promotions_ida" id="c_promotions_opportunities_1c_promotions_ida" value="{$fields.c_promotions_opportunities_1c_promotions_ida.value}">',
                        4 => '<input type="hidden" name="payment_price_1" id="payment_price_1" value="{sugar_number_format var=$fields.payment_price_1.value}">',
                        5 => '<input type="hidden" name="payment_rate_1" id="payment_rate_1" value="{sugar_number_format var=$fields.payment_rate_1.value precision=2}">',
                        6 => '<input type="hidden" name="marketing_fee" id="marketing_fee" value="{sugar_number_format var=$fields.marketing_fee.value}">',
                        7 => '<input type="hidden" name="center_fee" id="center_fee" value="{sugar_number_format var=$fields.center_fee.value}">',
                        8 => '<input type="hidden" name="lead_source_temp" id="lead_source_temp" value="{$fields.lead_source.value}">',
                        9 => '<input type="hidden" name="campaign_name_temp" id="campaign_name_temp" value="{$fields.campaign_name.value}">',
                        10 => '<input type="hidden" name="campaign_id_temp" id="campaign_id_temp" value="{$fields.campaign_id.value}">',
                        11 => '<input type="hidden" name="free_balance_temp" id="free_balance_temp" value="{sugar_number_format var=$fields.free_balance.value}">',
                        12 => '<input type="hidden" name="total_hours" id="total_hours" value="{$fields.total_hours.value}">',
                        13 => '<input type="hidden" name="interval" id="interval" value="{$fields.interval.value}">',
                        14 => '<input type="hidden" name="closed_date" id="closed_date" value="{$fields.closed_date.value}">',
                        15 => '<input type="hidden" name="after_discount_1" id="after_discount_1" value="{sugar_number_format var=$fields.after_discount_1.value}">',
                        16 => '<input type="hidden" name="isdiscount" id="isdiscount" value="{$fields.isdiscount.value}">',
                        17 => '<input type="hidden" name="discount_amount_pack" id="discount_amount_pack" value="{sugar_number_format var=$fields.discount_amount_pack.value}">',
                        18 => '<input type="hidden" name="sponsor_id" id="sponsor_id" value="{$fields.sponsor_id.value}">',
                        19 => '<input type="hidden" name="check_access" id="check_access" value="{$check_access}">',

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
                {sugar_getscript file="custom/include/javascripts/currency_word.js"}
                {sugar_getscript file="custom/modules/Opportunities/js/editview.js"}',
                'useTabs' => false,
                'tabDefs' =>
                array (
                    'LBL_OPPORTUNITY_INFORMATION' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_EDITVIEW_PANEL1' =>
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
                'LBL_OPPORTUNITY_INFORMATION' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'oder_id',
                            'customCode' => '{$NEWID}',
                        ),
                        1 =>
                        array (
                            'hideLabel' => true,
                            'customCode' => '{$DETAILS_HTML}',
                        ),
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'parent_name',
                            'label' => 'LBL_CONTACT_NAME',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'class' => 'sqsNoAutofill',
                            ),
                        ),
                        1 => array(
                            'name' => 'opportunity_type',
                            'customCode' => '<span id="opportunity_type_span">{$fields.opportunity_type.value}</span> <input type="hidden" name="opportunity_type" id="opportunity_type" value="{$fields.opportunity_type.value}">'
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'c_packages_opportunities_1_name',
                            'displayParams' =>
                            array (
                                'field_to_name_array' =>
                                array (
                                    'id' => 'c_packages_opportunities_1c_packages_ida',
                                    'name' => 'c_packages_opportunities_1_name',
                                    'price' => 'amount',
                                    'payment_price_1' => 'payment_price_1',
                                    'payment_rate_1' => 'payment_rate_1',
                                    'after_discount_1' => 'after_discount_1',
                                    'discount_amount' => 'discount_amount_pack',
                                    'isdiscount' => 'isdiscount',
                                    'total_hours' => 'total_hours',
                                    'interval_package' => 'interval',
                                ),
                                'required' => true,
                                'class' => 'sqsNoAutofill',
                                'call_back_function' => 'set_package_return',
                            ),
                            'label' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_C_PACKAGES_TITLE',
                        ),
                        1 =>
                        array (
                            'name' => 'amount',
                            'label' => 'LBL_PRICE',
                            'customCode' => '<input value="{sugar_number_format var=$fields.amount.value}" type="text" size="30" class="currency" name="amount" id="amount" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial; font-weight: bold; color: brown;" >',
                        ),
                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'discount',
                            'label' => 'LBL_SPONSOR_CODE',
                            'customCode' => '
                            <span><input type="text" name="sponsor_code" id="sponsor_code" maxlength="100" size="15" value="{$fields.sponsor_code.value}">&nbsp;<label style="display:none;" id="valid_code"><img src="custom/include/images/checked.gif" align="absmiddle" width="16"></label><label style="display:none;" id="invalid_valid_code"><img src="custom/include/images/unchecked.gif" align="absmiddle" width="16"></label> <input name="checkDuplicate" id="checkDuplicate" type="text" style="display:none;"/></span>
                            <label width="12.5%" style="background-color:#eee; color: #444; padding:.6em">{$MOD.LBL_DISCOUNT}: </label>&nbsp;
                            <input type="hidden" name="discount_name" id="discount_name" value="">
                            <input type="text" name="discount" style="background-color: rgb(221, 221, 221);" readonly class="currency" id="discount" size="3" maxlength="5" value="{sugar_number_format var=$fields.discount.value precision=2}" title="" tabindex="0">&nbsp;<b>%</b>&nbsp;&nbsp;<a id="addMoreItems" title="Get Discount" style="color: blue; cursor:pointer; text-decoration: underline; display:none;">Get Promotion</a>
                            ',
                        ),
                        1 => array(
                            'name' => 'discount_amount',
                            'customCode' => '<input value="{sugar_number_format var=$fields.discount_amount.value}" class="currency" type="text" size="30" name="discount_amount" id="discount_amount" style="font-weight: bold; color: brown;">'
                        ),

                    ),
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'tax_rate',
                            'label' => 'LBL_TAX_RATE',
                            'customCode' => '
                            {$TAXRATE_JAVASCRIPT}
                            <select name="taxrate_id" id="taxrate_id" onchange="this.form.tax_rate.value=get_taxrate(this.form.taxrate_id.options[selectedIndex].value);">{$TAXRATE_OPTIONS}</select>
                            <input type="hidden" name="tax_rate" id="tax_rate" size="10" maxlength="5" value="{sugar_number_format var=$fields.tax_rate.value precision=2}" title="" tabindex="0">
                            ',
                        ),
                        1 => array(
                            'name' => 'tax_amount',
                            'label' => 'LBL_TAX_AMOUNT',
                            'customCode' => '<input accesskey="" tabindex="0" value="{sugar_number_format var=$fields.tax_amount.value}" class="currency" type="text" size="30" name="tax_amount" id="tax_amount" readonly="true" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial; font-weight: bold; color: brown;">'
                        ),
                    ),
                    5 =>
                    array (
                        0 => 'date_closed',
                        1 =>
                        array (
                            'name' => 'total_in_invoice',
                            'label' => 'LBL_TOTAL_IN_INVOICE',
                            'customCode' => '<input value="{sugar_number_format var=$fields.total_in_invoice.value}" class="currency" type="text" size="30" name="total_in_invoice" id="total_in_invoice" readonly="" style="background-color: rgb(221, 221, 221); background-position: initial initial; background-repeat: initial initial; font-weight: bold; color: brown;" >',
                        ),
                    ),
                    6 =>
                    array (
                        0 => 'description',
                    ),
                    7 =>
                    array (
                        0 => 'sales_stage',
                    ),
                    8 =>
                    array (
                        0 => array(
                            'name' => 'isinvoice',
                            'customCode' => '
                            <input type="hidden" name="isinvoice" value="0">
                            <input type="checkbox" id="isinvoice" name="isinvoice" value="1" style="width: 1.5em; height: 1.5em;" tabindex="0">'
                        ),
                    ),

                    11 =>
                    array (
                        0 =>
                        array (
                            'name' => 'ispayment',
                            'customCode' => '<input type="hidden" name="ispayment" value="0">
                            <input type="checkbox" id="ispayment" name="ispayment" value="1" style="width: 1.5em; height: 1.5em;" tabindex="0">',
                        ),
                    ),
                ),
                'lbl_editview_panel1' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'customCode' => '{include file="custom/modules/Opportunities/tpls/payment_edit.tpl"}',
                            'hideLabel' => true,
                        ),
                    ),
                ),
                'LBL_PANEL_ASSIGNMENT' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_COMMISSIONER',
                        ),
                        1 => 'team_name',
                    ),
                ),
            ),
        ),
    );
