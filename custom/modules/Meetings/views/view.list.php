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


class MeetingsViewList extends ViewList{
            function listViewPrepare() {
        $_REQUEST['orderBy'] = 'date_start';
        $_REQUEST['sortOrder'] = 'DESC';
        parent::listViewPrepare();
    }
    public function listViewProcess(){

        $this->processSearchForm();

        //Custom listview hide all Session - 19/08/2014 - by MTN

        /*if($_GET['type_list']=='Meeting'){
        if ($this->where != "")
        $this->where .= ' and meetings.meeting_type != "Session" and meetings.meeting_type != "Placement Test" and meetings.meeting_type != "Demo"';
        else
        $this->where .= 'meetings.meeting_type != "Session" and meetings.meeting_type != "Placement Test" and meetings.meeting_type != "Demo"';
        }elseif($_GET['type_list']=='PT'){
        if ($this->where != "")
        $this->where .= ' and meetings.meeting_type = "Placement Test" ';
        else
        $this->where .= 'meetings.meeting_type = "Placement Test"';
        }else{*/
        if(!empty($this->where))
            $this->where .= ' AND (meetings.timesheet_id = "" OR meetings.timesheet_id IS NULL) AND meeting_type <> "Session" ';
        else
            $this->where .= ' (meetings.timesheet_id = "" OR meetings.timesheet_id IS NULL) AND meeting_type <> "Session" ';

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

    public function preDisplay(){
        parent::preDisplay();
        # Hide Quick Edit Pencil
        $this->lv->quickViewLinks = false;
        $this->lv->showMassupdateFields = false;
        $this->lv->mergeduplicates = false;
    //    $this->lv->delete = false;
        $this->lv->export = false;
    }


}
