<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
* By installing or using this file, you are confirming on behalf of the entity
* subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
* the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
* http://www.sugarcrm.com/master-subscription-agreement
*
* If Company is not bound by the MSA, then by installing or using this file
* you are agreeing unconditionally that Company will be bound by the MSA and
* certifying that you have authority to bind Company accordingly.
*
* Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
********************************************************************************/

$dictionary['Contact'] = array('table' => 'contacts', 'audited'=>true,

    'unified_search' => true, 'full_text_search' => true, 'unified_search_default_enabled' => true, 'duplicate_merge'=>true, 'fields' =>
    array (

        'email_and_name1' =>
        array (
            'name' => 'email_and_name1',
            'rname' => 'email_and_name1',
            'vname' => 'LBL_NAME',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '510',
            'importable' => 'false',
        ),
        'lead_source' =>
        array (
            'name' => 'lead_source',
            'vname' => 'LBL_LEAD_SOURCE',
            'type' => 'enum',
            'options' => 'lead_source_list',
            'len' => '255',
            'comment' => 'How did the contact come about',
            'merge_filter' => 'enabled',
            'required' => true,
            'importable' => 'required',
            'audited'=>true,
            'massupdate' => true,
        ),
        'activity' =>
        array (
            'name' => 'activity',
            'vname' => 'LBL_ACTIVITY',
            'type' => 'enum',
            'options'=> 'activity_source_list',
            'no_default' => false,
            'len' => '100',
            'audited'=>false,
            'massupdate' => false,
            'required' => false,
        ),
        'category' =>
        array (
            'name' => 'category',
            'vname' => 'LBL_CATEGORY',
            'type' => 'enum',
            'options'=> 'category_list',
            'no_default' => false,
            'len' => '100',
            'massupdate' => false,
            'required' => false,
        ),
        'birthmonth' =>
        array (
            'required' => false,
            'name' => 'birthmonth',
            'vname' => 'LBL_BIRTH_MONTH',
            'type' => 'enum',
            'massupdate' => 0,
            'default' => '',
            'no_default' => false,
            'comments' => '',
            'help' => 'Birth Month',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 5,
            'size' => '20',
            'options' => 'birth_month_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'contact_id' =>
        array (
            'required' => false,
            'name' => 'contact_id',
            'vname' => 'LBL_CONTACT_ID',
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
            'unified_search' => true,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 36,
            'size' => '20',
        ),
        'opportunity_role_fields' =>
        array (
            'name' => 'opportunity_role_fields',
            'rname' => 'id',
            'relationship_fields'=>array('id' => 'opportunity_role_id', 'contact_role' => 'opportunity_role'),
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'relate',
            'link' => 'opportunities',
            'link_type' => 'relationship_info',
            'join_link_name' => 'opportunities_contacts',
            'source' => 'non-db',
            'importable' => 'false',
            'duplicate_merge'=> 'disabled',
            'studio' => false,
            'massupdate' => 0,
        ),
        'opportunity_role_id' =>
        array(
            'name' => 'opportunity_role_id',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITY_ROLE_ID',
            'studio' => array('listview' => false),
        ),
        //bug 42902
        'email'=> array(
            'name' => 'email',
            'type' => 'email',
            'query_type' => 'default',
            'source' => 'non-db',
            'operator' => 'subquery',
            'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
            'db_field' => array(
                'id',
            ),
            'vname' =>'LBL_ANY_EMAIL',
            'studio' => array('visible'=>false, 'searchview'=>true),
            'unified_search' => true,
        ),
        'opportunity_role' =>
        array(
            'name' => 'opportunity_role',
            'type' => 'enum',
            'source' => 'non-db',
            'vname' => 'LBL_OPPORTUNITY_ROLE',
            'options' => 'opportunity_relationship_type_dom',
            'massupdate' => false,
        ),
        'reports_to_id'=>
        array(
            'name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO_ID',
            'type' => 'id',
            'required'=>false,
            'reportable'=>false,
            'comment' => 'The contact this contact reports to'
        ),
        'report_to_name' =>
        array (
            'name' => 'report_to_name',
            'rname' => 'last_name',
            'id_name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO',
            'type' => 'relate',
            'link' => 'reports_to_link',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => 'id',
            'reportable'=>false,
            'source' => 'non-db',
            'massupdate' => 0,
        ),
        'birthdate' =>
        array (
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'comment' => 'The birthdate of the contact',
            'unified_search' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'portal_name' =>
        array (
            'name' => 'portal_name',
            'vname' => 'LBL_PORTAL_NAME',
            'type' => 'varchar',
            'len' => '255',
            'group'=>'portal',
            'comment' => 'Name as it appears in the portal',
            'studio' => array(
                'portaleditview' => false,
                'portaldetailview' => false,
                'portallistview' => false,
            ),
        ),
        'portal_active' =>
        array (
            'name' => 'portal_active',
            'vname' => 'LBL_PORTAL_ACTIVE',
            'type' => 'bool',
            'default' => '1',
            'group'=>'portal',
            'comment' => 'Indicator whether this contact is a portal user'
        ),
        'is_stopped' =>
        array (
            'name' => 'is_stopped',
            'vname' => 'Is Stopped',
            'type' => 'bool',
            'default' => '0',
        ),
        'stopped_date' =>
        array (
            'name' => 'stopped_date',
            'vname' => 'Stopped Date',
            'massupdate' => false,
            'type' => 'date',
            'unified_search' => true,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),

        'portal_password' =>
        array (
            'name' => 'portal_password',
            'vname' => 'LBL_USER_PASSWORD',
            'type' => 'password',
            'dbType' => 'varchar',
            'len' => '255',
            'group'=>'portal',
            'reportable' => false,
            'studio' => array(
                'listview' => false,
                'portaleditview' => false,
                'portaldetailview' => false,
                'portallistview' => false,
            ),
        ),
        'portal_password1' =>
        array (
            'name' => 'portal_password1',
            'vname' => 'LBL_USER_PASSWORD',
            'type' => 'varchar',
            'source' => 'non-db',
            'len' => '255',
            'group'=>'portal',
            'reportable' => false,
            'importable' => 'false',
            'studio' => array(
                'listview' => false,
                'portaleditview' => false,
                'portaldetailview' => false,
                'portallistview' => false,
            ),
        ),
        'portal_app' =>
        array (
            'name' => 'portal_app',
            'vname' => 'LBL_PORTAL_APP',
            'type' => 'varchar',
            'group'=>'portal',
            'len' => '255',
            'comment' => 'Reference to the portal'
        ),
        'preferred_language' =>
        array(
            'name' => 'preferred_language',
            'type' => 'enum',
            'default' => 'en_us',
            'vname' => 'LBL_PREFERRED_LANGUAGE',
            'options' => 'available_language_dom',
            'popupHelp' => 'LBL_LANG_PREF_TOOLTIP',
            'massupdate' => false,
        ),
        'accounts' =>
        array (
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'accounts_contacts',
            'link_type' => 'one',
            'source' => 'non-db',
            'vname' => 'LBL_ACCOUNT',
            'duplicate_merge'=> 'disabled',
        ),
        //Add Relationship Student - Loyalty
        'loyalty_link'=>array(
            'name' => 'loyalty_link',
            'type' => 'link',
            'relationship' => 'student_loyaltys',
            'module' => 'J_Loyalty',
            'bean_name' => 'J_Loyalty',
            'source' => 'non-db',
            'vname' => 'LBL_LOYALTY',
        ),
        //Add Relationship Student - Membership
        'membership_link'=>array(
            'name' => 'membership_link',
            'type' => 'link',
            'relationship' => 'student_membership',
            'module' => 'C_Memberships',
            'bean_name' => 'C_Memberships',
            'source' => 'non-db',
            'vname' => 'LBL_MEMBERSHIP',
        ),
        //Custom Relationship JUNIOR. Student  - Payment Detail (1-n)  By Lap Nguyen
        'payment_detail_link'=>array(
            'name' => 'payment_detail_link',
            'type' => 'link',
            'relationship' => 'student_paymentdetail',
            'module' => 'J_PaymentDetail',
            'bean_name' => 'J_PaymentDetail',
            'source' => 'non-db',
            'vname' => 'LBL_PAYMENTDETAIL',
        ),
        'reports_to_link' =>
        array (
            'name' => 'reports_to_link',
            'type' => 'link',
            'relationship' => 'contact_direct_reports',
            'link_type' => 'one',
            'side' => 'right',
            'source' => 'non-db',
            'vname' => 'LBL_REPORTS_TO',
        ),
        'opportunities' =>
        array (
            'name' => 'opportunities',
            'type' => 'link',
            'relationship' => 'opportunities_contacts',
            'source' => 'non-db',
            'module' => 'Opportunities',
            'bean_name' => 'Opportunity',
            'vname' => 'LBL_OPPORTUNITIES',
        ),
        'email_addresses' =>
        array (
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => 'contacts_email_addresses',
            'module' => 'EmailAddress',
            'bean_name'=>'EmailAddress',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable'=>false,
            'rel_fields' => array('primary_address' => array('type'=>'bool')),
            'unified_search'=>true,
        ),
        'email_addresses_primary' =>
        array (
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => 'contacts_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge'=> 'disabled',
            'unified_search' => true,
        ),
        'bugs' =>
        array (
            'name' => 'bugs',
            'type' => 'link',
            'relationship' => 'contacts_bugs',
            'source' => 'non-db',
            'vname' => 'LBL_BUGS',
        ),
        'calls' =>
        array (
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'calls_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'cases' =>
        array (
            'name' => 'cases',
            'type' => 'link',
            'relationship' => 'contacts_cases',
            'source' => 'non-db',
            'vname' => 'LBL_CASES',
        ),
        'direct_reports'=>
        array (
            'name' => 'direct_reports',
            'type' => 'link',
            'relationship' => 'contact_direct_reports',
            'source' => 'non-db',
            'vname' => 'LBL_DIRECT_REPORTS',
        ),
        'emails'=>
        array (
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_contacts_rel',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
            'unified_search' => true,
        ),
        'documents'=>
        array (
            'name' => 'documents',
            'type' => 'link',
            'relationship' => 'documents_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_DOCUMENTS_SUBPANEL_TITLE',
        ),
        'leads'=>
        array (
            'name' => 'leads',
            'type' => 'link',
            'relationship' => 'contact_leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
        ),
        'products'=>
        array (
            'name' => 'products',
            'type' => 'link',
            'link_file' => 'modules/Products/AccountLink.php',
            'link_class' => 'AccountLink',
            'relationship' => 'contact_products',
            'source' => 'non-db',
            'vname' => 'LBL_PRODUCTS_TITLE',
        ),
        'contracts' => array (
            'name' => 'contracts',
            'type' => 'link',
            'vname' => 'LBL_CONTRACTS',
            'relationship' => 'contracts_contacts',
            'source' => 'non-db',
            'unified_search' => false,
        ),
        'meetings'=>
        array (
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'meetings_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'notes'=>
        array (
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'contact_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'project'=>
        array (
            'name' => 'project',
            'type' => 'link',
            'relationship' => 'projects_contacts',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTS',
        ),

        'project_resource' => array(
            'name' => 'project_resource',
            'type' => 'link',
            'relationship' => 'projects_contacts_resources',
            'source' => 'non-db',
            'vname' => 'LBL_PROJECTS_RESOURCES',
        ),


        'quotes' => array(
            'name' => 'quotes',
            'type' => 'link',
            'relationship' => 'quotes_contacts_shipto',
            'source' => 'non-db',
            'ignore_role' => 'true',
            'module' => 'Quotes',
            'bean_name' => 'Quote',
            'vname' => 'LBL_QUOTES_SHIP_TO',
        ),

        'billing_quotes' => array(
            'name' => 'billing_quotes',
            'type' => 'link',
            'relationship' => 'quotes_contacts_billto',
            'source' => 'non-db',
            'ignore_role' => 'true',
            'module' => 'Quotes',
            'bean_name' => 'Quote',
            'vname' => 'LBL_QUOTES_BILL_TO',
        ),

        'tasks'=>
        array (
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'contact_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'tasks_parent' => array(
            'name' => 'tasks_parent',
            'type' => 'link',
            'relationship' => 'contact_tasks_parent',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
            'reportable' => false
        ),
        'user_sync'=>
        array (
            'name' => 'user_sync',
            'type' => 'link',
            'relationship' => 'contacts_users',
            'source' => 'non-db',
            'vname' => 'LBL_USER_SYNC',
        ),
        'created_by_link' =>
        array (
            'name' => 'created_by_link',
            'type' => 'link',
            'relationship' => 'contacts_created_by',
            'vname' => 'LBL_CREATED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'modified_user_link' =>
        array (
            'name' => 'modified_user_link',
            'type' => 'link',
            'relationship' => 'contacts_modified_user',
            'vname' => 'LBL_MODIFIED_BY_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
        ),
        'assigned_user_link' =>
        array (
            'name' => 'assigned_user_link',
            'type' => 'link',
            'relationship' => 'contacts_assigned_user',
            'vname' => 'LBL_ASSIGNED_TO_USER',
            'link_type' => 'one',
            'module' => 'Users',
            'bean_name' => 'User',
            'source' => 'non-db',
            'rname' => 'user_name',
            'id_name' => 'assigned_user_id',
            'table' => 'users',
            'duplicate_merge'=>'enabled'
        ),
        'campaign_id' =>
        array (
            'name' => 'campaign_id',
            'comment' => 'Campaign that generated lead',
            'vname'=>'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'id_name' => 'campaign_id',
            'type' => 'id',
            //'dbType' => 'char',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            //            'reportable'=>false,
            'massupdate' => false,
            'duplicate_merge'=> 'disabled',
        ),
                'account_name' =>
        array (
            'name' => 'account_name',
            'rname' => 'name',
            'id_name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_NAME',
            'join_name'=>'accounts',
            'type' => 'relate',
            'link' => 'accounts',
            'table' => 'accounts',
            'isnull' => 'true',
            'module' => 'Accounts',
            'dbType' => 'varchar',
            'len' => '255',
            'source' => 'non-db',
            'unified_search' => true,
            'massupdate' => false,
        ),
        'account_id' =>
        array (
            'name' => 'account_id',
            'rname' => 'id',
            'id_name' => 'account_id',
            'vname' => 'LBL_ACCOUNT_ID',
            'type' => 'id',
            'table' => 'accounts',
            'isnull' => 'true',
            'module' => 'Accounts',
            'dbType' => 'id',
            'reportable'=>false,
            'source' => 'non-db',
            'massupdate' => false,
            'duplicate_merge'=> 'disabled',
            'hideacl'=>true,
            'link' => 'accounts',
        ),
        'campaign_name' =>
        array (
            'name' => 'campaign_name',
            'rname' => 'name',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_contacts',
            'isnull' => 'true',
            'reportable'=>false,
            'source'=>'non-db',
            'table' => 'campaigns',
            'id_name' => 'campaign_id',
            'module'=>'Campaigns',
            'duplicate_merge'=>'disabled',
            'comment' => 'The first campaign name for Contact (Meta-data only)',
            'unified_search' => true,
        ),

        'campaigns' =>
        array (
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'contact_campaign_log',
            'module'=>'CampaignLog',
            'bean_name'=>'CampaignLog',
            'source'=>'non-db',
            'vname'=>'LBL_CAMPAIGNLOG',
        ),

        'campaign_contacts' =>
        array (
            'name' => 'campaign_contacts',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGN_CONTACT',
            'relationship' => 'campaign_contacts',
            'source' => 'non-db',
        ),
        'c_accept_status_fields' =>
        array (
            'name' => 'c_accept_status_fields',
            'rname' => 'id',
            'relationship_fields'=>array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'calls',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'duplicate_merge'=> 'disabled',
            'studio' => false,
        ),
        'm_accept_status_fields' =>
        array (
            'name' => 'm_accept_status_fields',
            'rname' => 'id',
            'relationship_fields'=>array('id' => 'accept_status_id', 'accept_status' => 'accept_status_name'),
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'type' => 'relate',
            'link' => 'meetings',
            'link_type' => 'relationship_info',
            'source' => 'non-db',
            'importable' => 'false',
            'hideacl'=>true,
            'duplicate_merge'=> 'disabled',
            'studio' => false,
        ),
        'accept_status_id' =>
        array(
            'name' => 'accept_status_id',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'studio' => array('listview' => false),
        ),
        'accept_status_name' =>
        array(
            'massupdate' => false,
            'name' => 'accept_status_name',
            'type' => 'enum',
            'studio' => 'false',
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
        ),
        'prospect_lists' =>
        array (
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_contacts',
            'module'=>'ProspectLists',
            'source'=>'non-db',
            'vname'=>'LBL_PROSPECT_LIST',
        ),
        'sync_contact' =>
        array (
            'massupdate' => false,
            'name' => 'sync_contact',
            'vname' => 'LBL_SYNC_CONTACT',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Synch to outlook?  (Meta-Data only)',
            'studio' => 'true',
        ),
        'user_id' =>
        array(
            'required' => false,
            'name' => 'user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => '',
            'importable' => 'false',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => 0,
            'audited' => false,
            'reportable' => false,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 36,
            'size' => '20',
            'dbType' => 'id',
            'studio' => 'visible',
        ),
        'full_student_name' =>
        array (
            'required' => false,
            'name' => 'full_student_name',
            'vname' => 'LBL_FULL_NAME',
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
            'len' => '250',
            'size' => '20',
        ),
        // ******** TASK IMPORT ***********************
        'aims_id' =>
        array (
            'required' => false,
            'name' => 'aims_id',
            'vname' => 'LBL_AIMS_ID',
            'type' => 'varchar',
            'massupdate' => 0,
            'no_default' => false,
            'comments' => '',
            'help' => 'AIMS ID Int',
            'importable' => 'true',
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'audited' => false,
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => '10',
            'size' => '20',
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => true,
        ),
        'occupation' =>
        array (
            'required' => false,
            'name' => 'occupation',
            'vname' => 'LBL_OCCUPATION',
            'type' => 'varchar',
            'len' => '120',
        ),
        'payments_corporate' =>
        array (
            'required' => false,
            'name' => 'payments_corporate',
            'vname' => 'LBL_PAYMENT_CORPORATE',
            'type' => 'varchar',
            'len' => '20',
        ),
        'student_type' =>
        array (
            'required' => true,
            'name' => 'student_type',
            'vname' => 'LBL_STUDENT_TYPE',
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
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 50,
            'size' => '20',
            'options' => 'type_team_list',
            'studio' => 'visible',
            'dependency' => false,
        ),

        'identity_number' =>
        array (
            'required' => false,
            'name' => 'identity_number',
            'vname' => 'LBL_INDENTITY_NUMBER',
            'type' => 'varchar',
            'len' => '100',
            'size' => '20',
            'audited' => true,
        ),
        'identity_date' =>
        array (
            'name' => 'identity_date',
            'vname' => 'LBL_INDENTITY_DATE',
            'massupdate' => false,
            'type' => 'date',
            'unified_search' => false,
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'identity_location' =>
        array (
            'required' => false,
            'name' => 'identity_location',
            'vname' => 'LBL_INDENTITY_LOCATION',
            'type' => 'varchar',
            'len' => '200',
            'size' => '20',
        ),
        'place_of_birth' =>
        array (
            'required' => false,
            'name' => 'place_of_birth',
            'vname' => 'LBL_PLACE_OF_BIRTH',
            'type' => 'varchar',
            'len' => '200',
            'size' => '20',
        ),
        'height' =>
        array (
            'required' => false,
            'name' => 'height',
            'vname' => 'LBL_HEIGHT',
            'type' => 'decimal',
            'audited' => false,
            'dbType' => 'varchar',
            'len' => '20',
            'size' => '5',
            'enable_range_search' => false,
            'no_default' => true,
        ),
        'weight' =>
        array (
            'required' => false,
            'name' => 'weight',
            'vname' => 'LBL_WEIGHT',
            'type' => 'decimal',
            'audited' => false,
            'dbType' => 'varchar',
            'len' => '20',
            'size' => '5',
            'enable_range_search' => false,
            'no_default' => true,
        ),
        'graduated_year' =>
        array(
            'required' => false,
            'name' => 'graduated_year',
            'vname' => 'LBL_GRADUATED_YEAR',
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
            'len' => '100',
            'size' => '20',
            'studio' => 'visible',
        ),
        'experience_year' =>
        array (
            'required' => false,
            'name' => 'experience_year',
            'vname' => 'LBL_EXPERIENCE_YEAR',
            'type' => 'decimal',
            'audited' => false,
            'dbType' => 'varchar',
            'len' => '20',
            'size' => '5',
            'enable_range_search' => false,
            'no_default' => true,
        ),
        'facebook' =>
        array (
            'name' => 'facebook',
            'vname' => 'LBL_FACEBOOK',
            'type' => 'varchar',
            'len' => 255,
            'audited' => true,
            'comment' => 'URL of website for the company',
        ),
        'graduated_rate' =>
        array (
            'required' => false,
            'name' => 'graduated_rate',
            'vname' => 'LBL_GRADUATED_RATE',
            'type' => 'enum',
            'massupdate' => 0,
            'no_default' => false,
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
            'options' => 'graduated_rate_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        'graduated_major' =>
        array (
            'required' => false,
            'name' => 'graduated_major',
            'vname' => 'LBL_GRADUATED_MAJOR',
            'type' => 'varchar',
            'len' => '200',
            'size' => '20',
        ),
        'position' =>
        array (
            'required' => false,
            'name' => 'position',
            'vname' => 'LBL_POSITION',
            'type' => 'varchar',
            'len' => '200',
            'size' => '20',
        ),
        'branch' =>
        array (
            'name' => 'branch',
            'vname' => 'LBL_BRANCH',
            'type' => 'varchar',
            'len' => '255',
            'importable' => 'true',
        ),
        'email_parent_1' =>
        array (
            'name' => 'email_parent_1',
            'vname' => 'LBL_EMAIL_PARENT_1',
            'type' => 'varchar',
            'len' => '150',
            'importable' => 'true',
        ),
        'email_parent_2' =>
        array (
            'name' => 'email_parent_2',
            'vname' => 'LBL_EMAIL_PARENT_2',
            'type' => 'varchar',
            'len' => '150',
            'importable' => 'true',
        ),   
    ),
    'indices' => array (
        array(
            'name' => 'idx_cont_last_first_phone',
            'type' => 'index',
            'fields' => array('deleted', 'last_name', 'first_name', 'phone_mobile')
        ),
        array(
            'name' => 'idx_contacts_del_last',
            'type' => 'index',
            'fields' => array('deleted', 'last_name'),
        ),
        array(
            'name' => 'idx_cont_del_reports',
            'type' => 'index',
            'fields'=>array('deleted', 'reports_to_id', 'last_name')
        ),
        array(
            'name' => 'aims_id',
            'type' => 'index',
            'fields'=> array('aims_id'),
        ),
        array(
            'name' => 'idx_reports_to_id',
            'type' => 'index',
            'fields'=> array('reports_to_id'),
        ),
        array(
            'name' => 'idx_del_id_user',
            'type' => 'index',
            'fields'=> array('deleted', 'id', 'assigned_user_id'),
        ),
        array(
            'name' => 'idx_cont_assigned',
            'type' => 'index',
            'fields' => array('assigned_user_id')
        ),
        //	array(
        //		'name' => 'idx_cont_email1',
        //		'type' => 'index',
        //		'fields' => array('email1')
        //	),
        //	array(
        //		'name' => 'idx_cont_email2',
        //		'type' => 'index',
        //		'fields' => array('email2')
        //	),
    ),
    'relationships' => array(
        'contact_direct_reports' => array('lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'reports_to_id',
            'relationship_type' => 'one-to-many'),
        'contact_leads' => array('lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Leads',
            'rhs_table' => 'leads',
            'rhs_key' => 'contact_id',
            'relationship_type' => 'one-to-many'),
        'contact_notes' => array('lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Notes',
            'rhs_table' => 'notes',
            'rhs_key' => 'contact_id',
            'relationship_type' => 'one-to-many'),
        'contact_tasks' => array('lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'contact_id',
            'relationship_type' => 'one-to-many'),
        'contact_tasks_parent' => array('lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Tasks',
            'rhs_table' => 'tasks',
            'rhs_key' => 'parent_id',
            'relationship_type' => 'one-to-many',
            'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Contacts'
        ),
        'contacts_assigned_user' => array('lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'assigned_user_id',
            'relationship_type' => 'one-to-many'),
        'contacts_modified_user' => array('lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'modified_user_id',
            'relationship_type' => 'one-to-many'),
        'contacts_created_by' => array('lhs_module' => 'Users',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'Contacts',
            'rhs_table' => 'contacts',
            'rhs_key' => 'created_by',
            'relationship_type' => 'one-to-many'),
        'contact_products' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'Products',
            'rhs_table' => 'products',
            'rhs_key' => 'contact_id',
            'relationship_type' => 'one-to-many'),

        'contact_campaign_log' => array(
            'lhs_module'		=>	'Contacts',
            'lhs_table'			=>	'contacts',
            'lhs_key' 			=> 	'id',
            'rhs_module'		=>	'CampaignLog',
            'rhs_table'			=>	'campaign_log',
            'rhs_key' 			=> 	'target_id',
            'relationship_type'	=>'one-to-many',
            'relationship_role_column' => 'target_type',
            'relationship_role_column_value' => 'Contacts'
        ),

        //Add Relationship Student - Loyalty
        'student_loyaltys' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'J_Loyalty',
            'rhs_table' => 'j_loyalty',
            'rhs_key' => 'student_id',
            'relationship_type' => 'one-to-many'
        ),
        //Add Relationship Student - Membership
        'student_membership' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'C_Memberships',
            'rhs_table' => 'c_memberships',
            'rhs_key' => 'student_id',
            'relationship_type' => 'one-to-many'
        ),
            //Add Relationship Student - Payment Detail
        'student_paymentdetail' => array(
            'lhs_module' => 'Contacts',
            'lhs_table' => 'contacts',
            'lhs_key' => 'id',
            'rhs_module' => 'J_PaymentDetail',
            'rhs_table' => 'j_paymentdetail',
            'rhs_key' => 'student_id',
            'relationship_type' => 'one-to-many'
        ),
    ),

    //This enables optimistic locking for Saves From EditView
    'optimistic_locking'=>true,
);

VardefManager::createVardef('Contacts','Contact', array('default', 'assignable',
    'team_security',
    'person'));

?>
