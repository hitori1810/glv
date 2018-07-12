<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    class C_RoomsViewEdit extends ViewEdit
    {
        
        public function display()
        {
            if(!isset($this->bean->id) || empty($this->bean->id)){
              $this->ss->assign('ROOM_ID', '<span style="color:red;font-weight:bold"> New </span>');  
            }else{
              $this->ss->assign('ROOM_ID', '<span style="color:red;font-weight:bold"> '.$this->bean->room_id.'</span>');  
            }
            parent::display();   
        }
    }
?>
