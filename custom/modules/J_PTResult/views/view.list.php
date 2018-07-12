<?php
    if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

    /*********************************************************************************
    * By installing or using this file, you are confirming on behalf of the entity
    * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
    * the SugarCRM Inc. Master Subscription Agreement (“MSA”), which is viewable at:
    * http://www.sugarcrm.com/master-subscription-agreement
    *
    * If Company is not bound by the MSA, then by installing or using this file
    * you are agreeing unconditionally that Company will be bound by the MSA and
    * certifying that you have authority to bind Company accordingly.
    *
    * Copyright (C) 2004-2013 SugarCRM Inc.  All rights reserved.
    ********************************************************************************/

//Hoang Quyen
    class J_PTResultViewList extends ViewList{
        function preDisplay() {

            parent::preDisplay();

            $this->lv->quickViewLinks = false;
            $this->lv->multiSelect = false;
        }

        public function listViewProcess(){

            $this->processSearchForm();

            if($_GET['type_list']=='PT'){
                if ($this->where != "") 
                    $this->where .= ' and j_ptresult.type_result = "Placement Test" '; 
                else 
                    $this->where .= ' j_ptresult.type_result = "Placement Test" ';

            }else{
                if ($this->where != "") 
                    $this->where .= ' and j_ptresult.type_result = "Demo" ';
                else 
                    $this->where .= 'j_ptresult.type_result = "Demo" ';  
            }

            $this->lv->searchColumns = $this->searchForm->searchColumns;

            if (!$this->headers) {
                return;
            }
            if (empty($_REQUEST['search_form_only']) || $_REQUEST['search_form_only'] == false) {
                $this->lv->ss->assign("SEARCH",true);
                // add recurring_source field to filter to be able acl check to use it on row level
                $this->lv->mergeDisplayColumns = true;
                $filterFields = array('recurring_source' => 1);
                $this->lv->setup($this->seed, 'include/ListView/ListViewGeneric.tpl', $this->where, $this->params, 0, -1, $filterFields);
                $savedSearchName = empty($_REQUEST['saved_search_select_name']) ? '' : (' - ' . $_REQUEST['saved_search_select_name']);
                echo $this->lv->display();
            }
        }
    }
