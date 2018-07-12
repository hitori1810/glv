<?php
$viewdefs['Leads'] = 
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
                    3 => 
                    array (
                        'customCode' => '{$btn_convert_2}',
                    ),
                ),
                'headerTpl' => 'modules/Leads/tpls/DetailViewHeader.tpl',
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
            'javascript' => '
            {sugar_getscript file="custom/modules/Leads/js/addToPT.js"}
            {sugar_getscript file="modules/Leads/Lead.js"}
            {sugar_getscript file="custom/modules/Leads/js/DetailView.js"}
            ',
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
            'syncDetailEditViews' => true,
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
                        'label' => 'LBL_FULL_NAME',
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
                    0 => 'birthdate',
                ),
                2 => 
                array (
                    0 => 'email1',
                    1 => 'gender',
                ),
                3 => 
                array (
                    0 => 'phone_mobile',
                    1 => 
                    array (
                        'name' => 'j_school_leads_1_name',
                    ),
                ),
                4 => 
                array (
                    0 => 'do_not_call',
                    1 => 
                    array (
                        'name' => 'grade',
                        'studio' => 'visible',
                        'label' => 'LBL_GRADE',
                    ),
                ),
                5 => 
                array (
                    0 => 
                    array (
                        'name' => 'facebook',
                        'label' => 'LBL_FACEBOOK',
                    ),
                    1 => 'account_name',
                ),
                6 => 
                array (    
                    0 => 'description',
                ),
                7 => 
                array (
                    0 => 
                    array (
                        'name' => 'primary_address_street',
                        'label' => 'LBL_PRIMARY_ADDRESS',
                        'type' => 'address',
                        'displayParams' => 
                        array (
                            'key' => 'primary',
                        ),
                    ),
                    1 => 
                    array (
                        'name' => 'alt_address_street',
                        'label' => 'LBL_ALTERNATE_ADDRESS',
                        'type' => 'address',
                        'displayParams' => 
                        array (
                            'key' => 'alt',
                        ),
                    ),
                ),
            ),
            'lbl_panel_company' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'guardian_name',
                        'label' => 'LBL_GUARDIAN_NAME',
                    ),
                    1 => 'other_mobile',
                ),
                1 => 
                array (
                    0 => '',
                    1 => 'email_parent_1',
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'guardian_name_2',
                        'comment' => '',
                        'label' => 'LBL_GUARDIAN_NAME_2',
                    ),
                    1 => 
                    array (
                        'name' => 'phone_other',
                        'comment' => 'Other phone number for the contact',
                        'label' => 'LBL_OTHER_PHONE',
                    ),
                ),
                3 => 
                array (
                    0 => '',
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
                    1 => 'status_description',
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'campaign_name',
                        'label' => 'LBL_CAMPAIGN',
                    ),
                    1 => 
                    array (
                        'name' => 'potential',
                        'studio' => 'visible',
                        'label' => 'LBL_POTENTIAL',
                    ),
                ),
                3 => 
                array (
                    0 => 
                    array (
                        'name' => 'prefer_level',
                    ),
                    1 => 
                    array (
                        'name' => 'reason_not_interested',
                        'label' => 'LBL_REASON',
                        'hideIf' => '($fields.potential.value <> "Not Interested")',
                    ),
                ),
                4 => 
                array (
                    0 => '',
                    1 => 
                    array (
                        'name' => 'reason_description',
                        'label' => 'LBL_REASON_DESCRIPTION',
                        'hideIf' => '($fields.potential.value <> "Not Interested")',
                    ),
                ),
                5 => 
                array (
                    0 => 
                    array (
                        'name' => 'assigned_user_name',
                        'customCode' => '{$assigned_user_idQ}',
                    ),
                    1 => 'team_name',
                ),
            ),
        ),
    ),
);
