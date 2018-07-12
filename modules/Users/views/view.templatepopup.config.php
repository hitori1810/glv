<?php
    $view_config = array(
        'actions' => array(
            'templatepopup' => array(
                'show_header' => false,
                'show_subpanels' => false,
                'show_search' => false,
                'show_footer' => false,
                'show_javascript' => true,
            ),
        ),     
        'req_params' => array(
            'show_js' => array(
                'param_value' => true,
                'config' => array(
                    'show_header' => false,
                    'show_footer' => false,
                    'view_print'  => false,
                    'show_title' => false,
                    'show_subpanels' => false,
                    'show_javascript' => true,
                    'show_search' => false,
                )
            ),
            'ajax_load' => array(
                'param_value' => true,
                'config' => array(
                    'show_header' => false,
                    'show_footer' => false,
                    'view_print'  => false,
                    'show_title' => true,
                    'show_subpanels' => false,
                    'show_javascript' => false,
                    'show_search' => true,
                    'json_output' => true,
                )
            ),
        ),
    );