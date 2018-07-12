<?php
// created: 2017-08-10 11:53:30
$dictionary["Contract"]["fields"]["j_coursefee_contracts_1"] = array (
  'name' => 'j_coursefee_contracts_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_contracts_1',
  'source' => 'non-db',
  'module' => 'J_Coursefee',
  'bean_name' => 'J_Coursefee',
  'side' => 'right',
  'vname' => 'LBL_J_COURSEFEE_CONTRACTS_1_FROM_J_COURSEFEE_TITLE',
  'id_name' => 'j_coursefee_contracts_1j_coursefee_ida',
  'link-type' => 'one',
);
$dictionary["Contract"]["fields"]["j_coursefee_contracts_1_name"] = array (
  'name' => 'j_coursefee_contracts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_CONTRACTS_1_FROM_J_COURSEFEE_TITLE',
  'save' => true,
  'id_name' => 'j_coursefee_contracts_1j_coursefee_ida',
  'link' => 'j_coursefee_contracts_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'name',
);
$dictionary["Contract"]["fields"]["j_coursefee_contracts_1j_coursefee_ida"] = array (
  'name' => 'j_coursefee_contracts_1j_coursefee_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_CONTRACTS_1_FROM_CONTRACTS_TITLE_ID',
  'id_name' => 'j_coursefee_contracts_1j_coursefee_ida',
  'link' => 'j_coursefee_contracts_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
