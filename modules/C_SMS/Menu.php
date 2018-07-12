<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

global $mod_strings;
if(ACLController::checkAccess('C_SMS', 'list', true))$module_menu[]=Array("index.php?module=C_SMS&action=index&return_module=C_SMS&return_action=DetailView", $GLOBALS['mod_strings']['LNK_LIST'],"C_SMS");
?>