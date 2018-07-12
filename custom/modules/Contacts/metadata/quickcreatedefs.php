<?php
$viewdefs['Contacts'] =
array (
    'QuickCreate' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'hidden' =>
                array (
                    0 => '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
                    1 => '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
                    2 => '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
                    3 => '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
                    4 => '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
                    5 => '{if !empty($smarty.request.contact_id)}<input type="hidden" name="reports_to_id" value="{$smarty.request.contact_id}">{/if}',
                    6 => '{if !empty($smarty.request.contact_name)}<input type="hidden" name="report_to_name" value="{$smarty.request.contact_name}">{/if}',
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
                'LBL_CONTACT_INFORMATION' =>
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
            'lbl_contact_information' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'customLabel' => '{$MOD.LBL_NAME} <span class="required">*</span>',
                        'customCode' => '<table width="100%" style="padding:0px!important;width: 300px;">
                        <tbody><tr>
                        <td style="padding: 0px !important;" width = "60%"><input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME|replace:\':\':\'\'}" style="margin-right: 3px;" size="20" type="text"  value="{$fields.last_name.value}"></td>
                        <td style="padding: 0px !important;" width="40%"><input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME|replace:\':\':\'\'}" style="width:120px !important; margin-right: 3px;" size="15" type="text" value="{$fields.first_name.value}"></td>
                        </tr>
                        <tr><td colspan="2"><span style=" color: #A99A9A; font-style: italic;"> Bùi Vũ Thanh An  </br> Last Name: Bùi Vũ Thanh </br> First Name:  An </span></td></tr>
                        </tbody>
                        </table><div id = "dialogDuplicationLocated"></div>',
                    )
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'gender',
                        'studio' => 'visible',
                        'label' => 'LBL_GENDER',
                    ),
                    1 =>
                    array (
                        'name' => 'birthdate',
                        'comment' => 'The birthdate of the contact',
                        'label' => 'LBL_BIRTHDATE',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'phone_mobile',
                    ),
                    1 => '',
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'email1',
                    ),
                    1 => '',
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'primary_address_street',
                        'comment' => 'Street address for primary address',
                        'label' => 'LBL_PRIMARY_ADDRESS_STREET',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'lead_source',
                    ),
                    1 =>
                    array (
                        'name' => 'campaign_name',
                        'comment' => 'The first campaign name for Contact (Meta-data only)',
                        'label' => 'LBL_CAMPAIGN',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'assigned_user_name',
                    ),
                    1 =>
                    array (
                        'name' => 'team_name',
                    ),
                ),
            ),
        ),
    ),
);
