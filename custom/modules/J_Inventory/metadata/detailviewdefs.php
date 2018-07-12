<?php
    $module_name = 'J_Inventory';
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
                        1 => 'DELETE',
                        2 => array (
                            'customCode' => '{$EXPORT_DETAIL}',
                        ),
                        3 => array (
                            'customCode' => '{$PAYMENT_BUTTON}',
                        ),
                    ),
                ),
                'maxColumns' => '2',
                'javascript' => '        
                {sugar_getscript file="custom/modules/J_Inventory/js/custom.detail.view.js"}',
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
                            'name' => 'name',
                            'customCode' => '{$code_inven}',
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
                        0 => 
                        array (
                            'name' => 'request_no',
                            'label' => 'LBL_REQUEST_NO',
                        ),
                        1 => 
                        array (
                            'name' => 'date_create',
                            'label' => 'LBL_DATE_CREATE',
                        ),
                    ),
                    2 => 
                    array (
                        0 => 
                        array (
                            'name' => 'type',
                            'studio' => 'visible',
                            'label' => 'LBL_TYPE',
                        ),
                    ),
                    3 => 
                    array (
                        0 => array (
                            'label' => 'LBL_FROM',
                            'customCode' => '{$html_from}',
                        ),
                        1 => array (
                            'label' => 'LBL_TO',
                            'customCode' => '{$html_to}',
                        ),
                    ),
                    4 => 
                    array (
                        0 => array (
                            'customCode' => '<div id="detail_inventory">{$html}</div>',
                        ),
                    ),
                    5 => 
                    array (
                        0 => 
                        array (
                            'name' => 'total_quantity',
                            'label' => 'LBL_TOTAL_QUANTITY',
                        ),
                        1 => 
                        array (
                            'name' => 'total_amount',
                            'label' => 'LBL_TOTAL_AMOUNT',
                        ),
                    ),
                ),
                'lbl_detailview_panel2' => 
                array (
                    0 => 
                    array (
                        0 => 'description',
                    ),
                    1 => 
                    array (
                        0 => 'assigned_user_name',
                        1 => 'team_name',
                    ),
                    2 => 
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
