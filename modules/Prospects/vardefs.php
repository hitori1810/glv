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

$dictionary['Prospect'] = array(

    'table' => 'prospects',
    'unified_search' => true,
    'fields' => array (
        'tracker_key' => array (
            'name' => 'tracker_key',
            'vname' => 'LBL_TRACKER_KEY',
            'type' => 'int',
            'len' => '11',
            'required'=>true,
            'auto_increment' => true,
            'importable' => 'false',
            'studio' => array('editview' => false),
        ),
        'converted' =>
        array (
            'name' => 'converted',
            'vname' => 'LBL_CONVERTED',
            'type' => 'bool',
            'default' => '',
            'comment' => 'Has Lead been converted to a Contact (and other Sugar objects)',
            'massupdate' => true,
        ),
        'birthdate' =>
        array (
            'name' => 'birthdate',
            'vname' => 'LBL_BIRTHDATE',
            'massupdate' => false,
            'type' => 'date',
            'enable_range_search' => true,
            'options' => 'date_range_search_dom',
        ),
        'facebook' =>
        array (
            'name' => 'facebook',
            'vname' => 'LBL_FACEBOOK',
            'type' => 'url',
            'dbType' => 'varchar',
            'len' => 255,
            'audited' => true,
            'comment' => 'URL of website for the company',
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
        'do_not_call' =>
        array (
            'name' => 'do_not_call',
            'vname' => 'LBL_DO_NOT_CALL',
            'type'=>'bool',
            'default' =>'0',
            'massupdate' => false,
        ),
        'full_target_name' =>
        array (
            'required' => false,
            'name' => 'full_target_name',
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
        'lead_id' =>
        array (
            'name' => 'lead_id',
            'type' => 'id',
            'reportable'=>false,
            'vname'=>'LBL_LEAD_ID',
        ),
        'other_mobile' =>
        array (
            'name' => 'other_mobile',
            'vname' => 'LBL_OTHER_MOBILE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => '50',
        ),

        'account_name' =>
        array (
            'name' => 'account_name',
            'vname' => 'LBL_ACCOUNT_NAME',
            'type' => 'varchar',
            'len' => '150',
        ),
        'campaign_id' =>
        array (
            'name' => 'campaign_id',
            'comment' => 'Campaign that generated lead',
            'vname'=>'LBL_CAMPAIGN_ID',
            'rname' => 'id',
            'id_name' => 'campaign_id',
            'type' => 'id',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            //'dbType' => 'char',
            'reportable'=>false,
            'massupdate' => false,
            'duplicate_merge'=> 'disabled',
        ),
        'campaign_name' =>
        array (
            'name' => 'campaign_name',
            'rname' => 'name',
            'id_name' => 'campaign_id',
            'vname' => 'LBL_CAMPAIGN',
            'type' => 'relate',
            'link' => 'campaign_prospects',
            'table' => 'campaigns',
            'isnull' => 'true',
            'module' => 'Campaigns',
            'source' => 'non-db',
            'additionalFields' => array('id' => 'campaign_id')
        ),
        'email_addresses' =>
        array (
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => 'prospects_email_addresses',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable'=>false,
            'rel_fields' => array('primary_address' => array('type'=>'bool')),
        ),
        'email_addresses_primary' =>
        array (
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => 'prospects_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge'=> 'disabled',
            'massupdate' => false,
        ),
        'campaigns' =>
        array (
            'name' => 'campaigns',
            'type' => 'link',
            'relationship' => 'prospect_campaign_log',
            'module'=>'CampaignLog',
            'bean_name'=>'CampaignLog',
            'source'=>'non-db',
            'vname'=>'LBL_CAMPAIGNLOG',
        ),
        'campaign_prospects' =>
        array (
            'name' => 'campaign_prospects',
            'type' => 'link',
            'relationship' => 'campaign_prospects',
            'module'=>'Campaigns',
            'bean_name'=>'Campaigns',
            'source'=>'non-db',
            'vname'=>'LBL_CAMPAIGN',
        ),
        'prospect_lists' =>
        array (
            'name' => 'prospect_lists',
            'type' => 'link',
            'relationship' => 'prospect_list_prospects',
            'module'=>'ProspectLists',
            'source'=>'non-db',
            'vname'=>'LBL_PROSPECT_LIST',
        ),
        'calls' =>
        array (
            'name' => 'calls',
            'type' => 'link',
            'relationship' => 'prospect_calls',
            'source' => 'non-db',
            'vname' => 'LBL_CALLS',
        ),
        'meetings'=>
        array (
            'name' => 'meetings',
            'type' => 'link',
            'relationship' => 'prospect_meetings',
            'source' => 'non-db',
            'vname' => 'LBL_MEETINGS',
        ),
        'notes'=>
        array (
            'name' => 'notes',
            'type' => 'link',
            'relationship' => 'prospect_notes',
            'source' => 'non-db',
            'vname' => 'LBL_NOTES',
        ),
        'tasks'=>
        array (
            'name' => 'tasks',
            'type' => 'link',
            'relationship' => 'prospect_tasks',
            'source' => 'non-db',
            'vname' => 'LBL_TASKS',
        ),
        'emails'=>
        array (
            'name' => 'emails',
            'type' => 'link',
            'relationship' => 'emails_prospects_rel',
            'source' => 'non-db',
            'vname' => 'LBL_EMAILS',
        ),
    ),

    'indices' =>
    array (
        array(
            'name' => 'prospect_auto_tracker_key' ,
            'type'=>'index' ,
            'fields'=>array('tracker_key')
        ),
        array(	'name' 	=>	'idx_prospects_last_first',
            'type' 	=>	'index',
            'fields'=>	array(
                'last_name',
                'first_name',
                'phone_mobile',
                'deleted'
            )
        ),
        array(
            'name' =>	'idx_prospecs_del_last',
            'type' =>	'index',
            'fields'=>	array(
                'last_name',
                'deleted'
            )
        ),
        array('name' =>'idx_prospects_id_del', 'type'=>'index', 'fields'=>array('id','deleted')),
        array('name' =>'idx_prospects_assigned', 'type'=>'index', 'fields'=>array('assigned_user_id')),

    ),

    'relationships' => array (
        'prospect_tasks' => array('lhs_module'=> 'Prospects', 'lhs_table'=> 'prospects', 'lhs_key' => 'id',
            'rhs_module'=> 'Tasks', 'rhs_table'=> 'tasks', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Prospects'),
        'prospect_notes' => array('lhs_module'=> 'Prospects', 'lhs_table'=> 'prospects', 'lhs_key' => 'id',
            'rhs_module'=> 'Notes', 'rhs_table'=> 'notes', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Prospects'),
        'prospect_meetings' => array('lhs_module'=> 'Prospects', 'lhs_table'=> 'prospects', 'lhs_key' => 'id',
            'rhs_module'=> 'Meetings', 'rhs_table'=> 'meetings', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Prospects'),
        'prospect_calls' => array('lhs_module'=> 'Prospects', 'lhs_table'=> 'prospects', 'lhs_key' => 'id',
            'rhs_module'=> 'Calls', 'rhs_table'=> 'calls', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Prospects'),
        'prospect_emails' => array('lhs_module'=> 'Prospects', 'lhs_table'=> 'prospects', 'lhs_key' => 'id',
            'rhs_module'=> 'Emails', 'rhs_table'=> 'emails', 'rhs_key' => 'parent_id',
            'relationship_type'=>'one-to-many', 'relationship_role_column'=>'parent_type',
            'relationship_role_column_value'=>'Prospects'),
        'prospect_campaign_log' => array(
            'lhs_module'		=>	'Prospects',
            'lhs_table'			=>	'prospects',
            'lhs_key' 			=> 	'id',
            'rhs_module'		=>	'CampaignLog',
            'rhs_table'			=>	'campaign_log',
            'rhs_key' 			=> 	'target_id',
            'relationship_type'	=>'one-to-many',
            'relationship_role_column' => 'target_type',
            'relationship_role_column_value' => 'Prospects'
        ),

    )
);
VardefManager::createVardef('Prospects','Prospect', array('default', 'assignable',
    'team_security',
    'person'));

if (isset($GLOBALS['dictionary']['Prospect']['fields']['picture']))
{
    unset($GLOBALS['dictionary']['Prospect']['fields']['picture']);
}
