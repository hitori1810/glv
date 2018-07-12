<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class HomeViewLisenceInfo extends SugarView{   
    public function display(){
        include_once("custom/modules/Home/entryPointLisenceInfo.php");
    }
}