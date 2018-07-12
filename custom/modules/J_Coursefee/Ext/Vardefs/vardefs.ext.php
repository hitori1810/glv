<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-08-24 09:03:46
$dictionary["J_Coursefee"]["fields"]["j_coursefee_j_class_1"] = array (
  'name' => 'j_coursefee_j_class_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_j_class_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'vname' => 'LBL_J_COURSEFEE_J_CLASS_1_FROM_J_COURSEFEE_TITLE',
  'id_name' => 'j_coursefee_j_class_1j_coursefee_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-09-05 15:56:20
$dictionary["J_Coursefee"]["fields"]["j_coursefee_j_payment_1"] = array (
  'name' => 'j_coursefee_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_coursefee_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_COURSEFEE_J_PAYMENT_1_FROM_J_COURSEFEE_TITLE',
  'id_name' => 'j_coursefee_j_payment_1j_coursefee_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2018-06-08 09:01:48
$dictionary["J_Coursefee"]["fields"]["j_kindofcourse_j_coursefee_1"] = array (
  'name' => 'j_kindofcourse_j_coursefee_1',
  'type' => 'link',
  'relationship' => 'j_kindofcourse_j_coursefee_1',
  'source' => 'non-db',
  'module' => 'J_Kindofcourse',
  'bean_name' => 'J_Kindofcourse',
  'vname' => 'LBL_J_KINDOFCOURSE_J_COURSEFEE_1_FROM_J_KINDOFCOURSE_TITLE',
  'id_name' => 'j_kindofcourse_j_coursefee_1j_kindofcourse_ida',
);


$dictionary["J_Coursefee"]["fields"]["unit_price"] = array (
    'required' => true,
    'name' => 'unit_price',
    'vname' => 'LBL_UNIT_PRICE',
    'type' => 'currency',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => 'Unit Price',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => 26,
    'size' => '20',
    'enable_range_search' => false,
    'precision' => 2,
    'default' => '',
    'min' => '1',
);
$dictionary["J_Coursefee"]["fields"]["status"] = array (
    'required' => false,
    'name' => 'status',
    'vname' => 'LBL_STATUS',
    'type' => 'enum',
    'massupdate' => 1,
    'default' => 'Active',
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'len' => 100,
    'size' => '20',
    'options' => 'status_coursefee_list',
    'studio' => 'visible',
    'dependency' => false,
);
$dictionary["J_Coursefee"]["fields"]["apply_date"] = array (
    'required' => false,
    'name' => 'apply_date',
    'vname' => 'LBL_APPLY_DATE',
    'type' => 'date',
    'massupdate' => 1,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'size' => '20',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
);
$dictionary["J_Coursefee"]["fields"]["inactive_date"] = array (
    'name' => 'inactive_date',
    'vname' => 'LBL_INACTIVE_DATE',
    'type' => 'date',
    'massupdate' => 0,
    'no_default' => false,
    'comments' => '',
    'help' => '',
    'importable' => 'true',
    'duplicate_merge' => 'disabled',
    'duplicate_merge_dom_value' => '0',
    'audited' => false,
    'reportable' => true,
    'unified_search' => false,
    'merge_filter' => 'disabled',
    'calculated' => false,
    'size' => '20',
    'enable_range_search' => true,
    'options' => 'date_range_search_dom',
);


$dictionary['J_Coursefee']['fields']['description']['comments']='Full text of the note';
$dictionary['J_Coursefee']['fields']['description']['merge_filter']='disabled';
$dictionary['J_Coursefee']['fields']['description']['calculated']=false;
$dictionary['J_Coursefee']['fields']['description']['rows']='4';
$dictionary['J_Coursefee']['fields']['description']['cols']='60';
?>