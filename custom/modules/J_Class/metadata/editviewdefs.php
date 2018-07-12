<?php
$module_name = 'J_Class';
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
                    1 => '<input type="hidden" name="content" id="content" value="{$fields.content.value}">',
                    2 => '<input type="hidden" name="class_case" id="class_case" value="{$class_case}">',
                    3 => '<input type="hidden" name="root_start_date" id="root_start_date" value="{$fields.start_date.value}">',
                    4 => '<input type="hidden" name="root_end_date" id="root_end_date" value="{$fields.end_date.value}">',
                    5 => '<input type="hidden" name="history" id="history" value="{$fields.history.value}">',
                    6 => '<input type="hidden" name="main_schedule" id="main_schedule" value="{$fields.main_schedule.value}">',

                    7 => '<input type="hidden" name="history_temp" id="history_temp" value="">',
                    8 => '<input type="hidden" name="class_type" id="class_type" value="{$fields.class_type.value}">',
                    9 => '<input type="hidden" name="aims_id" id="aims_id" value="{$fields.aims_id.value}">',
                    10 => '<input type="hidden" name="fetched_row_j_class_j_class_1j_class_ida" id="fetched_row_j_class_j_class_1j_class_ida" value="{$fields.j_class_j_class_1j_class_ida.value}">',
                    //                    13 => '<input type="hidden" name="lesson_final_test" id="lesson_final_test" value="{$fields.lesson_final_test.value}">',
                    11 => '<input type="hidden" name="upgrade_to_id" id="upgrade_to_id" value="{$upgrade_to_id}">',
                    12 => '<input type="hidden" name="upgrade_to_name" id="upgrade_to_name" value="{$upgrade_to_name}">',
                    13 => '<input type="hidden" id="koc_id" name="koc_id" value="{$fields.koc_id.value}">',
                    14 => '<input type="hidden" id="team_type" name="team_type" value="{$team_type}">',
                    15 => '<input type="hidden" id="accept_schedule_change" name="accept_schedule_change" value="0"/>{include file="custom/modules/J_Class/tpls/situationNotify.tpl"}',
					16 => '<input type="hidden" id="short_course_name" name="short_course_name" value="{$fields.short_course_name.value}">',
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
            {if $fields.class_type.value == "Waiting Class"}
            {sugar_getscript file="custom/modules/J_Class/js/handleWaitingClass.js"}
            {else}
            {sugar_getscript file="custom/modules/J_Class/js/editview.js"}
            {/if}
            <script>var defaultTimeDelta = {$defTime};</script>
            {sugar_getscript file="custom/include/javascripts/Timepicker/js/jquery.timepicker.min.js"}
            {sugar_getscript file="custom/include/javascripts/Timepicker/js/datepair.min.js"}
            <link rel=\'stylesheet\' href=\'{sugar_getjspath file="custom/include/javascripts/Timepicker/css/jquery.timepicker.css"}\'/>
            <link rel=\'stylesheet\' href=\'{sugar_getjspath file="custom/modules/J_Class/css/style_nd.css"}\'/>',
            'useTabs' => false,
            'tabDefs' =>
            array (
                'LBL_EDITVIEW_PANEL1' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL3' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL2' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
                'LBL_EDITVIEW_PANEL4' =>
                array (
                    'newTab' => false,
                    'panelDefault' => 'expanded',
                ),
            ),
        ),
        'panels' =>
        array (
            'LBL_EDITVIEW_PANEL1' =>
            array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'class_code',
                        'label' => 'LBL_CLASS_CODE',
                        'customCode' => '<input type="text" class="input_readonly" name="class_code_s" id="class_code" maxlength="255" value="{$fields.class_code.value}" title="{$MOD.LBL_CLASS_CODE}" size="30" {if !$is_admin} readonly {/if}>',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'customCode' => '
                        {if $is_admin}
                        {html_options name="status" id="status" options=$fields.status.options selected=$fields.status.value}
                        {else}
                        <label id="status">{$fields.status.value}</label>
                        {/if}',
                    ),
                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'studio' => 'visible',
                        'label' => 'LBL_NAME',
                        'customCode' => '<input type="text" placeholder="Auto-Generate if blank" name="name_s" id="name" maxlength="255" value="{$fields.name.value}" title="{$MOD.LBL_NAME}" size="30">',
                        'displayParams' =>
                        array (
                            'required' => false,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'class_type',
                        'customCode' => '
                        <label id="class_type_label">
                        {$CLASS_TYPE_LABEL}
                        </label>
                        ',
                        'customLabel' => '{$MOD.LBL_CLASS_TYPE}: <span class="required">*</span>',
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'j_class_j_class_1_name',
                        'displayParams' =>
                        array (
                            'field_to_name_array' =>
                            array (
                                'id' => 'j_class_j_class_1j_class_ida',
                                'name' => 'j_class_j_class_1_name',
                                'content' => 'content',
                                'kind_of_course' => 'kind_of_course',
                                'level' => 'level',
                                'modules' =>  'modules',
                                'isupgrade' =>  'isupgrade',
                            ),
                            'initial_filter' => '&isupgrade_advanced=0',
                            'call_back_function' => 'set_class_return',
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'max_size',
                        'studio' => 'visible',
                        'label' => 'LBL_MAX_SIZE',
                        'customCode' => '{html_options  style="width:24%;" name="max_size" id="apply_for" options=$fields.max_size.options selected=$fields.max_size.value}',
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'kind_of_course',
                        'studio' => 'visible',
                        'label' => 'LBL_KIND_OF_COURSE',
                        'customCode' => '
                        <table width="40%" style="padding:0px!important;">
                        <tbody><tr colspan = "3">
                        <td nowrap width = "1%" >{$htm_koc}</td>
                        <td nowrap width = "1%" scope="col"> <label>{if $team_type == "Junior"}{$MOD.LBL_LEVEL}{else}Stage{/if}: </label><span class="required">*</span></td>
                        <td nowrap width = "1%" >{$dropdown_level}</td>
                        <td nowrap width = "1%" scope="col"> <label>{if $team_type == "Junior"}{$MOD.LBL_MODULE}{else}Level{/if}: </label></td>
                        <td nowrap width = "1%" >{html_options  style="width: 70px;" name="modules" id="modules" options=$fields.modules.options selected=$fields.modules.value}</td>
                        </tr></tbody>
                        </table>',
                    ),
                    1 => 'start_date',
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'hours',
                        'label' => 'LBL_HOURS',
                        'customCode' => '
                        {if $is_admin}
                        <input type="text" class="input_readonly" name="hours" id="hours"  maxlength="5" value="{sugar_number_format var=$fields.hours.value precision=2}" title="{$MOD.LBL_HOURS}" tabindex="0" size="5" {if !$is_admin} readonly {/if}>
                        {else}
                        {if !empty($fields.id.value)}
                        <span class="sugar_field">{sugar_number_format var=$fields.hours.value precision=2}</span><input type="hidden" name="hours" id="hours" value="{sugar_number_format var=$fields.hours.value precision=2}">
                        {else}
                        <input type="text" class="input_readonly" name="hours" id="hours"  maxlength="5" value="{sugar_number_format var=$fields.hours.value precision=2}" title="{$MOD.LBL_HOURS}" tabindex="0" size="5" {if !$is_admin} readonly {/if}>
                        {/if}
                        {/if}
                        ',
                    ),
                    1 =>
                    array (
                        'name' => 'end_date',
                        'customCode' => '<span class="dateTime"><input class="date_input input_readonly" autocomplete="off" type="text" name="end_date" id="end_date" value="{$fields.end_date.value}" tabindex="0" size="11" maxlength="10" readonly></span> {if !empty($fields.end_date.value)}<span style="font-style: italic;">cr:  {$fields.end_date.value}</span>{/if}',
                    ),
                ),
                5 =>
                array (
                    0 => array(
                        'name' => 'change_date_from',
                        'customLabel' =>'<label style="display:none;" id="change_date_from_label">{$MOD.LBL_CHANGE_DATE_FROM} <span class="required">*</span></label>',
                        'customCode' =>'
                        {if $lisence <> "Free" && $lisence <> "Standard"}
                        <input type="button" style="display:none;" name="btn_edit_schedule" class="button primary" id="btn_edit_schedule" value="{$MOD.LBL_CHANGE_SCHEDULE}" />
                        {if $is_lock_date == "1"}
                        <input type="button" style="display:none;" name="btn_edit_schedule" class="button" id="btn_edit_startdate" value="{$MOD.LBL_CHANGE_START_DATE}" />
                        {/if}
                        <span class="dateTime" id="change_date_from_span" style="display:none;">
                        <input class="date_input" autocomplete="off" type="text" name="change_date_from" id="change_date_from" value="" title="" tabindex="0" size="11" maxlength="10">
                        <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="change_date_from_trigger">
                        </span>
                        {literal}
                        <script type="text/javascript">
                        Calendar.setup ({
                        inputField : "change_date_from",
                        ifFormat : cal_date_format,
                        daFormat : cal_date_format,
                        button : "change_date_from_trigger",
                        singleClick : true,
                        dateStr : "",
                        startWeekday: 0,
                        step : 1,
                        weekNumbers:false
                        }
                        );
                        </script>
                        {/literal}

                        <label id="change_date_to_label" width="12.5%" style="background-color:#eee; color: #444; padding:.6em;display:none;">{$MOD.LBL_CHANGE_TO_DATE}:<span class="required">*</span></label>&nbsp;
                        <span class="dateTime" id="change_date_to_span" style="display:none;">
                        <input class="date_input" autocomplete="off" type="text" name="change_date_to" id="change_date_to" value="" title="" tabindex="0" size="11" maxlength="10">
                        <img src="themes/RacerX/images/jscalendar.png" alt="Enter Date" style="position:relative; top:6px" border="0" id="change_date_to_trigger">
                        </span>
                        {literal}
                        <script type="text/javascript">
                        Calendar.setup ({
                        inputField : "change_date_to",
                        ifFormat : cal_date_format,
                        daFormat : cal_date_format,
                        button : "change_date_to_trigger",
                        singleClick : true,
                        dateStr : "",
                        startWeekday: 0,
                        step : 1,
                        weekNumbers:false
                        }
                        );
                        </script>
                        {/literal}
                        {/if}
                        ',
                    ),

                    1 => array(
                        'customLabel' =>'{if !empty($fields.id.value)}{$MOD.LBL_CURRENT_SCHEDULE}{/if}',
                        'customCode' => '{if !empty($fields.id.value)}{$SCHEDULE}{/if}',
                    ),
                ),
                6 =>
                array (
                    0 => array (
                        'name' => 'change_reason',
                        'customLabel' =>'<label id="change_reason_label">{$MOD.LBL_CHANGE_REASON} <span class="required">*</span></label>',
                        'customCode' => '<textarea id="change_reason" name="change_reason" rows="4" cols="60" title="" tabindex="0"></textarea>',
                    ),
                ),
                7 =>
                array (
                    0 =>
                    array(
                        'customLabel' =>'{$MOD.LBL_DAY} <span class="required">*</span>',
                        'customCode' =>'
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Mon" value="Mon">&nbsp;{$MOD.LBL_MON}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Tue" value="Tue">&nbsp;{$MOD.LBL_TUE}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Wed" value="Wed">&nbsp;{$MOD.LBL_WED}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Thu" value="Thu">&nbsp;{$MOD.LBL_THU}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Fri" value="Fri">&nbsp;{$MOD.LBL_FRI}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Sat" value="Sat">&nbsp;{$MOD.LBL_SAT}&nbsp;-</label>
                        <label id="ct_date"><input style="width: 1.2em; height: 1.2em;" type="checkbox" name="week_date" id="Sun" value="Sun">&nbsp;{$MOD.LBL_SUN}&nbsp;</label>
                        <button class="button" type="button" id="btn_clr_time">{$MOD.LBL_BTN_CLEAR}</button>
                        <br><br>
                        <input name="validate_weekday" id="validate_weekday" type="text" style="display:none;"/>',
                    ),
                ),
                8 =>
                array (
                    0 => array(
                        'customLabel' =>'{$MOD.LBL_TIMESLOT}',
                        'customCode' => '{include file="custom/modules/J_Class/tpls/class_schedule.tpl"}',
                    ),

                ),
                9 =>
                array (
                    0 =>array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'rows' => 4,
                            'cols' => 60,
                        ),
                    ),

                ),
            ),
            'LBL_EDITVIEW_PANEL4' => array (
                0 =>
                array (
                    0 =>
                    array (
                        'name' => 'class_code',
                        'label' => 'LBL_CLASS_CODE',
                        'customCode' => '<input type="text" class="input_readonly" name="class_code_s" id="class_code" maxlength="255" value="{$fields.class_code.value}" title="{$MOD.LBL_CLASS_CODE}" size="30" readonly>',
                    ),

                ),
                1 =>
                array (
                    0 =>
                    array (
                        'name' => 'name',
                        'studio' => 'visible',
                        'label' => 'LBL_NAME',
                        'customCode' => '<input type="text" placeholder="{$MOD.LBL_AUTO_GENERATE_IF_BLANK}" name="name_s" id="name" maxlength="255" value="{$fields.name.value}" title="{$MOD.LBL_NAME}" size="30">',
                        'displayParams' =>
                        array (
                            'required' => false,
                        ),
                    ),
                    1 =>
                    array (
                        'name' => 'class_type',    
                        'customCode' => '
                        <label id="class_type_label">
                        {$CLASS_TYPE_LABEL}
                        </label>
                        ',
                    ),

                ),
                2 =>
                array (

                    0 =>
                    array (
                        'name' => 'max_size',
                        'studio' => 'visible',
                        'label' => 'LBL_MAX_SIZE',
                        'customCode' => '{html_options  style="width:24%;" name="max_size" id="apply_for" options=$fields.max_size.options selected=$fields.max_size.value} ',
                    ),
                    1 => array(
                        'name' => 'start_date',
                        'label' => 'LBL_OPEN_DATE'
                    ),
                ),
                3 =>
                array (
                    0 =>
                    array (
                        'name' => 'kind_of_course',
                        'studio' => 'visible',
                        'label' => 'LBL_KIND_OF_COURSE',
                        'customCode' => '
                        <table width="40%" style="padding:0px!important;">
                        <tbody><tr colspan = "3">
                        <td nowrap width = "1%" >{$htm_koc}</td>
                        <td nowrap width = "1%" scope="col"> <label>{$MOD.LBL_LEVEL}: </label><span class="required">*</span></td>
                        <td nowrap width = "1%" >{html_options  style="width: 70px;" name="level" id="level" options=$fields.level.options selected=$fields.level.value}</td>
                        <td nowrap width = "1%" scope="col"> <label>{$MOD.LBL_MODULE}: </label></td>
                        <td nowrap width = "1%" >{html_options  style="width: 70px;" name="modules" id="modules" options=$fields.modules.options selected=$fields.modules.value}</td>
                        </tr></tbody>
                        </table>',
                    ),

                ),
                4 =>
                array (
                    0 => 'description',
                ),
            ),
            'lbl_editview_panel2' =>
            array (
                0 =>
                array (
                    0 => array (
                        'name' => 'assigned_user_name',
                        'displayParams' =>
                        array (
                            'required' => true,
                        ),
                    ),
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
