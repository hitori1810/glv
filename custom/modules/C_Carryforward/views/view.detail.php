<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

require_once('include/MVC/View/views/view.detail.php');

class C_CarryforwardViewDetail extends ViewDetail {
    function display() {
        $payment_id = $GLOBALS['db']->getOne("SELECT name FROM j_payment WHERE id = '{$this->bean->payment_id}'");
        $this->bean->payment_id = '<a href="index.php?module=J_Payment&return_module=J_Payment&action=DetailView&record='.$this->bean->payment_id.'">'.$payment_id.'</a>'; 
        parent::display();
    }
}
?>