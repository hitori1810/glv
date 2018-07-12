<?php
// created: 2015-07-21 16:29:24
$dictionary["C_Payments"]["fields"]["c_payments_c_refunds_1"] = array (
  'name' => 'c_payments_c_refunds_1',
  'type' => 'link',
  'relationship' => 'c_payments_c_refunds_1',
  'source' => 'non-db',
  'module' => 'C_Refunds',
  'bean_name' => 'C_Refunds',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_REFUNDS_TITLE',
  'id_name' => 'c_payments_c_refunds_1c_refunds_idb',
);
$dictionary["C_Payments"]["fields"]["c_payments_c_refunds_1_name"] = array (
  'name' => 'c_payments_c_refunds_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_REFUNDS_TITLE',
  'save' => true,
  'id_name' => 'c_payments_c_refunds_1c_refunds_idb',
  'link' => 'c_payments_c_refunds_1',
  'table' => 'c_refunds',
  'module' => 'C_Refunds',
  'rname' => 'document_name',
);
$dictionary["C_Payments"]["fields"]["c_payments_c_refunds_1c_refunds_idb"] = array (
  'name' => 'c_payments_c_refunds_1c_refunds_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PAYMENTS_C_REFUNDS_1_FROM_C_REFUNDS_TITLE_ID',
  'id_name' => 'c_payments_c_refunds_1c_refunds_idb',
  'link' => 'c_payments_c_refunds_1',
  'table' => 'c_refunds',
  'module' => 'C_Refunds',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
