<?php
// created: 2015-09-05 15:56:20
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1"] = array (
  'name' => 'j_coursefee_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Coursefee',
  'bean_name' => 'J_Coursefee',
  'side' => 'right',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1_name"] = array (
  'name' => 'j_coursefee_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_COURSEFEE_TITLE',
  'save' => true,
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link' => 'j_coursefee_j_payment_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_coursefee_j_payment_1j_coursefee_ida"] = array (
  'name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link' => 'j_coursefee_j_payment_1',
  'table' => 'j_coursefee',
  'module' => 'J_Coursefee',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
