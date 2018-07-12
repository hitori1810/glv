<?php
// Add field - 16/07/2014 - created by MTN
$dictionary['Account']['fields']['picture'] = array(
    'name' => 'picture',
    'vname' => 'LBL_PICTURE_FILE',
    'type' => 'image',
    'dbtype' => 'varchar',
    'comment' => 'Picture file',
    'len' => 255,
    'width' => '120',
    'height' => '',
    'border' => '',
);
$dictionary['Account']['fields']['tax_code']=array (
	'required' => true,
	'name' => 'tax_code',
	'vname' => 'LBL_TAX_CODE',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['bank_number']=array (
	'required' => false,
	'name' => 'bank_number',
	'vname' => 'LBL_BANK_NUMBER',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['bank_name']=array (
	'required' => false,
	'name' => 'bank_name',
	'vname' => 'LBL_BANK_NAME',
	'type' => 'varchar',
	'massupdate' => 0,
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['account_id']=array (
	'required' => false,
	'name' => 'account_id',
	'vname' => 'LBL_ACCOUNT_ID',
	'type' => 'varchar',
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
	'calculated' => false,
	'len' => '100',
	'size' => '20',
);

$dictionary['Account']['fields']['account_status']=array (
	'required' => false,
	'name' => 'account_status',
	'vname' => 'LBL_ACCOUNT_STATUS',
	'type' => 'enum',
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
	'len' => 100,
	'size' => '20',
	'options' => 'account_status_list',
	'studio' => 'visible',
	'dependency' => false,
);
//add field type_of_account
$dictionary['Account']['fields']['type_of_account']=array (
	'required' => true,
	'name' => 'type_of_account',
	'vname' => 'LBL_ACCOUNT_TYPE',
	'type' => 'enum',
	'massupdate' => 1,
	'default' => 'Student',
	'no_default' => false,
	'comments' => '',
	'help' => '',
	'importable' => 'true',
	'duplicate_merge' => 'disabled',
	'duplicate_merge_dom_value' => '0',
	'audited' => true,
	'reportable' => true,
	'unified_search' => false,
	'merge_filter' => 'disabled',
	'calculated' => false,
	'len' => 100,
	'size' => '20',
	'options' => 'type_accounts_list',
	'studio' => 'visible',
	'dependency' => false,
);
//Custom Relationship Supplier - J_Inventory  by Quyen.Cao
$dictionary['Account']['relationships']['supplier_j_inventory'] = array(
	'lhs_module'        => 'Accounts',
	'lhs_table'            => 'accounts',
	'lhs_key'            => 'id',
	'rhs_module'        => 'J_Inventory',
	'rhs_table'            => 'j_inventory',
	'rhs_key'            => 'from_supplier_id',
	'relationship_type'    => 'one-to-many',
);

$dictionary['Account']['fields']['j_inventory_from'] = array(
	'name' => 'j_inventory_from',
	'type' => 'link',
	'relationship' => 'supplier_j_inventory',
	'module' => 'J_Inventory',
	'bean_name' => 'J_Inventory',
	'source' => 'non-db',
	'vname' => 'LBL_J_INVENTORY',
);
//END: Custom Relationship


//Custom Relationship Corp/BEP - J_Inventory  by Quyen.Cao

$dictionary['Account']['relationships']['corp_j_inventory'] = array(
	'lhs_module'        => 'Accounts',
	'lhs_table'            => 'accounts',
	'lhs_key'            => 'id',
	'rhs_module'        => 'J_Inventory',
	'rhs_table'            => 'j_inventory',
	'rhs_key'            => 'to_corp_id',
	'relationship_type'    => 'one-to-many',
);

$dictionary['Account']['fields']['j_inventory_to'] = array(
	'name' => 'j_inventory_to',
	'type' => 'link',
	'relationship' => 'corp_j_inventory',
	'module' => 'J_Inventory',
	'bean_name' => 'J_Inventory',
	'source' => 'non-db',
	'vname' => 'LBL_J_INVENTORY',
);
//END: Custom Relationship


//Add Relationship Account - Payment (Xuất hóa đơn Corporate)
$dictionary['Account']['relationships']['account_payments'] = array(
	'lhs_module' => 'Accounts',
	'lhs_table' => 'accounts',
	'lhs_key' => 'id',
	'rhs_module' => 'J_Payment',
	'rhs_table' => 'j_payment',
	'rhs_key' => 'account_id',
	'relationship_type' => 'one-to-many'
);
$dictionary['Account']['fields']['payment_link'] = array(
	'name' => 'payment_link',
	'type' => 'link',
	'relationship' => 'account_payments',
	'module' => 'J_Payment',
	'bean_name' => 'J_Payment',
	'source' => 'non-db',
	'vname' => 'LBL_PAYMENT_NAME',
);
$dictionary['Account']['fields']['email1']['required'] = true;
?>
