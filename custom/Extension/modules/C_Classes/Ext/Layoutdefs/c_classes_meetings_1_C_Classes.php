<?php
    $layout_defs["C_Classes"]["subpanel_setup"]["classes_meetings"] = array (
        'order' => 100,
        'module' => 'Meetings',
        'subpanel_name' => 'default',
        'title_key' => 'LBL_MEETING',
        'sort_order' => 'asc',
        'sort_by' => 'date_start',
        'get_subpanel_data' => 'meetings',
        'top_buttons' => 
        array (
            0 => 
            array (
                'widget_class' => 'SubPanelDeleteAllSessionButton',
            ),
        ),
    );
?>
