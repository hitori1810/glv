<?php
    $module_name = 'C_Contacts';
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
                    'LBL_EDITVIEW_PANEL1' => 
                    array (
                        'newTab' => false,
                        'panelDefault' => 'expanded',
                    ),
                ),
                'syncDetailEditViews' => true,
            ),
            'panels' => 
            array (
                'default' => 
                array (
                    0 => 
                    array (
                        0 => 'name',
                        1 => 
                        array (
                            'name' => 'mobile_phone',
                            'label' => 'LBL_MOBILE_PHONE',
                        ),
                    ),
                    1 => 
                    array (
                        0 => 
                        array (
                            'name' => 'email1',
                            'studio' => 
                            array (
                                'editview' => true,
                                'editField' => true,
                                'searchview' => false,
                                'popupsearch' => false,
                            ),
                            'label' => 'LBL_EMAIL_ADDRESS',
                        ),
                        1 => 
                        array (
                            'name' => 'address',
                            'studio' => 'visible',
                            'label' => 'LBL_ADDRESS',
                        ),
                    ),
                    2 => 
                    array (
                        0 => 'description',
                    ),
                ),
                'lbl_editview_panel1' => 
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
                            'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                            'label' => 'LBL_DATE_ENTERED',
                        ),
                    ),
                ),
            ),
        ),
    );
