<?php 
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point'); 

    class ListviewLogicHookOpportunities { 

        /** 
        * Changing color of listview rows according to Status
        */ 
        function listviewcolor_Opp(&$bean, $event, $arguments) {
            $q1 = "SELECT added_to_class FROM opportunities WHERE id = '{$bean->id}'";
            $added = $GLOBALS['db']->getOne($q1);
            if($bean->sales_stage != 'Deleted'){
                if($added=='0') 
                    $bean->oder_id = "<span class='textbg_blue'><b>". $bean->oder_id ."<b></span>";
                else
                    $bean->oder_id = "<span class='textbg_dream'><b>". $bean->oder_id ."<b></span>";  
            }else{
                $bean->oder_id = "<span class='textbg_black'><b>". $bean->oder_id ."<b></span>";  
            }    



            $colorClass = '';       
            switch($bean->sales_stage) {  
                case 'Success':     
                    $colorClass = "textbg_bluelight"; 
                    break; 
                case 'Deleted':     
                    $colorClass = "textbg_black"; 
                    break;
                case 'Draft':     
                    $colorClass = "textbg_crimson"; 
                    break; 
                default : 
                    $colorClass = "textbg_nocolor"; 
            }
            $tmp_sales_stage = translate('sales_stage_dom','',$bean->sales_stage); 
            $bean->sales_stage = "<span class=$colorClass>$tmp_sales_stage</span>";
        } 
    }
?>
