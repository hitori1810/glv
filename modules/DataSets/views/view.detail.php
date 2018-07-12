<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

/*********************************************************************************
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
 ********************************************************************************/

/*********************************************************************************

 * Description: This file is used to override the default Meta-data EditView behavior
 * to provide customization specific to the DataSets module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

require_once('include/MVC/View/views/view.detail.php');

class DataSetsViewDetail extends ViewDetail {
   
 	function DataSetsViewDetail(){
 		parent::ViewDetail();
 	}
 	
 	/**
 	 * display
     *
     */
 	function display() {
 		parent::display();
 		
		global $current_user, $app_strings, $mod_strings;
		
		if(isset($this->bean->query_id) && !empty($this->bean->query_id)){
			//CHECK FOR SUB-QUERIES
			$this->bean->check_interlock();
			//OUTPUT THE DATASET
			$data_set = new CustomQuery();
			$data_set->retrieve($this->bean->query_id);
			$QueryView = new ReportListView();
			$QueryView->initNewXTemplate( 'modules/CustomQueries/QueryView.html',$mod_strings);
			$QueryView->setHeaderTitle($this->bean->name);
		
			//below: make sure to aquire the custom layout headers if available
			$QueryView->export_type = "Ent";
			
			$QueryView->xTemplateAssign('EDIT_INLINE', SugarThemeRegistry::current()->getImage('edit_inline','align="absmiddle" border="0"',null,null,'.gif',$app_strings['LNK_EDIT']));

			$QueryView->xTemplateAssign('LEFTARROW_INLINE', SugarThemeRegistry::current()->getImage('calendar_previous','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_LEFT']));

			$QueryView->xTemplateAssign('RIGHTARROW_INLINE', SugarThemeRegistry::current()->getImage('calendar_next','align="absmiddle" border="0"', null,null,'.gif',$mod_strings['LBL_RIGHT']));

			$QueryView->setup($data_set, $this->bean, "main", "CUSTOMQUERY");
			$query_results = $QueryView->processDataSet();
		
			if($query_results['result']=="Error"){
			
				if (is_admin($current_user)){	
					echo "<font color=\"red\"><b>".$query_results['result_msg']."".$app_strings['ERROR_EXAMINE_MSG']."</font><BR>".$query_results['msg']."</b>";	
				} else {
					echo "<font color=\"red\"><b>".$query_results['result_msg']."</font></b><BR>";	
				}	
		
				
			}
			
			//end if there is even a query for the data set
			} else {
				echo "<font color=\"red\"><b>".$app_strings['NO_QUERY_SELECTED']."</font></b><BR>";	
			}	
 	} //display
}

?>