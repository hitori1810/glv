<?php 
 //WARNING: The contents of this file are auto-generated


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["User"]["fields"]["bc_survey_submission_users"] = array (
  'name' => 'bc_survey_submission_users',
  'type' => 'link',
  'relationship' => 'bc_survey_submission_users',
  'source' => 'non-db',
  'module' => 'bc_survey_submission',
  'bean_name' => false,
  'side' => 'right',
  'vname' => 'LBL_BC_SURVEY_SUBMISSION_USERS_FROM_BC_SURVEY_SUBMISSION_TITLE',
);


/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
// created: 2014-10-08 08:28:57
$dictionary["User"]["fields"]["bc_survey_users"] = array (
  'name' => 'bc_survey_users',
  'type' => 'link',
  'relationship' => 'bc_survey_users',
  'source' => 'non-db',
  'module' => 'bc_survey',
  'bean_name' => false,
  'vname' => 'LBL_BC_SURVEY_USERS_FROM_BC_SURVEY_TITLE',
);


// created: 2014-05-19 19:45:57
$dictionary["User"]["fields"]["c_kpi_group_users_1"] = array (
  'name' => 'c_kpi_group_users_1',
  'type' => 'link',
  'relationship' => 'c_kpi_group_users_1',
  'source' => 'non-db',
  'module' => 'C_KPI_Group',
  'bean_name' => 'C_KPI_Group',
  'vname' => 'LBL_C_KPI_GROUP_USERS_1_FROM_C_KPI_GROUP_TITLE',
);


    // Add by Trung Nguyen : 201.02.16
    $dictionary["User"]["fields"]["gender"] = array (
        'name' => 'gender',
        'type' => 'enum',
        'vname' => 'LBL_GENDER',
        'options' => 'gender_list',
        'massupdate' => false,
    );
    /* BEGIN Add By Hai Nguyen */
    $dictionary["User"]["fields"]["is_notify_new_task"] = array (
        'name'      => 'is_notify_new_task',
        'vname'     => 'LBL_IS_NOTIFY_NEW_TASK',
        'type'      => 'bool',
        'default'   => 0,
        'dbType'    => 'tinyint',
        'help'      => 'LBL_IS_NOTIFY_NEW_TASK_HELP',
        'massupdate' => false,
    );

    $dictionary["User"]["fields"]["is_notify_pending_task"] = array (
        'name'      => 'is_notify_pending_task',
        'vname'     => 'LBL_IS_NOTIFY_PENDING_TASK',
        'type'      => 'bool',
        'default'   => 0,
        'dbType'    => 'tinyint',
        'help'      => 'LBL_IS_NOTIFY_PENDING_TASK_HELP',
        'massupdate' => false,
    );

    $dictionary["User"]["fields"]["is_notify_customer_birthday"] = array (
        'name'      => 'is_notify_customer_birthday',
        'vname'     => 'LBL_IS_NOTIFY_CUSTOMER_BIRTHDAY',
        'type'      => 'bool',
        'default'   => 0,
        'dbType'    => 'tinyint',
        'help'      => 'LBL_IS_NOTIFY_CUSTOMER_BIRTHDAY_HELP',
        'massupdate' => false,
    );

    $dictionary["User"]["fields"]["numofday_notify_customer_birthday"] = array (
        'name'      => 'numofday_notify_customer_birthday',
        'vname'     => 'LBL_NUMOFDAY_NOTIFY_CUSTOMER_BIRTHDAY',
        'type'      => 'int',
        'help'      => 'LBL_NUMOFDAY_NOTIFY_CUSTOMER_BIRTHDAY_HELP',
        'dependency' => '$is_notify_customer_birthday',
        'massupdate' => false,
    );

    $dictionary["User"]["fields"]["numofday_before_notify_pending_task"] = array (
        'name'      => 'numofday_before_notify_pending_task',
        'vname'     => 'LBL_NUMOFDAY_BEFORE_NOTIFY_PENDING_TASK',
        'type'      => 'int',
        'help'      => 'LBL_NUMOFDAY_NOTIFY_PENDING_TASK_HELP',
        'dependency' => '$is_notify_pending_task',
        'massupdate' => false,
    );

    //***************  Fields Birthdate - Added By Lap Nguyen *************************
    $dictionary['User']['fields']['dob_day'] = array (
        'required' => false,
        'name' => 'dob_day',
        'vname' => 'LBL_DAY',
        'type' => 'enum',
        'len' => 10,
        'key' => 'dob',
        'options' => 'day_options',
        'studio'    => 'visible',
        'massupdate' => false,
    );
    $dictionary['User']['fields']['dob_month'] = array (
        'required' => false,
        'name' => 'dob_month',
        'vname' => 'LBL_MONTH',
        'type' => 'enum',
        'len' => 10,
        'key' => 'dob',
        'options' => 'month_options',
        'studio'    => 'visible',
        'massupdate' => false,
    );
    $dictionary['User']['fields']['dob_year'] = array (
        'name' => 'dob_year',
        'vname' => 'LBL_YEAR',
        'type' => 'int',
        'dbType' => 'varchar',
        'len' => 5,
        'key' => 'dob',
        'enable_range_search' => true,
        'options' => 'numeric_range_search_dom',
        'studio'    => 'visible',
        'massupdate' => false,
    );
    $dictionary['User']['fields']['dob_date'] = array(
        'name' => 'dob_date',
        'vname' => 'LBL_BIRTHDATE',
        'massupdate' => false,
        'type' => 'date',    
         'key' => 'dob',
        'enable_range_search' => true,
        'options' => 'date_range_search_dom',
    );
    //***************  END: Fields - Added By Lap Nguyen *************************


    /* END Add By Hai Nguyen */
    //Add by Trung Nguyen 2015.03.30

    $dictionary["User"]["fields"]["leaving_request_confirmer"] = array (
        'name' => 'leaving_request_confirmer',
        'type' => 'multienum',
        'vname' => 'LBL_LEAVE_CONFIRMER',
        'isMultiSelect' => true,
        'function'  => 'getUserList',
        'massupdate' => false,
    );
    $dictionary["User"]["fields"]["worklog_reminder_recipient"] = array (
        'name' => 'worklog_reminder_recipient',
        'type' => 'multienum',
        'isMultiSelect' => true,
        'vname' => 'LBL_WORKLOG_RECIPIENT',
        'function'  => 'getUserList',
        'massupdate' => false,
    );

