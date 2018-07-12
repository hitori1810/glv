<?php
if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class logicCarry {                                    
    function eliminateCF(&$bean, $event, $arguments){
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'Save'){
            if($bean->type == 'Junior' ){
                $bean->passed = 0;
                $payment_id = $GLOBALS['db']->getOne("SELECT name FROM j_payment WHERE id = '{$bean->payment_id}'");
                $bean->name = 'Eliminate amount '.format_number($bean->this_stock,2,2).': '.$payment_id;
            }
        }
    }
    function listColor(&$bean, $event, $arguments){
        if(!empty($bean->payment_id)){
            $payment_id = $GLOBALS['db']->getOne("SELECT name FROM j_payment WHERE id = '{$bean->payment_id}'");
            $bean->payment_id = '<a href="index.php?module=J_Payment&return_module=J_Payment&action=DetailView&record='.$bean->payment_id.'">'.$payment_id.'</a>'; 
        }
    }
}                                                                 
?>