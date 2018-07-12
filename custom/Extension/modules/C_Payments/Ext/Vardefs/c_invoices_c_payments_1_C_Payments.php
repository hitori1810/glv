<?php
// created: 2014-04-12 00:20:01
$dictionary["C_Payments"]["fields"]["c_invoices_c_payments_1"] = array (
  'name' => 'c_invoices_c_payments_1',
  'type' => 'link',
  'relationship' => 'c_invoices_c_payments_1',
  'source' => 'non-db',
  'module' => 'C_Invoices',
  'bean_name' => 'C_Invoices',
  'side' => 'right',
  'vname' => 'LBL_C_INVOICES_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'id_name' => 'c_invoices_c_payments_1c_invoices_ida',
  'link-type' => 'one',
);
$dictionary["C_Payments"]["fields"]["c_invoices_c_payments_1_name"] = array (
  'name' => 'c_invoices_c_payments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_C_PAYMENTS_1_FROM_C_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'c_invoices_c_payments_1c_invoices_ida',
  'link' => 'c_invoices_c_payments_1',
  'table' => 'c_invoices',
  'module' => 'C_Invoices',
  'rname' => 'name',
);
$dictionary["C_Payments"]["fields"]["c_invoices_c_payments_1c_invoices_ida"] = array (
  'name' => 'c_invoices_c_payments_1c_invoices_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE_ID',
  'id_name' => 'c_invoices_c_payments_1c_invoices_ida',
  'link' => 'c_invoices_c_payments_1',
  'table' => 'c_invoices',
  'module' => 'C_Invoices',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
