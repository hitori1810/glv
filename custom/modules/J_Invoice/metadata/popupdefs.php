<?php
$popupMeta = array (
    'moduleMain' => 'J_Invoice',
    'varName' => 'J_Invoice',
    'orderBy' => 'j_invoice.name',
    'whereClauses' => array (
  'name' => 'j_invoice.name',
  'invoice_date' => 'j_invoice.invoice_date',
  'serial_no' => 'j_invoice.serial_no',
  'status' => 'j_invoice.status',
  'assigned_user_id' => 'j_invoice.assigned_user_id',
  'date_entered' => 'j_invoice.date_entered',
  'payment_name' => 'j_invoice.payment_name',
  'team_name' => 'j_invoice.team_name',
),
    'searchInputs' => array (
  1 => 'name',
  3 => 'status',
  4 => 'invoice_date',
  5 => 'serial_no',
  6 => 'assigned_user_id',
  7 => 'date_entered',
  8 => 'payment_name',
  9 => 'team_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'name' => 'name',
    'width' => '10%',
  ),
  'invoice_date' => 
  array (
    'type' => 'date',
    'label' => 'LBL_INVOICE_DATE',
    'width' => '10%',
    'name' => 'invoice_date',
  ),
  'serial_no' => 
  array (
    'type' => 'varchar',
    'label' => 'LBL_SERIAL_NO',
    'width' => '10%',
    'name' => 'serial_no',
  ),
  'status' => 
  array (
    'type' => 'enum',
    'studio' => 'visible',
    'label' => 'LBL_STATUS',
    'width' => '10%',
    'name' => 'status',
  ),
  'assigned_user_id' => 
  array (
    'name' => 'assigned_user_id',
    'label' => 'LBL_ASSIGNED_TO',
    'type' => 'enum',
    'function' => 
    array (
      'name' => 'get_user_array',
      'params' => 
      array (
        0 => false,
      ),
    ),
    'width' => '10%',
  ),
  'date_entered' => 
  array (
    'type' => 'datetime',
    'studio' => 
    array (
      'portaleditview' => false,
    ),
    'label' => 'LBL_DATE_ENTERED',
    'width' => '10%',
    'name' => 'date_entered',
  ),
  'payment_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_PAYMENT_NAME',
    'width' => '10%',
    'id' => 'PAYMENT_ID',
    'name' => 'payment_name',
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
    'label' => 'LBL_TEAMS',
    'id' => 'TEAM_ID',
    'width' => '10%',
    'name' => 'team_name',
  ),
),
);
