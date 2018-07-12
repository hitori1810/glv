<?php
$viewdefs['Prospects'] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
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
                'LBL_PANEL_PARENT' =>
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
                        'customLabel' => '{$MOD.LBL_NAME} <span class="required">*</span>',       
                        'customCode' => '
                        <table width="100%" style="padding:0px!important;width: 300px;">
                        <tbody><tr>
                        <td style="padding: 0px !important;" width = "60%"><input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME_PLACEHOLDER}" style="margin-right: 3px;" size="20" type="text"  value="{$fields.last_name.value}"></td>
                        <td style="padding: 0px !important;" width="40%"><input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME_PLACEHOLDER}" style="width:120px !important; margin-right: 3px;" size="15" type="text" value="{$fields.first_name.value}"></td>
                        </tr>
                        </tbody>
                        </table><div id = "dialogDuplicationLocated"></div>',
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
                        'label' => 'LBL_GENDER',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
                    1 => 'j_school_prospects_1_name',
                ),   
                2 =>
                array (
                    0 => 'phone_mobile',
                    1 => 'facebook',
                ),  
                3 =>
                array (
                    0 => 'email1',       
                ),
                4 =>
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
                5 =>
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
                        'label' => 'LBL_LEAD_SOURCE',
                        'customCode' => '{$lead_source}',
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
                    0 =>
                    array (
                        'name' => 'lead_source_description',
                        'studio' => 'visible',
                        'label' => 'LBL_SOURCE_DESCRIPTION',
                    ),
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
