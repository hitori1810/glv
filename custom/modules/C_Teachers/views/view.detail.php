<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once('include/MVC/View/views/view.detail.php');

    class C_TeachersViewDetail extends ViewDetail {

        function C_TeachersViewDetail(){
            parent::ViewDetail();
        }

        function display() {                 
            echo '<link rel="stylesheet" type="text/css" href="custom/include/DateRange/daterangepicker.css">'; 
            if($this->bean->teacher_type=='TA')
                 $html 	= '<span class="textbg_yellow">'.$this->bean->teacher_id.'</span>';
            else
                $html 	= '<span class="textbg_blue">'.$this->bean->teacher_id.'</span>';
            
            $html .='<input type="hidden" id="teacher_type" name="teacher_type" value="'.$this->bean->teacher_type.'"/>'; 
            $this->ss->assign('TEACHER_CODE', $html);         
            parent::display();
        }
        function _displaySubPanels(){ 
            require_once ('include/SubPanel/SubPanelTiles.php'); 
            $subpanel = new SubPanelTiles($this->bean, $this->module); 
            if($this->bean->teacher_type=='Teacher'){
                unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['c_teachers_j_class_1']);//hiding Teaching Assistant's Class  
            }
            if($this->bean->teacher_type=='TA'){
                unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['teachers_meetings']);//hiding session  
            }
            unset($subpanel->subpanel_definitions->layout_defs['subpanel_setup']['c_classes_c_teachers_1']);//hiding
            echo $subpanel->display(); 
        }

    }
?>