<?php
$viewdefs['Contacts'] = 
array (
    'EditView' => 
    array (
        'templateMeta' => 
        array (
            'form' => 
            array (
                'hidden' => 
                array (
                    0 => '<input type="hidden" name="opportunity_id" value="{$smarty.request.opportunity_id}">',
                    1 => '<input type="hidden" name="lead_id" value="{$lead_id}">',
                    2 => '<input type="hidden" name="case_id" value="{$smarty.request.case_id}">',
                    3 => '<input type="hidden" name="bug_id" value="{$smarty.request.bug_id}">',
                    4 => '<input type="hidden" name="email_id" value="{$smarty.request.email_id}">',
                    5 => '<input type="hidden" name="inbound_email_id" value="{$smarty.request.inbound_email_id}">',
                    6 => '<input type="hidden" name="assigned_user_id_2" value="{$assigned_user_id_2}">',
                    7 => '<input type="hidden" id="contact_id" name value="{$fields.contact_id.value}">',
                    8 => '<input type="hidden" id="team_type" name value="{$team_type}">',
                    9 => '<input type="hidden" name="birthdate_2" value="{$birthdate_2}">',
                    10 => '<input type="hidden" name="last_name_2" value="{$last_name_2}">',
                    11 => '<input type="hidden" name="first_name_2" value="{$first_name_2}">',
                    12 => '<input type="hidden" name="phone_mobile_2" value="{$phone_mobile_2}">',
                ),
            ),
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
            {sugar_getscript file="custom/modules/Contacts/js/editviews.js"}
            {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.js"}
            {sugar_getscript file="custom/modules/Contacts/js/pGenerator.jquery.js"}
            {sugar_getscript file="custom/include/javascripts/AutoComplete/src/js/textext.core.js"}
            {sugar_getscript file="custom/include/javascripts/AutoComplete/src/js/textext.plugin.autocomplete.js"}

            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Select2/select2.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/AutoComplete/src/css/textext.core.css}"/>
            <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/AutoComplete/src/css/textext.plugin.autocomplete.css}"/>
            ',
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
                        'label' => 'LBL_CONTACT_ID',
//                        'customCode' => '<input type="text" class="input_readonly" name="contact_idd" id="contact_id" maxlength="255" value="{$fields.contact_id.value}" title="{$MOD.LBL_CONTACT_ID}" size="30" readonly>',
                    ),
                    1 => 'picture',
                ),
                1 => 
                array (
                    0 => 
                    array (
                        'name' => 'full_student_name',
                        'customLabel' => '{$MOD.LBL_FULL_NAME} <span class="required">*</span>',
                        'customCode' => ' 
                        {$SELECT_SAINT_HTML}   
                        &nbsp;<input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME_PLACEHOLDER}" style="margin-right: 3px;" size="20" type="text"  value="{$fields.last_name.value}">
                        &nbsp;<input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME_PLACEHOLDER}" style="width:120px !important; margin-right: 3px;" size="15" type="text" value="{$fields.first_name.value}">
                        <div id = "dialogDuplicationLocated"></div>',
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
                        'displayParams' => 
                        array (
                            'required' => true,
                        ),
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
                    1 => 'phone_home',
                ),
                4 => 
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
                        'customCode' => '
                        {$SELECT_PARENT_1_SAINT_HTML}
                        &nbsp;<input name="guardian_name" id="guardian_name" style="margin-right: 3px;" size="20" type="text"  value="{$fields.guardian_name.value}">
                        ',
                    ),
                    1 => 'other_mobile',
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
                        'customCode' => '
                        {$SELECT_PARENT_2_SAINT_HTML}
                        &nbsp;<input name="guardian_name_2" id="guardian_name_2" style="margin-right: 3px;" size="20" type="text"  value="{$fields.guardian_name_2.value}">
                        ',
                    ),   
                    1 => 'phone_other',
                ),     
                6 => 
                array (
                    0 => 
                    array (
                        'name' => 'primary_address_street',         
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
                        'comment' => '',
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
