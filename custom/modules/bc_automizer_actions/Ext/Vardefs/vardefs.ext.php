<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2016-07-07 16:43:11
$dictionary["bc_automizer_actions"]["fields"]["bc_survey_automizer_bc_automizer_actions"] = array (
  'name' => 'bc_survey_automizer_bc_automizer_actions',
  'type' => 'link',
  'relationship' => 'bc_survey_automizer_bc_automizer_actions',
  'source' => 'non-db',
  'module' => 'bc_survey_automizer',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_ACTIONS_FROM_BC_SURVEY_AUTOMIZER_TITLE',
  'id_name' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
);
$dictionary["bc_automizer_actions"]["fields"]["bc_survey_automizer_bc_automizer_actions_name"] = array (
  'name' => 'bc_survey_automizer_bc_automizer_actions_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_ACTIONS_FROM_BC_SURVEY_AUTOMIZER_TITLE',
  'save' => true,
  'id_name' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
  'link' => 'bc_survey_automizer_bc_automizer_actions',
  'table' => 'bc_survey_automizer',
  'module' => 'bc_survey_automizer',
  'rname' => 'name',
);
$dictionary["bc_automizer_actions"]["fields"]["bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida"]=array (
  'name' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_BC_SURVEY_AUTOMIZER_BC_AUTOMIZER_ACTIONS_FROM_BC_AUTOMIZER_ACTIONS_TITLE',
  'id_name' => 'bc_survey_automizer_bc_automizer_actionsbc_survey_automizer_ida',
  'link' => 'bc_survey_automizer_bc_automizer_actions',
  'table' => 'bc_survey_automizer',
  'module' => 'bc_survey_automizer',
  'rname' => 'id',
  'reportable' => false,
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


?>