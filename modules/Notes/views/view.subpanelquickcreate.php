<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/EditView/SubpanelQuickCreate.php');

class NotesSubpanelQuickcreate extends SubpanelQuickCreate {

    function NotesSubpanelQuickcreate() {
        //Fix bug SugarCRM gay kho hieu
        if($_REQUEST['return_module'] == 'Contacts' && empty($this->bean->id) && !empty($_REQUEST['return_id'])){
            $contact = BeanFactory::getBean('Contacts',$_REQUEST['return_id']);
            $_REQUEST['parent_type']    = $_REQUEST['return_module'];
            $_REQUEST['parent_id']      = $contact->id;
            $_REQUEST['parent_name']    = $contact->name;
        }
        parent::SubpanelQuickCreate("Notes");
    }
}