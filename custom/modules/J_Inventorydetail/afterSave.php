<?php
    if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class after_save_detail {
        function after_save_detail(&$bean, $event, $arguments)
        {       
            $bean->load_relationship('producttemplates_j_inventorydetail_1');
            $bean->producttemplates_j_inventorydetail_1->add($bean->book_id);
        }
    }
?>