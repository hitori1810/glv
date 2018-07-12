<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicKindOfCourse {
    //before save
    function handleSave($bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            $jsons_level    = array();
            foreach ($_POST["jsons"] as $key => $json){
                if($key>0)
                    $jsons_level[] = json_decode(html_entity_decode($json));
            }
            $bean->content = json_encode($jsons_level );
            
            $json_syl    = array();
            foreach ($_POST["json_syl"] as $key => $json){
                if($key>0)
                    $json_syl[] = json_decode(html_entity_decode($json));
            }
            $bean->syllabus = json_encode($json_syl );
            
			$bean->short_course_name = strtoupper($bean->short_course_name);
        }
    }
    //after save
    function addTeam($bean, $event, $arguments){    
        include_once("custom/modules/Teams/_helper.php");
        if($_POST['module'] == $bean->module_name  && ($_POST['action'] == 'Save' 
        || $_POST['action'] == 'MassUpdate')){
            $bean->name = str_replace(' | ', ' - ', $bean->name);
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

            //Add the teams
            if(!empty($team_list) && $bean->load_relationship('teams')){
                $bean->teams->replace($team_list);
            }
        }
    }
    ///to mau id va status Quyen.Cao
    function listViewColorKOC(&$bean, $event, $arguments){
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