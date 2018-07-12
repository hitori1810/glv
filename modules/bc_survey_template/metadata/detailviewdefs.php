<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$module_name = 'bc_survey_template';
$viewdefs [$module_name] = 
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
          1 => 'DELETE',
          2 => 
          array (
            'customCode' => '<input id="contrats_debranche_prima" title="Create_Survey" class="button" type="button" name="contrats_debranche_prima" value="Create Survey" onclick="window.open(\'index.php?module=bc_survey&action=EditView&template_id={$fields.id.value}&return_module=bc_survey_template&return_action=index\',\'_blank\')">',
          ),
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
          1 => 
          array (
            'name' => 'created_by_name',
            'label' => 'LBL_CREATED',
          ),
        ),
        1 => 
        array (
          0 => 'description',
        ),
        2 => 
        array (
          0 => 
          array (
            'name' => 'survey_page',
            'type'=>'AddSurveyPagefield',
            'label' => 'LBL_SURVEYPAGES',
          ),
        ),
      ),
    ),
  ),
);
?>


