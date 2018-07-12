<?php
$module_name = 'J_Partnership';
$viewdefs[$module_name] =
array (
  'EditView' =>
  array (
    'templateMeta' =>
    array (
      'maxColumns' => '2',
      'javascript' => '
                {sugar_getscript file="custom/modules/J_Partnership/js/editview.js"}
                {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.js"}',
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
        'LBL_EDITVIEW_PANEL1' =>
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
            'name' => 'status',
            'studio' => 'visible',
            'label' => 'LBL_STATUS',
          ),
        ),
        1 =>
        array (
          0 =>
          array (
            'name' => 'discount_amount',
            'label' => 'LBL_DISCOUNT_AMOUNT',
             'customCode' => '<input type="text" size="30" maxlength="255"  class="currency" name="discount_amount" id="discount_amount"  value="{sugar_number_format var=$fields.discount_amount.value}" title="{$MOD.LBL_DISCOUNT_AMOUNT}" >',
          ),
          1 =>
          array (
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
          ),
        ),
        2 =>
        array (
          0 =>
          array (
            'name' => 'discount_percent',
            'label' => 'LBL_DISCOUNT_PERCENT',
            'customCode' => '<input type="text" tabindex="0" size="4" maxlength="10" class="currency" name="discount_percent" id="discount_percent"  value="{$fields.discount_percent.value}" title="{$MOD.LBL_DISCOUNT_PERCENT}"> <b> %</b>',
          ),
          1 =>
          array (
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
          ),
        ),
        3 =>
        array (
          0 =>array (
                        'name' => 'description',
                        'displayParams' =>
                        array (
                            'rows' => 4,
                            'cols' => 60,
                        ),
                    ),
          1 => 'loyalty_type',
        ),
        4 =>
        array (
          0 => '',
          1 => 'hours',
        ),
      ),
      'lbl_editview_panel1' =>
      array (
        0 =>
        array (
          0 => 'assigned_user_name',
          1 =>
          array (
            'name' => 'team_name',
            'displayParams' =>
            array (
              'display' => true,
            ),
          ),
        ),
      ),
    ),
  ),
);
