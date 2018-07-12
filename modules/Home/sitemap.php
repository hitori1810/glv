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

$sm = sm_build_array();
$sm_smarty = new Sugar_Smarty();

global $sugar_config;
if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
{
    $current_language = $_SESSION['authenticated_user_language'];
}
else
{
    $current_language = $sugar_config['default_language'];
}

$mod_strings = return_module_language($current_language, 'Home');
$sm_smarty->assign('CLOSE', isset($mod_strings['LBL_CLOSE_SITEMAP']) ? $mod_strings['LBL_CLOSE_SITEMAP'] : '');

// get the list_strings in order for module friendly name display.
$app_list_strings = return_app_list_strings_language($current_language);

foreach ($sm as $mod_dir_name => $links)
{
    $module_friendly_name = $app_list_strings['moduleList'][$mod_dir_name];
    $temphtml = "";
    $temphtml .= '<h4><a href="javascript:window.location=\'index.php?module='.$mod_dir_name.'&action=index\'">' . $module_friendly_name .'</a></h4><ul class=\'noBullet\'>';

    foreach ($links as $name => $href)
    {
        $temphtml .= '<li class=\'noBullet\'><a href="javascript:window.location=\''. $href .'\'">' . $name . ' ' . '</a></li>';
    }

    $temphtml .= '</ul>';
    $sm_smarty->assign(strtoupper($mod_dir_name), $temphtml);
}

// Specify the sitemap template to use; allow developers to override this with a custom one to add/remove modules
// from the list
echo $sm_smarty->fetchCustom('modules/Home/sitemap.tpl');

function sm_build_array()
{
    //if the sitemap array is already stored, then pass it back
    if (isset($_SESSION['SM_ARRAY']) && !empty($_SESSION['SM_ARRAY'])){
        return $_SESSION['SM_ARRAY'];
    }


    include("include/modules.php");
	global $sugar_config,$mod_strings;


	// Need to set up mod_strings when we iterate through module menus.
    $orig_modstrings = array();
    if(!empty($mod_strings))
    {
     $orig_modstrings = $mod_strings;
    }
    if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
    {
        $current_language = $_SESSION['authenticated_user_language'];
    }
    else
    {
        $current_language = $sugar_config['default_language'];
    }
	$exclude= array();		// in case you want to exclude any.
    $mstr_array = array();

	global $modListHeader;
	if(!isset($modListHeader))
	{
		global $current_user;
		if(isset($current_user))
		{
			$modListHeader = query_module_access_list($current_user);
		}
	}

    foreach($modListHeader as $key=>$val)
    {
        if(!empty($exclusion_array) && in_array($val,$exclude ))
        {
           continue;
        }
        else
        {
		    if (SugarAutoLoader::fileExists('modules/'.$val.'/Menu.php'))
		    {
                $mod_strings = return_module_language($current_language, $val);
                $module_menu = array();
                include('modules/'.$val.'/Menu.php');

                $tmp_menu_items = array();
                foreach($module_menu as $menu)
                {
               		if(isset($menu[0]) && !empty($menu[0]) && isset($menu[1]) && !empty($menu[1]) && trim($menu[0]) !='#')
               		{
                        $tmp_menu_items[$menu[1]] =$menu[0];
                    }
                }
                $mstr_array[$val] = $tmp_menu_items;
            }
        }
    }

	//reset the modstrings to current module
	$mod_strings = $orig_modstrings ;
    //store master array into session variable
    $_SESSION['SM_ARRAY'] = $mstr_array;
	return $mstr_array;
}
