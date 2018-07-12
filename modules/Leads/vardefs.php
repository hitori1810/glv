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

$dictionary['Lead'] = array('table' => 'leads','audited'=>true, 'unified_search' => true, 'full_text_search' => true, 'unified_search_default_enabled' => true, 'duplicate_merge'=>true,
    'comment' => 'Leads are persons of interest early in a sales cycle', 'fields' => array (


        'converted' =>
        array (
            'name' => 'converted',
            'vname' => 'LBL_CONVERTED',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Has Lead been converted to a Contact (and other Sugar objects)',
            'massupdate' => false,
        ),
        'refered_by' =>
        array (
            'name' => 'refered_by',
            'vname' => 'LBL_REFERED_BY',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Identifies who refered the lead',
            'merge_filter' => 'enabled',
        ),
        'lead_source' =>
        array (
            'name' => 'lead_source',
            'vname' => 'LBL_LEAD_SOURCE',
            'type' => 'enum',
            'options'=> 'lead_source_list',
            'no_default' => false,
            'len' => '100',
            'audited'=>true,
            'comment' => 'Lead source (ex: Web, print)',
            'merge_filter' => 'enabled',
            'massupdate' => true,
            'required' => true,
            'importable' => 'true',
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
        'occupation' =>
        array (
            'required' => false,
            'name' => 'occupation',
            'vname' => 'LBL_OCCUPATION',
            'type' => 'varchar',
            'len' => '20',
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
        'lead_source_description' =>
        array (
            'name' => 'lead_source_description',
            'vname' => 'LBL_LEAD_SOURCE_DESCRIPTION',
            'type' => 'text',
            'group'=>'lead_source',
            'comment' => 'Description of the lead source'
        ),
        'utm_source' =>
        array (
            'name' => 'utm_source',
            'vname' => 'LBL_UTM_SOURCE',
            'type' => 'varchar',
            'len' => '100',
        ),
        'utm_medium' =>
        array (
            'name' => 'utm_medium',
            'vname' => 'LBL_UTM_MEDIUM',
            'type' => 'varchar',
            'len' => '100',
        ),
        'branch' =>
        array (
            'name' => 'branch',
            'vname' => 'LBL_BRANCH',
            'type' => 'varchar',
            'len' => '255',
            'importable' => 'true',
        ),
        'status' =>
        array (
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'len' => '100',
            'options' => 'lead_status_dom',
            'audited'=>true,
            'comment' => 'Status of the lead',
            'merge_filter' => 'enabled',
            'massupdate' => false,
        ),
        'status_description' =>
        array (
            'name' => 'status_description',
            'vname' => 'LBL_STATUS_DESCRIPTION',
            'type' => 'text',
            'group'=>'status',
            'comment' => 'Description of the status of the lead'
        ),
        'department' =>
        array (
            'name' => 'department',
            'vname' => 'LBL_DEPARTMENT',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Department the lead belongs to',
            'merge_filter' => 'enabled',
        ),
        'reports_to_id' =>
        array (
            'name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO_ID',
            'type' => 'id',
            'reportable'=>false,
            'comment' => 'ID of Contact the Lead reports to'
        ),
        'report_to_name' =>
        array (
            'name' => 'report_to_name',
            'rname' => 'name',
            'id_name' => 'reports_to_id',
            'vname' => 'LBL_REPORTS_TO',
            'type' => 'relate',
            'table' => 'contacts',
            'isnull' => 'true',
            'module' => 'Contacts',
            'dbType' => 'varchar',
            'len' => 'id',
            'source'=>'non-db',
            'reportable'=>false,
            'massupdate' => false,
        ),
        'reports_to_link' => array (
            'name' => 'reports_to_link',
            'type' => 'link',
            'relationship' => 'lead_direct_reports',
            'link_type'=>'one',
            'side'=>'right',
            'source'=>'non-db',
            'vname'=>'LBL_REPORTS_TO',
            'reportable'=>false
        ),
        'reportees' => array (
            'name' => 'reportees',
            'type' => 'link',
            'relationship' => 'lead_direct_reports',
            'link_type'=>'many',
            'side'=>'left',
            'source'=>'non-db',
            'vname'=>'LBL_REPORTS_TO',
            'reportable'=>false
        ),
        'contacts'=> array(
            'name' => 'contacts',
            'type' => 'link',
            'relationship' => 'contact_leads',
            'module' => "Contacts",
            'source' => 'non-db',
            'vname' => 'LBL_CONTACTS',
            'reportable'=>false
        ),
        //Edited Relationship By Lap nguyen
        'account_name' =>
        array (
            'required'  => false,
            'name'      => 'account_name',
            'vname'     => 'LBL_ACCOUNT_NAME',
            'type'      => 'relate',
            'rname'     => 'name',
            'id_name'   => 'account_id',
            'join_name' => 'accounts',
            'link'      => 'accounts',
            'table'     => 'accounts',
            'isnull'    => 'true',
            'massupdate'=> false,
            'module'    => 'Accounts',
        ),
        'account_id' => array (
            'name'              => 'account_id',
            'rname'             => 'id',
            'vname'             => 'LBL_ACCOUNT_ID',
            'type'              => 'id',
            'table'             => 'accounts',
            'isnull'            => 'true',
            'module'            => 'Accounts',
            'dbType'            => 'id',
            'reportable'        => false,
            'massupdate'        => false,
            'duplicate_merge'   => 'disabled',
        ),
        'accounts' =>
        array (
            'name' => 'accounts',
            'type' => 'link',
            'relationship' => 'account_leads',
            'link_type' => 'one',
            'source' => 'non-db',
            'module'        => 'Accounts',
            'bean_name'     => 'Accounts',
            'vname' => 'LBL_ACCOUNT',
            'duplicate_merge'=> 'disabled',
        ),
        //END: Edited Relationship By Lap nguyen
        'account_description' =>
        array (
            'name' => 'account_description',
            'vname' => 'LBL_ACCOUNT_DESCRIPTION',
            'type' => 'text',
            'group'=>'account_name',
            'unified_search' => false,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Description of lead account'
        ),
        'contact_id' =>
        array (
            'name' => 'contact_id',
            'type' => 'id',
            'reportable'=>false,
            'vname'=>'LBL_CONTACT_ID',
            'comment' => 'If converted, Contact ID resulting from the conversion'
        ),
        'contact' => array(
            'name' => 'contact',
            'type' => 'link',
            'link_type' => 'one',
            'relationship' => 'contact_leads',
            'source' => 'non-db',
            'vname' => 'LBL_LEADS',
            'reportable' => false,
        ),
        'account_id' =>
        array (
            'name' => 'account_id',
            'type' => 'id',
            'reportable'=>false,
            'vname'=>'LBL_ACCOUNT_ID',
            'comment' => 'If converted, Account ID resulting from the conversion'
        ),
        'opportunity_id' =>
        array (
            'name' => 'opportunity_id',
            'type' => 'id',
            'reportable'=>false,
            'vname'=>'LBL_OPPORTUNITY_ID',
            'comment' => 'If converted, Opportunity ID resulting from the conversion'
        ),
        'opportunity' => array (
            'name' => 'opportunity',
            'type' => 'link',
            'link_type' => 'one',
            'relationship' => 'opportunity_leads',
            'source'=>'non-db',
            'vname'=>'LBL_OPPORTUNITIES',
        ),
        'opportunity_name' =>
        array (
            'name' => 'opportunity_name',
            'vname' => 'LBL_OPPORTUNITY_NAME',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Opportunity name associated with lead'
        ),
        'opportunity_amount' =>
        array (
            'name' => 'opportunity_amount',
            'vname' => 'LBL_OPPORTUNITY_AMOUNT',
            'type' => 'varchar',
            'group'=>'opportunity_name',
            'len' => '50',
            'comment' => 'Amount of the opportunity'
        ),
        'campaign_id' =>
        array (
            'name' => 'campaign_id',
            'type' => 'id',
            'reportable'=>false,
            'vname'=>'LBL_CAMPAIGN_ID',
            'comment' => 'Campaign that generated lead'
        ),

        'campaign_name' =>
        array (
            'name' => 'campaign_name',
            'rname' => 'name',
            'id_name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_leads',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'source' => 'non-db',
            'additionalFields' => array('id' => 'campaign_id'),
            'studio' => true,
        ),
        'campaign_leads' =>
        array (
            'name' => 'campaign_leads',
            'type' => 'link',
            'vname' => 'LBL_CAMPAIGN_LEAD',
            'relationship' => 'campaign_leads',
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
            'source' => 'non-db',
            'vname' => 'LBL_LIST_ACCEPT_STATUS',
            'options' => 'dom_meeting_accept_status',
            'importable' => 'false',
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
        ),
        'webtolead_email1' =>
        array (
            'name' => 'webtolead_email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'email',
            'len' => '100',
            'source' => 'non-db',
            'comment' => 'Main email address of lead',
            'importable' => 'false',
            'studio' => 'false',
        ),
        'webtolead_email2' =>
        array (
            'name' => 'webtolead_email2',
            'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
            'type' => 'email',
            'len' => '100',
            'source' => 'non-db',
            'comment' => 'Secondary email address of lead',
            'importable' => 'false',
            'studio' => 'false',
        ),
        'webtolead_email_opt_out' =>
        array (
            'name' => 'webtolead_email_opt_out',
            'vname' => 'LBL_EMAIL_OPT_OUT',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Indicator signaling if lead elects to opt out of email campaigns',
            'importable' => 'false',
            'massupdate' => false,
            'studio'=>'false',
        ),
        'webtolead_invalid_email' =>
        array (
            'name' => 'webtolead_invalid_email',
            'vname' => 'LBL_INVALID_EMAIL',
            'type' => 'bool',
            'source' => 'non-db',
            'comment' => 'Indicator that email address for lead is invalid',
            'importable' => 'false',
            'massupdate' => false,
            'studio'=>'false',
        ),
        'birthdate' =>
        array (
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'comment' => 'The birthdate of the contact',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
            'unified_search' => true,
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
        'full_lead_name' =>
        array (
            'required' => false,
            'name' => 'full_lead_name',
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

        'portal_name' =>
        array (
            'name' => 'portal_name',
            'vname' => 'LBL_PORTAL_NAME',
            'type' => 'varchar',
            'len' => '255',
            'group'=>'portal',
            'comment' => 'Portal user name when lead created via lead portal',
            //BEGIN SUGARCRM flav!=ent
            'studio' => 'false',
            //END SUGARCRM
        ),
        'portal_app' =>
        array (
            'name' => 'portal_app',
            'vname' => 'LBL_PORTAL_APP',
            'type' => 'varchar',
            'group'=>'portal',
            'len' => '255',
            'comment' => 'Portal application that resulted in created of lead',
            //BEGIN SUGARCRM flav!=ent
            'studio' => 'false',
        ),
        'website' =>
        array (
            'name' => 'website',
            'vname' => 'LBL_WEBSITE',
            'type' => 'url',
            'dbType' => 'varchar',
            'len' => 255,
            'link_target' => '_blank',
            'comment' => 'URL of website for the company',
        ),

        'tasks' =>
        array (
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'lead_tasks',
            'source'=>'non-db',
            'vname'=>'LBL_TASKS',
        ),
        'notes' =>
        array (
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'lead_notes',
            'source'=>'non-db',
            'vname'=>'LBL_NOTES',
        ),
        'meetings' =>
        array (
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'meetings_leads',
            'source'=>'non-db',
            'vname'=>'LBL_MEETINGS',
        ),
        'calls' =>
        array (
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'calls_leads',
            'source'=>'non-db',
            'vname'=>'LBL_CALLS',
        ),
        'oldmeetings' =>
        array (
            'name' => 'oldmeetings',
            'type' => 'link',
            'relationship' => 'lead_meetings',
            'source'=>'non-db',
            'vname'=>'LBL_MEETINGS',
        ),
        'oldcalls' =>
        array (
            'name' => 'oldcalls',
            'type' => 'link',
            'relationship' => 'lead_calls',
            'source'=>'non-db',
            'vname'=>'LBL_CALLS',
        ),
        'emails' =>
        array (
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_leads_rel',
            'source'=>'non-db',
            'unified_search'=>true,
            'vname'=>'LBL_EMAILS',
        ),
        'email_addresses' =>
        array (
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => 'leads_email_addresses',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable'=>false,
            'rel_fields' => array('primary_address' => array('type'=>'bool')),
        ),
        'email_addresses_primary' =>
        array (
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => 'leads_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge'=> 'disabled',
        ),
        'campaigns' =>
        array (
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'lead_campaign_log',
            'module'=>'CampaignLog',
            'bean_name'=>'CampaignLog',
            'source'=>'non-db',
            'vname'=>'LBL_CAMPAIGNLOG',
        ),
        'prospect_lists' =>
        array (
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_leads',
            'module'=>'ProspectLists',
            'source'=>'non-db',
            'vname'=>'LBL_PROSPECT_LIST',
        ),
        'preferred_language' =>
        array(
            'name' => 'preferred_language',
            'type' => 'enum',
            'default' => 'en_us',
            'vname' => 'LBL_PREFERRED_LANGUAGE',
            'options' => 'available_language_dom',
            'massupdate' => false,
        ),
        // ******** TASK IMPORT ***********************
        'aims_id' =>
        array (
            'required' => true,
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
        // ******** TASK IMPORT ***********************
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
        'type' =>
        array (
            'name' => 'type',
            'vname' => 'LBL_TYPE',
            'massupdate' => 1,
            'type' => 'enum',
            'default' => 'Public',
            'len' => '20',
            'options' => 'student_type_list',
            'studio' => 'visible',
        ),
        'pt_score' =>
        array(
            'name' => 'pt_score',
            'type' => 'varchar',
            'source' => 'non-db',
            'vname' => 'LBL_PT_SCORE',
            'studio' => array('listview' => false),
        ),
        'email_parent_1' =>
        array (
            'name' => 'email_parent_1',
            'vname' => 'LBL_EMAIL_PARENT_1',
            'type' => 'varchar',
            'len' => '255',
            'importable' => 'true',
            'unified_search' => true,
        ),
        'email_parent_2' =>
        array (
            'name' => 'email_parent_2',
            'vname' => 'LBL_EMAIL_PARENT_2',
            'type' => 'varchar',
            'len' => '255',
            'importable' => 'true',
            'unified_search' => true,
        ),    
        'reason_not_interested' =>
        array (
            'name' => 'reason_not_interested',
            'vname' => 'LBL_REASON_NOT_INTERESTED',
            'type' => 'enum',
            'len' => '255',
            'importable' => 'true',
            'options' => 'reason_not_interested_leads_list',
        ),
        'reason_description' =>
        array (
            'name' => 'reason_description',
            'vname' => 'LBL_REASON_DESCRIPTION',
            'type' => 'text',
            'importable' => 'true',
        ),
    )
    , 'indices' => array (
        array('name' =>'idx_lead_last_first_phone', 'type'=>'index', 'fields'=>array('last_name','first_name','phone_mobile','deleted')),
        array('name' =>'idx_lead_acct_name_first', 'type'=>'index', 'fields'=>array('account_name','deleted')),
        array('name' =>'idx_lead_last_first', 'type'=>'index', 'fields'=>array('last_name','first_name','deleted')),
        //
        //        array('name' =>'idx_lead_del_stat', 'type'=>'index', 'fields'=>array('last_name','status','deleted','first_name')),
        //        array('name' =>'idx_lead_opp_del', 'type'=>'index', 'fields'=>array('opportunity_id','deleted',)),
        //        array('name' =>'idx_leads_acct_del', 'type'=>'index', 'fields'=>array('account_id','deleted',)),
        //        array('name' => 'idx_del_user', 'type' => 'index', 'fields'=> array('deleted', 'assigned_user_id')),
        //        array('name' =>'idx_lead_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),
        //        array('name' =>'idx_lead_contact', 'type'=>'index', 'fields'=>array('contact_id')),
        //        array('name' =>'idx_reports_to', 'type'=>'index', 'fields'=>array('reports_to_id')),
        //        // array('name' =>'idx_lead_phone_work', 'type'=>'index', 'fields'=>array('phone_work')),
        array('name' =>'idx_leads_id_del', 'type'=>'index', 'fields'=>array('id','deleted',)),
        array(
            'name' => 'aims_id',
            'type' => 'index',
            'fields'=> array('aims_id'),
        ),

    )
    , 'relationships' => array (
        'lead_direct_reports' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Leads', 'rhs_table'=> 'leads', 'rhs_key' => 'reports_to_id',
            'relationship_type'=>'one-to-many'),
        'lead_tasks' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Leads')
        ,'lead_notes' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Leads')

        ,'lead_meetings' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Leads')

        ,'lead_calls' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Leads')

        ,'lead_emails' => array('lhs_module'=> 'Leads', 'lhs_table'=> 'leads', 'lhs_key' => 'id',
            'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Leads'),
        'lead_campaign_log' => array(
            'lhs_module'        =>    'Leads',
            'lhs_table'            =>    'leads',
            'lhs_key'             =>     'id',
            'rhs_module'        =>    'CampaignLog',
            'rhs_table'            =>    'campaign_log',
            'rhs_key'             =>     'target_id',
            'relationship_type'    =>'one-to-many',
            'relationship_role_column' => 'target_type',
            'relationship_role_column_value' => 'Leads'
        )

    )
    //This enables optimistic locking for Saves From EditView
    ,'optimistic_locking'=>true,
);

VardefManager::createVardef('Leads','Lead', array('default', 'assignable',
    'team_security',
    'person'));

// if (isset($GLOBALS['dictionary']['Lead']['fields']['picture']))
// {
//     unset($GLOBALS['dictionary']['Lead']['fields']['picture']);
// }
