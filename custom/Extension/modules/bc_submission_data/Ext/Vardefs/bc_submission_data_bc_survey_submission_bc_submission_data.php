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
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_submission"] = array (
  'name' => 'bc_submission_data_bc_survey_submission',
  'type' => 'link',
  'relationship' => 'bc_submission_data_bc_survey_submission',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_SUBMISSION_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_submissionbc_survey_submission_ida',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_submission_name"] = array (
  'name' => 'bc_submission_data_bc_survey_submission_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_SUBMISSION_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'save' => true,
  'id_name' => 'bc_submission_data_bc_survey_submissionbc_survey_submission_ida',
  'link' => 'bc_submission_data_bc_survey_submission',
  'table' => 'bc_survey_submission',
  'module' => 'bc_survey_submission',
  'rname' => 'name',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_submissionbc_survey_submission_ida"]=array (
  'name' => 'bc_submission_data_bc_survey_submissionbc_survey_submission_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_SUBMISSION_FROM_BC_SUBMISSION_DATA_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_submissionbc_survey_submission_ida',
  'link' => 'bc_submission_data_bc_survey_submission',
  'table' => 'bc_survey_submission',
  'module' => 'bc_survey_submission',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

