<?php
    global $mod_strings;
    $viewdefs['Meetings'] =
    array (
        'EditView' =>
        array (
            'templateMeta' =>
            array (
                'maxColumns' => '2',
                'form' =>
                array (
                    'enctype' => 'multipart/form-data',
                    'hidden' =>
                    array (
                        0 => '<input type="hidden" name="isSaveAndNew" value="false">',
                        1 => '<input type="hidden" id="session_status" name="session_status" value="{$SESSION_STATUS}">',
                        2 => '<input type="hidden" id="meeting_type" name="meeting_type" value="{$meeting_type}">',
                    ),
                    'buttons' =>
                    array (
                        0 =>
                        array (
                            'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id ="SAVE_HEADER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.meetings.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if} formSubmitCheck();"type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                        ),
                        1 => 'CANCEL',
                    ),
                    'headerTpl' => 'modules/Meetings/tpls/header.tpl',
                    'buttons_footer' =>
                    array (
                        0 =>
                        array (
                            'customCode' => '<input title="{$APP.LBL_SAVE_BUTTON_TITLE}" id ="SAVE_FOOTER" accessKey="{$APP.LBL_SAVE_BUTTON_KEY}" class="button primary" onclick="SUGAR.meetings.fill_invitees();CAL.fillRepeatData();document.EditView.action.value=\'Save\'; document.EditView.return_action.value=\'DetailView\'; {if isset($smarty.request.isDuplicate) && $smarty.request.isDuplicate eq "true"}document.EditView.return_id.value=\'\'; {/if} formSubmitCheck();"type="button" name="button" value="{$APP.LBL_SAVE_BUTTON_LABEL}">',
                        ),
                        1 => 'CANCEL',
                    ),
                    'footerTpl' => 'modules/Meetings/tpls/footer.tpl',
                ),
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
                'includes' =>
                array (
                    0 =>
                    array (
                        'file' => 'custom/modules/Meetings/js/editview.js',
                    ),
                    1 =>
                    array (
                        'file' => 'custom/include/javascripts/Select2/select2.min.js',
                    ),
                ),
                'javascript' => '<script type="text/javascript">{$JSON_CONFIG_JAVASCRIPT}</script>
                {sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
                <script>toggle_portal_flag();function toggle_portal_flag()  {ldelim} {$TOGGLE_JS} {rdelim}
                function formSubmitCheck(){ldelim}if(check_form(\'EditView\') && CAL.checkRecurrenceForm()){ldelim}document.EditView.submit();{rdelim}{rdelim}</script>',
                'useTabs' => false,
                'tabDefs' =>
                array (
                    'LBL_MEETING_INFORMATION' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_EDITVIEW_PANEL2' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_PANEL_ASSIGNMENT' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_PT_INFORMATION' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_DEMO_INFORMATION' =>
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
            ),
            'panels' =>
            array (
                'LBL_DEMO_INFORMATION' =>
                array (
                    /*0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                        ),
                    ), */
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'type' => 'datetimecombo',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),
                        1 =>  ''

                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                            'type' => 'datetimecombo',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),
                        1 => '',
//                        array (
//                            'name' => 'room_name',
//                            'customCode' => '
//                            {html_options name="room_id" id="room_id" options=$fields.room_id.options selected=$fields.room_id.value}
//                            <input id="find_room" class="button primary" type="button" name="find_room" value="'.$mod_strings["LBL_FIND_ROOM"].'" style="width: 50px;">
//                            ',
//                        ),

                    ),
                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '
                            @@FIELD@@
                            <input id="duration_hours" name="duration_hours" type="hidden" value="{$fields.duration_hours.value}">
                            <input id="duration_minutes" name="duration_minutes" type="hidden" value="{$fields.duration_minutes.value}">
                            {sugar_getscript file="modules/Meetings/duration_dependency.js"}
                            <script type="text/javascript">
                            var date_time_format = "{$CALENDAR_FORMAT}";
                            {literal}
                            SUGAR.util.doWhen(function(){return typeof DurationDependency != "undefined" && typeof document.getElementById("duration") != "undefined"}, function(){
                            var duration_dependency = new DurationDependency("date_start","date_end","duration",date_time_format);
                            initEditView(YAHOO.util.Selector.query(\'select#duration\')[0].form);
                            });
                            {/literal}
                            </script>
                            ',
                            'customCodeReadOnly' => '{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                        ),
                        1 => '',
                    ),
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'description',
                            'comment' => 'Full text of the note',
                            'label' => 'LBL_DESCRIPTION',
                        ),
                    ),
                    /*7=>array(
                        0 =>
                        array (
                            'label' => 'LBL_TYPE',
                            'customCode' => '{html_options name="meeting_type" id="meeting_type" options=$fields.meeting_type.options selected=$fields.meeting_type.value}

                            ',
                        ),
                    ), */
                    5 =>
                    array (
                        0 =>
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_ASSIGNED_TO_NAME',
                        ),
                        1 => 'team_name',
                    ),
                ),
                'LBL_PT_INFORMATION'=>array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'type' => 'datetimecombo',
                            'customLabel' => '{$MOD.LBL_PT_DATE}',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),
                        1 =>  array (
                            'hideLabel' => true,
                        )
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '
                            @@FIELD@@
                            <input id="duration_hours" name="duration_hours" type="hidden" value="{$fields.duration_hours.value}">
                            <input id="duration_minutes" name="duration_minutes" type="hidden" value="{$fields.duration_minutes.value}">
                            {sugar_getscript file="modules/Meetings/duration_dependency.js"}
                            <script type="text/javascript">
                            var date_time_format = "{$CALENDAR_FORMAT}";
                            {literal}
                            SUGAR.util.doWhen(function(){return typeof DurationDependency != "undefined" && typeof document.getElementById("duration") != "undefined"}, function(){
                            var duration_dependency = new DurationDependency("date_start","date_end","duration",date_time_format);
                            initEditView(YAHOO.util.Selector.query(\'select#duration\')[0].form);
                            });
                            {/literal}
                            </script>
                            ',
                            'customCodeReadOnly' => '{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                            'displayParams' =>
                            array (
                                'required' => true,
                            ),
                        ), 
                        1 =>  array (
                            'hideLabel' => true,
                        )
                    ),
                    2 =>
                    array (

                        0 =>
                        array (
                            'name' => 'delivery_hour',
                            'customCode' => '<input tabindex="0" type="text" name="delivery_hour" id="delivery_hour" maxlength="100" size="15" value="{sugar_number_format var=$fields.delivery_hour.value precision=2}">',
                        ),
                        1 =>
                        array (
                            'name' => 'date_end',
                            'type' => 'datetimecombo',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),

                    ),
                   3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'description',
                            'comment' => 'Full text of the note',
                            'label' => 'LBL_DESCRIPTION',
                        ),
                    ),
                   /* 5=>array(
                        0 =>
                        array (
                            'label' => 'LBL_TYPE',
                            'customCode' => '{html_options name="meeting_type" id="meeting_type" options=$fields.meeting_type.options selected=$fields.meeting_type.value}

                            ',
                        ),
                    ), */
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_ASSIGNED_TO_NAME',
                        ),
                        1 => 'team_name',
                    ),
                ),
                'lbl_meeting_information' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'name',
                        ),
                        1 =>
                        array (
                            'name' => 'status',
                            'label' => 'LBL_STATUS',
                        ),
                    ),
                    1 =>
                    array (

                        0 =>
                        array (
                            'name' => 'location',
                            'comment' => 'Meeting location',
                            'label' => 'LBL_LOCATION',
                        ),
                        1 => '',
                        /*array (
                            'label' => 'LBL_TYPE',
                            'customCode' => '
                            {if $fields.meeting_type.value == "Session"}
                            {html_options name="meeting_type" id="meeting_type" options=$fields.meeting_type.options selected=$fields.meeting_type.value}
                            {else}
                            {html_options name="meeting_type" id="meeting_type" options=$fields.meeting_type.options selected=$fields.meeting_type.value}
                            {/if}
                            ',
                        ),*/
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'parent_name',
                            'label' => 'LBL_LIST_RELATED_TO',
                        ),
                    ),

                    3 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_start',
                            'type' => 'datetimecombo',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),
                        1 => ''

                    ),
                    4 =>
                    array (
                        0 =>
                        array (
                            'name' => 'date_end',
                            'type' => 'datetimecombo',
                            'displayParams' =>
                            array (
                                'required' => true,
                                'updateCallback' => 'SugarWidgetScheduler.update_time();',
                            ),
                        ),
                        1 => ''

                    ),
                    5 =>
                    array (

                        0 =>
                        array (
                            'name' => 'duration',
                            'customCode' => '
                            @@FIELD@@
                            <input id="duration_hours" name="duration_hours" type="hidden" value="{$fields.duration_hours.value}">
                            <input id="duration_minutes" name="duration_minutes" type="hidden" value="{$fields.duration_minutes.value}">
                            {sugar_getscript file="modules/Meetings/duration_dependency.js"}
                            <script type="text/javascript">
                            var date_time_format = "{$CALENDAR_FORMAT}";
                            {literal}
                            SUGAR.util.doWhen(function(){return typeof DurationDependency != "undefined" && typeof document.getElementById("duration") != "undefined"}, function(){
                            var duration_dependency = new DurationDependency("date_start","date_end","duration",date_time_format);
                            initEditView(YAHOO.util.Selector.query(\'select#duration\')[0].form);
                            });
                            {/literal}
                            </script>
                            ',
                            'customCodeReadOnly' => '{$fields.duration_hours.value}{$MOD.LBL_HOURS_ABBREV} {$fields.duration_minutes.value}{$MOD.LBL_MINSS_ABBREV} ',
                        ),
                        1 =>
                        array (
                            'name' => 'class_name',
                            'customCode' => '
                            <input type="text" name="class_name" id="class_name" size="30" value="{$fields.class_name.value}" readonly="" style="font-weight: bold; color: rgb(165, 42, 42); background-color: rgb(221, 221, 221);">
                            ',
                        ),
                    ),

                    6 =>
                    array (
                        0 =>
                        array (
                            'label' => 'LBL_APPLY_FOR',
                            'customCode' => '
                            <label>
                            <input name="check_all" type="hidden" value="0">
                            <input name="check_all" id="check_all" type="checkbox" class="checkbox" value="1"> '.$mod_strings["LBL_CHECK_ALL"].'
                            </label>',
                        ),
                    ),
                    7 =>
                    array (
                        0 =>
                        array (
                            'name' => 'description',
                            'comment' => 'Full text of the note',
                            'label' => 'LBL_DESCRIPTION',
                        ),
                    ),
                    8 =>
                    array (
                        0 =>
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_ASSIGNED_TO_NAME',
                        ),
                        1 => 'team_name',
                    ),
                ),
                'lbl_editview_panel2' =>
                array (
                    0 =>
                    array (
                        0 =>
                        array (
                            'name' => 'option_date1',
                            'studio' =>
                            array (
                                'wirelesseditview' => false,
                            ),
                            'label' => 'LBL_OPTION_DATE_1',
                        ),
                    ),
                    1 =>
                    array (
                        0 =>
                        array (
                            'name' => 'option_date2',
                            'studio' =>
                            array (
                                'wirelesseditview' => false,
                            ),
                            'label' => 'LBL_OPTION_DATE_2',
                        ),
                    ),
                    2 =>
                    array (
                        0 =>
                        array (
                            'name' => 'option_date3',
                            'studio' =>
                            array (
                                'wirelesseditview' => false,
                            ),
                            'label' => 'LBL_OPTION_DATE_3',
                        ),
                        1 =>array(
                            'name' => '',
                            'hideLabel' => true,
                        ),
                    ),
                ),
                'LBL_PANEL_ASSIGNMENT' =>
                array (

                ),
            ),
        ),
    );
