<?php
// created: 2014-09-03 17:39:42
$subpanel_layout['list_fields'] = array (
  'name' => 
  array (
    'name' => 'name',
    'vname' => 'LBL_LIST_OPPORTUNITY_NAME',
    'widget_class' => 'SubPanelDetailViewLink',
    'width' => '25%',
    'default' => true,
  ),
  'sales_stage' => 
  array (
    'name' => 'sales_stage',
    'vname' => 'LBL_LIST_SALES_STAGE',
    'width' => '20%',
    'default' => true,
  ),
  'date_closed' => 
  array (
    'name' => 'date_closed',
    'vname' => 'LBL_DATE_CLOSED',
    'width' => '15%',
    'default' => true,
  ),
  'total_in_invoice' => 
  array (
    'type' => 'currency',
    'vname' => 'LBL_TOTAL_IN_INVOICE',
    'currency_format' => true,
    'width' => '20%',
    'default' => true,
    'sortable' => false,
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'vname' => 'LBL_DATE_ENTERED',
    'width' => '20%',
    'default' => true,
  ),
  'currency_id' => 
  array (
    'name' => 'currency_id',
    'usage' => 'query_only',
  ),
);