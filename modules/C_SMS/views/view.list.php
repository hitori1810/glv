<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
    class C_SMSViewList extends ViewList{
        public function preDisplay(){
            parent::preDisplay();
            # Hide Quick Edit Pencil
            $this->lv->quickViewLinks = false;
        }
    }
