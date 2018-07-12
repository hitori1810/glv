<?php
    $layout_defs["C_Rooms"]["subpanel_setup"]["rooms_meetings"] = array (
        'order' => 100,
        'module' => 'Meetings',
        'subpanel_name' => 'default',
        'title_key' => 'LBL_MEETING',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'get_subpanel_data' => 'meetings',
        'top_buttons' => 
        array (
            0 => 
            array (
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
            1 => 
            array (
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
        ),
    );
?>
