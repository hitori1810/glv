<?php
$viewdefs['Contacts'] = 
array (
    'DetailView' => 
    array (
        'templateMeta' => 
        array (
            'form' => 
            array (
                'hidden' => 
                array (
                    0 => '<input type="hidden" name="password" id="password" value="">',
                ),
                'hideAudit' => true,
                'buttons' => 
                array (
                    0 => 'EDIT',
                    1 => 'DELETE',
                    2 => 
                    array (
                        'customCode' => '{$custom_button}',
                    ),
                ),
                'headerTpl' => 'custom/modules/Contacts/tpls/DetailViewHeader.tpl',
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
            {sugar_getscript file="custom/modules/Contacts/js/detailviews.js"}
            {sugar_getscript file="custom/modules/Contacts/js/pGenerator.jquery.js"}
            {sugar_getscript file="custom/modules/Contacts/js/handlePTDemoContact.js"}',
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
                'LBL_PORTAL_INFORMATION' => 
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
                        'name' => 'contact_id',       
                    ),
                    1 => 'picture',
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'name',
                        'label' => 'LBL_FULL_NAME',
                    ),
                    1 => 
                    array (
                        'name' => 'dob_date',
                        'label' => 'LBL_BIRTHDATE',
                        'type' => 'Dob',
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
                    1 => 
                    array (
                        'name' => 'gender',
                        'studio' => 'visible',
                        'label' => 'LBL_GENDER',
                    ),
                ),
                3 => 
                array (
                    0 => 
                    array (
                        'name' => 'phone_mobile',
                        'label' => 'LBL_MOBILE_PHONE',
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (
                        'name' => 'facebook',
                        'comment' => 'URL of website for the company',
                        'label' => 'LBL_FACEBOOK',
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
                6 => 
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
                ),
            ),
            'LBL_PANEL_COMPANY' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'guardian_name',
                        'comment' => '',
                        'label' => 'LBL_GUARDIAN_NAME',
                    ),
                    1 => 
                    array (
                        'name' => 'other_mobile',
                    ),
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
            'lbl_portal_information' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'portal_name',
                        'label' => 'LBL_PORTAL_NAME',
                        'hideIf' => 'empty($PORTAL_ENABLED)',
                        'customCode' => '<a href="index.php?module=Users&return_module=Users&action=DetailView&record={$fields.user_id.value}">{$fields.portal_name.value}</a> {$portalLoginPanel}',
                    ),
                    1 => 
                    array (
                        'name' => 'portal_active',
                        'label' => 'LBL_PORTAL_ACTIVE',
                        'hideIf' => 'empty($PORTAL_ENABLED)',
                    ),
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'password_generated',
                        'label' => 'LBL_PASS_GENERATED',
                        'customCode' => '{$fields.password_generated.value} {$portalResetPanel}',
                    ),
                    1 => '',
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'contact_status',   
                    ),
                ),
                1 => 
                array (
                    0 => 'status_description',
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'assigned_user_name',
                    ),
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
