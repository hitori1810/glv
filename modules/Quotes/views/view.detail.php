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

require_once('include/MVC/View/views/view.detail.php');

class QuotesViewDetail extends ViewDetail 
{
    /**
 	 * @see SugarView::display()
 	 */
 	public function display() 
 	{
		global $beanFiles;
		require_once($beanFiles['Quote']);
		require_once($beanFiles['TaxRate']);
		require_once($beanFiles['Shipper']);

		$this->bean->load_relationship('product_bundles');
        $product_bundle_list = $this->bean->product_bundles->getBeans();
        usort($product_bundle_list, array('ProductBundle', 'compareProductBundlesByIndex'));

        $this->ss->assign('ordered_bundle_list', $product_bundle_list);
		$currency = new Currency();
		$currency->retrieve($this->bean->currency_id);
		$this->ss->assign('CURRENCY_SYMBOL', $currency->symbol);
		$this->ss->assign('CURRENCY', $currency->iso4217);
		$this->ss->assign('CURRENCY_ID', $currency->id);
 		
 		if(!(strpos($_SERVER['HTTP_USER_AGENT'],'Mozilla/5') === false)) {
			$this->ss->assign('PDFMETHOD', 'POST');
		} else {
			$this->ss->assign('PDFMETHOD', 'GET');
		}
		
		global $app_list_strings, $current_user;
		$this->ss->assign('APP_LIST_STRINGS', $app_list_strings);
		$this->ss->assign('gridline', $current_user->getPreference('gridline') == 'on' ? '1' : '0');

 		parent::display();
		
 	}
}

