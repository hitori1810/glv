<?php
// created: 2014-04-12 00:24:13
$dictionary["C_Invoices"]["fields"]["c_invoices_opportunities_1"] = array (
  'name' => 'c_invoices_opportunities_1',
  'type' => 'link',
  'relationship' => 'c_invoices_opportunities_1',
  'source' => 'non-db',
  'module' => 'Opportunities',
  'bean_name' => 'Opportunity',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'c_invoices_opportunities_1opportunities_idb',
);
$dictionary["C_Invoices"]["fields"]["c_invoices_opportunities_1_name"] = array (
  'name' => 'c_invoices_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'save' => true,
  'id_name' => 'c_invoices_opportunities_1opportunities_idb',
  'link' => 'c_invoices_opportunities_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'name',
);
$dictionary["C_Invoices"]["fields"]["c_invoices_opportunities_1opportunities_idb"] = array (
  'name' => 'c_invoices_opportunities_1opportunities_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_INVOICES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'c_invoices_opportunities_1opportunities_idb',
  'link' => 'c_invoices_opportunities_1',
  'table' => 'opportunities',
  'module' => 'Opportunities',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
