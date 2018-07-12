<?php 
 // created: 2016-05-23 03:55:52

							$layout_defs['Leads']['subpanel_setup']['lead_c_sms'] = array (
							  'order' => 102,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'lead_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							?>