<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicCoursefee {
    function afterSave(&$bean, $event, $arguments){
        include_once("custom/modules/Teams/_helper.php");
        if($_POST['module'] == $bean->module_name && ($_POST['action'] == 'Save' || $_POST['action'] == 'MassUpdate')){
            $team_list = array();
            // Get all team set
            $teamSetBean = new TeamSet();
            $teams = $teamSetBean->getTeams($bean->team_set_id);
            
            // Add all child team - Update by Tung Bui
            foreach ($teams as $key => $value) {
                $team_list[] = $key;
                
                foreach(getAllChildIds($key) as $child){
                    $team_list[] = $child;    
                }
            }

            if(!empty($team_list)){
                $bean->load_relationship('teams');
                //Add the teams
                $bean->teams->replace($team_list);
            }
        }
    }
    function listViewColorFee(&$bean, $event, $arguments){
        ///to mau id va status Quyen.Cao

        switch ($bean->status) {
            case "Active":
                $bean->status = '<span class="textbg_dream">'.$bean->status.'</span>';
                break;
            case "Inactive":
                $bean->status = '<span class="textbg_crimson">'.$bean->status.'</span>';
                break;
        }

    }

}
?>