<?php 
// created: 2016-05-23 03:55:52
$dictionary['contact_c_sms'] =  array(
							  'relationships' => array (
								'contact_c_sms' => 
								array (
								'lhs_module'=> 'Contacts', 
								'lhs_table'=> 'contacts', 
								'lhs_key' => 'id',
								'rhs_module'=> 'C_SMS', 
								'rhs_table'=> 'c_sms', 
								'rhs_key' => 'parent_id',
								'relationship_type'=>'one-to-many', 
								'relationship_role_column'=>'parent_type',
								'relationship_role_column_value'=>'Contacts',
									),
								  ),
								'fields' => '',
							  'indices' => '',
							  'table' => '',								
							);
							?>