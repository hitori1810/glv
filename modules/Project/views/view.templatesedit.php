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


class ProjectViewTemplatesEdit extends ViewEdit 
{
 	/**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
	    $crumbs = array();
	    $crumbs[] = $this->_getModuleTitleListParam($browserTitle);
	    if(!empty($this->bean->id)){
	    	$crumbs[] =  "<a href='index.php?module=Project&action=EditView&record={$this->bean->id}'>{$this->bean->name}</a>";
	    }
	    $crumbs[] = $mod_strings['LBL_PROJECT_TEMPLATE'];
    	return $crumbs;
    }
    
	function display() 
	{
        $this->bean->is_template = 1;
        $this->ev->ss->assign("is_template", 1);

 		parent::display();
 	}
}
