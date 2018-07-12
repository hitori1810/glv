<?php 
// created: 2016-05-23 03:55:52
$dictionary['c_teachers_c_sms'] =  array(
							  'relationships' => array (
								'c_teachers_c_sms' => 
								array (
								'lhs_module'=> 'C_Teachers', 
								'lhs_table'=> 'c_teachers', 
								'lhs_key' => 'id',
								'rhs_module'=> 'C_SMS', 
								'rhs_table'=> 'c_sms', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'C_Teachers',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>