<?php
// created: 2018-06-13 15:51:40
$subpanel_layout['list_fields'] = array (
  'contacts_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_CONTACTS_J_FEEDBACK_1_FROM_CONTACTS_TITLE',
    'id' => 'CONTACTS_J_FEEDBACK_1CONTACTS_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Contacts',
    'target_record_key' => 'contacts_j_feedback_1contacts_ida',
  ),
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '10%',
    'default' => true,
  ),
  'description' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '15%',
    'default' => true,
  ),
  'type_feedback_list' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_TYPE_FEEDBACK_LIST',
    'width' => '10%',
    'default' => true,
  ),
  'relate_feedback_list' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'vname' => 'LBL_RELATE_FEEDBACK_LIST',
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
  'assigned_user_name' => 
  array (
    'name' => 'assigned_user_name',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'target_record_key' => 'assigned_user_id',
    'target_module' => 'Employees',
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