<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');



    class J_CoursefeeViewDetail extends ViewDetail {

        function display() {
            if($this->bean->type_of_course_fee > 0){
                $this->ss->assign('UNIT_PRICE_PER_HOUR', format_number($this->bean->unit_price / $this->bean->type_of_course_fee) );    
            }                                                                                                                       
            
            parent::display();
        }
    }
?>