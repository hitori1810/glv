<?php
$viewdefs['Prospects'] =
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
                        'customCode' => '{$btn_convert}',
                    ),  
                ),
                'hidden' =>
                array (
                    0 => '<input type="hidden" name="prospect_id" value="{$fields.id.value}">',
                ),
                'headerTpl' => 'modules/Prospects/tpls/DetailViewHeader.tpl',
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
            'tabDefs' =>
            array (
                'LBL_PROSPECT_INFORMATION' =>
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
            'lbl_prospect_information' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'label' => 'LBL_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'birthdate',
                        'label' => 'LBL_BIRTHDATE',
                    ),
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
                        'name' => 'j_school_prospects_1_name',
                        'label' => 'LBL_J_SCHOOL_PROSPECTS_1_FROM_J_SCHOOL_TITLE',
                    ),
                ), 
                3 =>
                array (
                    0 => 'phone_mobile',
                    1 =>
                    array (
                        'name' => 'facebook',
                        'comment' => 'URL of website for the company',
                        'label' => 'LBL_FACEBOOK',
                    ),
                ),  
                4 =>
                array (
                    0 => 'email1',        
                ),
                5 =>
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
                6 =>
                array (
                    0 => 'description',
                ),
            ),
            'LBL_PANEL_PARENT' =>
            array (  
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'guardian_name',
                        'label' => 'LBL_GUARDIAN_NAME',
                    ),
                    1 =>
                    array (
                        'name' => 'other_mobile',
                        'label' => 'LBL_OTHER_MOBILE',
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
                        'name' => 'status',
                        'comment' => 'Status of the target',
                        'label' => 'LBL_STATUS',
                        'customCode' => '{$STATUS}',
                    ),
                ),
                1 =>
                array (
                    0 => 'lead_source_description',
                    1 =>
                    array (
                        'name' => 'potential',
                        'studio' => 'visible',
                        'label' => 'LBL_POTENTIAL',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'campaign_name',
                        'label' => 'LBL_CAMPAIGN',
                    ),
                ),
                3 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),
                4 =>
                array (
                    0 => 'team_name',
                    1 =>
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
