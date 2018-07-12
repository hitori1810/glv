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
// created: 2014-10-08 08:28:57
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_template"] = array (
  'name' => 'bc_survey_bc_survey_template',
  'type' => 'link',
  'relationship' => 'bc_survey_bc_survey_template',
  'source' => 'non-db',
  'module' => 'bc_survey_template',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
);
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_template_name"] = array (
  'name' => 'bc_survey_bc_survey_template_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TEMPLATE_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'link' => 'bc_survey_bc_survey_template',
  'table' => 'bc_survey_template',
  'module' => 'bc_survey_template',
  'rname' => 'name',
);
$dictionary["bc_survey"]["fields"]["bc_survey_bc_survey_templatebc_survey_template_ida"]=array (
  'name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_BC_SURVEY_TEMPLATE_FROM_BC_SURVEY_TITLE',
  'id_name' => 'bc_survey_bc_survey_templatebc_survey_template_ida',
  'link' => 'bc_survey_bc_survey_template',
  'table' => 'bc_survey_template',
  'module' => 'bc_survey_template',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

