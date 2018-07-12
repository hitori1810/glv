<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

class NotesViewEdit extends ViewEdit
{
    /**
    * @see SugarView::display()
    */
    public function display()
    {
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
