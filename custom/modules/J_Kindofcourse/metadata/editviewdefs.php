<?php
$module_name = 'J_Kindofcourse';
$viewdefs[$module_name] =
array (
    'EditView' =>
    array (
        'templateMeta' =>
        array (
            'maxColumns' => '2',
            'javascript' => '
            {sugar_getscript file="custom/modules/J_Kindofcourse/js/custom.edit.view.js"}
            ',
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
                    0 =>
                    array (
                        'name' => 'kind_of_course',
                        'studio' => 'visible',
                        'label' => 'LBL_KIND_OF_COURSE',
                        'customCode' => '{if $team_type == "Adult"}
                        {html_options name="kind_of_course_adult" id="kind_of_course" options=$fields.kind_of_course_adult.options selected=$fields.kind_of_course_adult.value}
                        {else}
                        {html_options name="kind_of_course" id="kind_of_course" options=$fields.kind_of_course.options selected=$fields.kind_of_course.value}
                        {/if}
                        '
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
                    0 => 'name',
					1 => array(
                        'name' =>'short_course_name' ,
                        'customCode' => '<input type="text" name="short_course_name" id="short_course_name" value="{$fields.short_course_name.value}" size="5" style="text-transform: uppercase;" maxlength="10">',
                    ),

                ),
                2 =>
                array (
                    0 => array(
                        'label' =>'LBL_LEVEL_CONFIG' ,
                        'customCode' => '{$LEVEL_CONFIG}'),
                ),             
                3 =>
                array (
                    0 => array(
                        'label' =>'LBL_SYLLABUS' ,
                        'customCode' => '{$SYLLABUS_HTML}'),
                ),
                4 =>
                array (
                    0 => 'description',
					1 => 'year',
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
            ),
        ),
    ),
);
