<?php
$module_name = 'J_School';
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
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'width' => '10%',
        'default' => true,
      ),
    ),
    'advanced_search' => 
    array (
      'name' => 
      array (
        'type' => 'name',
        'link' => true,
        'default' => true,
        'width' => '10%',
        'label' => 'LBL_NAME',
        'name' => 'name',
      ),
      'level' => 
      array (
        'type' => 'enum',
        'studio' => 'visible',
        'label' => 'LBL_LEVEL',
        'width' => '10%',
        'default' => true,
        'name' => 'level',
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
        'default' => true,
      ),
      'address_address_street' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ADDRESS_STREET',
        'width' => '10%',
        'default' => true,
        'name' => 'address_address_street',
      ),
      'address_address_state' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ADDRESS_STATE',
        'width' => '10%',
        'default' => true,
        'name' => 'address_address_state',
      ),
      'address_address_city' => 
      array (
        'type' => 'varchar',
        'label' => 'LBL_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'address_address_city',
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
