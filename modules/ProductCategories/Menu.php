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

global $mod_strings;
$module_menu = Array(
	Array("index.php?module=ProductTemplates&action=EditView&return_module=ProductTemplates&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT'],"Products"),
	Array("index.php?module=ProductTemplates&action=index&return_module=ProductTemplates&return_action=DetailView", $mod_strings['LNK_PRODUCT_LIST'],"Price_List"),
	Array("index.php?module=Manufacturers&action=EditView&return_module=Manufacturers&return_action=DetailView", $mod_strings['LNK_NEW_MANUFACTURER'],"Manufacturers"),
	Array("index.php?module=ProductCategories&action=EditView&return_module=ProductCategories&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT_CATEGORY'],"Product_Categories"),
	Array("index.php?module=ProductTypes&action=EditView&return_module=ProductTypes&return_action=DetailView", $mod_strings['LNK_NEW_PRODUCT_TYPE'],"Product_Types"),
    Array("index.php?module=Import&action=Step1&import_module=ProductCategories&return_module=ProductCategories&return_action=index", $mod_strings['LNK_IMPORT_PRODUCT_CATEGORIES'],"Import"),
	);

?>