//Add by Tung Bui 27/06/2016
	$dictionary['User']['fields']['for_portal_only'] = array(   
        'required' => false,
        'name' => 'for_portal_only',
        'vname' => 'LBL_FOR_PORTAL_ONLY',
        'type' => 'bool',
        'massupdate' => 0,
        'default' => '0',
        'no_default' => false,
        'importable' => false,
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => false,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'studio' => 'visible', 
    ); 
	
$dictionary["User"]["fields"]["portal_contact_id"] = array(
    'required' => false,
    'name' => 'portal_contact_id',
    'vname' => 'LBL_PORTAL_CONTACT_ID',
    'type' => 'varchar',
    'massupdate' => 0,
    'no_default' => false,
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
);
//Add by Lam Hai 06/06/2016
    $dictionary["User"]["fields"]["sign"] = array(
        'name' => 'sign',
        'vname' => 'LBL_PICTURE_SIGN_FILE',
        'type' => 'image',
        'dbType' => 'varchar',
        'len' => '255',
        'width' => '',
        'height' => '',
        'border' => '',
    );

$dictionary['User']['fields']['user_template_id']= array(
        'name' => 'user_template_id',
        'vname' => 'LBL_USER_TEMPLATE_ID',
        'type' => 'varchar',
        'len' => '255',
        'source' => 'non-db',
);

    $dictionary['User']['fields']['lock_homepage'] = array (
        'name'      => 'lock_homepage',
        'vname'     => 'LBL_LOCK_HOMEPAGE',
        'type'      => 'enum',
        'options'   => 'lock_homepage_options',
        'massupdate' => false,
    );

    $dictionary['User']['fields']['default_homepage'] = array (
        'name'      => 'default_homepage',
        'vname'     => 'LBL_DEFAULT_HOMEPAGE',
        'type'      => 'enum',
        'options'   => 'default_homepage_options',
        'massupdate' => false,
    );

    $dictionary['User']['fields']['toggle'] = array (
        'name'      => 'toggle',
        'vname'     => 'LBL_TOGGLE',
        'type'      => 'bool',
        'dbType'    => 'tinyint',
        'default'   =>  '0',
        'massupdate' => false,
    ); 

    $dictionary['User']['fields']['only_once'] = array (
        'name'      => 'only_once',
        'vname'     => 'LBL_ONLY_ONCE',
        'type'      => 'bool',
        'dbType'    => 'tinyint',
        'default'   =>  '0',
        'massupdate' => false,
    );




