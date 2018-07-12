<?php

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


require_once('include/MVC/View/views/view.detail.php');

class ProjectViewTemplatesDetail extends ViewDetail 
{
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   $this->_getModuleTitleListParam($browserTitle),
    	   "<a href='index.php?module=Project&action=EditView&record={$this->bean->id}'>{$this->bean->name}</a>",
    	   $mod_strings['LBL_PROJECT_TEMPLATE']
    	   );
    }
    
	function display() 
	{
 		global $beanFiles;
		require_once($beanFiles['Project']);

		$focus = new Project();
		$focus->retrieve($_REQUEST['record']);

		global $app_list_strings, $current_user, $mod_strings;
		$this->ss->assign('APP_LIST_STRINGS', $app_list_strings);

		if($current_user->id == $focus->assigned_user_id || $current_user->is_admin){
			$this->ss->assign('OWNER_ONLY', true);
		}
		else{
			$this->ss->assign('OWNER_ONLY', false);
		}
		if(ACLController::checkAccess('ProjectTask', 'edit', true)) {
			$this->ss->assign('EDIT_RIGHTS_ONLY', true);
		}
		else{
			$this->ss->assign('EDIT_RIGHTS_ONLY', false);
		}

		$this->ss->assign('SAVE_AS', $mod_strings['LBL_SAVE_AS_PROJECT']);
		$this->ss->assign("IS_TEMPLATE", 1);
 		parent::display();
 	}

 	/**
     * @see SugarView::_displaySubPanels()
     */
    protected function _displaySubPanels()
    {
    	require_once ('include/SubPanel/SubPanelTiles.php');
   	 	$subpanel = new SubPanelTiles( $this->bean, 'ProjectTemplates' );
    	echo $subpanel->display( true, true );
    }

}
