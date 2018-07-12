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


require_once('include/json_config.php');
require_once("include/SugarDateTime.php");

class MeetingsViewEdit extends ViewEdit
{
    /**
    * @const MAX_REPEAT_INTERVAL Max repeat interval.
    */
    const MAX_REPEAT_INTERVAL = 30;
    /**
    * @see SugarView::preDisplay()
    *
    * Override preDisplay to check for presence of 'status' in $_REQUEST
    * This is to support the "Close And Create New" operation.
    */
    public function preDisplay()
    {
        //include CSS Plugin Selective2 - 23/07/2014 - by MTN
        echo '<style type="text/css">@import url("custom/include/javascripts/Select2/select2.css"); </style>';

        //Custom Lock Edit 1 Session after Calculator Carried forward on Third date every months - 20/08/2014 - by MTN

        //Get Format DB DateTime, convert date_start to format DB Datetime
        if($this->bean->meeting_type == "Session" && !empty($this->bean->id)){
            $date_start_db = $GLOBALS['timedate']->to_db($this->bean->date_start);
            $date_time_object = $GLOBALS['timedate']->fromDb($date_start_db);

            //set status: Defaut is Make-up
            if(isset($_GET['session_status']))
                $this->ss->assign("SESSION_STATUS", "{$_GET['session_status']}");
            else
                $this->ss->assign("SESSION_STATUS", "Make-up");
        }
        //END Custom Lock Edit
        parent::preDisplay();
    }

    /**
    * @see SugarView::display()
    */
    public function display()
    {
        // Dirty trick to clear cache, a must for EditView:
        $th = new TemplateHandler();
        $th->deleteTemplate('Meetings', 'EditView');
        /////------Atlantic Junior Show Hide Panel PT/Demo-------///////
        $meeting_type = "Meeting";
        if($_GET['type'] == "PT" || $this->bean->meeting_type == "Placement Test") {
            $meeting_type = "Placement Test";
            unset($this->ev->defs['panels']['lbl_meeting_information']);
            unset($this->ev->defs['panels']['lbl_editview_panel2']);
            unset($this->ev->defs['panels']['LBL_DEMO_INFORMATION']);
        }elseif($_GET['type'] == "Demo" || $this->bean->meeting_type == "Demo") {
            $meeting_type = "Demo";
            unset($this->ev->defs['panels']['lbl_meeting_information']);
            unset($this->ev->defs['panels']['lbl_editview_panel2']);
            unset($this->ev->defs['panels']['LBL_PT_INFORMATION']);
        }
        else{
            unset($this->ev->defs['panels']['LBL_PT_INFORMATION']);
            unset($this->ev->defs['panels']['LBL_DEMO_INFORMATION']);
            unset($this->ev->defs['panels']['LBL_PANEL_ASSIGNMENT']);

            if($this->bean->meeting_type != '') {
                $meeting_type = $this->bean->meeting_type;
            }else if(isset($_GET['type'])) {
                $meeting_type = $_GET['type'];
            }
        }
        $this->ss->assign("meeting_type", $meeting_type);
        //Trung Nguyen 2015.12.22: teacher list and room list.
        if($_GET['type'] != "Meeting" && $this->bean->meeting_type != "Meeting"){
            $this->bean->field_defs['teacher_id']['options'] = "session_teacher_options";
            $GLOBALS['app_list_strings']['session_teacher_options'] = array(
                $this->bean->teacher_id => ($this->bean->teacher_id !=''?$this->bean->teacher_name:"--None--"),
            );
            $this->bean->field_defs['room_id']['options'] = "session_room_options";
            $GLOBALS['app_list_strings']['session_room_options'] = array(
                $this->bean->room_id => ($this->bean->room_id !=''?$this->bean->room_name:"--None--"),
            );
        }//end
        if($this->bean->meeting_type == "Session"){
            echo '
            <script type="text/javascript">
            alert("Bạn vui lòng thực hiện thao tác sửa lịch lớp tại chức năng đổi lịch của module Class!");
            location.href=\'index.php?module=Meetings&action=DetailView&record='.$this->bean->id.'\';
            </script>';
            die();
        }

        global $json;
        $json = getJSONobj();
        $json_config = new json_config();
        if (isset($this->bean->json_id) && !empty ($this->bean->json_id)) {
            $javascript = $json_config->get_static_json_server(false, true, 'Meetings', $this->bean->json_id);

        } else {
            $this->bean->json_id = $this->bean->id;
            $javascript = $json_config->get_static_json_server(false, true, 'Meetings', $this->bean->id);

        }
        $this->ss->assign('JSON_CONFIG_JAVASCRIPT', $javascript);
        if($this->ev->isDuplicate){
            $this->bean->status = $this->bean->getDefaultStatus();
        } //if

        $repeatIntervals = array();
        for ($i = 1; $i <= self::MAX_REPEAT_INTERVAL; $i++) {
            $repeatIntervals[$i] = $i;
        }
        $this->ss->assign("repeat_intervals", $repeatIntervals);

        $this->ss->assign('repeatData', json_encode($this->view_object_map['repeatData']));
        parent::display();
    }
}
