<?php
$searchdefs['Contracts'] = 
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
      'contract_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CONTRACT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'contract_id',
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
      'contract_id' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_CONTRACT_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'contract_id',
      ),
      'customer_signed_date' => 
      array (
        'type' => 'date',
        'label' => 'LBL_CUSTOMER_SIGNED_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'customer_signed_date',
      ),
      'total_contract_value' => 
      array (
        'type' => 'currency',
        'label' => 'LBL_TOTAL_CONTRACT_VALUE',
        'currency_format' => true,
        'width' => '10%',
        'default' => true,
        'name' => 'total_contract_value',
      ),
      'account_name' => 
      array (
        'name' => 'account_name',
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
        'label' => 'LBL_TEAMS',
        'width' => '10%',
        'default' => true,
        'id' => 'TEAM_ID',
        'name' => 'team_name',
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
