<?php
    //Custom Relationship Teacher
    $dictionary['c_Timekeeping']['fields']['teacher_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'teacher_name',
        'vname'     => 'LBL_TEACHER_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'teacher_id',
        'join_name' => 'c_teachers',
        'link'      => 'teacher_timekeeping',
        'table'     => 'c_teachers',
        'isnull'    => 'true',
        'module'    => 'C_Teachers',
    );

    $dictionary['c_Timekeeping']['fields']['teacher_id'] = array(
        'name'              => 'teacher_id',
        'rname'             => 'id',
        'vname'             => 'LBL_TEACHER_ID',
        'type'              => 'id',
        'table'             => 'c_teachers',
        'isnull'            => 'true',
        'module'            => 'C_Teachers',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['c_Timekeeping']['fields']['teacher_timekeeping'] = array(
        'name'          => 'teacher_timekeeping',
        'type'          => 'link',
        'relationship'  => 'teacher_timekeeping',
        'module'        => 'C_Teachers',
        'bean_name'     => 'C_Teachers',
        'source'        => 'non-db',
        'vname'         => 'LBL_TEACHER_NAME',
    );
    //
    $dictionary['c_Timekeeping']['fields']['date_input'] = array(
        'required' => false,
        'name' => 'date_input',
        'vname' => 'LBL_DATE_INPUT',
        'type' => 'date',
        'massupdate' => 0,
        'no_default' => false,
        'importable' => 'true',
        'duplicate_merge' => 'disabled',
        'duplicate_merge_dom_value' => '0',
        'audited' => false,
        'reportable' => true,
        'unified_search' => false,
        'merge_filter' => 'disabled',
        'calculated' => false,
        'size' => '20',
        'enable_range_search' => true,
        'options' => 'date_range_search_dom',
    );    
    $dictionary['c_Timekeeping']['fields']['value_input'] = array(
        'required' => false,
        'name' => 'value_input',
        'vname' => 'LBL_VALUE_INPUT',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_2'] = array(
        'required' => false,
        'name' => 'value_input_2',
        'vname' => 'LBL_VALUE_INPUT_2',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_3'] = array(
        'required' => false,
        'name' => 'value_input_3',
        'vname' => 'LBL_VALUE_INPUT_3',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_4'] = array(
        'required' => false,
        'name' => 'value_input_4',
        'vname' => 'LBL_VALUE_INPUT_4',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_5'] = array(
        'required' => false,
        'name' => 'value_input_5',
        'vname' => 'LBL_VALUE_INPUT_5',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_6'] = array(
        'required' => false,
        'name' => 'value_input_6',
        'vname' => 'LBL_VALUE_INPUT_6',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
    $dictionary['c_Timekeeping']['fields']['value_input_7'] = array(
        'required' => false,
        'name' => 'value_input_7',
        'vname' => 'LBL_VALUE_INPUT_7',
        'type' => 'decimal',
        'len' => '5',
        'precision' => '2',
    );
?>