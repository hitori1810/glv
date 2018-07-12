<?php
    // Relationship Teacher ( 1 - n ) Timesheet Detail - Lap Nguyen
    $dictionary['C_Timesheet']['fields']['teacher_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'teacher_name',
        'vname'     => 'LBL_TEACHER_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'teacher_id',
        'join_name' => 'C_Teachers',
        'link'      => 'timesheet_teacher',
        'table'     => 'c_teachers',
        'isnull'    => 'true',
        'module'    => 'C_Teachers',
    );

    $dictionary['C_Timesheet']['fields']['teacher_id'] = array(
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

    $dictionary['C_Timesheet']['fields']['timesheet_teacher'] = array(
        'name'          => 'timesheet_teacher',
        'type'          => 'link',
        'relationship'  => 'timesheet_teacher',
        'module'        => 'C_Teachers',
        'bean_name'     => 'C_Teachers',
        'source'        => 'non-db',
        'vname'         => 'LBL_TEACHER_NAME',
    );
    //END : Relationship Teacher ( 1 - n ) Timesheet 
    $dictionary["C_Timesheet"]["fields"]["hours"] = array (
        'name' => 'hours',
        'type' => 'int',
        'dbType' => 'uint',
        'vname' => 'LBL_HOURS',
        'required' => true,
    );

    $dictionary["C_Timesheet"]["fields"]["minutes"] = array (
        'name' => 'minutes',
        'type' => 'enum',
        'options' => 'timesheet_minutes_list',
        'vname' => 'LBL_MINUTES',
    );

    $dictionary["C_Timesheet"]["fields"]["task_name"] = array (
        'name' => 'task_name',
        'type' => 'enum',
        'options' => 'timesheet_tasklist_list',
        'vname' => 'LBL_TASK',
    );