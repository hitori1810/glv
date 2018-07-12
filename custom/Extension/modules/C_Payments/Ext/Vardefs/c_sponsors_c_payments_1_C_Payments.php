<?php
// created: 2014-12-10 14:10:59
$dictionary["C_Payments"]["fields"]["c_sponsors_c_payments_1"] = array (
  'name' => 'c_sponsors_c_payments_1',
  'type' => 'link',
  'relationship' => 'c_sponsors_c_payments_1',
  'source' => 'non-db',
  'module' => 'C_Sponsors',
  'bean_name' => 'C_Sponsors',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_SPONSORS_TITLE',
  'id_name' => 'c_sponsors_c_payments_1c_sponsors_ida',
);
$dictionary["C_Payments"]["fields"]["c_sponsors_c_payments_1_name"] = array (
  'name' => 'c_sponsors_c_payments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_SPONSORS_TITLE',
  'save' => true,
  'id_name' => 'c_sponsors_c_payments_1c_sponsors_ida',
  'link' => 'c_sponsors_c_payments_1',
  'table' => 'c_sponsors',
  'module' => 'C_Sponsors',
  'rname' => 'name',
);
$dictionary["C_Payments"]["fields"]["c_sponsors_c_payments_1c_sponsors_ida"] = array (
  'name' => 'c_sponsors_c_payments_1c_sponsors_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_SPONSORS_TITLE_ID',
  'id_name' => 'c_sponsors_c_payments_1c_sponsors_ida',
  'link' => 'c_sponsors_c_payments_1',
  'table' => 'c_sponsors',
  'module' => 'C_Sponsors',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
