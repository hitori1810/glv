<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    require_once('include/MVC/View/views/view.detail.php');

    class J_GradebookViewDetail extends ViewDetail {

        function J_GradebookViewDetail(){
            parent::ViewDetail();
        }

        function preDisplay(){
            parent::preDisplay();
            global $timedate, $sugar_config;
            $this->ss->assign('MARKDETAIL', $this->bean->getGradebookDetailView())   ;

            $this->ss->assign('LOCKUPDATE', 0)   ;
        }
        /**
        * @see SugarView::display()
        *
        * We are overridding the display method to manipulate the portal information.
        * If portal is not enabled then don't show the portal fields.
        */


    }

