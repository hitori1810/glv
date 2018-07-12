<?php
$listViewDefs['Opportunities'] = 
array (
  'oder_id' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_ORDER_ID',
    'width' => '10%',
    'default' => true,
  ),
  'name' => 
  array (
    'width' => '30%',
    'label' => 'LBL_LIST_OPPORTUNITY_NAME',
    'link' => true,
    'default' => true,
  ),
  'sales_stage' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_SALES_STAGE',
    'default' => true,
  ),
  'total_in_invoice' => 
  array (
    'type' => 'currency',
    'label' => 'LBL_TOTAL_IN_INVOICE',
    'currency_format' => true,
    'width' => '10%',
    'default' => true,
  ),
  'date_closed' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_DATE_CLOSED',
    'default' => true,
  ),
  'assigned_user_name' => 
  array (
    'width' => '10%',
    'label' => 'LBL_COMMISSIONER',
    'module' => 'Employees',
    'id' => 'ASSIGNED_USER_ID',
    'default' => true,
  ),
  'contact_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CONTACT_NAME',
    'id' => 'CONTACT_ID',
    'width' => '10%',
    'default' => false,
  ),
  'active_since' => 
  array (
    'type' => 'date',
    'label' => 'LBL_ACTIVE_SINCE',
    'width' => '10%',
    'default' => false,
  ),
  'active_until' => 
  array (
    'type' => 'date',
    'label' => 'LBL_ACTIVE_UNTIL',
    'width' => '10%',
    'default' => false,
  ),
  'amount' => 
  array (
    'width' => '10%',
    'label' => 'LBL_LIST_AMOUNT',
    'align' => 'right',
    'default' => false,
    'currency_format' => true,
    'sortable' => false,
  ),
  'c_invoices_opportunities_1_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_C_INVOICES_TITLE',
    'id' => 'C_INVOICES_OPPORTUNITIES_1C_INVOICES_IDA',
    'width' => '10%',
    'default' => false,
  ),
  'discount' => 
  array (
    'type' => 'int',
    'label' => 'LBL_DISCOUNT',
    'width' => '10%',
    'default' => false,
  ),
  'tax_rate' => 
  array (
    'type' => 'float',
    'label' => 'LBL_TAX_RATE',
    'width' => '10%',
    'default' => false,
  ),
  'team_name' => 
  array (
    'width' => '5%',
    'label' => 'LBL_LIST_TEAM',
    'default' => false,
  ),
  'description' => 
  array (
    'type' => 'text',
    'label' => 'LBL_DESCRIPTION',
    'sortable' => false,
    'width' => '10%',
    'default' => false,
  ),
  'date_entered' => 
  array (
    'width' => '10%',
    'label' => 'LBL_DATE_ENTERED',
    'default' => false,
  ),
  'date_modified' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_MODIFIED',
    'width' => '10%',
    'default' => false,
  ),
  'modified_by_name' => 
  array (
    'width' => '5%',
    'label' => 'LBL_MODIFIED',
    'default' => false,
  ),
);
