<?php
// created: 2015-10-22 09:35:35
//$GLOBALS['sugar_config']['list_max_entries_per_subpanel'] = '1000';
$subpanel_layout['list_fields'] = array (
    //'custom_checkboxxxxx' =>
    //  array (
    //    'width' => '2%',
    //    'module' => 'J_StudentSituations',
    //    'field_value' => 'ID',
    //    'widget_class' => 'SubPanelCheckbox',
    //    'default' => true,
    //  ),
    'no_increasement' =>
    array (
        'type' => 'int',
        'vname' => 'LBL_INCREASEMENT',
        'width' => '3%',
        'default' => true,
        'sortable' => false,
    ),
    'name' =>
    array (
        'type' => 'relate',
        'vname' => 'LBL_NAME_SITUATION',
        'width' => '10%',
        'default' => true,
        'target_module' => NULL,
        'target_record_key' => NULL,
        'sortable' => false,
    ),
    'parent_name' =>
    array (
        'type' => 'varchar',
        'studio' => 'visible',
        'vname' => 'LBL_PARENT',
        'width' => '7%',
        'default' => true,
        'sortable' => false,
    ),
    'phone_situation' =>
    array (
        'type' => 'varchar',
        'studio' => 'visible',
        'vname' => 'LBL_PHONE',
        'width' => '5%',
        'default' => true,
        'sortable' => false,
    ),
    'birthdate_fake' =>
    array (
        'type' => 'date',
        'vname' => 'LBL_BIRTHDATE_FAKE',
        'width' => '8%',
        'default' => true,
        'sortable' => false,
    ),
    'student_type' =>
    array (
        'vname' => 'LBL_STUDENT_TYPE',
        'width' => '6%',
        'default' => true,
        'studio' => 'visible',
        'sortable' => false,
    ),
    'type' =>
    array (
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_TYPE',
        'width' => '4%',
        'default' => false,
        'sortable' => false,
    ),
    'status' =>
    array (
        'type' => 'enum',
        'studio' => 'visible',
        'vname' => 'LBL_STATUS',
        'width' => '8%',
        'default' => true,
        'sortable' => false,
    ),
    'start_study' =>
    array (
        'type' => 'date',
        'vname' => 'LBL_START_STUDY',
        'width' => '8%',
        'default' => true,
        'sortable' => false,
    ),
    'end_study' =>
    array (
        'type' => 'date',
        'vname' => 'LBL_END_STUDY',
        'width' => '8%',
        'default' => true,
        'sortable' => false,
    ),
    'total_hour' =>
    array (
        'type' => 'decimal',
        'studio' => 'visible',
        'vname' => 'LBL_TOTAL_HOUR',
        'width' => '9%',
        'default' => true,
        'sortable' => false,
    ),
    'total_amount' =>
    array (
        'type' => 'decimal',
        'studio' => 'visible',
        'vname' => 'LBL_TOTAL_AMOUNT',
        'width' => '9%',
        'default' => true,
        'sortable' => false,
    ),
    //  'remain_hour' =>
    //  array (
    //    'type' => 'decimal',
    //    'studio' => 'visible',
    //    'vname' => 'LBL_REMAIN_HOUR',
    //    'width' => '9%',
    //    'default' => true,
    //      'sortable' => false,
    //  ),
    //  'remain_amount' =>
    //  array (
    //    'type' => 'decimal',
    //    'studio' => 'visible',
    //    'vname' => 'LBL_REMAIN_AMOUNT',
    //    'width' => '9%',
    //    'default' => true,
    //      'sortable' => false,
    //  ),
    'description' =>
    array (
        'type' => 'text',
        'studio' => 'visible',
        'vname' => 'LBL_DESCRIPTION',
        'width' => '9%',
        'default' => true,
    ),
    'custom_button' =>
    array (
        'type' => 'varchar',
        'studio' => true,
        'vname' => 'Button',
        'width' => '7%',
        'default' => true,
        'widget_class' => 'SubPanelButtonDisplayList',
    ),
    'team_id' =>
    array (
        'name' => 'team_id',
        'usage' => 'query_only',
    ),
    'student_id' =>
    array (
        'name' => 'student_id',
        'usage' => 'query_only',
    ),
//    'parent_name' =>
//    array (
//        'name' => 'parent_name',
//        'usage' => 'query_only',
//    ),
);
//$subpanel_layout['sort_by'] = "name, start_study";
//$subpanel_layout['sort_order'] = "DESC";

if (($GLOBALS['current_user']->team_type == 'Adult')){
    unset($subpanel_layout['list_fields']['total_amount']);
}
