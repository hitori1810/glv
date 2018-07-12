<?php 
 //WARNING: The contents of this file are auto-generated


$layout_defs["J_Payment"]["subpanel_setup"]["payment_invoices"] = array (
    'order' => 9,
    'module' => 'J_Invoice',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_INVOICE',
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'get_subpanel_data' => 'invoice_link',
    'top_buttons' =>
    array (
        0 =>
        array (
            'widget_class' => 'SubPanelAddInvoiceBtn',
        ),
    ),
);

$layout_defs["J_Payment"]["subpanel_setup"]["payment_paymentdetails"] = array (
    'order' => 10,
    'module' => 'J_PaymentDetail',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_PAYMENT_DETAIL',
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'get_subpanel_data' => 'paymentdetail_link',
    'top_buttons' =>
    array (
    ),
);

$layout_defs["J_Payment"]["subpanel_setup"]["j_payment_j_sponsor"] = array (
    'order' => 2,
    'module' => 'J_Sponsor',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_SPONSOR',
    'sort_order' => 'asc',
    'sort_by' => 'name',
    'get_subpanel_data' => 'ju_sponsor',
    'top_buttons' =>
    array (
    ),
);
$layout_defs["J_Payment"]["subpanel_setup"]["payment_loyaltys"] = array (
    'order' => 3,
    'module' => 'J_Loyalty',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_LOYALTY',
    'sort_order' => 'asc',
    'sort_by' => 'input_date',
    'get_subpanel_data' => 'loyalty_link',
    'top_buttons' =>
    array (
    ),
);
$layout_defs["J_Payment"]["subpanel_setup"]["j_payment_studentsituations"] = array (
    'order' => 4,
    'module' => 'J_StudentSituations',
    'subpanel_name' => 'default',
    'title_key' => 'LBL_STUDENT_SITUATION_SUPPANEL',
    'sort_order' => 'asc',
    'sort_by' => 'type',
    'get_subpanel_data' => 'ju_studentsituations',
    'top_buttons' =>
    array (
    ),
);


 // created: 2015-08-06 10:50:14
$layout_defs["J_Payment"]["subpanel_setup"]['j_payment_j_discount_1'] = array (
  'order' => 1,
  'module' => 'J_Discount',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_PAYMENT_J_DISCOUNT_1_FROM_J_DISCOUNT_TITLE',
  'get_subpanel_data' => 'j_payment_j_discount_1',
  'top_buttons' => 
  array (
      //0 => 
      //    array (
      //      'widget_class' => 'SubPanelTopButtonQuickCreate',
      //    ),
      //    1 => 
      //    array (
      //      'widget_class' => 'SubPanelTopSelectButton',
      //      'mode' => 'MultiSelect',
      //    ),
  ),
);


 // created: 2016-02-01 16:10:28
$layout_defs["J_Payment"]["subpanel_setup"]['j_payment_j_payment_1'] = array (
  'order' => 5,
  'module' => 'J_Payment',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_J_PAYMENT_J_PAYMENT_1_FROM_J_PAYMENT_R_TITLE',
  'get_subpanel_data' => 'j_payment_j_payment_1',
  'top_buttons' => 
  array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  ),
);


//auto-generated file DO NOT EDIT
$layout_defs['J_Payment']['subpanel_setup']['j_payment_j_discount_1']['override_subpanel_name'] = 'J_Payment_subpanel_j_payment_j_discount_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Payment']['subpanel_setup']['j_payment_j_payment_1']['override_subpanel_name'] = 'J_Payment_subpanel_j_payment_j_payment_1';


//auto-generated file DO NOT EDIT
$layout_defs['J_Payment']['subpanel_setup']['j_payment_j_sponsor']['override_subpanel_name'] = 'J_Payment_subpanel_j_payment_j_sponsor';


//auto-generated file DO NOT EDIT
$layout_defs['J_Payment']['subpanel_setup']['payment_invoices']['override_subpanel_name'] = 'J_Payment_subpanel_payment_invoices';


//auto-generated file DO NOT EDIT
$layout_defs['J_Payment']['subpanel_setup']['payment_paymentdetails']['override_subpanel_name'] = 'J_Payment_subpanel_payment_paymentdetails';

?>