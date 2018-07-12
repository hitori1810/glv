<?php
$module_name = 'J_ConfigInvoiceNo';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
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
        'label' => 'LBL_TEAM',
        'id' => 'TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'team_name',
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'name' => 'name',
        'default' => true,
        'width' => '10%',
      ),
      'serial_no' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_SERIAL_NO',
        'width' => '10%',
        'default' => true,
        'name' => 'serial_no',
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
        'width' => '10%',
        'default' => true,
        'id' => 'TEAM_ID',
        'name' => 'team_name',
      ),
      'invoice_no_from' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_INVOICE_NO_FORM',
        'width' => '10%',
        'default' => true,
        'name' => 'invoice_no_from',
      ),
      'invoice_no_to' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_INVOICE_NO_TO',
        'width' => '10%',
        'default' => true,
        'name' => 'invoice_no_to',
      ),
      'invoice_no_current' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_INVOICE_NO_CURRENT',
        'width' => '10%',
        'default' => true,
        'name' => 'invoice_no_current',
      ),
    ),
  ),
  'templateMeta' => 
  array (
    'maxColumns' => '3',
    'maxColumnsBasic' => '4',
    'widths' => 
    array (
      'label' => '10',
      'field' => '30',
    ),
  ),
);
