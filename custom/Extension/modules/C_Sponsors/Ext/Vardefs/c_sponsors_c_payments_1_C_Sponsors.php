<?php
// created: 2014-12-10 14:10:59
$dictionary["C_Sponsors"]["fields"]["c_sponsors_c_payments_1"] = array (
  'name' => 'c_sponsors_c_payments_1',
  'type' => 'link',
  'relationship' => 'c_sponsors_c_payments_1',
  'source' => 'non-db',
  'module' => 'C_Payments',
  'bean_name' => 'C_Payments',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'id_name' => 'c_sponsors_c_payments_1c_payments_idb',
);
$dictionary["C_Sponsors"]["fields"]["c_sponsors_c_payments_1_name"] = array (
  'name' => 'c_sponsors_c_payments_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE',
  'save' => true,
  'id_name' => 'c_sponsors_c_payments_1c_payments_idb',
  'link' => 'c_sponsors_c_payments_1',
  'table' => 'c_payments',
  'module' => 'C_Payments',
  'rname' => 'name',
);
$dictionary["C_Sponsors"]["fields"]["c_sponsors_c_payments_1c_payments_idb"] = array (
  'name' => 'c_sponsors_c_payments_1c_payments_idb',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_SPONSORS_C_PAYMENTS_1_FROM_C_PAYMENTS_TITLE_ID',
  'id_name' => 'c_sponsors_c_payments_1c_payments_idb',
  'link' => 'c_sponsors_c_payments_1',
  'table' => 'c_payments',
  'module' => 'C_Payments',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);
