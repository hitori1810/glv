<?php
// created: 2015-07-30 11:08:49
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'vname' => 'LBL_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '20%',
    'default' => true,
  ),
  'contract_type' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_CONTRACT_TYPE',
    'width' => '10%',
  ),
  'contract_date' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_CONTRACT_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'contract_until' => 
  array (
    'type' => 'date',
    'vname' => 'LBL_CONTRACT_UNTIL',
    'width' => '10%',
    'default' => true,
  ),
  'day_off' => 
  array (
    'type' => 'multienum',
    'studio' => 'visible',
    'vname' => 'LBL_DAY_OFF',
    'width' => '10%',
    'default' => true,
  ),
  'status' => 
  array (
    'type' => 'enum',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_STATUS',
    'width' => '10%',
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'edit_button' => 
  array (
    'vname' => 'LBL_EDIT_BUTTON',
    'widget_class' => 'SubPanelEditButton',
    'module' => 'J_Teachercontract',
    'width' => '4%',
    'default' => true,
  ),
  'remove_button' => 
  array (
    'vname' => 'LBL_REMOVE',
    'widget_class' => 'SubPanelRemoveButton',
    'module' => 'J_Teachercontract',
    'width' => '5%',
    'default' => true,
  ),
);