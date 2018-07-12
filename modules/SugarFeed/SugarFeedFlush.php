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





class SugarFeedFlush {
    function flushStaleEntries($bean, $event, $arguments) {
        $admin = new Administration();
        $admin->retrieveSettings();

        $timedate = TimeDate::getInstance();

        $currDate = $timedate->nowDbDate();
        if (isset($admin->settings['sugarfeed_flushdate']) && $admin->settings['sugarfeed_flushdate'] != $currDate ) {
            global $db;
            if ( ! isset($db) ) { $db = DBManagerFactory::getInstance(); }

            $tmpTime = time();
            $tmpSF = new SugarFeed();
            $flushBefore = $timedate->asDbDate($timedate->getNow()->modify("-14 days")->setTime(0,0));
            $db->query("DELETE FROM ".$tmpSF->table_name." WHERE date_entered < '".$db->quote($flushBefore)."'");
            $admin->saveSetting('sugarfeed','flushdate',$currDate);
            // Flush the cache
            $admin->retrieveSettings(FALSE,TRUE);
        }
    }
}
