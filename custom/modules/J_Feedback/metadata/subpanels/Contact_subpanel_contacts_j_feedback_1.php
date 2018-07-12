
<?php
// created: 2015-08-20 17:25:09
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
      'j_class_j_feedback_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_J_CLASS_J_FEEDBACK_1_FROM_J_CLASS_TITLE',
    'id' => 'J_CLASS_J_FEEDBACK_1J_CLASS_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'J_Class',
    'target_record_key' => 'j_class_j_feedback_1j_class_ida',
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