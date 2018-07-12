<?php
/**
 *
 * LICENSE: The contents of this file are subject to the license agreement ("License") which is included
 * in the installation package (LICENSE.txt). By installing or using this file, you have unconditionally
 * agreed to the terms and conditions of the License, and you may not use this file except in compliance
 * with the License.
 *
 * @author     Original Author Biztech Co.
 */
require_once('include/MVC/Controller/SugarController.php');

class bc_survey_pagesController extends SugarController
{
    function action_EditView(){
        $this->view = 'noaccess';
    }
    function action_DetailView(){
        $this->view = 'noaccess';
    }
    function action_ListView(){
        $this->view = 'noaccess';
    }

}

?>

