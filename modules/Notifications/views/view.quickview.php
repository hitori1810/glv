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
* Copyright (C) 2004-2014 SugarCRM Inc.  All rights reserved.
********************************************************************************/


class ViewQuickview extends SugarView{
    function ViewQuickview(){
        parent::SugarView();
    }

    function display()
    {
        if(!empty($_REQUEST['record'])){
            if($_REQUEST['mark'] == 'Read'){
                $GLOBALS['db']->query("UPDATE notifications SET is_read = 1 WHERE id = '{$_REQUEST['record']}' AND deleted = 0");
                $out = json_encode(array(
                    "success" => "1",
                    "status" => 'Read',
                ));
            }else{
               $GLOBALS['db']->query("UPDATE notifications SET is_read = 0 WHERE id = '{$_REQUEST['record']}' AND deleted = 0");
                $out = json_encode(array(
                    "success" => "1",
                    "status" => 'Unread',
                ));
            }

        }else{
            $out = json_encode(array("success" => "0",));
        }
        ob_clean();
        print($out);
        sugar_cleanup(true);
    }

    //    function _formatNotificationForDisplay($notification)
    //    {
    //        global $app_strings;
    //        $this->ss->assign('APP', $app_strings);
    //        $this->ss->assign('focus', $notification);
    //        return $this->ss->fetch("modules/Notifications/tpls/detailView.tpl");
    //    }
}