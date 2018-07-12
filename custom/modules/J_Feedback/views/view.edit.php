<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
class J_FeedbackViewEdit extends ViewEdit
{
    public function __construct()
    {
        parent::ViewEdit();
        $this->useForSubpanel = true;
        $this->useModuleQuickCreateTemplate = true;
    }
    public function display(){
        if(empty($this->bean->receiver_id)) {
            $_REQUEST['receiver'] = $GLOBALS['current_user']->name;
            $_REQUEST['receiver_id'] = $GLOBALS['current_user']->id;
        }
        $relate_feedback_list = $GLOBALS['app_list_strings']['relate_feedback_list'];
        $this->ss->assign("relate_feedback_list",$relate_feedback_list);
        if(!empty($_REQUEST['j_class_j_feedback_1j_class_ida']))
            $this->bean->j_class_j_feedback_1_name = $GLOBALS['db']->getOne('SELECT name FROM j_class WHERE id="'.$_REQUEST['j_class_j_feedback_1j_class_ida'].'"');
        parent::display();
    }
}