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

require_once('include/SugarFields/Fields/SugarFieldVarchar/SugarFieldVarchar.php');

class SugarFieldText extends SugarFieldVarchar {

	function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams) {
        //do not call the url2html function if this is coming from kbdocs in portal
        //this would result in adding html even if it is already there
        if((isset($_REQUEST['module'])&& $_REQUEST['module'] == 'KBDocuments') &&  isset($GLOBALS['portal'])){
            $displayParams['url2html'] = false;
            $displayParams['nl2br'] = false;

        }else{
            $displayParams['url2html'] = true;
            $displayParams['nl2br'] = true;

        }
		return parent::getDetailViewSmarty($parentFieldArray, $vardef, $displayParams);
    }	
	
	function getEditViewSmarty($parentFieldArray, $vardef, $displayParams) {
        if(!isset($displayParams)) $displayParams = array();
        if(!isset($displayParams['rows'])) $displayParams['rows'] = 15;
        if(!isset($displayParams['cols'])) $displayParams['cols'] = 80;

        $this->setup($parentFieldArray, $vardef, $displayParams);

        return $this->ss->fetch('include/SugarFields/Fields/SugarFieldText/SugarFieldTextEditViewSmarty.tpl');
    }

}
?>