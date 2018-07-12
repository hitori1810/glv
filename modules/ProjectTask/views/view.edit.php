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
 * to provide customization specific to the Calls module.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class ProjectTaskViewEdit extends ViewEdit 
{
    /**
 	 * @see SugarView::display()
 	 */
 	public function display() 
 	{
		global $beanFiles;
		require_once($beanFiles['ProjectTask']);
		
		$focus = new ProjectTask();
		if (isset($_REQUEST['record'])){
			$focus->retrieve($_REQUEST['record']);
		}
		
		$this->ss->assign('resource', $focus->getResourceName());
		
		if (isset($_REQUEST['fromGrid']) && $_REQUEST['fromGrid'] == '1'){
			$this->ss->assign('project_id', $focus->project_id);
			$this->ss->assign('FROM_GRID', true);
		}
		else{
			$this->ss->assign('FROM_GRID', false);
		}
		
 		parent::display();
 	}
}
