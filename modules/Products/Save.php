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

 * Description:  
 ********************************************************************************/






$focus = new Product();

$focus->retrieve($_REQUEST['record']);
	if(!$focus->ACLAccess('Save')){
		ACLController::displayNoAccess(true);
		sugar_cleanup(true);
	}
$the_product_template_id = $focus->product_template_id;

require_once('include/formbase.php');
$focus = populateFromPost('', $focus);

if (!empty($_REQUEST['product_template_id']) && $_REQUEST['product_template_id'] != $the_product_template_id ) {
	global $beanFiles;
	require_once($beanFiles['ProductTemplate']);
	$template = new ProductTemplate();	
	$template->retrieve($_REQUEST['product_template_id']);
	foreach($focus->template_fields as $field)
	{
		if(isset($template->$field))
		{
			$GLOBALS['log']->debug("$field is ".$template->$field);
			$focus->$field = $template->$field;
			
		}
	}
}

if ($_REQUEST['discount_select']) {
    $focus->deal_calc= $_REQUEST['discount_amount']/100*$_REQUEST['discount_price'];
}
else {
    $focus->deal_calc= $_REQUEST['discount_amount'];
}

if (!empty($focus->pricing_formula)
	|| !empty($focus->cost_price)
	|| !empty($focus->list_price)
	|| !empty($focus->discount_price)
	|| !empty($focus->pricing_factor)
	|| !empty($focus->discount_amount)
	|| !empty($focus->discount_select)) {
	require_once('modules/ProductTemplates/Formulas.php');
    refresh_price_formulas();
	global $price_formulas;
	if (isset($price_formulas[$focus->pricing_formula])) 
	{
		include_once ($price_formulas[$focus->pricing_formula]);
		$formula = new $focus->pricing_formula;
		$focus->discount_price = $formula->calculate_price($focus->cost_price,$focus->list_price,$focus->discount_price,$focus->pricing_factor);
	}
}

$focus->unformat_all_fields();
$focus->save();

$return_id = $focus->id;

if(isset($_REQUEST['return_module']) && $_REQUEST['return_module'] != "") $return_module = $_REQUEST['return_module'];
else $return_module = "Products";
if(isset($_REQUEST['return_action']) && $_REQUEST['return_action'] != "") $return_action = $_REQUEST['return_action'];
else $return_action = "DetailView";
if(isset($_REQUEST['return_id']) && $_REQUEST['return_id'] != "") $return_id = $_REQUEST['return_id'];

$GLOBALS['log']->debug("Saved record with id of ".$return_id);

header("Location: index.php?action=$return_action&module=$return_module&record=$return_id");
?>
