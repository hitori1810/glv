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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_users"] = array (
  'name' => 'bc_survey_submission_users',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_users',
  'source' => 'non-db',
  'module' => 'Users',
  'bean_name' => 'User',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_USERS_TITLE',
  'id_name' => 'bc_survey_submission_usersusers_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_users_name"] = array (
  'name' => 'bc_survey_submission_users_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_USERS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_usersusers_ida',
  'link' => 'bc_survey_submission_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'name',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_usersusers_ida"]=array (
  'name' => 'bc_survey_submission_usersusers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_usersusers_ida',
  'link' => 'bc_survey_submission_users',
  'table' => 'users',
  'module' => 'Users',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

