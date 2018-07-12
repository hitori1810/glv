<?php 
// created: 2016-05-23 03:55:52
$dictionary['lead_c_sms'] =  array(
							  'relationships' => array (
								'lead_c_sms' => 
								array (
								'lhs_module'=> 'Leads', 
								'lhs_table'=> 'leads', 
								'lhs_key' => 'id',
								'rhs_module'=> 'C_SMS', 
								'rhs_table'=> 'c_sms', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'Leads',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>