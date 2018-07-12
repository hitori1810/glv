<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');     

require_once('include/MVC/View/views/view.detail.php');

class J_GradebookConfigViewDetail extends ViewDetail {

    function J_GradebookConfigViewDetail(){
        parent::ViewDetail();
    }

    function preDisplay(){
        parent::preDisplay(); 
        $queryParams = array(
            'module'                => 'J_GradebookConfig',
            'action'                => 'EditView',
            'gradebook_config_id'   => $this->bean->id,
            'team_id'               => $this->bean->team_id,
            'koc_id'                => $this->bean->koc_id,
            'type'                  => $this->bean->type,
        );
        SugarApplication::redirect('index.php?' . http_build_query($queryParams));
    }          
}  