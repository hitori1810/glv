<?php
$module_name = 'J_Kindofcourse';
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
                    0 =>
                    array (
                        'name' => 'kind_of_course',
                        'studio' => 'visible',
                        'label' => 'LBL_KIND_OF_COURSE',
                        'customCode' => '{if $team_type == "Adult"}
                        {$fields.kind_of_course_adult.value}
                        {else}
                        {$fields.kind_of_course.value}
                        {/if}'
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
                    1 => 'short_course_name',
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
