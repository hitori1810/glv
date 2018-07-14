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
                'LBL_EDITVIEW_PANEL1' => 
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
                        'name' => 'full_student_name',
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
                        'name' => 'gender',
                        'studio' => 'visible',
                        'label' => 'LBL_GENDER',
                    ),
                    1 => 
                    array (
                        'name' => 'place_of_birth',
                        'label' => 'LBL_PLACE_OF_BIRTH',
                    ),
                ),
                3 => 
                array (
                    0 => 
                    array (
                        'name' => 'phone_mobile',
                        'label' => 'LBL_MOBILE_PHONE',
                    ),
                    1 => 
                    array (
                        'name' => 'phone_home',
                        'comment' => 'Home phone number of the contact',
                        'label' => 'LBL_HOME_PHONE',
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (
                        'name' => 'description',
                        'comment' => 'Full text of the note',
                        'label' => 'LBL_DESCRIPTION',
                    ),
                    1 => 
                    array (
                        'name' => 'contact_status',
                    ),
                ),
            ),
            'LBL_PANEL_COMPANY' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'guardian_rela_1',
                        'comment' => '',
                        'label' => 'LBL_GUARDIAN_RELA_1',
                    ),
                ),
                1 => 
                array (
                    0 => 
                    array (                                 
                        'name' => 'guardian_name',  
                        'label' => 'LBL_GUARDIAN_NAME',
                        'customCode' => '{$GUARDIAN_NAME}'
                    ),
                    1 => 
                    array (   
                        'name' => 'other_mobile',                             
                    ),
                ),   
                3 => 
                array (
                    0 => 
                    array (                               
                        'name' => 'guardian_rela_2',
                        'comment' => '',
                        'label' => 'LBL_GUARDIAN_RELA_2',
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (                                
                        'name' => 'guardian_name_2',      
                        'label' => 'LBL_GUARDIAN_NAME_2',
                        'customCode' => '{$GUARDIAN_NAME_2}'
                    ),
                    1 => 
                    array (  
                        'name' => 'phone_other',                             
                    ),
                ),      
                6 => 
                array (
                    0 => 
                    array (
                        'name' => 'primary_address_street',
                        'label' => 'LBL_PRIMARY_ADDRESS',          
                        'customCode' => '{$field.primary_address_street.value}'
                    ),
                    1 => 
                    array (
                        'name' => 'address_no',
                        'comment' => '',
                        'label' => 'LBL_ADDRESS_NO',
                    ),
                ),
                7 => 
                array (
                    0 => 
                    array (
                        'name' => 'family_no',
                        'comment' => '',
                        'label' => 'LBL_FAMILY_NO',
                    ),
                    1 => 
                    array (
                        'name' => 'address_quarter',
                        'comment' => '',
                        'label' => 'LBL_ADDRESS_QUARTER',
                    ),
                ),
                8 => 
                array (
                    0 =>                                
                    array (                            
                    ),
                    1 => 
                    array (
                        'name' => 'address_ward',   
                        'label' => 'LBL_ADDRESS_WARD',
                    ),
                ),
            ),
            'lbl_editview_panel1' => 
            array (
                0 => 
                array (
                    0 => 
                    array (
                        'name' => 'baptism_date',
                        'label' => 'LBL_BAPTISM_DATE',
                    ),
                    1 => '',
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'eucharist_place',
                        'comment' => '',
                        'label' => 'LBL_EUCHARIST_PLACE',
                    ),
                    1 => 
                    array (
                        'name' => 'eucharist_godparent',
                        'comment' => '',
                        'label' => 'LBL_EUCHARIST_GODPARENT',
                    ),
                ),
                2 => 
                array (
                    0 => 
                    array (
                        'name' => 'eucharist_date',
                        'label' => 'LBL_EUCHARIST_DATE',
                    ),
                    1 => '',
                ),
                3 => 
                array (
                    0 => 
                    array (
                        'name' => 'baptism_place',
                        'comment' => '',
                        'label' => 'LBL_BAPTISM_PLACE',
                    ),
                    1 => 
                    array (
                        'name' => 'baptism_godparent',
                        'comment' => '',
                        'label' => 'LBL_BAPTISM_GODPARENT',
                    ),
                ),
                4 => 
                array (
                    0 => 
                    array (
                        'name' => 'confirmation_date',
                        'label' => 'LBL_CONFIRMATION_DATE',
                    ),
                    1 => '',
                ),
                5 => 
                array (
                    0 => 
                    array (
                        'name' => 'confirmation_place',
                        'comment' => '',
                        'label' => 'LBL_CONFIRMATION_PLACE',
                    ),
                    1 => 
                    array (
                        'name' => 'confirmation_godparent',
                        'comment' => '',
                        'label' => 'LBL_CONFIRMATION_GODPARENT',
                    ),
                ),
                6 => 
                array (
                    0 => 
                    array (
                        'name' => 'graduation_date',
                        'label' => 'LBL_GRADUATION_DATE',
                    ),
                    1 => '',
                ),
            ),
        ),
    ),
);
