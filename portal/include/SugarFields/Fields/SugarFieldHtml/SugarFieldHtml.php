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

require_once('include/SugarFields/Fields/SugarFieldBase/SugarFieldBase.php');

class SugarFieldHtml extends SugarFieldBase {
   
	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams){
		$vardef['value'] = $this->getVardefValue($vardef);
        $this->setup($parentFieldArray, $vardef, $displayParams);
        return $this->ss->fetch('include/SugarFields/Fields/SugarFieldHtml/SugarFieldHtmlDetailViewSmarty.tpl');
    }
    
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams){
    	$vardef['value'] = $this->getVardefValue($vardef);
        $this->setup($parentFieldArray, $vardef, $displayParams);
        return $this->ss->fetch('include/SugarFields/Fields/SugarFieldHtml/SugarFieldHtmlDetailViewSmarty.tpl');
    }
    
    function getVardefValue($vardef){
    	if(empty($vardef['value'])){
			if(!empty($vardef['default']))
				return from_html($vardef['default']);
			elseif(!empty($vardef['default_value']))
				return from_html($vardef['default_value']);
		} else {
			return from_html($vardef['value']);
		}
    }
}
?>