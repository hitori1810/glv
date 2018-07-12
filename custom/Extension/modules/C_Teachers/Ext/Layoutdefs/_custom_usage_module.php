<?php 
 // created: 2016-05-23 03:55:52

							$layout_defs['C_Teachers']['subpanel_setup']['c_teachers_c_sms'] = array (
							  'order' => 250,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'c_teachers_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							?>