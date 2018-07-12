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

if (!$GLOBALS['current_user']->isAdminForModule('Users')) sugar_die($GLOBALS['app_strings']['ERR_NOT_ADMIN']);	

global $mod_strings;
global $app_list_strings;
global $app_strings;
global $current_user;

$focus = new TeamNotice();

$is_edit = true;
if(!empty($_REQUEST['record'])) {
    $result = $focus->retrieve($_REQUEST['record']);
 
}
$GLOBALS['log']->info("TeamNotice list view");
global $theme;

$xtpl=new XTemplate ('modules/TeamNotices/DisplayNotices.html');
$ListView = new ListView();
$ListView->initNewXTemplate( 'modules/TeamNotices/DisplayNotices.html',$mod_strings);
$today = db_convert("'".$timedate->nowDbDate()."'", 'date');

$ListView->setHeaderTitle(translate('LBL_NOTICES', 'TeamNotices'));
$ListView->setQuery($focus->table_name.".date_start <= $today and ".$focus->table_name.".date_end >= $today and ".$focus->table_name.'.status=\'Visible\'', "", "date_start", "TEAMNOTICE");
$ListView->processListView($focus, "main", "TEAMNOTICE");




?>
