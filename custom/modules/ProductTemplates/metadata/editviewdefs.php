<?php
$viewdefs['ProductTemplates'] =
array (
  'EditView' =>
  array (
    'templateMeta' =>
    array (
      'maxColumns' => '2',
      'javascript' => '
            {sugar_getscript file="custom/modules/ProductTemplates/js/editview.js"}',
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
      'syncDetailEditViews' => true,
    ),
    'panels' =>
    array (
      'default' =>
      array (
        0 =>
        array (
          0 =>
          array (
            'name' => 'code',
            'label' => 'LBL_CODE',
          ),
          1 =>
          array (
            'name' => 'status2',
            'label' => 'LBL_STATUS_2',
            'customCode' => '{html_options  style="width:28%;" name="status2" id="status2" options=$fields.status2.options selected=$fields.status2.value} ',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'category_name',
            'label' => 'LBL_CATEGORY_NAME',
          ),
          1 =>
          array (
            'name' => 'date_available',
            'label' => 'LBL_DATE_AVAILABLE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'name',
            'label' => 'LBL_NAME',
            'displayParams' =>
            array (
              'required' => true,
            ),
          ),
          1 =>
          array (
            'name' => 'type_id',
            'label' => 'LBL_TYPE',
            'displayParams' =>
            array (
              'size' => 50,
            ),
          ),
        ),
        3 =>
        array (
          0 => 'list_price',
          1 =>
          array (
            'name' => 'unit',
            'studio' => 'visible',
            'label' => 'LBL_UNIT',
            'customCode' => '{html_options  style="width:28%;" name="unit" id="unit" options=$fields.unit.options selected=$fields.unit.value} ',
          ),
        ),
        4 =>
        array (
          0 =>
          array (
            'name' => 'description',
            'label' => 'LBL_DESCRIPTION',
          ),
        ),
      ),
    ),
  ),
);
