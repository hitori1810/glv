<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class before_save_supplier {
        function saveSupplier(&$bean, $event, $arguments){
            $bean->supplier=$_POST['Supplier'];
        } 
        ///to mau id va status Quyen.Cao
        function listViewColorBook(&$bean, $event, $arguments){
            $bean->code = '<span class="textbg_blue">'.$bean->code.'</span>';

            switch ($bean->unit) {
                case "Pieces":
                    $bean->unit = '<span class="textbg_yellow_light">'.$bean->unit.'</span>';
                    break;
                case "Items":
                    $bean->unit = '<span class="textbg_green">'.$bean->unit.'</span>';
                    break; 

            }
            if($bean->type_name=='Book'){
                $colorClass = "textbg_dream";
            }elseif($bean->type_name=='Gift'){
                $colorClass = "textbg_orange";
            } else{
                $colorClass = "textbg_crimson";
            }  
            $bean->type_name = '<span class="'.$colorClass.'">'.$bean->type_name.'</span>';
        }           
    }  
