<?php
$module_name = 'C_KeyboardSetting';
$viewdefs[$module_name] = 
array (
  'DetailView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'buttons' => 
        array (
          0 => 'EDIT',
          1 => 'DUPLICATE',
          2 => 'DELETE',
          3 => 'FIND_DUPLICATES',
        ),
      ),
      'maxColumns' => '2',
      'widths' => 
      array (
        0 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
        1 => 
        array (
          'label' => '10',
          'field' => '30',
        ),
      ),
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'default' => 
      array (
        0 => 
        array (
          0 => 'name',
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'target_module',
            'label' => 'LBL_TARGET_MODULE',
          ),
          1 => 
          array (
            'name' => 'is_active',
            'label' => 'LBL_IS_ACTIVE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'target_fields',
            'label' => 'LBL_TARGET_FIELDS',
            'customCode' => '{$TARGET_FIELD_TABLE}',
          ),
        ),
        3 => 
        array (
          0 => 'team_name',
          1 => '',
        ),
        4 => 
        array (
          0 => 'description',
          1 => 'assigned_user_name',
        ),
      ),
    ),
  ),
);
