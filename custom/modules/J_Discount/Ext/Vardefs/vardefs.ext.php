<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-07-21 17:32:55
$dictionary["J_Discount"]["fields"]["j_discount_j_discount_1"] = array (
  'name' => 'j_discount_j_discount_1',
  'type' => 'link',
  'relationship' => 'j_discount_j_discount_1',
  'source' => 'non-db',
  'module' => 'J_Discount',
  'bean_name' => 'J_Discount',
  'vname' => 'LBL_J_DISCOUNT_J_DISCOUNT_1_FROM_J_DISCOUNT_L_TITLE',
  'id_name' => 'j_discount_j_discount_1j_discount_ida',
);
$dictionary["J_Discount"]["fields"]["j_discount_j_discount_1"] = array (
  'name' => 'j_discount_j_discount_1',
  'type' => 'link',
  'relationship' => 'j_discount_j_discount_1',
  'source' => 'non-db',
  'module' => 'J_Discount',
  'bean_name' => 'J_Discount',
  'vname' => 'LBL_J_DISCOUNT_J_DISCOUNT_1_FROM_J_DISCOUNT_R_TITLE',
  'id_name' => 'j_discount_j_discount_1j_discount_ida',
);


// created: 2015-09-15 15:09:53
$dictionary["J_Discount"]["fields"]["j_discount_j_partnership_1"] = array (
  'name' => 'j_discount_j_partnership_1',
  'type' => 'link',
  'relationship' => 'j_discount_j_partnership_1',
  'source' => 'non-db',
  'module' => 'J_Partnership',
  'bean_name' => 'J_Partnership',
  'vname' => 'LBL_J_DISCOUNT_J_PARTNERSHIP_1_FROM_J_PARTNERSHIP_TITLE',
  'id_name' => 'j_discount_j_partnership_1j_partnership_idb',
);


// created: 2015-08-06 10:50:14
$dictionary["J_Discount"]["fields"]["j_payment_j_discount_1"] = array (
  'name' => 'j_payment_j_discount_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_discount_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PAYMENT_J_DISCOUNT_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_payment_j_discount_1j_payment_ida',
);


 // created: 2015-07-23 14:30:20
$dictionary['J_Discount']['fields']['description']['comments']='Full text of the note';
$dictionary['J_Discount']['fields']['description']['merge_filter']='disabled';
$dictionary['J_Discount']['fields']['description']['calculated']=false;
$dictionary['J_Discount']['fields']['description']['rows']='4';
$dictionary['J_Discount']['fields']['description']['cols']='60';

 

 // created: 2015-07-08 11:26:14
$dictionary['J_Discount']['fields']['end_date']['options']='date_range_search_dom';
$dictionary['J_Discount']['fields']['end_date']['enable_range_search']='1';

 

 // created: 2015-07-23 14:30:44
$dictionary['J_Discount']['fields']['policy']['cols']='60';

 

 // created: 2015-07-08 11:25:51
$dictionary['J_Discount']['fields']['start_date']['options']='date_range_search_dom';
$dictionary['J_Discount']['fields']['start_date']['enable_range_search']='1';

 

    $dictionary["J_Discount"]["fields"]["discount_schema"] = array (
        'name'      => 'discount_schema',
        'vname'     => 'LBL_DISCOUNT_SCHEMA',
        'type'      => 'multienum',
        'function' => 'getdiscount',
        'source' => 'non-db',
        'studio'    => 'visible',
        'massupdate' => 0,
    );
?>