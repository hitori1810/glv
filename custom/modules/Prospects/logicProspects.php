<?php
class logicProspects {
    function beforeSaveProspects(&$bean, $event, $arguments) {
        if($_POST['action'] != 'MassUpdate'){
            $bean->full_target_name = $bean->last_name .' '. $bean->first_name;
            if(!empty($bean->birthdate))
                $bean->birthmonth  = date('n', strtotime($bean->birthdate));
            else $bean->birthmonth = '';
            $bean->assistant = viToEn($bean->full_target_name);
        }

        //        //save team type - Also do when import
        //        $target_type = $GLOBALS['db']->getOne("SELECT team_type FROM teams WHERE id = '{$bean->team_id}'");
        //        $bean->team_type = $target_type;

        //Mass Update - Auto convert To Lead
        if($_POST['module'] == $bean->module_name && $_POST['action'] == 'MassUpdate'){
            if($bean->converted == true || $bean->converted == '1'){
                //Check dulicate
                $q1 = "SELECT DISTINCT
                IFNULL(leads.id, '') primaryid
                FROM
                leads
                WHERE
                ((CONCAT(IFNULL(leads.last_name, ''),
                ' ',
                IFNULL(leads.first_name, '')) = '{$bean->name}'
                AND (leads.phone_mobile = '{$bean->phone_mobile}')))
                AND leads.deleted = 0";
                $lead_id = $GLOBALS['db']->getOne($q1);
                if (empty($bean->lead_id) && empty($lead_id)){
                    $lead = new Lead();
                    foreach ($lead->field_defs as $keyField => $aFieldName)
                        $lead->$keyField = $bean->$keyField;

                    $lead->id = '';
                    $lead->save();
                    $bean->lead_id = $lead->id;
                }

                //Copy Log Call
                $GLOBALS['db']->query("UPDATE calls SET parent_id = '{$lead->id}', parent_type = 'Leads' WHERE parent_id = '{$bean->id}' AND deleted = 0");

                $GLOBALS['db']->query("INSERT INTO calls_leads (id, call_id, lead_id, required, accept_status, date_modified, deleted)
                    SELECT id, id, '{$lead->id}', 1, 'none', date_modified, deleted FROM calls WHERE parent_id = '{$lead->id}' AND deleted = 0;");

                //Copy Task
                $GLOBALS['db']->query("UPDATE tasks SET parent_id = '{$lead->id}', parent_type = 'Leads' WHERE parent_id = '{$bean->id}' AND deleted = 0");

                //Copy Meeting
                $GLOBALS['db']->query("UPDATE meetings SET parent_id = '{$lead->id}', parent_type = 'Leads' WHERE parent_id = '{$bean->id}' AND deleted = 0 AND meeting_type = 'Meeting'");

                $GLOBALS['db']->query("INSERT INTO meetings_leads (id, meeting_id, lead_id, required, accept_status, date_modified, deleted)
                    SELECT id, id, '{$lead->id}', 1, 'none', date_modified, deleted FROM meetings WHERE parent_id = '{$lead->id}' AND deleted = 0;");
            }
        }
        if($bean->converted == true || $bean->converted == '1')
            $bean->status = 'Converted';
    }
    /**
    * Changing color of listview rows according to Status
    */
    function listviewcolor(&$bean, $event, $arguments) {
        $colorClass = '';
        switch($bean->status) {
            case 'New':
                $colorClass = "textbg_green";
                break;
            case 'In Process':
                $colorClass = "textbg_blue";
                break;
            case 'Dead':
                $colorClass = "textbg_black";
                break;
            case 'Converted':
                $colorClass = "textbg_red";
                break;
        }
        $tmp_status = translate('target_status_dom','',$bean->status);
        $bean->status = "<span class=$colorClass >$tmp_status</span>";
    }
}