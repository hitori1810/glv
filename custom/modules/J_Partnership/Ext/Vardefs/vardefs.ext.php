<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-09-15 15:09:53
$dictionary["J_Partnership"]["fields"]["j_discount_j_partnership_1"] = array (
  'name' => 'j_discount_j_partnership_1',
  'type' => 'link',
  'relationship' => 'j_discount_j_partnership_1',
  'source' => 'non-db',
  'module' => 'J_Discount',
  'bean_name' => 'J_Discount',
  'vname' => 'LBL_J_DISCOUNT_J_PARTNERSHIP_1_FROM_J_DISCOUNT_TITLE',
  'id_name' => 'j_discount_j_partnership_1j_discount_ida',
);


// created: 2015-09-04 09:23:24
$dictionary["J_Partnership"]["fields"]["j_partnership_j_payment_1"] = array (
  'name' => 'j_partnership_j_payment_1',
  'type' => 'link',
  'relationship' => 'j_partnership_j_payment_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PARTNERSHIP_J_PAYMENT_1_FROM_J_PARTNERSHIP_TITLE',
  'id_name' => 'j_partnership_j_payment_1j_partnership_ida',
  'link-type' => 'many',
  'side' => 'left',
);


    $dictionary["J_Partnership"]["fields"]["status"] = array ( 
        'required' => false,
        'name' => 'status',
        'vname' => 'LBL_STATUS',
        'type' => 'enum',
        'massupdate' => 0,
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
        'options' => 'status_partnership_list',
        'studio' => 'visible',
        'dependency' => false,
    ); 
   /* $dictionary["J_Partnership"]["fields"]["discount"] = array ( 
        'required' => false,
        'name' => 'discount',
        'vname' => 'LBL_DISCOUNT',
        'type' => 'enum',
        'massupdate' => 0,
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
        'options' => 'discount_partnership_list',
        'studio' => 'visible',
        'dependency' => false,
    ); */
     $dictionary["J_Partnership"]["fields"]["schema"] = array ( 
        'name'      => 'schema',
        'vname'     => 'LBL_SCHEMA',
        'type'      => 'text',
        'source' => 'non-db',
        'studio'    => 'visible',
    );  


?>