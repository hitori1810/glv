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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospects"] = array (
  'name' => 'bc_survey_submission_prospects',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_prospects',
  'source' => 'non-db',
  'module' => 'Prospects',
  'bean_name' => 'Prospect',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_PROSPECTS_TITLE',
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospects_name"] = array (
  'name' => 'bc_survey_submission_prospects_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_PROSPECTS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
  'link' => 'bc_survey_submission_prospects',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'account_name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_prospectsprospects_ida"]=array (
  'name' => 'bc_survey_submission_prospectsprospects_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_PROSPECTS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_prospectsprospects_ida',
  'link' => 'bc_survey_submission_prospects',
  'table' => 'prospects',
  'module' => 'Prospects',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

