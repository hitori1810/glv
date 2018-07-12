<?php

if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
require_once('include/MVC/View/views/view.list.php');
class NotificationsViewList extends ViewList {
    public function preDisplay() {
        parent::preDisplay();
        $this->lv->export = false;
        $this->lv->quickViewLinks = false;
    }

    function listViewPrepare() {
        global $current_user;
        if(!empty($this->where))
            $this->where .= " AND notifications.assigned_user_id = '{$current_user->id}' ";
        else
            $this->where .= " notifications.assigned_user_id = '{$current_user->id}' ";


        $_REQUEST['orderBy'] = 'date_entered';
        $_REQUEST['sortOrder'] = 'DESC';
        parent::listViewPrepare();
    }
}

