<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once('include/MVC/View/views/view.detail.php');

    class C_RoomsViewDetail extends ViewDetail {

        function C_RoomsViewDetail(){
            parent::ViewDetail();
        }

        function display() {
            
            parent::display();
        }
    }
?>