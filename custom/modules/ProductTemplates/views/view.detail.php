<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');



    class ProductTemplatesViewDetail extends ViewDetail {


        function display() {     
            $html = '';
            $html = '<span> '.$this->bean->supplier.'</span>';
            $this->ss->assign('SUPPLIER', $html);
            $this->ss->assign('code', '<span class="textbg_blue">'.$this->bean->code.'</span>');  
            parent::display();
        }
    }
?>