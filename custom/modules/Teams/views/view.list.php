<?php
require_once('include/MVC/View/views/view.list.php');

class TeamsViewList extends ViewList
{
    public function preDisplay(){
        global $current_user;
        //bug #46690: Developer Access to Users/Teams/Roles
        if (!is_admin($current_user))
            sugar_die("Unauthorized access to administration.");   


        //Customize Team Management - By Lap Nguyen
        include_once("custom/modules/Teams/_helper.php");
        $ss = new Sugar_Smarty();

        $nodes = getTeamNodes();
        $ss->assign("MOD", $GLOBALS['mod_strings']);
        $ss->assign("NODES", json_encode($nodes));
        $ss->assign("APPS", $GLOBALS['app_strings']);
        $ss->assign("CURRENT_USER_ID", $current_user->id);

        $team = BeanFactory::getBean('Teams',"1");

        //Prepare Users List
        $html   = "";     

        if($team_id != "1"){
            $html .= '<input class="button primary" type="button" value="'.translate('LBL_ADD_USER','Users').'" id="add_user_bt"><br><br>';
        } 

        $html .= "<table width='100%' class='table table-striped table-bordered dataTable' id='celebs'>";
        $html .= "<thead><tr>
        <th width='15%'>".translate('LBL_NAME','Users')."</th>
        <th width='15%'>".translate('LBL_USER_NAME','Users')."</th>
        <th width='15%'>".translate('LBL_TITLE','Users')."</th>
        <th width='15%'>".translate('LBL_DEFAULT_TEAM','Users')."</th>
        <th width='20%'>".translate('LBL_DEFAULT_SUBPANEL_TITLE','Roles')."</th>
        <th width='10%'>".$mod_strings['LBL_STATUS']."</th>
        <th style='min-width: 50px; text-align:center;'></th>
        </tr></thead>
        <tbody>"; 
        $html   .= "</tbody>";
        $html   .= "</table>";       
//
        echo $ss->fetch('custom/modules/Teams/tpls/TeamManagement.tpl');
        $sv = new SugarView();
        $sv->displayFooter();

        die(); // Don't want to show Listview anymore.     
    }
}
