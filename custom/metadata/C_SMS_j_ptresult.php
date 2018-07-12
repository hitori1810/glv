<?php 
// created: 2016-05-23 03:55:52
$dictionary['j_ptresult_c_sms'] =  array(
							  'relationships' => array (
								'j_ptresult_c_sms' => 
								array (
								'lhs_module'=> 'J_PTResult', 
								'lhs_table'=> 'j_ptresult', 
								'lhs_key' => 'id',
								'rhs_module'=> 'C_SMS', 
								'rhs_table'=> 'c_sms', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'J_PTResult',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>