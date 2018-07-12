<?php
    $module_name = 'J_Kindofcourse';
    $viewdefs[$module_name] = 
    array (
        'QuickCreate' => 
        array (
            'templateMeta' => 
            array (
                'maxColumns' => '2',
                'javascript' => '                                      
                {sugar_getscript file="custom/modules/J_Kindofcourse/js/LevelConfig.js"}                     
                {sugar_getscript file="custom/include/javascripts/jquery.multiple.select.js"}
                <link rel="stylesheet" href="{sugar_getjspath file=\'custom/include/javascripts/multiple-select.css\'}"/>
                {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}
                <link rel="stylesheet" href="{sugar_getjspath file=\'custom/include/javascripts/Select2/select2.css\'}"/>',
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
                            'name' => 'kind_of_course',
                            'studio' => 'visible',
                            'label' => 'LBL_KIND_OF_COURSE',
                        ),
                        1 => 'name',
                    ),
                    1 => 
                    array (
                        0 => 
                        array (
                            'name' => 'status',
                            'studio' => 'visible',
                            'label' => 'LBL_STATUS',
                        ),
                        1 => '',
                    ),
                    2 => 
                    array (
                        0 => array(
                            'label' =>'LBL_LEVEL_CONFIG' ,
                            'customCode' => '{include file = "custom/modules/J_Kindofcourse/tpls/LevelConfig.tpl"}'),
                        1 => '',
                    ),
                    3 => 
                    array (
                        0 => 'assigned_user_name',
                        1 => '',
                    ),
                    4 => 
                    array (
                        0 => 'description',
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
