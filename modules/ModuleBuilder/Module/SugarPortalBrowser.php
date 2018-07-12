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



require_once('modules/ModuleBuilder/Module/SugarPortalModule.php');
require_once('modules/ModuleBuilder/Module/SugarPortalFunctions.php');

class SugarPortalBrowser
{
    var $modules = array();

    function loadModules()
    {
        foreach(SugarAutoLoader::getDirFiles("modules", true) as $mdir) {
            // strip modules/ from name
            $mname = substr($mdir, 8);
            if(SugarAutoLoader::fileExists("$mdir/metadata/studio.php")  && $this->isPortalModule($mname)) {
                $this->modules[$mname] = new SugarPortalModule($mname);
            }
        }
    }

    function getNodes(){
        $nodes = array();
        $functions = new SugarPortalFunctions();
        $nodes = $functions->getNodes();
        $this->loadModules();
        $layouts = array();
        foreach($this->modules as $module){
            $layouts[$module->name] = $module->getNodes();
        }
        $nodes[] = array(
            'name'=> translate('LBL_LAYOUTS'),
            'imageTitle' => 'Layouts',
            'type'=>'Folder',
            'children'=>$layouts,
            'action'=>'module=ModuleBuilder&action=wizard&portal=1&layout=1');
        ksort($nodes);
        return $nodes;
    }

    /**
     * Runs through the views metadata directory to check for expected portal
     * files to verify if a given module is a portal module.
     *
     * This replaces the old file path checker that looked for
     * portal/modules/$module/metadata. We are now looking for
     * modules/$module/metadata/portal/views/(edit|list|detail).php
     *
     * @param string $module The module to check portal validity on
     * @return bool True if a portal/view/$type.php file was found
     */
    function isPortalModule($module)
    {
        // Create the path to search
        $path = "modules/$module/clients/portal/views/";

        // Handle it
        // Bug 55003 - Notes showing as a portal module because it has non
        // standard layouts
        $views = SugarPortalModule::getViewFiles();
        $viewFiles = array_keys($views);
        foreach ($viewFiles as $file) {
            if (SugarAutoLoader::fileExists($path . basename($file, '.php') . '/' . $file)) {
                return true;
            }
        }

        return false;
    }

}
?>