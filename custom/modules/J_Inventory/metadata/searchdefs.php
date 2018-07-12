<?php
$module_name = 'J_Inventory';
$searchdefs[$module_name] = 
array (
  'layout' => 
  array (
    'basic_search' => 
    array (
      'type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'name' => 'type',
      ),
      'from_inventory_list' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_FROM_INVENTORY_LIST',
        'width' => '10%',
        'name' => 'from_inventory_list',
      ),
      'to_inventory_list' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TO_INVENTORY_LIST',
        'width' => '10%',
        'name' => 'to_inventory_list',
      ),
      'current_user_only' => 
      array (
        'name' => 'current_user_only',
        'label' => 'LBL_CURRENT_USER_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
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
      'type' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TYPE',
        'width' => '10%',
        'name' => 'type',
      ),
      'from_inventory_list' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_FROM_INVENTORY_LIST',
        'width' => '10%',
        'name' => 'from_inventory_list',
      ),
      'from_supplier_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_FROM_SUPPLIER_NAME',
        'id' => 'FROM_SUPPLIER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'from_supplier_name',
      ),
      'from_team_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_FROM_TEAM_NAME',
        'id' => 'FROM_TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'from_team_name',
      ),
      'to_inventory_list' => 
      array (
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_TO_INVENTORY_LIST',
        'width' => '10%',
        'name' => 'to_inventory_list',
      ),
      'to_teacher_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_TO_TEACHER_NAME',
        'id' => 'TO_TEACHER_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'to_teacher_name',
      ),
      'to_corp_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_TO_CORP_NAME',
        'id' => 'TO_CORP_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'to_corp_name',
      ),
      'to_team_name' => 
      array (
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_TO_TEAM_NAME',
        'id' => 'TO_TEAM_ID',
        'width' => '10%',
        'default' => true,
        'name' => 'to_team_name',
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
        'default' => true,
        'width' => '10%',
      ),
      'favorites_only' => 
      array (
        'name' => 'favorites_only',
        'label' => 'LBL_FAVORITES_FILTER',
        'type' => 'bool',
        'default' => true,
        'width' => '10%',
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
