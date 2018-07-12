<?php 
 //WARNING: The contents of this file are auto-generated


 // created: 2015-08-05 14:40:26
$layout_defs["Contacts"]["subpanel_setup"]['contacts_contacts_1'] = array (
  'order' => 100,
  'module' => 'Contacts',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_CONTACTS_CONTACTS_1_FROM_CONTACTS_R_TITLE',
  'get_subpanel_data' => 'contacts_contacts_1',
  'top_buttons' => 
  array (
    0 => 
    array (
      'widget_class' => 'SubPanelTopButtonQuickCreate',
    ),
    1 => 
    array (
      'widget_class' => 'SubPanelTopSelectButton',
      'mode' => 'MultiSelect',
    ),
  ),
);


 // created: 2014-07-15 14:40:52
$layout_defs["Contacts"]["subpanel_setup"]['c_classes_contacts_1'] = array (
  'order' => 51,
  'module' => 'C_Classes',
  'subpanel_name' => 'default',
  'sort_order' => 'asc',
  'sort_by' => 'id',
  'title_key' => 'LBL_C_CLASSES_CONTACTS_1_FROM_C_CLASSES_TITLE',
  'get_subpanel_data' => 'c_classes_contacts_1',
  'top_buttons' => 
  array (
  ),
);


 // created: 2015-09-23 15:23:34
// $layout_defs["Contacts"]["subpanel_setup"]['leads_contacts_1'] = array (
//   'order' => 100,
//   'module' => 'Leads',
//   'subpanel_name' => 'default',
//   'sort_order' => 'asc',
//   'sort_by' => 'id',
//   'title_key' => 'LBL_LEADS_CONTACTS_1_FROM_LEADS_TITLE',
//   'get_subpanel_data' => 'leads_contacts_1',
//   'top_buttons' => 
//   array (
//    0 => 
//    array (
//      'widget_class' => 'SubPanelTopButtonQuickCreate',
//    ),
//    1 => 
//    array (
//      'widget_class' => 'SubPanelTopSelectButton',
//      'mode' => 'MultiSelect',
//    ),
  //),
//);

 
 // created: 2016-05-23 03:55:52

							$layout_defs['Contacts']['subpanel_setup']['contact_c_sms'] = array (
							  'order' => 250,
							  'module' => 'C_SMS',
							  'subpanel_name' => 'default',
							  'sort_order' => 'asc',
							  'sort_by' => 'date_entered',
							  'title_key' => 'LBL_C_SMS',
							  'get_subpanel_data' => 'contact_c_sms',
							  'top_buttons' =>
							  array (
								    array('widget_class' => 'SubPanelSMSButton')
							  ),
							);
							
?>