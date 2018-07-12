<?php 
// created: 2016-05-23 03:55:52
$dictionary['j_studentsituations_c_sms'] =  array(
							  'relationships' => array (
								'j_studentsituations_c_sms' => 
								array (
								'lhs_module'=> 'J_StudentSituations', 
								'lhs_table'=> 'j_studentsituations', 
								'lhs_key' => 'id',
								'rhs_module'=> 'C_SMS', 
								'rhs_table'=> 'c_sms', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'J_StudentSituations',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>