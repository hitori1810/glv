<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class UsersViewCreateDemoUser extends SugarView{   
    public function display(){
        global $mod_strings, $app_strings, $current_user, $timedate;
        if(!is_admin($current_user)) sugar_die($app_strings['ERR_NOT_ADMIN']); 

        //Default expiry date is 1 months ago
        $dbDate = $timedate->to_db_date();
        $dateStr = strtotime("+1 months ".$date);
        $defaultExpiryDate = date('Y-m-d', $dateStr);
        $defaultExpiryDate = $timedate->to_display_date($defaultExpiryDate, false);

        //load role list
        $q1 = "SELECT id, name FROM acl_roles
        WHERE acl_roles.deleted=0 
        ORDER BY name";
        $roles = array();
        $rs1 = $GLOBALS['db']->query($q1);
        while($row = $GLOBALS['db']->fetchByAssoc($rs1)){
            $roles[$row['id']] = $row['name'];
        }
        
        $roleOptions = "";
        foreach($roles as $key => $value){
            $selected = "";
            if($value == "Center Manager") $selected = "selected";
            
            $roleOptions .= "<option value='$key' $selected>$value</option>";    
        }


        $smarty = new Sugar_Smarty();
        $smarty->assign('MOD', $mod_strings);
        $smarty->assign('DEFAULT_PASSWORD', "crm123");
        $smarty->assign('DEFAULT_EXPIRY_DATE', $defaultExpiryDate);
        $smarty->assign('ROLE_OPTIONS', $roleOptions);

        foreach ($emailUsers as $emailUser) {
            $smarty2 = new Sugar_Smarty();
            $smarty2->assign('MOD', $mod_strings);

            $htmlUserSaved .= $smarty2->fetch('custom/modules/Users/tpls/RowDemoUser.tpl');
        }            

        echo $smarty->fetch("custom/modules/Users/tpls/CreateDemoUser.tpl");
    }   
}