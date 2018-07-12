<?php
$module_name = 'J_StudentSituations';
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
                    1 => '<input type="hidden" name="from_class_closed_session" id="from_class_closed_session" value="">',
                    2 => '<input type="hidden" name="move_to_class_closed_session" id="move_to_class_closed_session" value="">',
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
			'javascript' => '           
			{sugar_getscript file="custom/include/javascripts/Bootstrap/bootstrap.min.js"}      
			{sugar_getscript file="custom/include/javascripts/BootstrapMultiselect/js/bootstrap-multiselect.js"}
			{sugar_getscript file="custom/modules/J_StudentSituations/js/editview.js"}

			<link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Bootstrap/bootstrap.min.css}"/>
			<link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/BootstrapMultiselect/css/bootstrap-multiselect.css}"/>
			<link rel="stylesheet" href="{sugar_getjspath file=custom/modules/J_Payment/css/custom_style.css}"/>
			',
			'useTabs' => false,
			'tabDefs' => 
			array (
				'LBL_MOVING_OUT' => 
				array (
					'newTab' => false,
					'panelDefault' => 'expanded',
				),
			),
			'syncDetailEditViews' => true,
		),
		'panels' => 
		array (
			'LBL_MOVING_OUT' => 
			array (
				0 => 
				array (
					0 => 
					array (
						'name' => 'student_name',
						'label' => 'LBL_STUDENT_NAME',
						'displayParams' => 
						array (
							'field_to_name_array' => 
							array (
								'id' => 'student_id',
								'name' => 'student_name',
							),
							'class' => 'sqsNoAutofill',
							'call_back_function' => 'set_student_return',
						),
					),
				),
				1 => 
				array (
					0 => 
					array (
						'name' => 'ju_class_name',
						'label' => 'LBL_MOVING_FROM_CLASS',
						'customCode' => '<select id="ju_class_name" name="ju_class_name"> </select>',
					),
					1 => array (
						'name' => 'move_to_class',
						'label' => 'LBL_MOVE_TO_CLASS_NAME',
						'customCode' => '<select id="move_to_class" name="move_to_class"> {$classOptions} </select>',
					),
				),
				2 => 
				array (
					0 => 
					array (
						'hideLabel' => true,
						'customCode' => '
						<input type="hidden" name="type" value="{$fields.type.value}">
						<input type="hidden" name="used_hour" value="{sugar_number_format var=$fields.used_hour.value precision=2}">
						<input type="hidden" name="moving_hour" value="{sugar_number_format var=$fields.moving_hour.value precision=2}">
						<input type="hidden" name="used_amount" value="{sugar_number_format var=$fields.used_amount.value}">
						<input type="hidden" name="moving_amount" value="{sugar_number_format var=$fields.moving_amount.value precision=2}">
						<input type="hidden" name="remaining_hour" value="{sugar_number_format var=$fields.remaining_hour.value precision=2}">
						<input type="hidden" name="situation_id" value="">
						<fieldset id="credit_info_1" style="width:70%; min-height: 50px; display: none;">
						<legend><b> Class Information: </b></legend>
						Start: <label id="lbl_start_move_from"></label><br>
						Finish: <label id="lbl_finish_move_from"></label><br>
						<b>Schedule:</b><br><label id="lbl_schedule_move_from"></label><br>
                        Type: <span class="textbg_green"> Normal Class </span><br>
						</fieldset>
                        <fieldset id="credit_info_5" style="width:70%; min-height: 50px; display: none;">
                        <legend><b> Class Information: </b></legend>
                        Type: <span class="textbg_orange"> Waiting Class </span><br>
                        </fieldset>
                        ',
					),
					1 => array (
						'hideLabel' => true,
						'customCode' => '
						<fieldset id="credit_info_2" style="width:70%; min-height: 50px; display: block;">
						<legend><b> Class Information: </b></legend>
						Start:  <label id="lbl_start_move_to"></label><br>
						Finish:  <label id="lbl_finish_move_to"></label><br>
						<b>Schedule:</b><br><label id="lbl_schedule_move_to"></label>
						</fieldset>',
					),
				),
				3 => 
				array (
					0 => 
					array (
						'hideLabel' => true,
					),
					1 => 
					array (
						'name' => 'move_to_class_date',
						'label' => 'LBL_MOVE_TO_CLASS_DATE',
					),

				),
				4 => array(
					0 => 
					array (
						'name' => 'last_lesson_date',
						'label' => 'LBL_LAST_LESSON_DATE',
					),
					1 => 
					array (
						'name' => 'move_to_class_date_end',
						'label' => 'LBL_MOVE_TO_CLASS_DATE_END',
						'customCode' => '<input class="date_input input_readonly" autocomplete="off" type="text" name="move_to_class_date_end" id="move_to_class_date_end" value="{$fields.move_to_class_date_end.value}" tabindex="0" size="11" maxlength="10" readonly>',
					),
				),
				5 => 
				array (
					0 => 
					array (
						'hideLabel' => true,
						'customCode' => '
						<fieldset id="credit_info_3" style="width:70%; min-height: 50px; display: block;">
						<legend><b> Hours and amount in old class: </b></legend>
						<table>
						<tbody>
						<tr>
						<td width="25%">Total Hour: </td>
						<td width="12.5%" id="lbl_total_hour_old"></td>
						<td width="25%">Total Amount: </td>
						<td width="25%" id="lbl_payment_amount_old"></td>
						</tr>
						<tr>
						<td>Used Hour: </td>
						<td id="lbl_used_hour_old"></td>
						<td>Used Amount: </td>
						<td id="lbl_used_amount_old"></td>
						</tr>
						<tr>
						<td>Moving Hour: </td>
						<td id="lbl_moving_hour_old" style="font-weight: bold;"></td>
						<td>Moving Amount: </td>
						<td id="lbl_moving_amount_old"></td>
						</tr>
						</tbody>
						</table>
						</fieldset>',
					),
					1 => 
					array (
						'hideLabel' => true,
						'customCode' => '
						<fieldset id="credit_info_4" style="width:70%; min-height: 50px; display: block;">
						<legend><b> Hours and amount in new class: </b></legend>
						<table>
						<tbody>
						<tr><td width="12.5%"></td>
						<td width="25%">Total Class Hour: </td>
						<td width="12.5%" scope="col" style="font-weight: bold;" id="lbl_total_hour_new"></td>
						</tr>
						<tr style="display: none;"><td></td>
						<td>Total Hour Studied: </td>
						<td id="lbl_studied_hour_new"></td>
						</tr>
                        <tr><td></td>
                        <td>Study: </td>
                        <td id="lbl_moving_time" nowrap scope="col" style="font-weight: bold;"></td>
                        </tr>
						<tr><td></td>
						<td>Moving Hour: </td>
						<td id="lbl_remaining_hour_new" scope="col" style="font-weight: bold;"></td>
						</tr>
						</tbody>
						</table>
						</fieldset>',
					),
				),
				6 => 
				array (
					0 =>
					array (
						'name' => 'description',
					),
				),
				7 => 
				array (
					0 => 'assigned_user_name',
				),
			),
		),
	),
);
