<?php
// created: 2016-07-07 16:43:11
$dictionary["bc_automizer_condition"]["fields"]["bc_survey_automizer_bc_automizer_condition"] = array (
  'name' => 'bc_survey_automizer_bc_automizer_condition',
  'type' => 'link',
  'relationship' => 'bc_survey_automizer_bc_automizer_condition',
  'source' => 'non-db',
  'module' => 'bc_survey_automizer',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_CONDITION_FROM_BC_SURVEY_AUTOMIZER_TITLE',
  'id_name' => 'bc_survey_3b38tomizer_ida',
);
$dictionary["bc_automizer_condition"]["fields"]["bc_survey_automizer_bc_automizer_condition_name"] = array (
  'name' => 'bc_survey_automizer_bc_automizer_condition_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_CONDITION_FROM_BC_SURVEY_AUTOMIZER_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_3b38tomizer_ida',
  'link' => 'bc_survey_automizer_bc_automizer_condition',
  'table' => 'bc_survey_automizer',
  'module' => 'bc_survey_automizer',
  'rname' => 'name',
);
$dictionary["bc_automizer_condition"]["fields"]["bc_survey_3b38tomizer_ida"]=array (
  'name' => 'bc_survey_3b38tomizer_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_CONDITION_FROM_BC_AUTOMIZER_CONDITION_TITLE',
  'id_name' => 'bc_survey_3b38tomizer_ida',
  'link' => 'bc_survey_automizer_bc_automizer_condition',
  'table' => 'bc_survey_automizer',
  'module' => 'bc_survey_automizer',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);

