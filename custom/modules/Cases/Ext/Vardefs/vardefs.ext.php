<?php 
 //WARNING: The contents of this file are auto-generated


    // created: 2014-12-26 05:23:44
    $dictionary['Case']['fields']['work_log']['comments']='Free-form text used to denote activities of interest';
    $dictionary['Case']['fields']['work_log']['merge_filter']='disabled';
    $dictionary['Case']['fields']['work_log']['calculated']=false;
    $dictionary['Case']['fields']['work_log']['rows']='6';
    $dictionary['Case']['fields']['work_log']['cols']='80';

    //Related Field  - Fisrt Aproach
    $dictionary["Case"]["fields"]["student_name"] = array (
        'name' => 'student_name',
        'rname' => 'name',
        'id_name' => 'student_id',
        'vname' => 'LBL_STUDENT_NAME',
        'type' => 'relate',
        'link'=>'students',
        'table' => 'contacts',
        'join_name'=>'contacts',
        'isnull' => 'true',
        'module' => 'Contacts',
        'dbType' => 'varchar',
        'len' => 100,
        'source'=>'non-db',
        'unified_search' => true,
        'comment' => 'The name of the account represented by the account_id field',
        'required' => true,
        'importable' => 'required',
    );
    $dictionary["Case"]["fields"]["student_id"] = array (
        'name'=>'student_id',
        'type' => 'relate',
        'dbType' => 'id',
        'rname' => 'id',
        'module' => 'Contacts',
        'id_name' => 'student_id',
        'reportable'=>false,
        'vname'=>'LBL_STUDENT_NAME',
        'massupdate' => false,
        'comment' => 'The account to which the case is associated'
    );
    $dictionary["Case"]["fields"]["students"] = array (
        'name' => 'students',
        'type' => 'link',
        'relationship' => 'contacts_cases',
        'link_type'=>'one',
        'side'=>'right',
        'source'=>'non-db',
    );

   
$dictionary['Case']['fields']['target'] = array(   
    'name' => 'target',
    'vname' => 'LBL_TARGET',
    'type' => 'enum',
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
    'options' => 'case_target_options',
    'studio' => 'visible',
    'default' => 'EC',
    'massupdate' => 0,                  
); 




?>