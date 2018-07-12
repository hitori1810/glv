<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once('include/MVC/View/views/view.quickcreate.php');

    class OpportunitiesViewQuickcreate extends ViewQuickcreate
    {
        function display(){
            parent::display();
            return '';
        }
}