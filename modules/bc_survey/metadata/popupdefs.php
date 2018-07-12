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
$popupMeta = array (
    'moduleMain' => 'bc_survey',
    'varName' => 'bc_survey',
    'orderBy' => 'bc_survey.name',
    'whereClauses' => array (
  'name' => 'bc_survey.name',
  'bc_survey_bc_survey_template_name' => 'bc_survey.bc_survey_bc_survey_template_name',
),
    'searchInputs' => array (
  1 => 'name',
  4 => 'bc_survey_bc_survey_template_name',
),
    'searchdefs' => array (
  'name' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'name' => 'name',
  ),
  'bc_survey_bc_survey_template_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
    'id' => 'BC_SURVEY_BC_SURVEY_TEMPLATEBC_SURVEY_TEMPLATE_IDA',
    'width' => '10%',
    'name' => 'bc_survey_bc_survey_template_name',
  ),
),
    'listviewdefs' => array (
  'NAME' => 
  array (
    'type' => 'name',
    'link' => true,
    'label' => 'LBL_NAME',
    'width' => '10%',
    'default' => true,
  ),
  'BC_SURVEY_BC_SURVEY_TEMPLATE_NAME' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
    'id' => 'BC_SURVEY_BC_SURVEY_TEMPLATEBC_SURVEY_TEMPLATE_IDA',
    'width' => '10%',
    'default' => true,
  ),
  'START_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_START_DATE',
    'width' => '10%',
    'default' => true,
  ),
  'END_DATE' => 
  array (
    'type' => 'datetimecombo',
    'label' => 'LBL_END_DATE',
    'width' => '10%',
    'default' => true,
  ),
),
);
