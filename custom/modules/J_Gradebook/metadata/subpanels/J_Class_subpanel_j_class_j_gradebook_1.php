<?php
// created: 2018-06-13 15:53:02
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '25%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'vname' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'date_confirm' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_DATE_CONFIRM',
    'width' => '10%',
    'default' => true,
  ),
  'date_input' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
  ),
  'c_teachers_j_gradebook_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
    'id' => 'C_TEACHERS_J_GRADEBOOK_1C_TEACHERS_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Teachers',
    'target_record_key' => 'c_teachers_j_gradebook_1c_teachers_ida',
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '15%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'J_Gradebook',
    'width' => '4%',
    'default' => true,
  ),
);