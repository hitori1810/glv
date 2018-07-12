<?php
$module_name = 'J_Class';
$viewdefs[$module_name] =
array (
    'DetailView' =>
    array (
        'templateMeta' =>
        array (
            'form' =>
            array (
                'hidden' =>
                array (
                    1 => '
                    {if $fields.class_type.value == "Waiting Class"}
                    {include file="custom/modules/J_Class/tpls/delayClassWaiting.tpl"}
                    {else}
                    {include file="custom/modules/J_Class/tpls/oustanding_template.tpl"}
                    {include file="custom/modules/J_Class/tpls/delayClass.tpl"}
                    {/if}
                    {include file="custom/modules/J_Class/tpls/export_attendance_dialog.tpl"}
                    {include file="custom/modules/J_Class/tpls/teacher_schedule_screen.tpl"}
                    {include file="custom/modules/J_Class/tpls/closeClass.tpl"}
                    {include file="custom/modules/J_Class/tpls/situationNotify.tpl"}
                    ',
                    2 => '<input type="hidden" name="json_sessions" id="json_sessions" value={$json_ss}>',
                    3 => '<input type="hidden" name="next_session_date" id="next_session_date" value={$next_session_date}>',
                    4 => '<input type="hidden" name="current_status" id="current_status" value={$fields.status.value}>',
                    5 => '<input type="hidden" name="kind_of_course" id="kind_of_course" value={$fields.kind_of_course.value}>',
                    6 => '{include file="custom/modules/J_Class/tpls/demo_template.tpl"}',
                    7 => '<input type="hidden" id="accept_schedule_change" name="accept_schedule_change" value="0"/>',
                ),
                'buttons' =>
                array (
                    0 => 'EDIT',
                    1 => 'DELETE',
                    2 =>
                    array (
                        'customCode' => '{
                        ',
                    ),   
                    5 =>
                    array (
                        'customCode' => '
                        {if $fields.class_type.value == "Normal Class"}
                        <input type="button" class="button" id="send_SMS" name="send_SMS" value="{$MOD.BTN_TOP_ATTENDANCE}"/>
                        {/if}',
                    ),
                    6 =>
                    array (
                        'customCode' => '
                        {$BTN_EXPORT}
                        {include file="custom/modules/J_Class/tpls/viewExport.tpl"}
                        ',
                    ),
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
            {sugar_getscript file="custom/modules/J_Class/js/detailview.js"}
            {sugar_getscript file="custom/modules/Administration/smsPhone/javascript/jquery.jmpopups-0.5.1.js"}   
            {sugar_getscript file="custom/modules/Administration/smsPhone/javascript/smsPhone.js"} 
            {sugar_getscript file="custom/modules/J_Class/js/handleOutStanding.js"}
            {sugar_getscript file="custom/modules/J_Class/js/handleDemoClass.js"}
            {sugar_getscript file="custom/modules/J_Class/js/handleSchedules.js"}
            {sugar_getscript file="custom/include/javascripts/CustomDatePicker.js"}
            {sugar_getscript file="custom/include/javascripts/Timepicker/js/jquery.timepicker.min.js"}
            {sugar_getscript file="custom/include/javascripts/Timepicker/js/datepair.min.js"}
            {sugar_getscript file="custom/modules/J_Class/js/handleCancels.js"}
            {sugar_getscript file="custom/modules/J_Class/js/handleDelay.js"}
                      
            {if $fields.class_type.value == "Waiting Class"}
            {sugar_getscript file="custom/modules/J_Class/js/handleDelayWaiting.js"}
            {sugar_getscript file="custom/modules/J_Class/js/handleWaitingClass.js"}
            {sugar_getscript file="custom/include/javascripts/bootstrap-number-input.js"}
            {else}
            
            {include file="custom/modules/J_Class/tpls/session_cancel.tpl"}
                                                                                  
            {/if}
            <link rel=\'stylesheet\' href=\'{sugar_getjspath file="custom/include/javascripts/Timepicker/css/jquery.timepicker.css"}\'/>
            <link rel=\'stylesheet\' href=\'{sugar_getjspath file="custom/modules/Administration/smsPhone/style/smsPhone.css"}\'/>
            ',
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
                    0 =>
                    array (
                        'name' => 'class_code',
                    ),
                    1 =>
                    array (
                        'name' => 'status',
                        'customCode' => '
                        <span class="{$STATUS_CSS}"><b>{$STATUS}<b></span>
                        
                        &nbsp;&nbsp;
                        {if $fields.class_type.value == "Normal Class"}
                        {$BTN_SUBMIT_IN_PROGRESS}
                        {/if}
                        &nbsp;&nbsp;
                        {if $fields.status.value != "Closed" && $fields.status.value == "In Progress"}
                        <input type="button" class="button" id="btn_submit_close" name="btn_submit_close" value="{$MOD.BTN_CLOSE}" />
                        {/if}
                        '
                    ),
                ),
                1 =>
                array (
                    0 => 'name',
                    1 =>
                    array (
                        'name' => 'class_type',       
                        'label' => 'LBL_CLASS_TYPE',  
                    ),
                ),
                2 =>
                array (
                    0 =>
                    array (
                        'name' => 'j_class_j_class_1_name',
                        'customCode' => '
                        <a href="index.php?module=J_Class&action=DetailView&record={$fields.j_class_j_class_1j_class_ida.value}">
                        <span id="j_class_j_class_1j_class_ida" class="sugar_field" data-id-value="{$fields.j_class_j_class_1j_class_ida.value}">{$fields.j_class_j_class_1_name.value}</span></a>
                        &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; {$MOD.LBL_UPGRADE_TO}: {$UTC}
                        
                        {if $fields.class_type.value == "Normal Class" && $fields.status.value == "Closed"}
                        {$UPGRADE_BUTTON}{/if}
                        '
                    ),
                    1 =>
                    array (
                        'name' => 'start_date',
                        'label' => 'LBL_START_DATE',
                    ),
                ),
                3 =>
                array (                    
                    0 =>
                    array (
                        'name' => 'max_size',
                        'studio' => 'visible',
                        'label' => 'LBL_MAX_SIZE',
                    ),

                    1 =>
                    array (
                        'name' => 'end_date',
                        'label' => 'LBL_END_DATE',
                    ),
                ),
                4 =>
                array (
                    0 =>
                    array (
                        'name' => 'kind_of_course',
                        'studio' => 'visible',
                        'label' => 'LBL_KIND_OF_COURSE',
                        'customCode' => '{$KOC}',
                    ),
                    1 =>
                    array (
                        'name' => 'lesson_test_1',
                        'customLabel' => ' {if !empty($LESSON_TEST_1)} {$MOD.LBL_LESSON_TEST_1}: {/if}',
                        'customCode' => '{if !empty($LESSON_TEST_1)} {$LESSON_TEST_1} {/if}',
                    ),
                ),
                5 =>
                array (
                    0 =>
                    array (
                        'name' => 'hours',
                        'label' => 'LBL_HOURS',
                    ),
                    1 =>
                    array (
                        'name' => 'lesson_test_2',
                        'customLabel' => ' {if !empty($LESSON_TEST_2)} {$MOD.LBL_LESSON_TEST_2}: {/if}',
                        'customCode' => '{if !empty($LESSON_TEST_2)} {$LESSON_TEST_2} {/if}',
                    ),
                ),
                6 =>
                array (
                    0 =>
                    array (
                        'name' => 'main_schedule',
                        'label' => 'LBL_MAIN_SCHEDULE',
                        'customCode' => '{$SCHEDULE}',
                    ),
                    1 =>
                    array (
                        'name' => 'lesson_final_test',
                        'customLabel' => ' {if !empty($LESSON_FINAL_TEST)} {$MOD.LBL_LESSON_FINAL_TEST}: {/if}',
                        'customCode' => '{if !empty($LESSON_FINAL_TEST)} {$LESSON_FINAL_TEST} {/if}',
                    ),
                ),
                7 =>
                array (
                    0 => 'description',
                ),
            ),
            'lbl_detailview_panel2' =>
            array (
                0 =>
                array (
                    0 => 'assigned_user_name',
                    1 =>
                    array (
                        'name' => 'date_modified',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                        'label' => 'LBL_DATE_MODIFIED',
                    ),
                ),
                1 =>
                array (
                    0 => 'team_name',
                    1 =>
                    array (
                        'name' => 'date_entered',
                        'customCode' => '
                        {$fields.date_entered.value} {$APP.LBL_BY}
                        {$fields.created_by_name.value}
                        ',
                        'label' => 'LBL_DATE_ENTERED',
                    ),
                ),     
            ),
        ),
    ),
);
