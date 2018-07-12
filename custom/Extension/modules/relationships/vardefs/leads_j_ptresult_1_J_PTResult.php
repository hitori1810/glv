<?php
// created: 2015-09-07 09:49:06
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1"] = array (
  'name' => 'leads_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'leads_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'side' => 'right',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1_name"] = array (
  'name' => 'leads_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link' => 'leads_j_ptresult_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1leads_ida"] = array (
  'name' => 'leads_j_ptresult_1leads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link' => 'leads_j_ptresult_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
