<?php
// created: 2014-04-12 00:24:13
$dictionary["Opportunity"]["fields"]["c_invoices_opportunities_1"] = array (
  'name' => 'c_invoices_opportunities_1',
  'type' => 'link',
  'relationship' => 'c_invoices_opportunities_1',
  'source' => 'non-db',
  'module' => 'C_Invoices',
  'bean_name' => 'C_Invoices',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_C_INVOICES_TITLE',
  'id_name' => 'c_invoices_opportunities_1c_invoices_ida',
);
$dictionary["Opportunity"]["fields"]["c_invoices_opportunities_1_name"] = array (
  'name' => 'c_invoices_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_C_INVOICES_TITLE',
  'save' => true,
  'id_name' => 'c_invoices_opportunities_1c_invoices_ida',
  'link' => 'c_invoices_opportunities_1',
  'table' => 'c_invoices',
  'module' => 'C_Invoices',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["c_invoices_opportunities_1c_invoices_ida"] = array (
  'name' => 'c_invoices_opportunities_1c_invoices_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_C_INVOICES_TITLE_ID',
  'id_name' => 'c_invoices_opportunities_1c_invoices_ida',
  'link' => 'c_invoices_opportunities_1',
  'table' => 'c_invoices',
  'module' => 'C_Invoices',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
