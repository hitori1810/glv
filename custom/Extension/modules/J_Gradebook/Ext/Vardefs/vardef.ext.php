<?php
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
