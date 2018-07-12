<?php

class J_CoursefeeViewEdit extends ViewEdit {
    public function display(){
        if(empty($this->bean->id)){ //In Case create new

        }else{ //In Case edit



//            if (checkDataLockDate($this->bean->start_date))
//                $this->ss->assign('is_lock_date','1');
//            else
//                $this->ss->assign('is_lock_date','1');

        }
        parent::display();
    }
}

?>