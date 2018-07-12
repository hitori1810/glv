<?php
$module_name = 'J_Feedback';
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
                    3 => 'FIND_DUPLICATES',
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
            'useTabs' => false,
            'tabDefs' =>
            array (
                'LBL_DETAILVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_DETAILVIEW_PANEL2' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' =>
        array (
            'lbl_detailview_panel1' =>
            array (
                0 =>
                array (
                    0 => 'name',
                    1 =>
                    array (
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                1 =>
                array (
                    0 => 'type_feedback_list',
                    1 => 'relate_feedback_list'
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'contacts_j_feedback_1_name',
                    ),
                    1 => 'received_date'

                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'j_class_j_feedback_1_name',
                    ),
                    1 =>
                    array (
                        'name' => 'resolved_date',
                        'label' => 'LBL_RESOLVED_DATE',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'feedback_time',
                    ),
                    1 =>
                    array (
                        'name' => 'priority',
                        'studio' => 'visible',
                        'label' => 'LBL_PRIORITY',
                    ),
                ),

                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'description',
                    ),
                    1 =>
                    array (
                        'name' => 'ec_action',
                        'label' => 'LBL_EC_ACTION',
                    ),
                ),
                6 =>
                array (
                    0=>array(
                        'label'=> 'LBL_FEEDBACK',
                        'customCode' => '<slot><div id="text_feedback" style="background-color: #ffffff;padding: 5px"><pre style="white-space: pre-wrap;">{$fields.feedback.value|escape:"htmlentitydecode"}</pre></div></slot>
                        ',
                    ),
                    1 => array (
                        'name' => 'efl_action',
                        'label' => 'LBL_EFL_ACTION',
                    ),
                ),
                7=> array(
                    0 =>
                    array (
                        'name' => 'receiver',
                        'label' => 'LBL_RECEIVER',
                    ),
                )
            ),
            'lbl_detailview_panel2' =>
            array (
                0 =>
                array (
                    0 => 'assigned_user_name',
                    1 => 'team_name',
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
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
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
