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


class HolidaysViewEdit extends ViewEdit 
{
    /**
    * @see SugarView::display()
    */
    public function display() 
    {
        global $beanFiles, $mod_strings;

        // the user admin (MLA) cannot edit any administrator holidays
        global $current_user;
        if(isset($_REQUEST['record'])){
            //	 		$result = $GLOBALS['db']->query("SELECT is_admin FROM users WHERE id=(SELECT person_id FROM holidays WHERE id='$_REQUEST[record]')");
            //			$row = $GLOBALS['db']->fetchByAssoc($result);
            //			if(!is_admin($current_user)&& $current_user->isAdminForModule('Users')&& $row['is_admin']==1){
            //				sugar_die('Unauthorized access');
            //			}
        }

        $this->ev->process();
        if(!($GLOBALS['current_user']->isAdmin()))
            die();

        if ($_REQUEST['return_module'] == 'Project'){

            $projectBean = new Project();

            $projectBean->retrieve($_REQUEST['return_id']);

            $userBean = new User();
            $contactBean = new Contact();

            $projectBean->load_relationship("user_resources");
            $userResources = $projectBean->user_resources->getBeans($userBean);
            $projectBean->load_relationship("contact_resources");
            $contactResources = $projectBean->contact_resources->getBeans($contactBean);

            ksort($userResources);
            ksort($contactResources);	

            $this->ss->assign("PROJECT", true);
            $this->ss->assign("USER_RESOURCES", $userResources);
            $this->ss->assign("CONTACT_RESOURCES", $contactResources);

            $this->ss->assign("MOD", $mod_strings);

            $holiday_js = "<script type='text/javascript'>\n";
            $holiday_js .= $projectBean->resourceSelectJS();
            $holiday_js .= "\n</script>";

            echo $holiday_js;
        }

        echo '<h2 style="text-align:center;"> Set Public Holidays</h2>';
        echo '<script type="text/javascript" src="custom/include/javascripts/MultiDatesPicker/js/jquery.ui.core.js"></script>';
        echo '<script type="text/javascript" src="custom/include/javascripts/MultiDatesPicker/js/jquery.ui.datepicker.js"></script>';
        echo '<script type="text/javascript" src="custom/include/javascripts/MultiDatesPicker/js/jquery-ui.multidatespicker.js"></script>';
        echo $this->ev->display();
    }
}