<?php
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