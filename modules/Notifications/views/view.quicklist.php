<?php
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
* Copyright (C) 2004-2014 SugarCRM Inc.  All rights reserved.
********************************************************************************/


class ViewQuicklist extends SugarView{
    function ViewQuicklist(){
        parent::SugarView();
    }

    function display()
    {
        global $current_user,$timedate;
        $data = array();
        $query = "SELECT DISTINCT
        IFNULL(n.id, '') id,
        IFNULL(n.name, '') name,
        IFNULL(l9.full_user_name, '') assigned_to,
        IFNULL(l10.full_user_name, '') created_by,
        IFNULL(l1.full_lead_name, '') Lead_name,
        IFNULL(l2.name, '') Task_name,
        IFNULL(n.parent_type, '') parent_type,
        IFNULL(n.parent_id, '') parent_id,
        IFNULL(n.severity, '') severity,
        IFNULL(n.description, '') description,
        IFNULL(n.is_read, 0) is_read,
        IFNULL(n.date_entered, '') date_entered
        FROM
        notifications n
        LEFT JOIN
        leads l1 ON n.parent_id = l1.id AND l1.deleted = 0
        LEFT JOIN
        tasks l2 ON n.parent_id = l2.id AND l2.deleted = 0
        INNER JOIN
        users l9 ON n.assigned_user_id = l9.id
        AND l9.deleted = 0
        INNER JOIN
        users l10 ON n.created_by = l10.id
        AND l10.deleted = 0
        WHERE
        n.deleted = 0
        AND n.assigned_user_id = '{$current_user->id}' AND is_read = 0
        ORDER BY /*CASE
        WHEN
        (n.parent_type = ''
        OR n.parent_type IS NULL)
        THEN
        0
        WHEN n.parent_type = 'Leads' THEN 1
        WHEN n.parent_type = 'Tasks' THEN 2
        WHEN n.parent_type = 'Contacts' THEN 3
        ELSE 3
        END ASC,*/ n.date_entered DESC
        LIMIT 10";
        $result = $GLOBALS['db']->query($query);
        while($row = $GLOBALS['db']->fetchByAssoc($result)){
            $n1                 = new Notifications();
            $n1->id             = $row['id'];
            $n1->name           = $row['name'];
            $n1->assigned_to    = $row['assigned_to'];
            $n1->created_by     = $row['created_by'];
            $n1->nofi_name      = $row[$GLOBALS['app_list_strings']['parent_type_display'][$row['parent_type']].'_name'];
            $n1->parent_type    = $GLOBALS['app_list_strings']['parent_type_display'][$row['parent_type']];
            $n1->severity       = $row['severity'];
            $n1->description    = $row['description'];
            $n1->is_read        = $row['is_read'];
            $n1->date_entered   = $timedate->to_display_date_time($row['date_entered']);
            $n1->timeLapse      = SugarFeed::getTimeLapse($n1->date_entered);
            if($n1->is_read == 1)
                $n1->status     = 'Read';
            else $n1->status    = 'Unread';

            $n1->content        = "<span><a style='font-weight:bold;' href='index.php?module={$row['parent_type']}&action=DetailView&record={$row['parent_id']}' onclick=\"markNotification('".$row['id']."');\" title='Click here for more information' rel='tooltip'>{$n1->nofi_name}</a> has been assigned to you.</span>";
            $data['list'][]     = $n1;

        }
        $newCount = Notifications::getUnreadNotificationCountForUser($current_user);
        $js_notification ="updateNotifCount({$newCount});";


        echo $this->_formatNotificationsForQuickDisplay($data['list'], "modules/Notifications/tpls/quickView.tpl", $js_notification);
    }
    function _formatNotificationsForQuickDisplay($notifications, $tplFile, $js_notification)
    {
        global $app_strings;
        $this->ss->assign('APP', $app_strings);
        $this->ss->assign('data', $notifications);
        $this->ss->assign('js_notification', $js_notification);
        return $this->ss->display($tplFile);
    }
}