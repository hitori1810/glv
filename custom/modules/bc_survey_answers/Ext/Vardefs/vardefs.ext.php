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
$dictionary["bc_survey_answers"]["fields"]["bc_submission_data_bc_survey_answers"] = array (
  'name' => 'bc_submission_data_bc_survey_answers',
  'type' => 'link',
  'relationship' => 'bc_submission_data_bc_survey_answers',
  'source' => 'non-db',
  'module' => 'bc_submission_data',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_ANSWERS_FROM_BC_SUBMISSION_DATA_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:58
$dictionary["bc_survey_answers"]["fields"]["bc_survey_answers_bc_survey_questions"] = array (
  'name' => 'bc_survey_answers_bc_survey_questions',
  'type' => 'link',
  'relationship' => 'bc_survey_answers_bc_survey_questions',
  'source' => 'non-db',
  'module' => 'bc_survey_questions',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_ANSWERS_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_QUESTIONS_TITLE',
  'id_name' => 'bc_survey_answers_bc_survey_questionsbc_survey_questions_ida',
);
$dictionary["bc_survey_answers"]["fields"]["bc_survey_answers_bc_survey_questions_name"] = array (
  'name' => 'bc_survey_answers_bc_survey_questions_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_ANSWERS_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_QUESTIONS_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_answers_bc_survey_questionsbc_survey_questions_ida',
  'link' => 'bc_survey_answers_bc_survey_questions',
  'table' => 'bc_survey_questions',
  'module' => 'bc_survey_questions',
  'rname' => 'name',
);
$dictionary["bc_survey_answers"]["fields"]["bc_survey_answers_bc_survey_questionsbc_survey_questions_ida"]=array (
  'name' => 'bc_survey_answers_bc_survey_questionsbc_survey_questions_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_ANSWERS_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_ANSWERS_TITLE',
  'id_name' => 'bc_survey_answers_bc_survey_questionsbc_survey_questions_ida',
  'link' => 'bc_survey_answers_bc_survey_questions',
  'table' => 'bc_survey_questions',
  'module' => 'bc_survey_questions',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);



/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
$dictionary["bc_survey_answers"]["fields"]["name"]['type'] = 'text';
$dictionary["bc_survey_answers"]["fields"]["name"]['rows'] = 6;
$dictionary["bc_survey_answers"]["fields"]["name"]['rows'] = 80;

unset($dictionary["bc_survey_answers"]["fields"]["name"]['dbType']);
unset($dictionary["bc_survey_answers"]["fields"]["name"]['len']);


?>