<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_LoyaltyViewEdit extends ViewEdit
{
    public function __construct(){
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }
    public function display(){
        if($_REQUEST['return_module'] == 'Contacts'){
            $student = BeanFactory::getBean('Contacts',$_REQUEST['return_id']);
            $_REQUEST['student_id']     = $student->id;
            $_REQUEST['student_name']   = $student->name;
        }
        $typeOptions = $GLOBALS['app_list_strings']['input_loyalty_type_list'];
        $this->ss->assign("typeOptions",$typeOptions);

        parent::display();
    }

}