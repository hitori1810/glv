<?php
$viewdefs['Leads'] = 
array (
    'EditView' => 
    array (
        'templateMeta' => 
        array (
            'form' => 
            array (
                'hidden' => 
                array (
                    0 => '<input type="hidden" name="prospect_id" value="{if isset($smarty.request.prospect_id)}{$smarty.request.prospect_id}{else}{$bean->prospect_id}{/if}">',
                    1 => '<input type="hidden" name="contact_id" value="{if isset($smarty.request.contact_id)}{$smarty.request.contact_id}{else}{$bean->contact_id}{/if}">',
                    2 => '<input type="hidden" name="opportunity_id" value="{if isset($smarty.request.opportunity_id)}{$smarty.request.opportunity_id}{else}{$bean->opportunity_id}{/if}">',
                    3 => '<input type="hidden" name="assigned_user_id_2" value="{$assigned_user_id_2}">',
                    4 => '<input type="hidden" name="birthdate_2" value="{$birthdate_2}">',
                    5 => '<input type="hidden" name="last_name_2" value="{$last_name_2}">',
                    6 => '<input type="hidden" name="first_name_2" value="{$first_name_2}">',
                    7 => '<input type="hidden" name="phone_mobile_2" value="{$phone_mobile_2}">',
                    8 => '<input type="hidden" name="is_role_mkt" id="is_role_mkt" value="{$is_role_mkt}">',
                ),
                'buttons' => 
                array (
                    0 => 'SAVE',
                    1 => 'CANCEL',
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
            'javascript' => '<script type="text/javascript" language="Javascript">function copyAddressRight(form)  {ldelim} form.alt_address_street.value = form.primary_address_street.value;form.alt_address_city.value = form.primary_address_city.value;form.alt_address_state.value = form.primary_address_state.value;form.alt_address_postalcode.value = form.primary_address_postalcode.value;form.alt_address_country.value = form.primary_address_country.value;return true; {rdelim} function copyAddressLeft(form)  {ldelim} form.primary_address_street.value =form.alt_address_street.value;form.primary_address_city.value = form.alt_address_city.value;form.primary_address_state.value = form.alt_address_state.value;form.primary_address_postalcode.value =form.alt_address_postalcode.value;form.primary_address_country.value = form.alt_address_country.value;return true; {rdelim} </script>
            {sugar_getscript file="custom/modules/Leads/js/editviews.js"}',
            'tabDefs' => 
            array (
                'LBL_CONTACT_INFORMATION' => 
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_COMPANY' => 
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
            'syncDetailEditViews' => false,
        ),
        'panels' => 
        array (
            'lbl_contact_information' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'full_lead_name',
                        'customLabel' => '{$MOD.LBL_FULL_NAME} <span class="required">*</span>',
                        'customCode' => '   
                        {html_options name="salutation" id="salutation" options=$fields.salutation.options selected=$fields.salutation.value}&nbsp;
                        &nbsp;<input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME_PLACEHOLDER}" style="margin-right: 3px;" size="20" type="text"  value="{$fields.last_name.value}">
                        &nbsp;<input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME_PLACEHOLDER}" style="width:120px !important; margin-right: 3px;" size="15" type="text" value="{$fields.first_name.value}">
                        <div id = "dialogDuplicationLocated"></div>',
                    ),
                    1 => 
                    array (
                        'name' => 'picture',
                        'comment' => 'Picture file',
                        'label' => 'LBL_PICTURE_FILE',
                    ),
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'birthdate',
                        'customLabel' => '{$MOD.LBL_BIRTHDATE}',
                    ),
                    1 => 
                    array (
                        'name' => 'gender',
                        'studio' => 'visible',
                        'label' => 'LBL_GENDER',
                        'displayParams' => 
                        array (
                            'required' => true,
                        ),
                    ),
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'email1',
                        'studio' => 'false',
                        'label' => 'LBL_EMAIL_ADDRESS',
                    ),
                ),
                3 => 
                array (
                    0 => 
                    array (
                        'name' => 'phone_mobile',
                        'customLabel' => '{$MOD.LBL_MOBILE_PHONE}',        
                    ),
                    1 => 
                    array (
                        'name' => 'j_school_leads_1_name',
                        'displayParams' => 
                        array (
                            'required' => false,
                        ),
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (
                        'name' => 'do_not_call',
                        'comment' => 'An indicator of whether contact can be called',
                        'label' => 'LBL_DO_NOT_CALL',
                    ),
                    1 => 
                    array (
                        'name' => 'grade',
                        'studio' => 'visible',
                        'label' => 'LBL_GRADE',
                    ),
                ),
                5 => 
                array (
                    0 => 'facebook',
                    1 => 
                    array (
                        'name' => 'account_name',    
                    ),
                ),
                6 => 
                array (              
                    0 => 
                    array (
                        'name' => 'description',
                        'displayParams' => 
                        array (
                            'rows' => 4,
                            'cols' => 55,
                        ),
                    ),           
                    1 => 
                    array (
                        'hideLabel' => true,
                    ),
                ),
                7 => 
                array (
                    0 => 
                    array (
                        'name' => 'primary_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => 
                        array (
                            'key' => 'primary',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                    ),
                    1 => 
                    array (
                        'name' => 'alt_address_street',
                        'hideLabel' => true,
                        'type' => 'address',
                        'displayParams' => 
                        array (
                            'key' => 'alt',
                            'copy' => 'primary',
                            'rows' => 2,
                            'cols' => 30,
                            'maxlength' => 150,
                        ),
                    ),
                ),
            ),
            'lbl_panel_company' => 
            array (
                0 => 
                array (
                    0 => 'guardian_name',
                    1 => 'other_mobile',
                ),
                1 => 
                array (
                    0 =>
                    array(
                        'customCode' => '<button data-type="0" type="button" id="copy_parent_1">{$MOD.LBL_COPY_TO_STUDENT}</button> 
                        <span style="font-size: 18px; display: none; vertical-align: middle;">&#10004;</span>',
                    ),
                    1 => 'email_parent_1',
                ),
                2 => 
                array (
                    0 => 'guardian_name_2',
                    1 => 
                    array (
                        'name' => 'phone_other',
                        'comment' => 'Other phone number for the contact',
                        'label' => 'LBL_OTHER_PHONE',
                    ),
                ),
                3 => 
                array (
                    0 =>
                    array(
                        'customCode' => '<button data-type="0" type="button" id="copy_parent_2">{$MOD.LBL_COPY_TO_STUDENT}</button> 
                        <span style="font-size: 18px; display: none; vertical-align: middle;">&#10004;</span>',
                    ),
                    1 => 'email_parent_2',
                ),
            ),
            'lbl_panel_assignment' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'lead_source',
                    ),
                    1 => 
                    array (
                        'name' => 'status',
                        'customCode' => '<span class="{$STATUS_CSS}"><b>{$STATUS}<b></span>  ',
                    ),
                ),
                1 => 
                array (
                    0 => 'lead_source_description',
                    1 => 
                    array (
                        'name' => 'status_description',
                    ),
                ),
                2 => 
                array (
                    0 => 'campaign_name',
                    1 => 
                    array (
                        'name' => 'potential',
                        'studio' => 'visible',
                        'label' => 'LBL_POTENTIAL',
                    ),
                ),
                3 => 
                array (
                    0 => 'prefer_level',
                    1 => 
                    array (
                        'name' => 'reason_not_interested',
                        'label' => 'LBL_REASON',
                    ),
                ),
                4 => 
                array (
                    0 => '',
                    1 => 
                    array (
                        'name' => 'reason_description',
                        'label' => 'LBL_REASON_DESCRIPTION',
                    ),
                ),
                5 => 
                array (
                    0 => 
                    array (
                        'name' => 'assigned_user_name',
                        'displayParams' => 
                        array (
                            'required' => true,
                        ),
                    ),
                    1 => 'team_name',
                ),
            ),
        ),
    ),
);
