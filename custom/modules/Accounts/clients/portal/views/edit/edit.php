<?php
$viewdefs['Accounts'] = 
array (
  'portal' => 
  array (
    'view' => 
    array (
      'edit' => 
      array (
        'buttons' => 
        array (
          0 => 
          array (
            'name' => 'cancel_button',
            'type' => 'button',
            'label' => 'LBL_CANCEL_BUTTON_LABEL',
            'value' => 'cancel',
            'events' => 
            array (
              'click' => 'function(){ window.history.back(); }',
            ),
            'css_class' => 'btn-invisible btn-link',
          ),
          1 => 
          array (
            'name' => 'save_button',
            'type' => 'button',
            'label' => 'LBL_SAVE_BUTTON_LABEL',
            'value' => 'save',
            'css_class' => 'btn-primary',
          ),
        ),
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
          'formId' => 'BugEditView',
          'formName' => 'BugEditView',
          'hiddenInputs' => 
          array (
            'module' => 'Bugs',
            'returnmodule' => 'Bugs',
            'returnaction' => 'DetailView',
            'action' => 'Save',
          ),
          'hiddenFields' => 
          array (
            0 => 
            array (
              'name' => 'portal_viewable',
              'operator' => '=',
              'value' => '1',
            ),
          ),
          'useTabs' => false,
          'tabDefs' => 
          array (
            'LBL_PANEL_DEFAULT' => 
            array (
              'newTab' => false,
              'panelDefault' => 'expanded',
            ),
          ),
        ),
        'panels' => 
        array (
          0 => 
          array (
            'label' => 'LBL_PANEL_DEFAULT',
            'fields' => 
            array (
              0 => 
              array (
                'name' => 'name',
                'displayParams' => 
                array (
                  'colspan' => 2,
                ),
              ),
              1 => 
              array (
                'name' => 'description',
                'displayParams' => 
                array (
                  'colspan' => 2,
                ),
              ),
              2 => 'status',
              3 => 'type',
              4 => 'product_category',
              5 => 'priority',
              6 => 
              array (
                'name' => 'bug_number',
                'comment' => 'Visual unique identifier',
                'studio' => 
                array (
                  'quickcreate' => false,
                ),
                'label' => 'LBL_NUMBER',
              ),
              7 => '',
            ),
          ),
        ),
      ),
    ),
  ),
);
