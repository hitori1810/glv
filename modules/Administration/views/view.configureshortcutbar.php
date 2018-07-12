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

/*********************************************************************************

 * Description:  TODO: To be written.
 * Portions created by SugarCRM are Copyright (C) SugarCRM, Inc.
 * All Rights Reserved.
 * Contributor(s): ______________________________________..
 ********************************************************************************/

class ViewConfigureshortcutbar extends SugarView
{
    /**
     * List of modules that should not be available for selection.
     *
     * @var array
     */
    private $blacklistedModules = array('EAPM', 'Users', 'Employees', 'PdfManager');
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

    	return array("<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>", $mod_strings['LBL_CONFIGURE_SHORTCUT_BAR']);
    }

    /**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
	{
	    global $current_user;

	    if (!is_admin($current_user))
        {
	        sugar_die("Unauthorized access to administration.");
        }
	}

    /**
	 * @see SugarView::display()
	 */
	public function display()
	{
        require_once("include/JSON.php");
        $json = new JSON();

        global $mod_strings;
        global $app_list_strings;
        global $app_strings;
        global $current_user;

        $title = getClassicModuleTitle(
                    "Administration",
                    array("<a href='index.php?module=Administration&action=index'>{$mod_strings['LBL_MODULE_NAME']}</a>",translate('LBL_CONFIGURE_SHORTCUT_BAR')),
                    false
                    );
        $msg = "";

        global $theme, $currentModule, $app_list_strings, $app_strings;
        $GLOBALS['log']->info("Administration ConfigureShortcutBar view");
        $actions_path = "include/DashletContainer/Containers/DCActions.php";

        //If save is set, save then let the user know if the save worked.
        if (!empty($_REQUEST['enabled_modules']))
        {
            $toDecode = html_entity_decode  ($_REQUEST['enabled_modules'], ENT_QUOTES);
            $modules = json_decode($toDecode);

            //fixing bug #49878: XSS - Administration, Configure Shortcut Bar, enabled_modules
            //prevent attempt of html-injection
            global $moduleList;
            foreach($modules as $key => $value)
            {
                if (!in_array($value, $moduleList))
                {
                    unset($modules[$key]);
                }
            }

            $actions_path = create_custom_directory($actions_path);
            if(!write_array_to_file("DCActions", $modules, $actions_path)) {
               echo translate("LBL_SAVE_FAILED");
            } else {
               echo "true";
            }

        }
        else
        {
            foreach(SugarAutoLoader::existingCustom($actions_path) as $file) {
                include $file;
            }
            //Start with the default module
            $availibleModules = $DCActions;
            //Next add the ones we detect as having quick create defs.
            $modules = $app_list_strings['moduleList'];
            foreach ($modules as $module => $modLabel)
            {
                if (SugarAutoLoader::existingCustom("modules/$module/metadata/quickcreatedefs.php"))
                   $availibleModules[$module] = $module;
            }

            $availibleModules = array_diff($availibleModules, $DCActions);

            $enabled = array();
            foreach($DCActions as $mod)
            {
                $enabled[] = array("module" => $mod, 'label' => translate($mod));
            }

            $disabled = array();
            foreach($availibleModules as $mod)
            {
                $disabled[] = array("module" => $mod, 'label' => translate($mod));
            }

            $enabled = $this->filterModules($enabled);
            $disabled = $this->filterModules($disabled);
            $this->ss->assign('APP', $GLOBALS['app_strings']);
            $this->ss->assign('MOD', $GLOBALS['mod_strings']);
            $this->ss->assign('title',  $title);

            $this->ss->assign('enabled_modules', $json->encode ( $enabled ));
            $this->ss->assign('disabled_modules',$json->encode ( $disabled));
            $this->ss->assign('description',  translate("LBL_CONFIGURE_SHORTCUT_BAR"));
            $this->ss->assign('msg',  $msg);

            echo $this->ss->fetch('modules/Administration/templates/ShortcutBar.tpl');
        }
    }

    protected function filterModules($moduleList)
    {
        $results = array();
        foreach($moduleList as $mod)
        {
            if(!in_array($mod['module'], $this->blacklistedModules))
                $results[] = $mod;
        }
        return $results;
    }
}
