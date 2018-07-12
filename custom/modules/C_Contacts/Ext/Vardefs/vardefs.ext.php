<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2017-08-09 23:33:27
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1"] = array (
  'name' => 'accounts_c_contacts_1',
  'type' => 'link',
  'relationship' => 'accounts_c_contacts_1',
  'source' => 'non-db',
  'module' => 'Accounts',
  'bean_name' => 'Account',
  'side' => 'right',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_ACCOUNTS_TITLE',
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link-type' => 'one',
);
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1_name"] = array (
  'name' => 'accounts_c_contacts_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_ACCOUNTS_TITLE',
  'save' => true,
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link' => 'accounts_c_contacts_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'name',
);
$dictionary["C_Contacts"]["fields"]["accounts_c_contacts_1accounts_ida"] = array (
  'name' => 'accounts_c_contacts_1accounts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_ACCOUNTS_C_CONTACTS_1_FROM_C_CONTACTS_TITLE_ID',
  'id_name' => 'accounts_c_contacts_1accounts_ida',
  'link' => 'accounts_c_contacts_1',
  'table' => 'accounts',
  'module' => 'Accounts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-08-06 14:27:38
$dictionary["C_Contacts"]["fields"]["c_contacts_contacts_1"] = array (
  'name' => 'c_contacts_contacts_1',
  'type' => 'link',
  'relationship' => 'c_contacts_contacts_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'vname' => 'LBL_C_CONTACTS_CONTACTS_1_FROM_C_CONTACTS_TITLE',
  'id_name' => 'c_contacts_contacts_1c_contacts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2017-08-10 20:47:56
$dictionary["C_Contacts"]["fields"]["c_contacts_contracts_1"] = array (
  'name' => 'c_contacts_contracts_1',
  'type' => 'link',
  'relationship' => 'c_contacts_contracts_1',
  'source' => 'non-db',
  'module' => 'Contracts',
  'bean_name' => 'Contract',
  'vname' => 'LBL_C_CONTACTS_CONTRACTS_1_FROM_CONTRACTS_TITLE',
  'id_name' => 'c_contacts_contracts_1c_contacts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-10-20 14:44:48
$dictionary["C_Contacts"]["fields"]["c_contacts_leads_1"] = array (
  'name' => 'c_contacts_leads_1',
  'type' => 'link',
  'relationship' => 'c_contacts_leads_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'vname' => 'LBL_C_CONTACTS_LEADS_1_FROM_C_CONTACTS_TITLE',
  'id_name' => 'c_contacts_leads_1c_contacts_ida',
  'link-type' => 'many',
  'side' => 'left',
);


 // created: 2015-09-07 09:26:20
$dictionary['C_Contacts']['fields']['address']['rows']='3';
$dictionary['C_Contacts']['fields']['address']['cols']='50';
$dictionary['C_Contacts']['fields']['address']['required']=false;

 

 // created: 2014-05-12 17:48:32
$dictionary['C_Contacts']['fields']['description']['comments']='Full text of the note';
$dictionary['C_Contacts']['fields']['description']['merge_filter']='disabled';
$dictionary['C_Contacts']['fields']['description']['calculated']=false;
$dictionary['C_Contacts']['fields']['description']['cols']='50';

 
?>