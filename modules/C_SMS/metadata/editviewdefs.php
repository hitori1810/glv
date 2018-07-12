<?php
$module_name = 'C_SMS';
$viewdefs [$module_name] = 
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
      'useTabs' => false,
      'tabDefs' => 
      array (
        'DEFAULT' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
      'syncDetailEditViews' => false,
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
            'name' => 'parent_name',
            'label' => 'LBL_LIST_RELATED_TO',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'template_name',
          ),
          1 => 
          array (
            'name' => 'delivery_status',
            'studio' => 'visible',
            'label' => 'LBL_DELIVERY_STATUS',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_send',
            'label' => 'LBL_DATE_SEND',
          ),
          1 => 'assigned_user_name',
        ),
        3 => 
        array (
          0 => 'description',
        ),
      ),
    ),
  ),
);
?>
