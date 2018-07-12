<?php
$module_name = 'J_Feedback';
$viewdefs[$module_name] =
array (
    'QuickCreate' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/J_Feedback/js/custom.edit.view.js"}',
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
                'LBL_EDITVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL2' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' =>
        array (
            'lbl_editview_panel1' =>
            array (
                0 =>
                array (
                    0 => array(
                        'label'=> 'LBL_NAME',
                        'name' => 'name',
                        'displayParams' =>
                        array (
                            'size' => '35',
                        ),
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
                    0 => array(
                        'label'=> 'LBL_TYPE',
                        'customCode' => '
                        {html_options name="type_feedback_list" id="type_feedback_list" options=$fields.type_feedback_list.options selected=$fields.type_feedback_list.value}
                        {html_options options="$relate_feedback_list" style="width: 180px;margin-left: 10px;" name="relate_feedback_list" id="relate_feedback_list" selected=$fields.relate_feedback_list.value}
                        ',
                    ),
                    1 =>
                    array (
                        'name' => 'priority',
                        'studio' => 'visible',
                        'label' => 'LBL_PRIORITY',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'contacts_j_feedback_1_name',
                        'label' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
                        'displayParams' =>
                            array (
                                'class' => 'sqsNoAutofill',
                                'initial_filter' => '&class_id_j_feedback_advanced={$fields.j_class_j_feedback_1j_class_ida.value}',
                            ),
                    ),
                    1 =>
                    array (
                        'name' => 'received_date',
                        'label' => 'LBL_RECEIVED_DATE ',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'j_class_j_feedback_1_name',
                        'label' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
                    ),
                    1 =>
                    array (
                        'name' => 'resolved_date',
                        'label' => 'LBL_RESOLVED_DATE',
                    ),
                ),
                4 =>
                array (
                    0 => 'feedback_time',
                    1 =>
                    array (
                        'name' => 'ec_action',
                        'label' => 'LBL_EC_ACTION',
                    ),
                ),
                5 =>
                array (
                    0 => 'description',
                    1 =>
                    array (
                        'name' => 'efl_action',
                        'label' => 'LBL_EFL_ACTION',
                    ),
                ),
                6 =>
                array (
                    0=>array(
                        'customLabel'=> '<b>History Response</b>',
                        'customCode' => '<slot><div id="text_feedback" style="background-color: #ffffff;padding: 5px"><pre>{$fields.feedback.value|escape:"htmlentitydecode"}</pre></div></slot>
                        ',
                    ),
                ),
                7 =>
                array (
                    0 =>
                    array (
                        'name' => 'feedback',
                        'label' => 'LBL_FEEDBACK',
                        'customCode' => '<textarea id="feedback" name="feedback" rows="4" cols="40" title="" tabindex="0" db-data=""></textarea>'
                    ),
                ),
                8 =>
                array (
                    0 =>
                    array (
                        'name' => 'receiver',
                        'label' => 'LBL_RECEIVER',
                    ),
                ),
            ),
            'lbl_editview_panel2' =>
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
                                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'users_j_feedback_1_name',
                        'label' => 'CC User 1:',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'users_j_feedback_2_name',
                        'label' => 'CC User 2:',
                    ),
                ),
            ),
        ),
    ),
);
