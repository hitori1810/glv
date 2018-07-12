<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class C_CarryforwardViewEdit extends ViewEdit
{

    public function display()
    {
        if(!isset($this->bean->id) || empty($this->bean->id)){
            $this->bean->type = 'Junior';
        }else{
        }
        parent::display();   
    }
}
?>
