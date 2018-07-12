<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2015-07-28 09:30:06
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1"] = array (
  'name' => 'j_payment_j_inventory_1',
  'type' => 'link',
  'relationship' => 'j_payment_j_inventory_1',
  'source' => 'non-db',
  'module' => 'J_Payment',
  'bean_name' => 'J_Payment',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE',
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1_name"] = array (
  'name' => 'j_payment_j_inventory_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE',
  'save' => true,
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'name',
);
$dictionary["J_Inventory"]["fields"]["j_payment_j_inventory_1j_payment_ida"] = array (
  'name' => 'j_payment_j_inventory_1j_payment_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_PAYMENT_J_INVENTORY_1_FROM_J_PAYMENT_TITLE_ID',
  'id_name' => 'j_payment_j_inventory_1j_payment_ida',
  'link' => 'j_payment_j_inventory_1',
  'table' => 'j_payment',
  'module' => 'J_Payment',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'left',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


    //Custom Relationship 1-n  Teams - J_Inventory (For From center) by Quyen.Cao 

    $dictionary['J_Inventory']['fields']['from_team_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'from_team_name',
        'vname'     => 'LBL_FROM_TEAM_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'from_team_id',
        'join_name' => 'teams',
        'link'      => 'from_team',
        'table'     => 'teams',
        'isnull'    => 'true',
        'module'    => 'Teams',
    );

    $dictionary['J_Inventory']['fields']['from_team_id'] = array(
        'name'              => 'from_team_id',
        'rname'             => 'id',
        'vname'             => 'LBL_FROM_TEAM_ID',
        'type'              => 'id',
        'table'             => 'teams',
        'isnull'            => 'true',
        'module'            => 'Teams',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['from_team'] = array(
        'name'          => 'from_team',
        'type'          => 'link',
        'relationship'  => 'teamfrom_j_inventory',
        'module'        => 'Teams',
        'bean_name'     => 'Team',
        'source'        => 'non-db',
        'vname'         => 'LBL_FROM_TEAMS',
    );
    //END: Custom Relationship 1-n  Teams - J_Inventory (For From center) by Quyen.Cao 


    //Custom Relationship 1-n Account - J_Inventory (For Supplier)
    $dictionary['J_Inventory']['fields']['from_supplier_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'from_supplier_name',
        'vname'     => 'LBL_FROM_SUPPLIER_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'from_supplier_id',
        'join_name' => 'accounts',
        'link'      => 'from_supplier',
        'table'     => 'accounts',
        'isnull'    => 'true',
        'module'    => 'Accounts',
    );

    $dictionary['J_Inventory']['fields']['from_supplier_id'] = array(
        'name'              => 'from_supplier_id',
        'rname'             => 'id',
        'vname'             => 'LBL_FROM_SUPPLIER_ID',
        'type'              => 'id',
        'table'             => 'accounts',
        'isnull'            => 'true',
        'module'            => 'Accounts',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['from_supplier'] = array(
        'name'          => 'from_supplier',
        'type'          => 'link',
        'relationship'  => 'supplier_j_inventory',
        'module'        => 'Accounts',
        'bean_name'     => 'Account',
        'source'        => 'non-db',
        'vname'         => 'LBL_FROM_SUPPLIER',
    );
    //END: Custom Relationship 1-n Teams - J_Inventory

    //Custom Relationship 1-n  Teams - J_Inventory (For To center) by Quyen.Cao 

    $dictionary['J_Inventory']['fields']['to_team_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'to_team_name',
        'vname'     => 'LBL_TO_TEAM_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'to_team_id',
        'join_name' => 'teams',
        'link'      => 'to_team',
        'table'     => 'teams',
        'isnull'    => 'true',
        'module'    => 'Teams',
    );

    $dictionary['J_Inventory']['fields']['to_team_id'] = array(
        'name'              => 'to_team_id',
        'rname'             => 'id',
        'vname'             => 'LBL_TO_TEAM_ID',
        'type'              => 'id',
        'table'             => 'teams',
        'isnull'            => 'true',
        'module'            => 'Teams',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['to_team'] = array(
        'name'          => 'to_team',
        'type'          => 'link',
        'relationship'  => 'teamto_j_inventory',
        'module'        => 'Teams',
        'bean_name'     => 'Team',
        'source'        => 'non-db',
        'vname'         => 'LBL_TO_TEAMS',
    );
    //END: Custom Relationship 1-n  Teams - J_Inventory (For From center) by Quyen.Cao\

    //Custom Relationship 1-n Teacher - J_Inventory
    $dictionary['J_Inventory']['fields']['to_teacher_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'to_teacher_name',
        'vname'     => 'LBL_TO_TEACHER_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'to_teacher_id',
        'join_name' => 'c_teachers',
        'link'      => 'to_teacher',
        'table'     => 'c_teachers',
        'isnull'    => 'true',
        'module'    => 'C_Teachers', 
    );    

    $dictionary['J_Inventory']['fields']['to_teacher_id'] = array(
        'name'              => 'to_teacher_id',
        'rname'             => 'id',
        'vname'             => 'LBL_TO_TEACHER_ID',
        'type'              => 'id',
        'table'             => 'c_teachers',
        'isnull'            => 'true',
        'module'            => 'C_Teachers',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['to_teacher'] = array(
        'name'          => 'to_teacher',
        'type'          => 'link',
        'relationship'  => 'teacher_j_inventory',
        'module'        => 'C_Teachers',
        'bean_name'     => 'C_Teachers',
        'source'        => 'non-db',
        'vname'         => 'LBL_FROM_SUPPLIER',
    );
    //END: Custom Relationship 1-n Teacher - J_Inventory

    //Custom Relationship 1-n Corp - J_Inventory
    $dictionary['J_Inventory']['fields']['to_corp_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'to_corp_name',
        'vname'     => 'LBL_TO_CORP_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'to_corp_id',
        'join_name' => 'accounts',
        'link'      => 'to_corp',
        'table'     => 'accounts',
        'isnull'    => 'true',
        'module'    => 'Accounts',
    );

    $dictionary['J_Inventory']['fields']['to_corp_id'] = array(
        'name'              => 'to_corp_id',
        'rname'             => 'id',
        'vname'             => 'LBL_TO_CORP_ID',
        'type'              => 'id',
        'table'             => 'accounts',
        'isnull'            => 'true',
        'module'            => 'Accounts',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['to_corp'] = array(
        'name'          => 'to_corp',
        'type'          => 'link',
        'relationship'  => 'corp_j_inventory',
        'module'        => 'Accounts',
        'bean_name'     => 'Account',
        'source'        => 'non-db',
        'vname'         => 'LBL_FROM_SUPPLIER',
    );
    //END: Custom Relationship 1-n Corp - J_Inventory

    $dictionary['J_Inventory']['fields']['from_object_id'] = array(
        'name'              => 'from_object_id',
        'vname'             => 'LBL_FROM_OBJECT_ID',
        'type'              => 'id',          
    );          

    $dictionary['J_Inventory']['fields']['to_object_id'] = array(
        'name'              => 'to_object_id',
        'vname'             => 'LBL_FROM_OBJECT_ID',
        'type'              => 'id',          
    );
  $dictionary['J_Inventory']['fields']['to_students_name'] = array(
        'required'  => false,
        'source'    => 'non-db',
        'name'      => 'to_students_name',
        'vname'     => 'LBL_TO_CORP_NAME',
        'type'      => 'relate',
        'rname'     => 'name',
        'id_name'   => 'to_student_id',
        'join_name' => 'contacts',
        'link'      => 'to_student',
        'table'     => 'contacts',
        'isnull'    => 'true',
        'module'    => 'Contacts',
    );

    $dictionary['J_Inventory']['fields']['to_student_id'] = array(
        'name'              => 'to_student_id',
        'rname'             => 'id',
        'vname'             => 'LBL_TO_CORP_ID',
        'type'              => 'id',
        'table'             => 'contacts',
        'isnull'            => 'true',
        'module'            => 'Contacts',
        'dbType'            => 'id',
        'reportable'        => false,
        'massupdate'        => false,
        'duplicate_merge'   => 'disabled',
    );

    $dictionary['J_Inventory']['fields']['to_student'] = array(
        'name'          => 'to_student',
        'type'          => 'link',
        'relationship'  => 'student_j_inventory',
        'module'        => 'Contacts',
        'bean_name'     => 'Contact',
        'source'        => 'non-db',
        'vname'         => 'LBL_FROM_SUPPLIER',
    );
?>