<?php
$module_name = 'C_Teachers';
$viewdefs[$module_name] =
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
				),
			),
			'includes' =>
			array (
				1 =>
				array (
					'file' => 'custom/include/DateRange/moment.min.js',
				),
				2 =>
				array (
					'file' => 'custom/include/DateRange/jquery.daterangepicker.js',
				),
				3 =>
				array (
					'file' => 'custom/modules/C_Teachers/js/detailview.js',
				),
			),
			'useTabs' => false,
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
						'name' => 'teacher_type',
						'label' => 'LBL_TEACHER_TYPE',
					),
				),
				1 =>
				array (
					0 =>
					array (
						'name' => 'teacher_id',
						'label' => 'LBL_TEACHER_ID',
						'customCode' => '{$TEACHER_CODE}',
					),
					1 =>
					array (
						'name' => 'status',
						'studio' => 'visible',
						'label' => 'LBL_STATUS',
					),
				),
				2 =>
				array (
					0 =>
					array (
						'name' => 'full_teacher_name',
						'comment' => 'First name of the contact',
						'label' => 'LBL_NAME',
					),
					1 =>
					array (
						'name' => 'picture',
						'comment' => 'Picture file',
						'label' => 'LBL_PICTURE_FILE',
					),
				),
				3 =>
				array (
					0 => 'phone_mobile',
					1 => 'phone_other',
				),
				4 =>
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
				5 =>
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
				6 =>
				array (
					0 => 'email1',
					1 =>
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
				7 =>
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
				0 => array (
					0 => 'assigned_user_name',
					1 => array (
						'name' => 'date_entered',
						'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
						'label' => 'LBL_DATE_ENTERED',
					),
				),
				1 => array (
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
