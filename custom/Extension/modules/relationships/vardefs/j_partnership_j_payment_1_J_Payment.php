<?php
// created: 2015-09-04 09:23:24
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1"] = array (
  'name' => 'j_partnership_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_partnership_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Partnership',
  'bean_name' => 'J_Partnership',
  'side' => 'right',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link-type' => 'one',
);
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1_name"] = array (
  'name' => 'j_partnership_j_payment_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PARTNERSHIP_TITLE',
  'save' => true,
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link' => 'j_partnership_j_payment_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'name',
);
$dictionary["J_Payment"]["fields"]["j_partnership_j_payment_1j_partnership_ida"] = array (
  'name' => 'j_partnership_j_payment_1j_partnership_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link' => 'j_partnership_j_payment_1',
  'table' => 'j_partnership',
  'module' => 'J_Partnership',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
