<?php 
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class ListviewCard { 

        function listviewcolor_card(&$bean, $event, $arguments) {

            if($_REQUEST['action'] != 'Popup' || $_REQUEST['action'] == null){
                if($bean->type == 'Student')
                    $bean->name = '<a href="index.php?module=C_Memberships&return_module=C_Memberships&action=DetailView&record='.$bean->id.'"><span class="textbg_dream">'.$bean->name.'</span></a>'; 
                else
                    $bean->name = '<a href="index.php?module=C_Memberships&return_module=C_Memberships&action=DetailView&record='.$bean->id.'"><span class="textbg_yellow_light">'.$bean->name.'</span></a>'; 

            }
            if($bean->type == 'Student'){
                $card = BeanFactory::getBean('C_Memberships',$bean->id);
                $sql = "SELECT picture FROM contacts WHERE id = '{$card->c_memberships_contacts_2contacts_idb}'";
                $picture = $GLOBALS['db']->getOne($sql);
                $bean->picture = $picture;   
            }
        } 
    }
?>
