<?php
// created: 2016-07-28 10:59:47
$subpanel_layout['list_fields'] = array (
  'j_class_relate_field' =>
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'vname' => 'LBL_J_CLASS_RELATE_FIELD',
    'id' => 'J_CLASS_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'J_Class',
    'target_record_key' => 'j_class_id',
  ),
  'teacher_name' =>
  array (
    'type' => 'relate',
    'studio' => 'visible',
    'vname' => 'LBL_TEACHER_NAME',
    'id' => 'TEACHER_ID',
    'link' => true,
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'C_Teachers',
    'target_record_key' => 'teacher_id',
  ),
  'gradebook_name' =>
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_GRADEBOOK_NAME',
    'id' => 'GRADEBOOK_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'J_Gradebook',
    'target_record_key' => 'gradebook_id',
  ),
  'date_input' =>
  array (
    'type' => 'date',
    'vname' => 'LBL_DATE_INPUT',
    'width' => '10%',
    'default' => true,
  ),
  'final_result' =>
  array (
    'type' => 'decimal',
    'vname' => 'LBL_FINAL_RESULT',
    'width' => '10%',
    'default' => true,
  ),
  'certificate_type' =>
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CERTIFICATE_TYPE',
    'sortable' => true,
    'width' => '10%',
    'default' => true,
  ),
  'certificate_level' =>
  array (
    'type' => 'varchar',
    'vname' => 'LBL_CERTIFICATE_LEVEL',
    'sortable' => true,
    'width' => '10%',
    'default' => true,
  ),
  'description' =>
  array (
    'type' => 'text',
    'vname' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
);