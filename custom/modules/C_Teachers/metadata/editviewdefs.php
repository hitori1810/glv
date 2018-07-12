<?php
$module_name = 'C_Teachers';
$viewdefs[$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'hidden' =>
                array (
                    1 => '<input type="hidden" name="teacher_type" id="teacher_type" value="{$fields.teacher_type.value}">',
                ),
            ),
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/C_Teachers/js/editview.js"}',
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
                'LBL_MORE_INFORMATION' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_OTHER_INFORMATION' =>
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
                        'name' => 'teacher_id',
                        'label' => 'LBL_TEACHER_ID',
                        'customCode' => '<input type="text" class="input_readonly" name="teacher_idd" id="teacher_id" maxlength="255" value="{$fields.teacher_id.value}" title="{$MOD.LBL_NO_CONTRACT}" size="30" readonly>',


                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'customLabel' => '{$MOD.LBL_NAME} <span class="required">*</span>',
                        'customCode' => '
                        <input name="first_name" id="first_name" placeholder="{$MOD.LBL_FIRST_NAME|replace:\':\':\'\'}" size="20" type="text"  value="{$fields.first_name.value}">
                        &nbsp;<input name="last_name" id="last_name" placeholder="{$MOD.LBL_LAST_NAME|replace:\':\':\'\'}" style="width:120px !important" size="15" type="text" value="{$fields.last_name.value}"> </br>
                        <span style=" color: #A99A9A; font-style: italic;">Ex: Amanda Natalie Costigan </br> First Name: Amanda  </br> Last Name: Natalie Costigan</span>',
                    ),
                    1 =>
                    array (
                        'name' => 'picture',
                        'comment' => 'Picture file',
                        'label' => 'LBL_PICTURE_FILE',
                    ),
                ),
                2 =>
                array (
                    0 => 'phone_mobile',
                    1 => 'phone_other',
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'dob',
                        'label' => 'LBL_DOB',
                    ),
                    1 =>
                    array (
                        'name' => 'nationality',
                        'label' => 'LBL_NATIONALITY',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'experience',
                        'studio' => 'visible',
                        'label' => 'LBL_EXPERIENCE',
                    ),
                    1 =>
                    array (
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                ),
                5 =>
                array (
                    0 => 'email1',
                    1 =>
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
                ),
                6 =>
                array (
                    0 => 'description',
                ),
            ),
            'LBL_MORE_INFORMATION' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'teaching_cerificate',
                        'studio' => 'visible',
                        'label' => 'LBL_TEACHING_CERIFICATE',
                    ),
                    1 => 'passport_number'
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'visa_number',
                        'label' => 'LBL_VISA_NUMBER',
                    ),
                    1 =>
                    array (
                        'name' => 'visa_expiration_date',
                        'label' => 'LBL_VISA_EXPIRATION_DATE',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'passport_issued_date',
                        'label' => 'LBL_PASSPORT_ISSUED_DATE',
                    ),
                    1 =>
                    array (
                        'name' => 'passport_expired_date',
                        'label' => 'LBL_PASSPORT_EXPIRED_DATE',
                    ),
                ),
                //3 =>
                //				array (
                //					0 =>
                //					array (
                //						'name' => 'contract_date',
                //					),
                //					1 =>
                //					array (
                //						'name' => 'contract_until',
                //					),
                //				),

                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'current_university',
                        'label' => 'LBL_CURRENT_UNIVERSITY',
                    ),
                    1 =>
                    array (
                        'name' => 'avilable_time',
                        'studio' => 'visible',
                        'label' => 'LBL_AVILABLE_TIME',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'bank_no',
                        'label' => 'LBL_BANK_NO',
                    ),
                    1 =>
                    array (
                        'name' => 'CITAD_code',
                        'label' => 'LBL_CITAD_CODE',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'reason_inactive',
                        'studio' => 'visible',
                        'label' => 'LBL_REASON_INACTIVE',
                    ),
                ),
                7 =>
                array (
                    0 => 'hr_code' ,
                    1 => 'pit' ,
                ),
            ),
            'LBL_OTHER_INFORMATION' =>
            array (
                0 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'team_name',
                        'displayParams' =>
                        array (
                            'display' => true,
                        ),
                    ),
                ),
            ),
        ),
    ),
);
