<?php
// created: 2015-08-03 14:47:24
$subpanel_layout['list_fields'] = array (
  'producttemplates_j_inventorydetail_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'vname' => 'LBL_PRODUCTTEMPLATES_J_INVENTORYDETAIL_1_FROM_PRODUCTTEMPLATES_TITLE',
    'id' => 'PRODUCTTEMPLATES_J_INVENTORYDETAIL_1PRODUCTTEMPLATES_IDA',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'ProductTemplates',
    'target_record_key' => 'producttemplates_j_inventorydetail_1producttemplates_ida',
  ),
  'quantity' => 
  array (
    'type' => 'int',
    'vname' => 'LBL_QUANTITY',
    'width' => '10%',
    'default' => true,
  ),
  'price' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_PRICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'sortable' => false,
  ),
  'amount' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_AMOUNT',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
    'sortable' => false,
  ),
  'remark' => 
  array (
    'type' => 'text',
    'vname' => 'LBL_REMARK',
    'sortable' => false,
    'width' => '10%',
    'default' => true,
  ),
  'date_modified' => 
  array (
    'vname' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'link' => true,
    'type' => 'relate',
    'vname' => 'LBL_ASSIGNED_TO_NAME',
    'id' => 'ASSIGNED_USER_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Users',
    'target_record_key' => 'assigned_user_id',
  ),
  'team_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'studio' => 
    array (
      'portallistview' => false,
      'portaldetailview' => false,
      'portaleditview' => false,
    ),
    'vname' => 'LBL_TEAMS',
    'id' => 'TEAM_ID',
    'width' => '10%',
    'default' => true,
    'widget_class' => 'SubPanelDetailViewLink',
    'target_module' => 'Teams',
    'target_record_key' => 'team_id',
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'usage' => 'query_only',
  ),
);