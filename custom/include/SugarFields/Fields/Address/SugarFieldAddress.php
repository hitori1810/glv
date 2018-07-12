<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/SugarFields/Fields/Base/SugarFieldBase.php');
class SugarFieldAddress extends SugarFieldBase {

    function getDetailViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);
        global $app_strings;
        if(!isset($displayParams['key'])) {
           $GLOBALS['log']->debug($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);	
           $this->ss->trigger_error($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);
           return;
        }
        
        //Allow for overrides.  You can specify a Smarty template file location in the language file.
        if(isset($app_strings['SMARTY_ADDRESS_DETAILVIEW'])) {
           $tplCode = $app_strings['SMARTY_ADDRESS_DETAILVIEW'];
           return $this->fetch($tplCode);	
        }
        
        return $this->fetch($this->findTemplate('DetailView'));
    }
    
    function getEditViewSmarty($parentFieldArray, $vardef, $displayParams, $tabindex) {
        $this->setup($parentFieldArray, $vardef, $displayParams, $tabindex);        
        global $app_strings;
        if(!isset($displayParams['key'])) {
           $GLOBALS['log']->debug($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);	
           $this->ss->trigger_error($app_strings['ERR_ADDRESS_KEY_NOT_SPECIFIED']);
           return;
        }
        
        //Allow for overrides.  You can specify a Smarty template file location in the language file.
        if(isset($app_strings['SMARTY_ADDRESS_EDITVIEW'])) {
           $tplCode = $app_strings['SMARTY_ADDRESS_EDITVIEW'];
           return $this->fetch($tplCode);	
        }       
        /*if($displayParams['key'] == 'shipping' || $displayParams['key'] == 'alt') {
            $this->ss->assign('GOOGLEPLUGIN',"<script type='text/javascript' src='https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true&libraries=places'></script>");
            $this->ss->assign('ADRESSHELPER',"<script type='text/javascript' src='custom/include/javascripts/AddressHelper.js'></script>");
        }*/
        return $this->fetch($this->findTemplate('EditView'));      
    }
    
}
?>
