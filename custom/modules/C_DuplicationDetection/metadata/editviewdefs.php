<?php
$module_name = 'C_DuplicationDetection';
$viewdefs[$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
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
      'javascript' => '
        {sugar_getscript file="cache/include/javascript/sugar_grp_yui_widgets.js"}
        {sugar_getscript file="custom/modules/C_DuplicationDetection/js/EditView.js"}
      ',
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
            'customCode' => '{include file="custom/modules/C_DuplicationDetection/tpls/target_fields.tpl"}',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'team_name',
            'displayParams' => 
            array (
              'display' => true,
            ),
          ),
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
