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
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_answers"] = array (
  'name' => 'bc_submission_data_bc_survey_answers',
  'type' => 'link',
  'relationship' => 'bc_submission_data_bc_survey_answers',
  'source' => 'non-db',
  'module' => 'bc_survey_answers',
  'bean_name' => false,
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_ANSWERS_FROM_BC_SURVEY_ANSWERS_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_answersbc_survey_answers_ida',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_answers_name"] = array (
  'name' => 'bc_submission_data_bc_survey_answers_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_ANSWERS_FROM_BC_SURVEY_ANSWERS_TITLE',
  'save' => true,
  'id_name' => 'bc_submission_data_bc_survey_answersbc_survey_answers_ida',
  'link' => 'bc_submission_data_bc_survey_answers',
  'table' => 'bc_survey_answers',
  'module' => 'bc_survey_answers',
  'rname' => 'name',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_answersbc_survey_answers_ida"]=array (
  'name' => 'bc_submission_data_bc_survey_answersbc_survey_answers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_ANSWERS_FROM_BC_SUBMISSION_DATA_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_answersbc_survey_answers_ida',
  'link' => 'bc_submission_data_bc_survey_answers',
  'table' => 'bc_survey_answers',
  'module' => 'bc_survey_answers',
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
// created: 2014-10-08 08:28:57
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_questions"] = array (
  'name' => 'bc_submission_data_bc_survey_questions',
  'type' => 'link',
  'relationship' => 'bc_submission_data_bc_survey_questions',
  'source' => 'non-db',
  'module' => 'bc_survey_questions',
  'bean_name' => false,
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_QUESTIONS_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_questionsbc_survey_questions_ida',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_questions_name"] = array (
  'name' => 'bc_submission_data_bc_survey_questions_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_QUESTIONS_FROM_BC_SURVEY_QUESTIONS_TITLE',
  'save' => true,
  'id_name' => 'bc_submission_data_bc_survey_questionsbc_survey_questions_ida',
  'link' => 'bc_submission_data_bc_survey_questions',
  'table' => 'bc_survey_questions',
  'module' => 'bc_survey_questions',
  'rname' => 'name',
);
$dictionary["bc_submission_data"]["fields"]["bc_submission_data_bc_survey_questionsbc_survey_questions_ida"]=array (
  'name' => 'bc_submission_data_bc_survey_questionsbc_survey_questions_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SUBMISSION_DATA_BC_SURVEY_QUESTIONS_FROM_BC_SUBMISSION_DATA_TITLE',
  'id_name' => 'bc_submission_data_bc_survey_questionsbc_survey_questions_ida',
  'link' => 'bc_submission_data_bc_survey_questions',
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


?>