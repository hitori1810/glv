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


require_once('include/SearchForm/SearchForm.php');

class ViewViewofflineclients extends SugarView
{
    /**
	 * @see SugarView::_getModuleTitleParams()
	 */
	protected function _getModuleTitleParams($browserTitle = false)
	{
	    global $mod_strings;
	    
    	return array(
    	   "<a href='index.php?module=Administration&action=index'>".$mod_strings['LBL_MODULE_NAME']."</a>",
    	   $mod_strings['LBL_MANAGE_OFFLINE_CLIENT']
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
        global $current_user;
        global $sugar_config;
        global $currentModule;
        
        $seed = new System();
        $searchForm = new SearchForm('Administration', $seed);
        
        $admin = new Administration();
        $admin->retrieveSettings();
        $system_id = 1;
        if(!empty($admin->settings['system_system_id'])){
            $system_id = $admin->settings['system_system_id'];
        }else{
            $admin->saveSetting('system', 'system_id', $system_id);
        }
        
        $num_lic_oc = $admin->settings['license_num_lic_oc'];
        if(!isset($system_id) || empty($system_id)){
           $system_id = 1;
        }
        $error = '';
        $where = 'system_id != '.$system_id;
        
        $row_count = $seed->getEnabledOfflineClients($seed->create_new_list_query("",$where));
        
        if(isset($_REQUEST['view']) && ($_REQUEST['view'] == 'disable' || $_REQUEST['view'] == 'enable')){
            if(isset($_REQUEST['system_id'])){
                $system = new System();
                $system->retrieve($_REQUEST['system_id']);
                if($system != null && $system->deleted != 1){
                    if($_REQUEST['view'] == 'disable'){
                        $system->status = 'Inactive';
                         $system->save();
                    }else if($_REQUEST['view'] == 'enable'){
                        if($row_count == $num_lic_oc){
                            $error = $mod_strings['ERR_NUM_OFFLINE_CLIENTS_MET'];
                        }else{
                            $system->status = 'Active';
                            $system->save();
                            $row_count++;
                        }   
                    } 
                    if(!empty($_SESSION['EXCEEDING_OC_LICENSES']) && $_SESSION['EXCEEDING_OC_LICENSES'] == true){
                        if(($row_count-1) <= $num_lic_oc){
                            unset($_SESSION['EXCEEDING_OC_LICENSES']);
                            header('Location: index.php?module=Administration&action=ListViewOfflineClient');             
                        }
                    }
                }   
            }   
        }
        
        $user_name = '';
        if(isset($_REQUEST['query']))
        {
            $user_name = (isset($_REQUEST['user_name']) ? $_REQUEST['user_name'] : ''); 
            require_once('modules/Administration/metadata/SearchFields.php');
            $searchForm->setup();
            $user = new User();
            $searchForm->populateFromRequest('advanced_search');
            
            $where_clauses = $searchForm->generateSearchWhere(true, "Administration"); 
            $search_where = "";
            if (count($where_clauses) > 0 )$search_where= implode(' and ', $where_clauses);
        }
        
        if(isset($search_where) && !empty($search_where)){
            $where .= ' AND '.$search_where;
        }
        
        echo getClassicModuleTitle(
                "Administration", 
                array(
                    "<a href='index.php?module=Administration&action=index'>".translate('LBL_MODULE_NAME','Administration')."</a>",
                   $mod_strings['LBL_MANAGE_OFFLINE_CLIENT'],
                   ), 
                false
                );
        echo '<div class="listViewBody">';
        
        //now we want to display how many offline clients are currently in use
        echo "<h4>";
        if (!empty($error)) {
            echo "<span class='error'>{$error}</span><br />";
        }    
        if ($row_count == 0) {
            echo "{$mod_strings['NO_ENABLED_OFFLINE_CLIENTS']}";
        }
        else {
            echo "$row_count of $num_lic_oc {$mod_strings['ENABLED_OFFLINE_CLIENTS']}";
        }
        echo "</h4>";
        
        //SEARCH
        if (!isset($_REQUEST['search_form']) || $_REQUEST['search_form'] != 'false') {
            $search_form = new Sugar_Smarty;
            $search_form->assign("MOD", $mod_strings);
            $search_form->assign("APP", $app_strings);
            $search_form->assign("BASIC_SEARCH_PNG", SugarThemeRegistry::current()->getImage('basic_search','border="0"', null,null,'.gif',$app_strings['LNK_BASIC_SEARCH']));
            if (isset($user_name)) $search_form->assign("USER_NAME", $user_name);
            $search_form->assign("JAVASCRIPT", get_clear_form_js());
            $search_form->display('modules/Administration/templates/OfflineClientSearchForm.tpl');
        }
        //SEARCH
        
        $ListView = new ListView();
        $ListView->initNewXTemplate( 'modules/Administration/templates/ViewOfflineClients.html',$mod_strings);
        $ListView->xTemplateAssign("RETURN_URL", "&return_module=".$currentModule."&return_action=ListView");
        $ListView->xTemplateAssign("DELETE_INLINE_PNG",  SugarThemeRegistry::current()->getImage('delete_inline','align="absmiddle" border="0"', null,null,'.gif', "{SYSTEM.LBL_DISABLE}"));
        $ListView->setQuery($where, "", "system_key", "SYSTEM");
        $ListView->show_export_button = false;
        $ListView->show_mass_update_form = true;
        $ListView->show_delete_button = false;
        $ListView->show_select_menu = false;
        $ListView->show_mass_update = false;
        $ListView->processListView($seed, "main", "SYSTEM");
        
        echo '</div>';
    }
}
