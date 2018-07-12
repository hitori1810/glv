<?php
 if(!defined('sugarEntry'))define('sugarEntry', true);
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

//change directories to where this file is located.
//this is to make sure it can find dce_config.php
chdir(dirname(__FILE__));

require_once('include/entryPoint.php');
require_once('include/SugarSearchEngine/SugarSearchEngineAbstractBase.php');

if (SugarSearchEngineAbstractBase::isSearchEngineDown())
{
    sugar_die('Is fts server down?'."\n");
}

$sapi_type = php_sapi_name();
if (substr($sapi_type, 0, 3) != 'cli') {
    sugar_die("silentFTSIndex.php is CLI only.");
}

if(empty($current_language)) {
	$current_language = $sugar_config['default_language'];
}

$app_list_strings = return_app_list_strings_language($current_language);
$app_strings = return_application_language($current_language);

global $current_user;
$current_user = new User();
$current_user->getSystemUser();

// Pop off the filename
array_shift($argv);

// Don't wipe the index if we're just doing individual modules
$clearData = empty($argv);

// Allows for php -f silentFTSIndex.php Bugs Cases
$modules = $argv;

require_once('include/SugarSearchEngine/SugarSearchEngineFullIndexer.php');
$indexer = new SugarSearchEngineFullIndexer();
$results = $indexer->performFullSystemIndex($modules, $clearData);
