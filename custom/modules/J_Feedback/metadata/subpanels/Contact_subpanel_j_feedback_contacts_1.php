<?php
// created: 2015-07-08 16:00:27
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'vname' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => NULL,
    'target_record_key' => NULL,
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
    'default' => true,
  ),
  'priority' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_PRIORITY',
    'width' => '10%',
    'default' => true,
  ),
  'feedback' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_FEEDBACK',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'received_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_RECEIVED_DATE ',
    'width' => '10%',
    'default' => true,
  ),
  'resolved_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_RESOLVED_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'J_Feedback',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'J_Feedback',
    'width' => '5%',
    'default' => true,
  ),
);