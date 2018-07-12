<?php 
 //WARNING: The contents of this file are auto-generated


// created: 2016-07-27 11:01:41
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1"] = array (
  'name' => 'c_teachers_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'c_teachers_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'C_Teachers',
  'bean_name' => 'C_Teachers',
  'side' => 'right',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link-type' => 'one',
);
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1_name"] = array (
  'name' => 'c_teachers_j_gradebook_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_C_TEACHERS_TITLE',
  'save' => true,
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link' => 'c_teachers_j_gradebook_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'name',
  'db_concat_fields' => 
  array (
    0 => 'first_name',
    1 => 'last_name',
  ),
);
$dictionary["J_Gradebook"]["fields"]["c_teachers_j_gradebook_1c_teachers_ida"] = array (
  'name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_C_TEACHERS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE_ID',
  'id_name' => 'c_teachers_j_gradebook_1c_teachers_ida',
  'link' => 'c_teachers_j_gradebook_1',
  'table' => 'c_teachers',
  'module' => 'C_Teachers',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
);


// created: 2016-05-12 11:08:29
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1"] = array (
  'name' => 'j_class_j_gradebook_1',
  'type' => 'link',
  'relationship' => 'j_class_j_gradebook_1',
  'source' => 'non-db',
  'module' => 'J_Class',
  'bean_name' => 'J_Class',
  'side' => 'right',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link-type' => 'one',
);
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1_name"] = array (
  'name' => 'j_class_j_gradebook_1_name',
  'type' => 'relate',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_CLASS_TITLE',
  'save' => true,
  'required' => true,
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link' => 'j_class_j_gradebook_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'name',
);
$dictionary["J_Gradebook"]["fields"]["j_class_j_gradebook_1j_class_ida"] = array (
  'name' => 'j_class_j_gradebook_1j_class_ida',
  'type' => 'id',
  'source' => 'non-db',
  'vname' => 'LBL_J_CLASS_J_GRADEBOOK_1_FROM_J_GRADEBOOK_TITLE_ID',
  'id_name' => 'j_class_j_gradebook_1j_class_ida',
  'link' => 'j_class_j_gradebook_1',
  'table' => 'j_class',
  'module' => 'J_Class',
  'rname' => 'id',
  'reportable' => false,
  'side' => 'right',
  'massupdate' => false,
  'duplicate_merge' => 'disabled',
  'hideacl' => true,
  'required' => true,
  
);


    $dictionary["J_Gradebook"]["fields"]["description"]['cols'] = 25 ;
    //add custom relationship J_Gradebook - J_GradebookDetail by Lam Hai
    $dictionary['Contact']['fields']['j_gradebook_j_gradebookdetail'] = array(
        'name' => 'j_gradebook_j_gradebookdetail',
        'type' => 'link',
        'relationship' => 'j_gradebook_j_gradebookdetail',
        'module' => 'J_GradebookDetail',
        'bean_name' => 'J_GradebookDetail',
        'source' => 'non-db',
        'vname' => 'LBL_GRADEBOOK_DETAIL',
    );
    
    $dictionary['Contact']['relationships']['j_gradebook_j_gradebookdetail'] = array(
        'lhs_module'        => 'J_Gradebook',
        'lhs_table'            => 'j_gradebook',
        'lhs_key'            => 'id',
        'rhs_module'        => 'J_GradebookDetail',
        'rhs_table'            => 'j_gradebookdetail',
        'rhs_key'            => 'gradebook_id',
        'relationship_type'    => 'one-to-many',
    );
    //end


?>