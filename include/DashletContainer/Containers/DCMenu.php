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


class DCMenu extends DashletContainer
{
    protected function getMenuItem($module)
    {
        $imageURL = SugarThemeRegistry::current()->getImageURL("icon_{$module}_bar_32.png");
        $imageName = "icon_{$module}_bar_32.png";
        if (empty($imageURL))
            $imageName = "icon_generic_bar_32.png";
        $module_mod_strings = return_module_language($GLOBALS['current_language'], $module);
        if ( isset($module_mod_strings['LNK_NEW_RECORD']) )
            $createRecordTitle = $module_mod_strings['LNK_NEW_RECORD'];
        elseif ( isset($module_mod_strings['LNK_NEW_'.strtoupper($GLOBALS['beanList'][$module])]) )
            $createRecordTitle = $module_mod_strings['LNK_NEW_'.strtoupper($GLOBALS['beanList'][$module])];
        elseif ( strtoupper($GLOBALS['beanList'][$module]) == 'ACASE' && isset($module_mod_strings['LNK_NEW_CASE']) )
            $createRecordTitle = $module_mod_strings['LNK_NEW_CASE'];
        else
            $createRecordTitle = $GLOBALS['app_strings']['LBL_CREATE_BUTTON_LABEL'].' '.$module_mod_strings['LBL_MODULE_NAME'];

        $image = SugarThemeRegistry::current()->getImage($imageName, "class='icon' alt='{$createRecordTitle}' title='{$createRecordTitle}' id='dcMenu_{$module}_quick_create_icon'");
        return array('module'=>$module,'createRecordTitle'=>$createRecordTitle, 'image' => $image);

    }

    protected function getDynamicMenuItem($def)
    {
        if (!empty($def['icon']))
        {
            $imageName = basename($def['icon']);
        } else {
            $imageName = "icon_generic_bar_32.png";
        }

        $module = !empty($def['module']) ? $def['module'] : "";
        $label = isset($def['label']) ? translate($def['label'], $module) : "";
        $modal = isset($def['modal']) ? $def['modal'] : true;
        $action = isset($def['action']) ? $def['action'] : "DCMenu.menu('$module','$label', $modal); return false;";
        $script = isset($def['script_url']) ? '<script type="text/javascript" src="' . $def['script_url'] . '"></script>' : "";
        $url = "javascript: $action";
        $image = SugarThemeRegistry::current()->getLink($url, $label, '', $imageName, 'class="icon"');
        return array('script'=>$script, 'image' => $image);
    }


    public function getSearchIcon() {
        $iconSearchUrl = "javascript: DCMenu.spot(document.getElementById('sugar_spot_search').value);";
        $iconSearch = SugarThemeRegistry::current()->getLink($iconSearchUrl, '', 'class="searchIconLink"', "", '',null,null,'','','<i class="icon icon-search"></i>');
        return $iconSearch;
    }

    public function getNotifications()
    {
        global $current_user;

        $iconImage = "index.php?entryPoint=download&id=".$current_user->picture."&type=SugarFieldImage&isTempFile=1&isProfile=1";
        $code = '';
        $class = ' class="none"';
        //        if( is_admin($current_user) )
        //        {
        $unreadNotifications = 0;
        /*            $shouldSkip = sugar_cache_retrieve('dcmenu_check_notifications');
        if ( $shouldSkip != '1' )
        {
        require_once('modules/Notifications/Notifications.php');
        $n = new Notifications();
        $unreadNotifications = $n->getSystemNotificationsCount();
        if ( $unreadNotifications == 0 )
        sugar_cache_put('dcmenu_check_notifications','1');
        } //Comment by Lap nguyen **/
        //***Customize Notify - By Lap nguyen
        require_once('modules/Notifications/Notifications.php');
        $n = new Notifications();
        $unreadNotifications = $n->getUnreadNotificationCountForUser($current_user);


        if($unreadNotifications > 0)
        {
            $code = '<div id="notifCount" class="notifCount">'.$unreadNotifications.'</div>';
            $class = "";
        }elseif($unreadNotifications == 0){

            $code = '<div id="notifCount" class="notifNoneCount">'.$unreadNotifications.'</div>';
            $class = "";
        }

        $image = '<span class="dc_notif_icon" border="0" alt="'.$unreadNotifications.'" style="background-image: url('.$iconImage.');  "></span>';
        //        }
        //        else
        //        {
        //            $image = '<span class="dc_notif_icon" border="0" style="background-image: url('.$iconImage.');  "></span>';
        //        }
        //
        return array('class'=>$class,'icon'=>$image, 'code'=> $code);
    }


    public function getScript() {
        $record = !empty($_REQUEST['record'])?$_REQUEST['record']:'';
        $module = !empty($_REQUEST['module'])?$_REQUEST['module']:'';


        $action = $GLOBALS['app']->controller->action;
        $html = getVersionedScript('include/DashletContainer/Containers/DCMenu.js');

        if (!is_file(sugar_cached("include/javascript/sugar_field_grp.js"))) {
            $_REQUEST['root_directory'] = ".";
            require_once("jssource/minify_utils.php");
            ConcatenateFiles(".");
        }
        $html .= getVersionedScript('cache/include/javascript/sugar_field_grp.js');
        $html .= <<<EOQ
        <script>
        YUI({combine: true, timeout: 10000, base:"include/javascript/yui3/build/", comboBase:"index.php?entryPoint=getYUIComboFile&"}).use('node-base', 'event-key', function(Y){
            function init(){
                DCMenu.module = '$module';
                DCMenu.record = '$record';
                DCMenu.action = '$action';

                // store the return value from Y.on to remove the listener later
                var handle = Y.on('key', function(e) {
                    DCMenu.spot(document.getElementById('sugar_spot_search').value);
                    e.halt();
                 }, '#sugar_spot_search', 'down:13', Y);


            }
            YAHOO.util.Event.onDOMReady(init);

        });
        </script>
EOQ;

        return $html;
    }

    public function getLayout() {

    }

    /**
    * @return array
    */
    public function getMenus()
    {
        $DCActions = array();

        $actions_path = SugarAutoLoader::existingCustomOne("include/DashletContainer/Containers/DCActions.php");
        if(!empty($actions_path)) {
            include $actions_path;
        }

        foreach(SugarAutoLoader::existing('custom/application/Ext/DashletContainer/Containers/dcactions.ext.php') as $file) {
            include $file;
        }

        $filterDCActions = array();
        foreach($DCActions as $action)
        {
            if(ACLController::checkAccess($action, 'edit', true))
            {
                $filterDCActions[] = $this->getMenuItem($action);
            }
        }

        return $filterDCActions;

    }

    /**
    * Get the partner icon menus that are displayed in the bottom right footer.
    *
    * @return array
    */
    public function getPartnerIconMenus()
    {
        $dynamicDCActions = array();
        $filterDynamicDCActions = array();
        $dyn_actions_path = SugarAutoLoader::existingCustomOne("include/DashletContainer/Containers/DynamicDCActions.php");
        if($dyn_actions_path) {
            include $dyn_actions_path;
        }

        foreach(SugarAutoLoader::existing('custom/application/Ext/DashletContainer/Containers/dcactions.ext.php') as $file)
        {
            include $file;
        }

        foreach($dynamicDCActions as $def)
        {
            $filterDynamicDCActions[] = $this->getDynamicMenuItem($def);
        }

        return $filterDynamicDCActions;
    }
}
