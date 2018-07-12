<?php 
 //WARNING: The contents of this file are auto-generated


    // Relationship Student ( 1 - n ) Attendance - Lap Nguyen
    $dictionary['C_Attendance']['fields']['student_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'student_name',
        'vname'     => 'LBL_STUDENT_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'student_id',
        'join_name' => 'contacts',
        'link'      => 'student_attendances',
        'table'     => 'contacts',
        'isnull'    => 'true',
        'module'    => 'Contacts',
    );

    $dictionary['C_Attendance']['fields']['student_id'] = array(
        'name'              => 'student_id',
        'rname'             => 'id',
        'vname'             => 'LBL_STUDENT_ID',
        'type'              => 'id',
        'table'             => 'contacts',
        'isnull'            => 'true',
        'module'            => 'Contacts',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['C_Attendance']['fields']['student_attendances'] = array(
        'name'          => 'student_attendances',
        'type'          => 'link',
        'relationship'  => 'student_attendances',
        'module'        => 'Contacts',
        'bean_name'     => 'Contacts',
        'source'        => 'non-db',
        'vname'         => 'LBL_STUDENT_NAME',
    );
    //END: Relationship Student ( 1 - n ) Attendance 

    // Relationship Session ( 1 - n ) Attendance - Lap Nguyen
    $dictionary['C_Attendance']['fields']['meeting_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'meeting_name',
        'vname'     => 'LBL_MEETING_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'meeting_id',
        'join_name' => 'meetings',
        'link'      => 'meeting_attendances',
        'table'     => 'meetings',
        'isnull'    => 'true',
        'module'    => 'Meetings',
    );

    $dictionary['C_Attendance']['fields']['meeting_id'] = array(
        'name'              => 'meeting_id',
        'rname'             => 'id',
        'vname'             => 'LBL_MEETING_ID',
        'type'              => 'id',
        'table'             => 'meetings',
        'isnull'            => 'true',
        'module'            => 'Meetings',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['C_Attendance']['fields']['meeting_attendances'] = array(
        'name'          => 'meeting_attendances',
        'type'          => 'link',
        'relationship'  => 'meeting_attendances',
        'module'        => 'Meetings',
        'bean_name'     => 'Meetings',
        'source'        => 'non-db',
        'vname'         => 'LBL_MEETING_NAME',
    );
    //END: Relationship Student ( 1 - n ) Attendance 

    //create by leduytan 18/8/2014
    $dictionary['C_Attendance']['fields']['absent_reason'] = array(
        'name'          => 'absent_reason',
        'type'          => 'text',
        'vname'         => 'LBL_ABSENT_REASON',
    );
    //create by Lam Hai 26/09/2016
    $dictionary['C_Attendance']['fields']['absent_continuously'] = array(
        'name'          => 'absent_continuously',
        'type'          => 'bool',
        'vname'         => 'LBL_ABSENT_CONTINUOSLY',
    );
?>