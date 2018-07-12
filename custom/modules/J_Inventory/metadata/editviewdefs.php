<?php
$module_name = 'J_Inventory';
$viewdefs[$module_name] = 
array (
  'EditView' => 
  array (
    'templateMeta' => 
    array (
      'form' => 
      array (
        'hidden' => 
        array (
          1 => '<input type="hidden" name="type" id="type" value="{$fields.type.value}">',
          2 => '<input type="hidden" name="to_object_key" id="to_object_key" value="{$to_object_key}">',
        ),
      ),
      'maxColumns' => '2',
      'javascript' => '
                {sugar_getscript file="custom/include/javascripts/Select2/select2.min.js"}      
                {sugar_getscript file="custom/include/javascripts/Multifield/jquery.multifield.js"}        
                {sugar_getscript file="custom/modules/J_Inventory/js/custom.edit.view.js"}
                <link rel="stylesheet" href="{sugar_getjspath file=custom/include/javascripts/Select2/select2.css}"/>',
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
        'LBL_EDITVIEW_PANEL1' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
        'LBL_EDITVIEW_PANEL2' => 
        array (
          'newTab' => false,
          'panelDefault' => 'expanded',
        ),
      ),
    ),
    'panels' => 
    array (
      'lbl_editview_panel1' => 
      array (
        0 => 
        array (
          0 => 
          array (
            'name' => 'j_payment_j_inventory_1_name',
            'label' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE',
          ),
        ),
        1 => 
        array (
          0 => 
          array (
            'name' => 'request_no',
            'label' => 'LBL_REQUEST_NO',
          ),
          1 => 
          array (
            'name' => 'date_create',
            'label' => 'LBL_DATE_CREATE',
          ),
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'date_create',
            'label' => 'LBL_DATE_CREATE',
          ),
          1 => 
          array (
            'name' => 'date_create',
            'label' => 'LBL_DATE_CREATE',
          ),
        ),
        3 => 
        array (
          0 => 
          array (
            'name' => 'date_create',
            'label' => 'LBL_DATE_CREATE',
          ),
        ),
      ),
      'lbl_editview_panel2' => 
      array (
        0 => 
        array (
          0 => 'description',
        ),
        1 => 
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
