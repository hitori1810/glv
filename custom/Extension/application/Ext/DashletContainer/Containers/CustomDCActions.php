<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    
    /*
    *   CustomDCAction.php 
    *   Author: Hieu Nguyen
    *   Date: 2017-08-09
    *   Pupose: Allow to configure for quick create links in the top right menu
    */

    //$DCActions[] = 'AnyModule'; // Example 1: add a module to quick create group. Remember: this module should have quickcreatedefs.php to be displayed
    //unset($DCActions['AnyModule']); // Example 2: remove a module from quick create group

    // ========= BEGIN CONFIG ======== //


    // ========= END CONFIG ======== //

    // Hide disabled modules
    require('custom/include/DisabledModules.php');
	
    if(!empty($disabledModules)) {
        foreach($disabledModules as $moduleName) {
            unset($DCActions[$moduleName]);
        }
    }