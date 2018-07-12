<?php

if (!defined('sugarEntry') || !sugarEntry)
    die('Not A Valid Entry Point');
/**
*
* LICENSE: The contents of this file are subject to the license agreement ("License") which is included
* in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
* agreed to the terms and conditions of the License, and you may not use this file except in compliance
* with the License.
*
* @author     Original Author Biztech Co.
*/


require_once('include/MVC/View/views/view.list.php');

class ProspectsViewList extends ViewList {
    //Customize Survey
    public function preDisplay() {

        parent::preDisplay();
        $this->lv->targetList = true;    
    }
      

    function listViewPrepare() {
        $_REQUEST['orderBy'] = 'date_entered';
        $_REQUEST['sortOrder'] = 'DESC';
        parent::listViewPrepare();
    }

}