// created: 2015-02-06 14:00:49
$dictionary["User"]["fields"]["project_users_1"] = array (
  'name' => 'project_users_1',
  'type' => 'link',
  'relationship' => 'project_users_1',
  'source' => 'non-db',
  'module' => 'Project',
  'bean_name' => 'Project',
  'vname' => 'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE',
  'id_name' => 'project_users_1project_ida',
);


 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['asteriskname_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['call_hangup_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['call_notification_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['call_transfer_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['create_account_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['create_case_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['create_contact_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:33
$dictionary['User']['fields']['create_lead_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['dialout_prefix_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['dial_plan_c']['duplicate_merge_dom_value']=0;

 

$dictionary['User']['fields']['is_template'] = array(
    'name' => 'is_template',
    'vname' => 'LBL_IS_TEMPLATE',
    'type' => 'bool',
    'required' => false,
    'default' => '0',
    'comment' => 'Should be checked if the user is a template',
    'massupdate' => false,
);

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['lastcall_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['phoneextension_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['relate_account_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['relate_contact_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['schedulecall_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['showclicktocall_c']['duplicate_merge_dom_value']=0;

 

 // created: 2018-01-30 15:54:32
$dictionary['User']['fields']['taskcall_c']['duplicate_merge_dom_value']=0;

 

// created: 2017-01-05 12:05:27
$dictionary["User"]["fields"]["users_j_feedback_1"] = array (
  'name' => 'users_j_feedback_1',
  'type' => 'link',
  'relationship' => 'users_j_feedback_1',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_USERS_J_FEEDBACK_1_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'users_j_feedback_1users_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2017-01-05 13:58:05
$dictionary["User"]["fields"]["users_j_feedback_2"] = array (
  'name' => 'users_j_feedback_2',
  'type' => 'link',
  'relationship' => 'users_j_feedback_2',
  'source' => 'non-db',
  'module' => 'J_Feedback',
  'bean_name' => 'J_Feedback',
  'vname' => 'LBL_USERS_J_FEEDBACK_2_FROM_J_FEEDBACK_TITLE',
  'id_name' => 'users_j_feedback_2users_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-07-31 11:48:19
$dictionary["User"]["fields"]["users_j_marketingplan_1"] = array (
  'name' => 'users_j_marketingplan_1',
  'type' => 'link',
  'relationship' => 'users_j_marketingplan_1',
  'source' => 'non-db',
  'module' => 'J_Marketingplan',
  'bean_name' => 'J_Marketingplan',
  'vname' => 'LBL_USERS_J_MARKETINGPLAN_1_FROM_USERS_TITLE',
  'id_name' => 'users_j_marketingplan_1users_ida',
  'link-type' => 'many',
  'side' => 'left',
);


// created: 2015-07-31 11:52:05
$dictionary["User"]["fields"]["users_j_marketingplan_2"] = array (
  'name' => 'users_j_marketingplan_2',
  'type' => 'link',
  'relationship' => 'users_j_marketingplan_2',
  'source' => 'non-db',
  'module' => 'J_Marketingplan',
  'bean_name' => 'J_Marketingplan',
  'vname' => 'LBL_USERS_J_MARKETINGPLAN_2_FROM_USERS_TITLE',
  'id_name' => 'users_j_marketingplan_2users_ida',
  'link-type' => 'many',
  'side' => 'left',
);

?>