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

class ViewLanguages extends SugarView
{
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;

    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>",
    	   $mod_strings['LBL_MANAGE_LANGUAGES']
    	   );
    }

    /**
	 * @see SugarView::preDisplay()
	 */
	public function preDisplay()
	{
	    global $current_user;

	    if (!is_admin($current_user)) {
	        sugar_die("Unauthorized access to administration.");
        }
	}

    /**
	 * @see SugarView::display()
	 */
	public function display()
	{
        global $mod_strings;
        global $app_list_strings;
        global $app_strings;
        global $sugar_config;
        
        $disabled = array();
        $disabled_list = array();
        if ( isset($sugar_config['disabled_languages'])) {
            if(!is_array($sugar_config['disabled_languages'])){
                $disabled_list = array_flip(explode(',', $sugar_config['disabled_languages']));
            }else{
                 $disabled_list = array_flip($sugar_config['disabled_languages']);
            }
        }
        foreach ($sugar_config['languages'] as $key=>$value)
        {
            if(isset($disabled_list[$key])) {
                $disabled[] = array("module" => $key, 'label' => $value);
            } else {
                $enabled[] = array("module" => $key, 'label' => $value);
            }
        }

        $this->ss->assign('APP', $GLOBALS['app_strings']);
        $this->ss->assign('MOD', $GLOBALS['mod_strings']);
        $this->ss->assign('enabled_langs', json_encode($enabled));
        $this->ss->assign('disabled_langs', json_encode($disabled));
        $this->ss->assign('title',$this->getModuleTitle(false));

        echo $this->ss->fetch('modules/Administration/templates/Languages.tpl');
    }
}
