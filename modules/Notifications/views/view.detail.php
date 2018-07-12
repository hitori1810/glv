<?php

class NotificationsViewDetail extends ViewDetail {

    function NotificationsViewDetail(){
        parent::ViewEdit();
    }
    public function display(){
        if(!empty($this->bean->parent_id) && !empty($this->bean->parent_type)){
            $parentt                    = BeanFactory::getBean('Leads', $this->bean->id);
            $this->bean->parent_name    = $parentt->name;
        }
        parent::display();
    }
}

?>