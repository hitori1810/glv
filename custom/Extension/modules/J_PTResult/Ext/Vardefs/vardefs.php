<?php
    $dictionary['J_PTResult']['fields']['custom_button'] = array (
        'name' => 'custom_button',
        'vname' => 'LBL_CUSTOM_BUTTON',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    $dictionary['J_PTResult']['fields']['custom_button_demo'] = array (
        'name' => 'custom_button_demo',
        'vname' => 'LBL_CUSTOM_BUTTON',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    $dictionary['J_PTResult']['fields']['date_start'] = array (
        'name' => 'date_start',
        'vname' => 'LBL_START_DATE',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    $dictionary['J_PTResult']['fields']['date_end'] = array (
        'name' => 'date_end',
        'vname' => 'LBL_DATE_END',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    $dictionary['J_PTResult']['fields']['duration_cal'] = array (
        'name' => 'duration_cal',
        'vname' => 'LBL_DURATION',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    $dictionary['J_PTResult']['fields']['full_teacher_name'] = array (
        'name' => 'full_teacher_name',
        'vname' => 'LBL_TEACHER',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
     $dictionary['J_PTResult']['fields']['room_name'] = array (
        'name' => 'room_name',
        'vname' => 'LBL_ROOM',
        'type' => 'varchar',
        'studio' => 'visible',
        'source' => 'non-db',
    );
    ///Custom relation ship 1 contact - n ptresult
//    $dictionary['J_PTResult']['fields']['parent_name'] = array(
//        'required'  => false,
//        'source'    => 'non-db',
//        'name'      => 'parent_name',
//        'vname'     => 'LBL_CONTACT_NAME',
//        'type'      => 'relate',
//        'rname'     => 'name',
//        'id_name'   => 'parent_id',
//        'join_name' => 'j_ptresult',
//        'link'      => 'j_ptresult',
//        'table'     => 'j_ptresult',
//        'isnull'    => 'true',
//        'module'    => 'J_PTResult',
//    );
//
//    $dictionary['J_PTResult']['fields']['parent_id'] = array(
//        'name'              => 'parent_id',
//        'rname'             => 'id',
//        'vname'             => 'LBL_CONTACT_ID',
//        'type'              => 'id',
//        'table'             => 'j_ptresult',
//        'isnull'            => 'true',
//        'module'            => 'J_PTResult',
//        'dbType'            => 'id',
//        'reportable'        => false,
//        'massupdate'        => false,
//        'duplicate_merge'   => 'disabled',
//    );
//
//    $dictionary['J_PTResult']['fields']['contact_result'] = array(
//        'name'          => 'j_ptresult',
//        'type'          => 'link',
//        'relationship'  => 'contact_result',
//        'module'        => 'J_PTResult',
//        'bean_name'     => 'J_PTResult',
//        'source'        => 'non-db',
//        'vname'         => 'LBL_PT_RESULT',
//    );
//    /End Custom Relation 1 - contact n result
