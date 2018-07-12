<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
 // created: 2014-10-08 08:28:57
$layout_defs["bc_survey_submission"]["subpanel_setup"]['bc_submission_data_bc_survey_submission'] = array (
  'order' => 100,
  'module' => 'bc_submission_data',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_SUBMISSION_FROM_BC_SUBMISSION_DATA_TITLE',
  'get_subpanel_data' => 'bc_submission_data_bc_survey_submission',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);

?>