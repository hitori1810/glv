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
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leads"] = array (
  'name' => 'bc_survey_submission_leads',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_leads',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_LEADS_TITLE',
  'id_name' => 'bc_survey_submission_leadsleads_ida',
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leads_name"] = array (
  'name' => 'bc_survey_submission_leads_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_submission_leadsleads_ida',
  'link' => 'bc_survey_submission_leads',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["bc_survey_submission"]["fields"]["bc_survey_submission_leadsleads_ida"]=array (
  'name' => 'bc_survey_submission_leadsleads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_LEADS_FROM_BC_SURVEY_SUBMISSION_TITLE',
  'id_name' => 'bc_survey_submission_leadsleads_ida',
  'link' => 'bc_survey_submission_leads',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

