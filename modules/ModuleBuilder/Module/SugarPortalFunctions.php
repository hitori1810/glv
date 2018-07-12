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



class SugarPortalFunctions{

	function getNodes()
	{
	    global $mod_strings;
		$nodes = array();
        $nodes[] = array( 'name'=>$mod_strings['LBL_PORTAL_CONFIGURE'], 'action'=>'module=ModuleBuilder&action=portalconfig','imageTitle' => 'SPSync', );
        $nodes[] = array( 'name'=>$mod_strings['LBL_PORTAL_THEME'], 'action'=>'module=ModuleBuilder&action=portaltheme','imageTitle' => 'SPUploadCSS', );
		return $nodes;
	}
	
	
	
	
	
	
}
?>