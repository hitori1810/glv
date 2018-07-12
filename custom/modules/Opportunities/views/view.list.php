<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once 'include/MVC/View/views/view.list.php';

    class OpportunitiesViewList extends ViewList
    {
        public function preDisplay(){
            parent::preDisplay();
            //add js into listview - 02/08/2014 - by MTN
            echo '<script type="text/javascript" src="custom/modules/Opportunities/js/listview.js"></script>'; 

            //Dialog
            echo $GLOBALS['app_strings']['LBL_THONGBAO_VAOLOP']; 

            # Hide Quick Edit Pencil
            $this->lv->quickViewLinks = false;
            $this->lv->showMassupdateFields = false;
            $this->lv->mergeduplicates = false;
            $this->lv->delete = true;

            //add Session into listview
            if(isset($_GET['class_id'])){
                $_SESSION['class_id'] = $_GET['class_id'];
            }
            //add button Add to Class
            if(ACLController::checkAccess('C_Classes', 'import', false))
                $this->lv->actionsMenuExtraItems[] = $this->buildMyMenuItem(); 
        }

        protected function buildMyMenuItem(){
            if(ACLController::checkAccess('C_Classes', 'import', false))
            $html ='<a id="add_to_class" class="menuItem" href="javascript:void(0)" onclick="open_popup(\'C_Classes\',600,400, \'&id_advanced='.$_SESSION['class_id'].'&type_advanced=Practice\' ,true,true,{\'call_back_function\':\'showPopupConfirm\',\'form_name\':\'DetailView\',\'field_to_name_array\':{\'id\':\'class_id\'},},\'Select\',true);"  >'.$GLOBALS['mod_strings']['LBL_ADD_TO_CLASS'].'</a>';
            return $html;
        }
}