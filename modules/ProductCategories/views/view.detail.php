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


loadParentView('edit');

class ProductCategoriesViewDetail extends ViewEdit 
{
    /**
 	 * @see SugarView::display()
 	 */
 	public function display() 
 	{
		include ('modules/ProductCategories/ListView.php'); 		
		//Disable the VCR Control for this module since we already have a ListView 		
 		$this->ev->showVCRControl = false;
 		parent::display();
 	}
}
