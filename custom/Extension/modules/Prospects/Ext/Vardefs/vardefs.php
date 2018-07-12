<?php
    $dictionary['Prospect']['fields']['potential']=array (
        'name' => 'potential',
        'vname' => 'LBL_POTENTIAL',
        'type' => 'enum',
        'comments' => '',
        'help' => '',
        'default' => 'Low',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'unified_search' => false,
        'massupdate' => false,
        'merge_filter' => 'disabled',
        'len' => 20,
        'size' => '20',
        'options' => 'level_lead_list',
        'studio' => 'visible',
    );
    $dictionary['Prospect']['fields']['working_date']=array (
        'name' => 'working_date',
        'vname' => 'LBL_WORKING_DATE',
        'type' => 'date',
        'required' => true,
        'massupdate' => false,
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'size' => '20',
        //        'enable_range_search' => true,
        //        'options' => 'date_range_search_dom',
        'display_default' => 'now',
        'source' => 'non-db',
    );

    //Bo Sung Lead source cho Target
    $dictionary['Prospect']['fields']['lead_source']=array (
        'name' => 'lead_source',
        'vname' => 'LBL_LEAD_SOURCE',
        'type' => 'enum',
        'options'=> 'lead_source_list',
        'len' => '100',
        'massupdate' => true,
        'required' => true,
    );
    $dictionary["Prospect"]["fields"]["lead_source_description"] = array (
        'name' => 'lead_source_description',
        'type' => 'text',
        'rows' => '2',
        'cols' => '30',
        'studio' => 'visible',
        'vname'=> 'LBL_SOURCE_DESCRIPTION',
    );
    //Bo Sung Status : New Assigned
    $dictionary['Prospect']['fields']['status']=array (
        'name' => 'status',
        'vname' => 'LBL_STATUS',
        'type' => 'enum',
        'len' => '100',
        'options' => 'target_status_dom',
        'default' => 'New',
        'comment' => 'Status of the target',
        'massupdate' => false,
    );
    $dictionary["Prospect"]["fields"]["age"] = array (
        'name' => 'age',
        'type' => 'varchar',
        'len' => '30',
        'studio' => 'visible',
        'vname'=> 'LBL_CONTACT_AGE',
    );

    //Add Checking Duplicate Field mobile
   /* $dictionary['Prospect']['indices'][] = array(
        'name' => 'idx_mobile_phone_cstm',
        'type' => 'index',
        'fields' => array(
            0 => 'phone_mobile',
            1 => 'birthdate',
        ),
        'source' => 'non-db',
    );*/
    $dictionary['Prospect']['fields']['guardian_name']=array (
        'name' => 'guardian_name',
        'vname' => 'LBL_GUARDIAN_NAME',
        'type' => 'varchar',
        'len' => '100',
        'comment' => '',
        'merge_filter' => 'disabled',
    );
    //add team type
    $dictionary['Prospect']['fields']['team_type'] = array(
        'name' => 'team_type',
        'vname' => 'LBL_TEAM_TYPE',
        'type' => 'enum',
        'importable' => 'false',
        'reportable' => true,
        'len' => 30,
        'size' => '20',
        'options' => 'type_team_list',
        'studio' => 'visible',
        'massupdate' => 0,
    );
    // END: add team type
    $dictionary['Prospect']['fields']['gender']=array (
        'name' => 'gender',
        'vname' => 'LBL_GENDER',
        'type' => 'enum',
        'massupdate' => 0,
        'default' => ' ',
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
        'len' => 20,
        'size' => '20',
        'options' => 'gender_lead_list',
        'studio' => 'visible',
        'dbType' => 'enum',
        'required'=>false,
    );
