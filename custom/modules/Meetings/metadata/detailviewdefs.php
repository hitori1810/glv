<?php
    $viewdefs['Meetings'] =
    array (
        'DetailView' =>
        array (
            'templateMeta' =>
            array (
                'form' =>
                array (
                    'buttons' =>
                    array (
                        0 => array ('customCode' => '{$EDIT}',),
                        1 => array ('customCode' => '{$DELETE}',),
                        2 =>
                        array (
                            'customCode' => '{$COVER_BUTTON}',
                        ),
                        3 =>
                        array (
                            'customCode' => '{$MAKE_UP_BUTTON}',
                        ),
                        4 =>
                        array (
                            'customCode' => '{$DELETE_BUTTON}',
                        ),
                    ),
                    'hidden' =>
                    array (
                        0 => '<input type="hidden" name="isSaveAndNew">',
                        1 => '<input type="hidden" name="status">',
                        2 => '<input type="hidden" name="isSaveFromDetailView">',
                        3 => '<input type="hidden" name="isSave">',
                        4 => '<input type="hidden" id="add_outstanding_template" value=\'{$ADD_OUTSTANGDING_TEMPLATE}\'/>',
                        5 => '<div id="diaglog_lead"></div>',
                        6 => '{include file="custom/modules/J_Class/tpls/demo_template.tpl"}',
                    ),
                    'headerTpl' => 'modules/Meetings/tpls/detailHeader.tpl',
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
                {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
                {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.js"}
                {sugar_getscript file="custom/modules/Meetings/js/detailview.js"}
                {sugar_getscript file="custom/modules/Leads/js/addToPT.js"}
                {sugar_getscript file="custom/modules/Contacts/js/handlePTDemoContact.js"}

                <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Select2/select2.css}"/>
                ',
                'useTabs' => false,
                'tabDefs' =>
                array (
                    'LBL_MEETING_INFORMATION' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_PANEL_ASSIGNMENT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_DEMO' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_PT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
            ),
            'panels' =>
            array (
                'LBL_MEETING_INFORMATION' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                            'label' => 'LBL_SUBJECT',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_STATUS',
                            'name' => 'status',
                        ),
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'parent_name',
                            'customLabel' => '{sugar_translate label=\'LBL_MODULE_NAME\' module=$fields.parent_type.value}',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_MEETING_TYPE',
                            'name' => 'meeting_type',
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'label' => 'LBL_DATE_TIME',
                        ),
                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                        ),
                    ),

                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '<div id="result_limit"></div>{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'label' => 'LBL_DURATION',
                        ),
                    ),
                    5 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_SESSION' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                            'label' => 'LBL_SUBJECT',
                        ),
                       /* 1 =>
                        array (
                            'label' => 'LBL_STATUS',
                            'name' => 'status',
                        ),*/
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'ju_class_name',
                            'customCode' => '{$class}',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_MEETING_TYPE',
                            'name' => 'meeting_type',
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'label' => 'LBL_DATE_TIME',
                        ),
                        1 => ''
//                        array (
//                            'name' => 'teacher_name',
//                        ),

                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                        ),
                        1 => ''
//                        array (
//                            'name' => 'room_name',
//                        ),

                    ),
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '<div id="result_limit"></div>{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'label' => 'LBL_DURATION',
                        ),
                        1 => ''

                    ),

                    5 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_OTHER' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                            'label' => 'LBL_SUBJECT',
                        ),
                       /* 1 =>
                        array (
                            'label' => 'LBL_STATUS',
                            'name' => 'status',
                        ),*/
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'class_name',
                            'customCode' => '{$class}',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_MEETING_TYPE',
                            'name' => 'meeting_type',
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'label' => 'LBL_DATE_TIME',
                        ),
                        1 =>  ''

                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                            'label' => 'LBL_DATE_END',
                        ),
                        1 => ''

                    ),
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '<div id="result_limit"></div>{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'label' => 'LBL_DURATION',
                        ),
                        1 => ''
                    ),

                    5 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_DEMO' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                            'label' => 'LBL_SUBJECT',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_MEETING_TYPE',
                            'name' => 'meeting_type',
                        ),
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'label' => 'LBL_DATE_TIME',
                        ),
                        1 => ''

                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                        ),
                        1 => ''

                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '<div id="result_limit"></div>{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'label' => 'LBL_DURATION',
                        ),

                    ),
                    4 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_PT' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                            'label' => 'LBL_SUBJECT',
                        ),
                        1 =>
                        array (
                            'label' => 'LBL_MEETING_TYPE',
                            'name' => 'meeting_type',
                        ),
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'label' => 'LBL_DATE_TIME',
                        ),
                        1 => ''

                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                        ),

                        1 =>  ''

                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '<div id="result_limit"></div>{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'label' => 'LBL_DURATION',
                        ),
                        //1=> 'first_time'
                    ),
                    4 =>
                    array (
                        0 => 'description',
                    ),
                ),
                'LBL_PANEL_ASSIGNMENT' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_ASSIGNED_TO',
                        ),
                        1 =>
                        array (
                            'name' => 'date_modified',
                            'label' => 'LBL_DATE_MODIFIED',
                            'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        ),
                    ),
                    1 =>
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