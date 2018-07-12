<?php
$layout_defs["J_Class"]["subpanel_setup"]["j_class_meetings"] = array (
    'order' => 30,
    'module' => 'Meetings',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_SUPPANEL_SESSION',
    'sort_order' => 'asc',
    'sort_by' => 'date_start',
    'get_subpanel_data' => 'ju_meetings',
    'top_buttons' =>
    array (    
        1 =>
        array (
            'widget_class' => 'SubPanelClass',
        ),
    ),
);
$layout_defs["J_Class"]["subpanel_setup"]["j_class_meetings_syllabus"] = array (
    'order' => 40,
    'module' => 'Meetings',
    'subpanel_name' => 'J_Class_subpanel_j_class_meetings_syllabus',
    'title_key' => 'LBL_SUPPANEL_SYLLABUS',
    'sort_order' => 'asc',
    'sort_by' => 'date_start',
    'get_subpanel_data' => 'ju_meetings',
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPabelSyllabus',
        ),
    ),
);
$layout_defs["J_Class"]["subpanel_setup"]["j_class_studentsituations"] = array (
    'order' => 20,
    'module' => 'J_StudentSituations',
    'subpanel_name' => 'default',                    
    'title_key' => 'LBL_SUPPANEL_ENROLLMENT_INFO',
    'sort_order' => 'desc',
    'sort_by' => 'student_id, start_study',
    'get_subpanel_data' => 'function:getSubSituation',
    'function_parameters' => array(
        'import_function_file' => 'custom/modules/J_Class/customSubPanel.php',
        'class_id' => $this->_focus->id,
        'return_as_array' => 'true'
    ),
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPanelDateSituation',
        ),

    ),
);
?>
