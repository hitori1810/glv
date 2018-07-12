<?php
// created: 2014-09-28 15:16:06
$dictionary["C_Payments"]["fields"]["leads_c_payments_1"] = array (
  'name' => 'leads_c_payments_1',
  'type' => 'link',
  'relationship' => 'leads_c_payments_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'side' => 'right',
  'vname' => 'LBL_LEADS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'id_name' => 'leads_c_payments_1leads_ida',
  'link-type' => 'one',
);
$dictionary["C_Payments"]["fields"]["leads_c_payments_1_name"] = array (
  'name' => 'leads_c_payments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_C_PAYMENTS_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_c_payments_1leads_ida',
  'link' => 'leads_c_payments_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["C_Payments"]["fields"]["leads_c_payments_1leads_ida"] = array (
  'name' => 'leads_c_payments_1leads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE_ID',
  'id_name' => 'leads_c_payments_1leads_ida',
  'link' => 'leads_c_payments_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
