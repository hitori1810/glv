<?php 
 //WARNING: The contents of this file are auto-generated


    // created: 2014-11-12 02:35:22
    $dictionary["Opportunity"]["fields"]["c_classes_opportunities_1"] = array (
        'name' => 'c_classes_opportunities_1',
        'type' => 'link',
        'relationship' => 'c_classes_opportunities_1',
        'source' => 'non-db',
        'module' => 'C_Classes',
        'bean_name' => 'C_Classes',
        'vname' => 'LBL_C_CLASSES_OPPORTUNITIES_1_FROM_C_CLASSES_TITLE',
        'id_name' => 'c_classes_opportunities_1c_classes_ida',
    );


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


// created: 2014-04-12 01:02:18
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1"] = array (
  'name' => 'c_packages_opportunities_1',
  'type' => 'link',
  'relationship' => 'c_packages_opportunities_1',
  'source' => 'non-db',
  'module' => 'C_Packages',
  'bean_name' => 'C_Packages',
  'side' => 'right',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link-type' => 'one',
);
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1_name"] = array (
  'name' => 'c_packages_opportunities_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_C_PACKAGES_TITLE',
  'save' => true,
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link' => 'c_packages_opportunities_1',
  'table' => 'c_packages',
  'module' => 'C_Packages',
  'rname' => 'name',
);
$dictionary["Opportunity"]["fields"]["c_packages_opportunities_1c_packages_ida"] = array (
  'name' => 'c_packages_opportunities_1c_packages_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_PACKAGES_OPPORTUNITIES_1_FROM_OPPORTUNITIES_TITLE_ID',
  'id_name' => 'c_packages_opportunities_1c_packages_ida',
  'link' => 'c_packages_opportunities_1',
  'table' => 'c_packages',
  'module' => 'C_Packages',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2014-04-30 19:59:14
$dictionary["Opportunity"]["fields"]["opportunities_c_refunds_1"] = array (
  'name' => 'opportunities_c_refunds_1',
  'type' => 'link',
  'relationship' => 'opportunities_c_refunds_1',
  'source' => 'non-db',
  'module' => 'C_Refunds',
  'bean_name' => 'C_Refunds',
  'vname' => 'LBL_OPPORTUNITIES_C_REFUNDS_1_FROM_OPPORTUNITIES_TITLE',
  'id_name' => 'opportunities_c_refunds_1opportunities_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2014-11-22 02:54:14
$dictionary["Opportunity"]["fields"]["opportunities_meetings_1"] = array (
  'name' => 'opportunities_meetings_1',
  'type' => 'link',
  'relationship' => 'opportunities_meetings_1',
  'source' => 'non-db',
  'module' => 'Meetings',
  'bean_name' => 'Meeting',
  'vname' => 'LBL_OPPORTUNITIES_MEETINGS_1_FROM_MEETINGS_TITLE',
  'id_name' => 'opportunities_meetings_1meetings_idb',
);


 // created: 2014-08-29 14:50:49
$dictionary['Opportunity']['fields']['date_closed']['display_default']='now';
$dictionary['Opportunity']['fields']['date_closed']['comments']='Expected or actual date the oppportunity will close';
$dictionary['Opportunity']['fields']['date_closed']['merge_filter']='disabled';
$dictionary['Opportunity']['fields']['date_closed']['calculated']=false;

 

 // created: 2014-12-23 07:09:21
$dictionary['Opportunity']['fields']['description']['comments']='Full text of the note';
$dictionary['Opportunity']['fields']['description']['merge_filter']='disabled';
$dictionary['Opportunity']['fields']['description']['calculated']=false;
$dictionary['Opportunity']['fields']['description']['rows']='4';
$dictionary['Opportunity']['fields']['description']['cols']='60';

 

 // created: 2014-05-14 05:28:35
$dictionary['Opportunity']['fields']['sales_stage']['default']='Success';
$dictionary['Opportunity']['fields']['sales_stage']['len']=100;
$dictionary['Opportunity']['fields']['sales_stage']['comments']='Indication of progression towards closure';
$dictionary['Opportunity']['fields']['sales_stage']['merge_filter']='disabled';
$dictionary['Opportunity']['fields']['sales_stage']['calculated']=false;
$dictionary['Opportunity']['fields']['sales_stage']['dependency']=false;

 

 // created: 2014-06-04 14:22:03
$dictionary['Opportunity']['fields']['total_in_invoice']['options']='numeric_range_search_dom';
$dictionary['Opportunity']['fields']['total_in_invoice']['enable_range_search']='1';

 

    //Custom field - By Lap Nguyen
    $dictionary["Opportunity"]["fields"]["contact_name"] = array (
        'name'=>'contact_name',
        'rname'=>'name',
        'id_name'=>'contact_id',
        'vname'=>'LBL_CONTACT_NAME',
        'type'=>'relate',
        'link'=>'contacts',
        'table'=>'contacts',
        'isnull'=>'true',
        'module'=>'Contacts',
        'dbType' => 'varchar',
        'len' => '255',
        'source'=>'non-db',
        'required' => true,
        'importable' => 'true',
    );
    $dictionary["Opportunity"]["fields"]["contact_id"] = array (
        'name' => 'contact_id',
        'vname' => 'LBL_CONTACT_ID',
        'type' => 'id',
        'source'=>'non-db',
        'audited'=>true,
    );
    $dictionary["Opportunity"]["fields"]["oder_id"] = array (
        'required' => false,
        'name' => 'oder_id',
        'vname' => 'LBL_ORDER_ID',
        'type' => 'varchar',
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
        'len' => '100',
        'size' => '20',
    );
    $dictionary["Opportunity"]["fields"]["active_since"] = array (
        'required' => false,
        'name' => 'active_since',
        'vname' => 'LBL_ACTIVE_SINCE',
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
        'enable_range_search' => false,
        'display_default' => 'now',
    );
    $dictionary["Opportunity"]["fields"]["tax_rate"] = array (
        'required' => false,
        'name' => 'tax_rate',
        'vname' => 'LBL_TAX_RATE',
        'type' => 'decimal',
        'len' => 5,
        'precision' => 2,
    );
    $dictionary["Opportunity"]["fields"]["discount"] = array (
        'required' => false,
        'name' => 'discount',
        'vname' => 'LBL_DISCOUNT',
        'type' => 'decimal',
        'len' => 5,
        'precision' => 2,
    );
  $dictionary["Opportunity"]["fields"]["total_hours"] = array (
            'required' => false,
            'name' => 'total_hours',
            'vname' => 'LBL_TOTAL_HOURS',
            'type' => 'decimal',
            'len' => '13',
            'precision' => '2',
        );
    $dictionary["Opportunity"]["fields"]["total_in_invoice"] = array (
        'required' => true,
        'name' => 'total_in_invoice',
        'vname' => 'LBL_TOTAL_IN_INVOICE',
        'type' => 'currency',
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
        'len' => 26,
        'size' => '20',
        'options' => 'numeric_range_search_dom',
        'enable_range_search' => true,
        'precision' => 2,
    );
    $dictionary["Opportunity"]["fields"]["expire_date"] = array (
        'name' => 'expire_date',
        'vname' => 'LBL_EXPIRE_DATE',
        'type' => 'date',
        'enable_range_search' => true,
        'options' => 'date_range_search_dom',
        'massupdate' => 0,
    );
    $dictionary["Opportunity"]["fields"]["publish_invoice_date"] = array (
        'name' => 'publish_invoice_date',
        'vname' => 'LBL_PUBLISH_INVOICE_DATE',
        'type' => 'date',
        'reportable' => false,
        'display_default' => 'now',
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["payment_date"] = array (
        'name' => 'payment_date',
        'vname' => 'LBL_PAYMENT_DATE',
        'type' => 'date',
        'reportable' => false,
        'display_default' => 'now',
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["ispayment"] = array (
        'required' => false,
        'name' => 'ispayment',
        'vname' => 'LBL_ISPAYMENT',
        'type' => 'bool',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
    ); 

    $dictionary["Opportunity"]["fields"]["added_to_class"] = array (
        'required' => false,
        'name' => 'added_to_class',
        'vname' => 'LBL_ADDED_TO_CLASS',
        'type' => 'bool',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
    );

    $dictionary["Opportunity"]["fields"]["isinvoice"] = array (
        'required' => false,
        'name' => 'isinvoice',
        'vname' => 'LBL_ISINVOICE',
        'type' => 'bool',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
    );
    // create field for Payment panel - Field Non-DB
    $dictionary["Opportunity"]["fields"]["payment_method"] = array (
        'required' => false,
        'name' => 'payment_method',
        'vname' => 'LBL_PAYMENT_METHOD',
        'type' => 'radioenum',
        'massupdate' => 0,
        'default' => 'Cash',
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 100,
        'size' => '20',
        'options' => 'menthod_payments_list',
        'studio' => 'visible',
        'source' => 'non-db',
    );

    $dictionary["Opportunity"]["fields"]["status"] = array (
        'required' => false,
        'name' => 'status',
        'vname' => 'LBL_STATUS',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => 'Paid',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => true,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 100,
        'size' => '20',
        'options' => 'status_payments_list',
        'studio' => 'visible',
        'dependency' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["card_type"] = array (
        'required' => false,
        'name' => 'card_type',
        'vname' => 'LBL_CARD_TYPE',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => '',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 100,
        'size' => '20',
        'options' => 'card_type_payments_list',
        'studio' => 'visible',
        'dependency' => false,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["card_name"] = array (
        'required' => false,
        'name' => 'card_name',
        'vname' => 'LBL_CARD_NAME',
        'type' => 'varchar',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => '255',
        'size' => '20',
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["card_number"] = array (
        'required' => false,
        'name' => 'card_number',
        'vname' => 'LBL_CARD_NUMBER',
        'type' => 'int',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => '255',
        'size' => '20',
        'enable_range_search' => false,
        'disable_num_format' => '1',
        'min' => false,
        'max' => false,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["expiration_date"] = array (
        'required' => false,
        'name' => 'expiration_date',
        'vname' => 'LBL_EXPIRATION_DATE',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => 'January',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 100,
        'size' => '20',
        'options' => 'expiration_date_payment_list',
        'studio' => 'visible',
        'dependency' => false,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["expiration_year"] = array (
        'required' => false,
        'name' => 'expiration_year',
        'vname' => 'LBL_EXPIRATION_YEAR',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => '2014',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 100,
        'size' => '20',
        'options' => 'year_list',
        'studio' => 'visible',
        'dependency' => false,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["remaining"] = array (
        'required' => false,
        'studio' => 'visible',
        'name' => 'remaining',
        'vname' => 'LBL_REMAINING',
        'type' => 'currency',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 2,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["payment_amount"] = array (
        'required' => false,
        'studio' => 'visible',
        'name' => 'payment_amount',
        'vname' => 'LBL_PAYMENT_AMOUNT',
        'type' => 'currency',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 6,
        'source' => 'non-db',
    );        
    $dictionary["Opportunity"]["fields"]["payment_balance"] = array (
        'required' => false,
        'studio' => 'visible',
        'name' => 'payment_balance',
        'vname' => 'LBL_PAYMENT_BALANCE',
        'type' => 'currency',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 6,
        'source' => 'non-db',
    );           
    $dictionary["Opportunity"]["fields"]["taxrate_id"] = array (
        'name' => 'taxrate_id',
        'vname' => 'LBL_TAXRATE_ID',
        'type' => 'id', 
    );
    $dictionary["Opportunity"]["fields"]["card_rate"] = array ( 
        'required' => false,
        'name' => 'card_rate',
        'vname' => 'LBL_CARD_RATE',
        'type' => 'decimal',
        'len' => 5,
        'precision' => 2,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["card_amount"] = array (
        'required' => false,
        'name' => 'card_amount',
        'vname' => 'LBL_CARD_AMOUNT',
        'type' => 'currency',
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
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 2,
        'source' => 'non-db',
    );
    //Add Field 14-05 - By Lap Nguyen
    $dictionary["Opportunity"]["fields"]["loan_type"] = array (
        'required' => false,
        'name' => 'loan_type',
        'vname' => 'LBL_LOAN_TYPE',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => '',
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 50,
        'size' => '20',
        'options' => 'loan_type_list',
        'studio' => 'visible',
        'dependency' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["bank_fee_rate"] = array (
        'required' => false,
        'name' => 'bank_fee_rate',
        'vname' => 'LBL_BANK_FEE_RATE',
        'type' => 'decimal',
        'len' => 5,
        'precision' => 2,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["loan_fee_rate"] = array (
        'required' => false,
        'name' => 'loan_fee_rate',
        'vname' => 'LBL_LOAN_FEE_RATE',
        'type' => 'decimal',
        'len' => 5,
        'precision' => 2,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["bank_fee_amount"] = array (
        'name' => 'bank_fee_amount',
        'vname' => 'LBL_BANK_FEE_AMOUNT',
        'type' => 'currency',
        'len' => 26,
        'precision' => 2,
        'reportable' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["loan_fee_amount"] = array (
        'name' => 'loan_fee_amount',
        'vname' => 'LBL_LOAN_FEE_AMOUNT',
        'type' => 'currency',
        'len' => 26,
        'precision' => 2,
        'reportable' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["bank_name"] = array (
        'required' => false,
        'name' => 'bank_name',
        'vname' => 'LBL_BANK_NAME',
        'type' => 'enum',
        'default' => '',
        'len' => 50,
        'size' => '20',
        'reportable' => false,
        'options' => 'bank_name_list',
        'source' => 'non-db',
    );

    //Flex-relate field: Lead; Account; Student - By Nguyen Huu Lap
    $dictionary['Opportunity']['fields']['parent_name'] = array(
        'name' => 'parent_name',
        'vname' => 'LBL_ACCOUNT_NAME',
        'type' => 'parent',
        'massupdate' => 0,
        'dbtype' => 'varchar',
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'len' => 100,
        'size' => '20',
        'options' => 'flexparent_options',
        'studio' => 'visible',
        'type_name' => 'parent_type',
        'id_name' => 'parent_id',
        'parent_type' => 'flexparent_options',
    );
    $dictionary['Opportunity']['fields']['parent_type'] = array(
        'required' => false,
        'name' => 'parent_type',
        'vname' => 'LBL_PARENT_TYPE',
        'type' => 'parent_type',
        'massupdate' => 0,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => 0,
        'audited' => false,
        'reportable' => true,
        'len' => 255,
        'size' => '20',
        'default' => 'Leads',
        'dbType' => 'varchar',
        'studio' => 'hidden',
    );
    $dictionary['Opportunity']['fields']['parent_id'] = array(
        'required' => false,
        'name' => 'parent_id',
        'vname' => 'LBL_PARENT_ID',
        'type' => 'id',
        'massupdate' => 0,
        'comments' => '',
        'help' => '',
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => 0,
        'audited' => false,
        'reportable' => true,
        'len' => 36,
        'size' => '20',
    );
    $dictionary["Opportunity"]["fields"]["discount_amount"] = array (
        'required' => false,
        'name' => 'discount_amount',
        'vname' => 'LBL_DISCOUNT_AMOUNT',
        'type' => 'currency',
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
        'len' => 26,
        'size' => '20',
        'enable_range_search' => true,
        'options' => 'numberic_range_search_dom',
        'precision' => 2,
    );
    //Add Field 14/05 - By Lap Nguyen
    $dictionary["Opportunity"]["fields"]["tax_amount"] = array (
        'required' => false,
        'studio' => 'visible',
        'name' => 'tax_amount',
        'vname' => 'LBL_TAX_AMOUNT',
        'type' => 'currency',
        'massupdate' => 0,
        'no_default' => false,
        'comments' => '',
        'help' => '',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'len' => 26,
        'size' => '20',
        'enable_range_search' => true,
        'options' => 'numberic_range_search_dom',
        'precision' => 6,
    );
    //Invoice
    $dictionary["Opportunity"]["fields"]["is_company"] = array (
        'required' => false,
        'name' => 'is_company',
        'vname' => 'LBL_IS_COMPANY',
        'type' => 'bool',
        'importable' => 'false',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["company_name"] = array (
        'required' => false,
        'name' => 'company_name',
        'vname' => 'LBL_COMPANY_NAME',
        'type' => 'varchar',
        'len' => '100',
        'reportable' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["tax_code"] = array (
        'required' => false,
        'name' => 'tax_code',
        'vname' => 'LBL_TAX_CODE',
        'type' => 'varchar',
        'len' => '50',
        'reportable' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["company_address"] = array (
        'required' => false,
        'name' => 'company_address',
        'vname' => 'LBL_COMPANY_ADDRESS',
        'type' => 'varchar',
        'len' => '200',
        'reportable' => false,
        'source' => 'non-db',
    );

    $dictionary["Opportunity"]["fields"]["marketing_fee"] = array (
        'required' => false,
        'name' => 'marketing_fee',
        'vname' => 'LBL_MARKETING_FEE',
        'type' => 'currency',
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
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 2,
    );
    $dictionary["Opportunity"]["fields"]["center_fee"] = array (
        'required' => false,
        'name' => 'center_fee',
        'vname' => 'LBL_CENTER_FEE',
        'type' => 'currency',
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
        'len' => 26,
        'size' => '20',
        'enable_range_search' => false,
        'precision' => 2,
    );
    //Enrollment
    $dictionary["Opportunity"]["fields"]["free_balance"] = array (
        'name' => 'free_balance',
        'vname' => 'LBL_FREE_BALANCE',
        'type' => 'currency',
        'dbType' => 'double',
        'default' => 0,
        'duplicate_merge'=>'disabled',
        'reportable' => false,
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["payment_type"] = array (
        'name' => 'payment_type',
        'vname' => 'LBL_PAYMENT_TYPE',
        'type' => 'enum',
        'len' => '100',
        'default' => '',
        'options' => 'payment_type_dom',
        'reportable' => false,
        'source' => 'non-db',
    );
    //Related Field  - Fisrt Aproach
    $dictionary["Opportunity"]["fields"]["username_approached"] = array (
        'name' => 'username_approached',
        'rname' => 'name',
        'id_name' => 'user_apprached_id',
        'vname' => 'LBL_USERNAME_APPROACH',
        'type' => 'relate',
        'link'=>'users',
        'table' => 'users',
        'join_name'=>'users',
        'module' => 'Users',
        'dbType' => 'varchar',
        'len' => 100,
        'source'=>'non-db',
    );
    $dictionary["Opportunity"]["fields"]["user_apprached_id"] = array (
        'name'=>'user_apprached_id',
        'type' => 'relate',
        'dbType' => 'id',
        'rname' => 'id',
        'module' => 'Users',
    );
    $dictionary["Opportunity"]["fields"]["users"] = array (
        'name' => 'users',
        'type' => 'link',
        'source'=>'non-db',
    );
    //create by leduytan - new field

    $dictionary["Opportunity"]["fields"]["current_stage"] = array (
        'required' => false,
        'name' => 'current_stage',
        'vname' => 'LBL_CURRENT_STAGE',
        'type'=>'enum',
        'options'=>'stage_score_list',
        'source' => 'non-db',
    );   
    $dictionary["Opportunity"]["fields"]["current_level"] = array (
        'required' => false,
        'name' => 'current_level',
        'vname' => 'LBL_CURRENT_LEVEL',
        'type'=>'enum',
        'options'=>'level_score_list',
        'source' => 'non-db',
    );
    $dictionary["Opportunity"]["fields"]["interval"] = array (
        'required' => false,
        'name' => 'interval',
        'vname' => 'LBL_INTERVAL',
        'type'=>'varchar',
        'source' => 'non-db',
    );
    // end field
   $dictionary["Opportunity"]["fields"]["sponsor_code"] = array (
        'name' => 'sponsor_code',
        'rname' => 'name',
        'id_name' => 'sponsor_id',
        'vname' => 'LBL_SPONSOR_CODE',
        'type' => 'relate',
        'link'=>'sponsors',
        'table' => 'c_sponsors',
        'join_name'=>'c_sponsors',
        'module' => 'C_Sponsors',
        'dbType' => 'varchar',
        'len' => 50,
    );
    $dictionary["Opportunity"]["fields"]["sponsor_id"] = array (
        'name'=>'sponsor_id',
        'type' => 'relate',
        'dbType' => 'id',
        'rname' => 'id',
        'module' => 'C_Sponsors',
    );
    $dictionary["Opportunity"]["fields"]["sponsors"] = array (
        'name' => 'sponsors',
        'type' => 'link',
        'source'=>'non-db',
    );
    
    
    //Left side Enrollment (1 - n ) Delivery
    $dictionary['Opportunity']['fields']['deliveries'] = array(
        'name' => 'deliveries',
        'type' => 'link',
        'relationship' => 'enrollment_delivery',
        'source' => 'non-db',
        'module'=>'C_DeliveryRevenue',
        'bean_name'=>'C_DeliveryRevenue',
        'vname' => 'LBL_DELIVERY',
    );

    $dictionary['Opportunity']['relationships']['enrollment_delivery'] = array(
        'lhs_module' => 'Opportunities',
        'lhs_table' => 'opportunities',
        'lhs_key' => 'id',
        'rhs_module' => 'C_DeliveryRevenue',
        'rhs_table' => 'c_deliveryrevenue',
        'rhs_key' => 'enrollment_id',
        'relationship_type' => 'one-to-many'
    );
    
     //Left side Enrollment (1 - n ) Carryforward
    $dictionary['Opportunity']['fields']['carry_forwards'] = array(
        'name' => 'carry_forwards',
        'type' => 'link',
        'relationship' => 'enrollment_carry',
        'source' => 'non-db',
        'module'=>'C_Carryforward',
        'bean_name'=>'c_carryforward',
        'vname' => 'LBL_DELIVERY',
    );

    $dictionary['Opportunity']['relationships']['enrollment_carry'] = array(
        'lhs_module' => 'Opportunities',
        'lhs_table' => 'opportunities',
        'lhs_key' => 'id',
        'rhs_module' => 'C_Carryforward',
        'rhs_table' => 'c_carryforward',
        'rhs_key' => 'enrollment_id',
        'relationship_type' => 'one-to-many'
    );
    
    
      












?>