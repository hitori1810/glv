<?php
    $viewdefs['Meetings'] = 
    array (
        'QuickCreate' => 
        array (
            'templateMeta' => 
            array (
                'maxColumns' => '2',
                'form' => 
                array (
                    'hidden' => 
                    array (
                        0 => '<input type="hidden" name="isSaveAndNew" value="false">',
                        1 => '<input type="hidden" id="meeting_type" name="meeting_type" value="Meeting">',
                    ),
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
                        'file' => 'custom/modules/Meetings/js/quickedit.js',
                    ),
                ),
                'javascript' => '<script type="text/javascript">{$JSON_CONFIG_JAVASCRIPT}</script>
                {sugar_getscript file="cache/include/javascript/sugar_grp_jsolait.js"}
                <script>toggle_portal_flag();function toggle_portal_flag()  {literal} { {/literal} {$TOGGLE_JS} {literal} } {/literal} </script>',
                'useTabs' => false,
                'tabDefs' => 
                array (
                    'DEFAULT' => 
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_OPTION_DATE_MEETING_MODULE' => 
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
            ),
            'panels' => 
            array (
                'default' => 
                array (
                    0 => 
                    array (
                        0 => 
                        array (
                            'name' => 'name',
                            'displayParams' => 
                            array (
                                'required' => true,
                            ),
                        ),
                        1 => 
                        array (
                            'name' => 'status',
                            'fields' => 
                            array (
                                0 => 
                                array (
                                    'name' => 'status',
                                ),
                            ),
                        ),
                    ),
                    1 => 
                    array (
                        0 => '',//'meeting_type',
                        1 => 'password',
                    ),
                    2 => 
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
                        1 => 
                        array (
                            'name' => 'parent_name',
                            'label' => 'LBL_LIST_RELATED_TO',
                        ),
                    ),
                    3 => 
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
                        1 =>  ''
                    ),
                    4 => 
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
                        ),
                        1 => 
                        array (
                            'name' => 'reminder_time',
                            'customCode' => '{include file="modules/Meetings/tpls/reminders.tpl"}',
                            'label' => 'LBL_REMINDER',
                        ),
                    ),
                    5 => 
                    array (
                        0 => 
                        array (
                            'name' => 'assigned_user_name',
                            'label' => 'LBL_ASSIGNED_TO_NAME',
                        ),
                        1 => 
                        array (
                            'name' => 'team_name',
                        ),
                    ),
                    6 => 
                    array (
                        0 => 
                        array (
                            'name' => 'description',
                            'comment' => 'Full text of the note',
                            'label' => 'LBL_DESCRIPTION',
                        ),
                    ),
                ),
                'lbl_option_date_meeting_module' => 
                array (
                    0 => 
                    array (
                        0 => 
                        array (
                            'name' => 'option_date1',
                            'label' => 'LBL_OPTION_DATE_1',
                        ),
                    ),
                    1 => 
                    array (
                        0 => 
                        array (
                            'name' => 'option_date2',
                            'label' => 'LBL_OPTION_DATE_2',
                        ),
                    ),
                    2 => 
                    array (
                        0 => 
                        array (
                            'name' => 'option_date3',
                            'label' => 'LBL_OPTION_DATE_3',
                        ),
                        1 =>array(
                            'name' => '',
                            'hideLabel' => true,
                        ),
                    ),
                ),
            ),
        ),
    );
