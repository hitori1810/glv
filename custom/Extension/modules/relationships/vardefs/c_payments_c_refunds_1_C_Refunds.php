<?php
// created: 2015-07-21 16:29:24
$dictionary["C_Refunds"]["fields"]["c_payments_c_refunds_1"] = array (
  'name' => 'c_payments_c_refunds_1',
  'type' => 'link',
  'relationship' => 'c_payments_c_refunds_1',
  'source' => 'non-db',
  'module' => 'C_Payments',
  'bean_name' => 'C_Payments',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_PAYMENTS_TITLE',
  'id_name' => 'c_payments_c_refunds_1c_payments_ida',
);
$dictionary["C_Refunds"]["fields"]["c_payments_c_refunds_1_name"] = array (
  'name' => 'c_payments_c_refunds_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_PAYMENTS_TITLE',
  'save' => true,
  'id_name' => 'c_payments_c_refunds_1c_payments_ida',
  'link' => 'c_payments_c_refunds_1',
  'table' => 'c_payments',
  'module' => 'C_Payments',
  'rname' => 'name',
);
$dictionary["C_Refunds"]["fields"]["c_payments_c_refunds_1c_payments_ida"] = array (
  'name' => 'c_payments_c_refunds_1c_payments_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_PAYMENTS_TITLE_ID',
  'id_name' => 'c_payments_c_refunds_1c_payments_ida',
  'link' => 'c_payments_c_refunds_1',
  'table' => 'c_payments',
  'module' => 'C_Payments',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
