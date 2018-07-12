<?php
$module_name = 'C_DuplicationDetection';
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
      'javascript' => '{sugar_getscript file="custom/modules/C_DuplicationDetection/js/DetailView.js"}',
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
          1 => 
          array (
            'name' => 'is_active',
            'label' => 'LBL_IS_ACTIVE',
          ),
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
            'name' => 'preventive_type',
            'label' => 'LBL_PREVENTIVE_TYPE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'target_fields',
            'label' => 'LBL_TARGET_FIELDS',
            'customCode' => '<div>{$fields.target_fields.value}</div>',
          ),
        ),
        3 => 
        array (
          0 => 'team_name',
          1 => 'assigned_user_name',
        ),
        4 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
