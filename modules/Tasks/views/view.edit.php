<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class TasksViewEdit extends ViewEdit
{
    /**
    * @see SugarView::preDisplay()
    */
    public function preDisplay()
    {
        if($_REQUEST['module'] != 'Tasks' && isset($_REQUEST['status']) && empty($_REQUEST['status'])) {
            $this->bean->status = '';
        } //if
        if(!empty($_REQUEST['status']) && ($_REQUEST['status'] == 'Completed')) {
            $this->bean->status = 'Completed';
        }
        parent::preDisplay();
    }

    /**
    * @see SugarView::display()
    */
    public function display()
    {
        if($this->ev->isDuplicate){
            $this->bean->status = $this->bean->getDefaultStatus();
        } //if

        //Fix bug SugarCRM gay kho hieu
        if($_REQUEST['return_module'] == 'Contacts' && empty($this->bean->id) && !empty($_REQUEST['return_id'])){
            $contact = BeanFactory::getBean('Contacts',$_REQUEST['return_id']);
            $_REQUEST['parent_type']    = $_REQUEST['return_module'];
            $_REQUEST['parent_id']      = $contact->id;
            $_REQUEST['parent_name']    = $contact->name;
        }
        parent::display();
    }
}
