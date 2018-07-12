<?php
    $module_name = 'J_GradebookConfig';
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
                    'DEFAULT' => 
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                    'LBL_CONFIGDETAIL' => 
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
                'javascript' => '
                <link rel="stylesheet" href="{sugar_getjspath file=custom/modules/J_GradebookConfig/css/detailview.css}"/>     
                ',
            ),
            'panels' => 
            array (
                'default' => 
                array (
                    0 => 
                    array (
                        0 => 'name',
                        1 => 'team_name',
                    ),
                    1 => 
                    array (
                        0 => 
                        array (
                            'name' => 'koc_name',
                            'label' => 'LBL_KOC_NAME',
                        ),
                        1 => 
                        array (
                            'name' => 'level',
                            'studio' => 'visible',
                            'label' => 'LBL_LEVEL',
                        ),
                    ),
                    2 => 
                    array (
                        0 => 
                        array (
                            'name' => 'weight',
                            'label' => 'LBL_WEIGHT',
                        ),
                        1 => 'assigned_user_name',
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
                'lbl_configdetail' => array(
                    0 => array(
                        0 => array(
                            'name' => 'content',
                            'hideLabel' => true,
                            'customCode' => '{$CONTENT}',
                        )
                    ),
                ),
            ),

        ),
    );
