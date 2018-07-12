<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2014-07-15 14:47:51
$layout_defs["C_Rooms"]["subpanel_setup"]['c_classes_c_rooms_1'] = array (
  'order' => 100,
  'module' => 'C_Classes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CLASSES_C_ROOMS_1_FROM_C_CLASSES_TITLE',
  'get_subpanel_data' => 'c_classes_c_rooms_1',
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