<?php
// created: 2014-08-16 21:57:21
$subpanel_layout['list_fields'] = array (
'checkbox' => 
  array (
    'type' => 'varchar',
    'studio' => true,
    'vname' => 'LBL_CHECKBOX',
    'width' => '3%',
    'default' => true,
    'sortable' => false,
    'widget_class' => 'SubPanelCheckAllTeacher',
  ),
  'teacher_id' => 
  array (
    'type' => 'varchar',
    'vname' => 'LBL_TEACHER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_NAME',
    'sort_by' => 'last_name',
    'sort_order' => 'asc',
    'widget_class' => 'SubPanelDetailViewLink',
    'module' => 'Contacts',
    'width' => '20%',
    'default' => true,
  ),
    'phone_mobile' => 
  array (
    'name' => 'phone_mobile',
    'vname' => 'LBL_MOBILE_PHONE',
    'width' => '20%',
    'default' => true,
  ),
  'email1' => 
  array (
    'name' => 'email1',
    'vname' => 'LBL_LIST_EMAIL',
    'widget_class' => 'SubPanelEmailLink',
    'width' => '20%',
    'sortable' => false,
    'default' => true,
  ),
  'team_name' => 
  array (
    'type' => 'relate',
    'default' => true,
    'studio' => 'visible',
    'vname' => 'LBL_TEAM',
    'width' => '10%',
  ),
  'first_name' => 
  array (
    'name' => 'first_name',
    'usage' => 'query_only',
  ),
  'last_name' => 
  array (
    'name' => 'last_name',
    'usage' => 'query_only',
  ),
  'salutation' => 
  array (
    'name' => 'salutation',
    'usage' => 'query_only',
  ),
  'edit_button'=>array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
             'module' => 'Contacts',
            'width' => '5%',
        ),
);