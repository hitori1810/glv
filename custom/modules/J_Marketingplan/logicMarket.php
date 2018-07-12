<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class logicMarket {
        ///to mau id va status Quyen.Cao
        function listViewColorMarket(&$bean, $event, $arguments){

            switch ($bean->status) {
                case "Active":
                    $colorClass = "textbg_green";
                    break;
                case "Inactive":
                    $colorClass = "textbg_yellow_light";
                    break;
                case "Complete":
                    $colorClass = "textbg_violet";
                    break;
                case "In Queue":
                    $colorClass = "textbg_orange";
                    break;
                case "Sending":
                    $colorClass = "textbg_dream";
                    break;

            }   
            $bean->status = '<span class="'.$colorClass.'">'.$bean->status.'</span>'; 
        }
    }
?>