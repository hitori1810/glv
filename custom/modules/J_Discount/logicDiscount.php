<?php

if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class logicDiscount {
    //before save
    function handleSaveDiscount(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            //save gift by json
            if($_POST["type"]=="Gift"){
                $result= array();
                for ($i=0; $i<count($_POST['book_id']);$i++){
                    if($_POST['book_id'][$i]!=''){
                        $result[$_POST['book_id'][$i]]=array(
                            'book_name'=> $_POST['book_name'][$i],
                            'qty_book'=> $_POST['qty_book'][$i],
                        );
                    }
                }
                $bean->content=json_encode($result);
            }
            // save partnership
            if($_POST["type"]=="Partnership"){
                $sql = "DELETE FROM j_discount_j_partnership_1_c WHERE j_discount_j_partnership_1j_discount_ida='{$bean->id}'";
                $delete =  $GLOBALS['db']->query($sql);
                $bean->load_relationship('j_discount_j_partnership_1');
                $bean->j_discount_j_partnership_1->add(array_filter($_POST["pa_id"]));
            }

            // save "do not apply with" list
            $notApplyList = array();
            if(!empty($bean->fetched_row)){
                //delete before save
                $ff = json_decode(html_entity_decode($bean->fetched_row['disable_list']),true);
                foreach ($ff as $value){
                    $count_unset = 0;
                    $o_ff =  json_decode(html_entity_decode($GLOBALS['db']->getOne("SELECT disable_list FROM j_discount WHERE id = '$value' AND deleted = 0 AND status = 'Active'")),true);
                    if(!empty($o_ff)){
                        if (($key = array_search($bean->id, $o_ff)) !== false){
                            $count_unset++;
                            unset($o_ff[$key]);
                        }
                        if($count_unset > 0)
                            $GLOBALS['db']->query("UPDATE j_discount set disable_list = '".json_encode($o_ff)."' WHERE id = '$value' AND deleted = 0");
                    }
                }
            }

            foreach ($_POST['check_schema'] as $value){
                if(!in_array($value, $notApplyList)){
                    $notApplyList[] = $value;
                    $o_ff =  json_decode(html_entity_decode($GLOBALS['db']->getOne("SELECT disable_list FROM j_discount WHERE id = '$value' AND deleted = 0 AND status = 'Active'")),true);
                    if (!in_array($bean->id, $o_ff))
                        $o_ff[] = $bean->id;
                    $GLOBALS['db']->query("UPDATE j_discount set disable_list = '".json_encode($o_ff)."' WHERE id = '$value' AND deleted = 0");
                }
            }
            $bean->disable_list = json_encode($notApplyList);

            if($bean->type == 'Partnership' ||$bean->type == 'Reward')
                $bean->order_no = 99;
            if($bean->type == 'Hour'){
                $bean->order_no = 1;
                $discount_by_hour = array();
                foreach ($_POST['promotion_hours'] as $index => $value){
                    if(!empty($value)){
                        $discount_by_hour[$index]['hours']              = $_POST['hours'][$index];
                        $discount_by_hour[$index]['promotion_hours']    = $value;
                    }
                }
                $discount_by_range = array();
                foreach ($_POST['block'] as $index => $value){
                    if(!empty($value)){
                        $discount_by_range[$index]['start_hour']    = $_POST['start_hour'][$index];
                        $discount_by_range[$index]['to_hour']       = $_POST['to_hour'][$index];
                        $discount_by_range[$index]['block']         = $value;
                    }
                }
                $discount_hour_range = array(
                    'discount_by_hour' => $discount_by_hour,
                    'discount_by_range' => $discount_by_range,
                );
                $bean->content = json_encode($discount_hour_range);
            }
            $bean->assigned_user_id=$GLOBALS['current_user']->id;
        }
    }
    //after save
    function addTeam(&$bean, $event, $arguments){
        include_once("custom/modules/Teams/_helper.php");
        if($_POST['module'] == $bean->module_name && ($_POST['action'] == 'Save' || $_POST['action'] == 'MassUpdate')){
            $team_list = array();
            // Get all team set
            $teamSetBean = new TeamSet();
            $teams = $teamSetBean->getTeams($bean->team_set_id);
            // Add all team set to  $team_list
            foreach ($teams as $key => $value) {
                $team_list[] = $key;
            }
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
    function listViewColorDiscount(&$bean, $event, $arguments){
        switch ($bean->status) {
            case "Active":
                $bean->status = '<span class="textbg_green">'.$bean->status.'</span>';
                break;
            case "Inactive":
                $bean->status = '<span class="textbg_orange">'.$bean->status.'</span>';
                break;
        }
    }
}