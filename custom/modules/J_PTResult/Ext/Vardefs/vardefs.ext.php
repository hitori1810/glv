<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-09-07 09:33:54
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1"] = array (
  'name' => 'contacts_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'contacts_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Contacts',
  'bean_name' => 'Contact',
  'side' => 'right',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1_name"] = array (
  'name' => 'contacts_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_CONTACTS_TITLE',
  'save' => true,
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link' => 'contacts_j_ptresult_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_PTResult"]["fields"]["contacts_j_ptresult_1contacts_ida"] = array (
  'name' => 'contacts_j_ptresult_1contacts_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_CONTACTS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'contacts_j_ptresult_1contacts_ida',
  'link' => 'contacts_j_ptresult_1',
  'table' => 'contacts',
  'module' => 'Contacts',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-09-07 09:49:06
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1"] = array (
  'name' => 'leads_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'leads_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Leads',
  'bean_name' => 'Lead',
  'side' => 'right',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE',
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1_name"] = array (
  'name' => 'leads_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_LEADS_TITLE',
  'save' => true,
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link' => 'leads_j_ptresult_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_PTResult"]["fields"]["leads_j_ptresult_1leads_ida"] = array (
  'name' => 'leads_j_ptresult_1leads_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_LEADS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'leads_j_ptresult_1leads_ida',
  'link' => 'leads_j_ptresult_1',
  'table' => 'leads',
  'module' => 'Leads',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2015-08-05 15:56:02
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1"] = array (
  'name' => 'meetings_j_ptresult_1',
  'type' => 'link',
  'relationship' => 'meetings_j_ptresult_1',
  'source' => 'non-db',
  'module' => 'Meetings',
  'bean_name' => 'Meeting',
  'side' => 'right',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_MEETINGS_TITLE',
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link-type' => 'one',
);
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1_name"] = array (
  'name' => 'meetings_j_ptresult_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_MEETINGS_TITLE',
  'save' => true,
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link' => 'meetings_j_ptresult_1',
  'table' => 'meetings',
  'module' => 'Meetings',
  'rname' => 'name',
);
$dictionary["J_PTResult"]["fields"]["meetings_j_ptresult_1meetings_ida"] = array (
  'name' => 'meetings_j_ptresult_1meetings_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_MEETINGS_J_PTRESULT_1_FROM_J_PTRESULT_TITLE_ID',
  'id_name' => 'meetings_j_ptresult_1meetings_ida',
  'link' => 'meetings_j_ptresult_1',
  'table' => 'meetings',
  'module' => 'Meetings',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


$dictionary['J_PTResult']['fields']['student_mobile']['type'] = 'function';
$dictionary['J_PTResult']['fields']['student_mobile']['function'] = array('name'=>'sms_phone', 'returns'=>'html', 'include'=>'custom/fieldFormat/sms_phone_fields.php');




 // created: 2015-08-12 10:54:46
$dictionary['J_PTResult']['fields']['time_end']['merge_filter']='disabled';
$dictionary['J_PTResult']['fields']['time_end']['calculated']=false;

 

 // created: 2015-08-12 10:54:23
$dictionary['J_PTResult']['fields']['time_start']['merge_filter']='disabled';
$dictionary['J_PTResult']['fields']['time_start']['calculated']=false;

 

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


// created: 2016-05-23 03:55:52
$dictionary['J_PTResult']['fields']['j_ptresult_c_sms'] = array (
								  'name' => 'j_ptresult_c_sms',
									'type' => 'link',
									'relationship' => 'j_ptresult_c_sms',
									'source'=>'non-db',
									'vname'=>'LBL_C_SMS',
							);


?>