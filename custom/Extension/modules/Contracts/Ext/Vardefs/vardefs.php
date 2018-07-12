<?php
// Add field - 15/07/2014 - created by MTN
$dictionary['Contract']['fields']['contract_id']=array (
	'required' => false,
	'name' => 'contract_id',
	'vname' => 'LBL_CONTRACT_ID',
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

$dictionary['Contract']['fields']['account_address']=array (
	'required' => false,
	'name' => 'account_address',
	'vname' => 'LBL_ACCOUNT_ADDRESS',
	'type' => 'text',
    'source' => 'non-db',
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
	'size' => '20',
	'studio' => 'visible',
	'rows' => 4,
	'cols' => 60,
);

$dictionary['Contract']['fields']['account_tax_code']=array (
	'required' => false,
	'name' => 'account_tax_code',
	'vname' => 'LBL_ACCOUNT_TAX_CODE',
	'type' => 'varchar',
    'source' => 'non-db',
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

$dictionary['Contract']['fields']['account_phone']=array (
	'required' => false,
	'name' => 'account_phone',
	'vname' => 'LBL_ACCOUNT_PHONE',
	'type' => 'varchar',
    'source' => 'non-db',
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

$dictionary['Contract']['fields']['account_fax']=array (
	'required' => false,
	'name' => 'account_fax',
	'vname' => 'LBL_ACCOUNT_FAX',
	'type' => 'varchar',
    'source' => 'non-db',
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

$dictionary['Contract']['fields']['account_bank_number']=array (
	'required' => false,
	'name' => 'account_bank_number',
	'vname' => 'LBL_ACCOUNT_BANK_NUMBER',
	'type' => 'varchar',
    'source' => 'non-db',
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

$dictionary['Contract']['fields']['account_bank_name']=array (
	'required' => false,
	'name' => 'account_bank_name',
	'vname' => 'LBL_ACCOUNT_BANK_NAME',
	'type' => 'varchar',
    'source' => 'non-db',
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

$dictionary['Contract']['fields']['payment_date_1']=array (
	'name' => 'payment_date_1',
	'vname' => 'LBL_PAYMENT_DATE_1',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_date_2']=array (
	'name' => 'payment_date_2',
	'vname' => 'LBL_PAYMENT_DATE_2',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_date_3']=array (
	'name' => 'payment_date_3',
	'vname' => 'LBL_PAYMENT_DATE_3',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);

$dictionary['Contract']['fields']['payment_date_4']=array (
	'name' => 'payment_date_4',
	'vname' => 'LBL_PAYMENT_DATE_4',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);

$dictionary['Contract']['fields']['payment_date_5']=array (
	'name' => 'payment_date_5',
	'vname' => 'LBL_PAYMENT_DATE_5',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_date_6']=array (
	'name' => 'payment_date_6',
	'vname' => 'LBL_PAYMENT_DATE_6',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_date_7']=array (
	'name' => 'payment_date_7',
	'vname' => 'LBL_PAYMENT_DATE_7',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_date_8']=array (
	'name' => 'payment_date_8',
	'vname' => 'LBL_PAYMENT_DATE_8',
	'type' => 'date',
	'comment' => '',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);
$dictionary['Contract']['fields']['payment_amount_1']=array (
	'name' => 'payment_amount_1',
	'vname' => 'LBL_PAYMENT_AMOUNT_1',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 1'
);
$dictionary['Contract']['fields']['payment_amount_2']=array (
	'name' => 'payment_amount_2',
	'vname' => 'LBL_PAYMENT_AMOUNT_2',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 2'
);
$dictionary['Contract']['fields']['payment_amount_3']=array (
	'name' => 'payment_amount_3',
	'vname' => 'LBL_PAYMENT_AMOUNT_3',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 3'
);
$dictionary['Contract']['fields']['payment_amount_4']=array (
	'name' => 'payment_amount_4',
	'vname' => 'LBL_PAYMENT_AMOUNT_4',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 4'
);
$dictionary['Contract']['fields']['payment_amount_5']=array (
	'name' => 'payment_amount_5',
	'vname' => 'LBL_PAYMENT_AMOUNT_5',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 5'
);
$dictionary['Contract']['fields']['payment_amount_6']=array (
	'name' => 'payment_amount_6',
	'vname' => 'LBL_PAYMENT_AMOUNT_6',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 6'
);
$dictionary['Contract']['fields']['payment_amount_7']=array (
	'name' => 'payment_amount_7',
	'vname' => 'LBL_PAYMENT_AMOUNT_7',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 7'
);
$dictionary['Contract']['fields']['payment_amount_8']=array (
	'name' => 'payment_amount_8',
	'vname' => 'LBL_PAYMENT_AMOUNT_8',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 8'
);
$dictionary['Contract']['fields']['total_paid']=array (
	'name' => 'total_paid',
	'vname' => 'LBL_TOTAL_PAID',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Payment 8'
);

$dictionary['Contract']['fields']['discount_amount']=array (
	'name' => 'discount_amount',
	'vname' => 'LBL_DISCOUNT_AMOUNT',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '13,0',
	'comment' => 'Discount Amount'
);
$dictionary['Contract']['fields']['total_after_discount']=array (
	'name' => 'total_after_discount',
	'vname' => 'LBL_TOTAL_AFTER_DISCOUNT',
	'dbType' => 'decimal',
	'type' => 'currency',
	'len' => '20,0',
	'comment' => 'Total After Discount'
);

//Bo sung Field Kind Of Course thay the Module Program
$dictionary['Contract']['fields']['kind_of_course']=array (
	'name' => 'kind_of_course',
	'vname' => 'LBL_KIND_OF_COURSE',
	'type' => 'enum',
	'comments' => '',
	'help' => '',
	'default' => '',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'len' => 200,
	'size' => '20',
	'options' => 'kind_of_course_list',
	'studio' => 'visible',
	'required' => true,
);
//END - Field Kind Of Course

//Left side Enrollment (1 - n ) Delivery
$dictionary['Contract']['fields']['contracts_link'] = array(
	'name' => 'contracts_link',
	'type' => 'link',
	'relationship' => 'contract_delivery',
	'source' => 'non-db',
	'module'=>'Contracts',
	'bean_name'=>'Contract',
	'vname' => 'LBL_DELIVERY_CONTRACT',
);

$dictionary['Contract']['relationships']['contract_delivery'] = array(
	'lhs_module' => 'Contracts',
	'lhs_table' => 'contracts',
	'lhs_key' => 'id',
	'rhs_module' => 'C_DeliveryRevenue',
	'rhs_table' => 'c_deliveryrevenue',
	'rhs_key' => 'contract_id',
	'relationship_type' => 'one-to-many'
);


//Right Side (n)
$dictionary['Contract']['fields']['from_contract_id'] = array(
	'name' => 'from_contract_id',
	'vname' => 'LBL_FROM_CONTRACT_ID',
	'type' => 'id',
	'required'=>false,
	'reportable'=>false,
	'comment' => 'The country this Team belong to'
);

$dictionary['Contract']['fields']['from_contract_name'] = array(
	'name' => 'from_contract_name',
	'rname' => 'name',
	'id_name' => 'from_contract_id',
	'vname' => 'LBL_FROM_CONTRACT',
	'type' => 'relate',
	'link' => 'contract_link_1',
	'table' => 'contracts',
	'isnull' => 'true',
	'module' => 'Contracts',
	'dbType' => 'varchar',
	'len' => 'id',
	'reportable'=>true,
	'source' => 'non-db',
);

$dictionary['Contract']['fields']['contract_link_1'] = array(
	'name' => 'contract_link_1',
	'type' => 'link',
	'relationship' => 'contract_contract_move',
	'link_type' => 'one',
	'side' => 'right',
	'source' => 'non-db',
	'vname' => 'LBL_FROM_CONTRACT',
);

//Left side (1)
$dictionary['Contract']['fields']['contract_link_2'] = array(
	'name' => 'contract_link_2',
	'type' => 'link',
	'relationship' => 'contract_contract_move',
	'source' => 'non-db',
	'vname' => 'LBL_FROM_CONTRACT',
);

$dictionary['Contract']['relationships']['contract_contract_move'] = array(
	'lhs_module' => 'Contracts',
	'lhs_table' => 'contracts',
	'lhs_key' => 'id',
	'rhs_module' => 'Contracts',
	'rhs_table' => 'contracts',
	'rhs_key' => 'from_contract_id',
	'relationship_type' => 'one-to-many'
);

$dictionary['Contract']['fields']['closed_date'] = array(
	'name' => 'closed_date',
	'vname' => 'LBL_CLOSED_DATE',
	'type' => 'date',
	'audited' => false,
	'comment' => 'Ngày chốt hợp đồng cũ',
	'enable_range_search' => true,
	'options' => 'date_range_search_dom',
);

 //Right Side (n)
$dictionary['Contract']['fields']['drop_student_id'] = array(
	'name' => 'drop_student_id',
	'vname' => 'LBL_DROP_STUDENT_ID',
	'type' => 'id',
	'required'=>false,
	'reportable'=>false,
	'comment' => 'The country this Team belong to'
);

$dictionary['Contract']['fields']['drop_student_name'] = array(
	'name' => 'drop_student_name',
	'rname' => 'name',
	'id_name' => 'drop_student_id',
	'vname' => 'LBL_DROP_STUDENT',
	'type' => 'relate',
	'link' => 'student_link',
	'table' => 'contacts',
	'isnull' => 'true',
	'module' => 'Contacts',
	'dbType' => 'varchar',
	'len' => 'id',
	'reportable'=>true,
	'source' => 'non-db',
);

$dictionary['Contract']['fields']['student_link'] = array(
	'name' => 'student_link',
	'type' => 'link',
	'link_type' => 'one',
	'side' => 'right',
	'source' => 'non-db',
	'vname' => 'LBL_DROP_STUDENT',
);




